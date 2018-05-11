<?php

namespace App\Http\Controllers\Content;

use App\Libraries\listGenerates;
use App\Models\Content\Service;
use App\Models\Content\Structure;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Content\Portlet;
use Validator;
use App\Repositories\RepositoryInterface;
use App\Libraries\PortletManage;

class PortletController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Portlet')->setSearchFields(['name']);
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)   {
        return Validator::make($data, [
            //'structure_id' => 'required',
        ]);
    }

    /**
     * Visualizza la lista delle portlet, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $portlets = $this->rp->paginate($request);
        $list->setModel($portlets);
        return view('content.listPortlet')->with(compact('portlets','list'));
    }

    /**
     * Salva il tag nel database dopo aver validato i dati
     * @param Request $request
     * @param PortletManage $pm
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request, PortletManage $pm) {
        if ($request->file('filePortlet')) {
            $pm->uploadPortlet($this->rp);
            return redirect('admin/portlets')->withSuccess('Portlets installate correttamente');
        } else {
            return redirect('admin/portlets')->withErrors('Non è stata selezionata alcuna portlet');
        }
    }

    /**
     * Cancella la Portlet - chiede conferma prima della cancellazione
     * @param $id
     * @param PortletManage $pm
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id, PortletManage $pm)   {
        if ($pm->uninstallPortlet($id,$this->rp)) {
            return redirect()->back()->withSuccess('Portlet cancellata correttamente');
        }
        return redirect()->back();
    }

    /**
     * restituisce in formato json la lista delle portlet installate
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPortletDisp() {
        return  response()->json($this->rp->all());
    }

    /**
     * mostra le informazioni sulla portlet installata
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request) {
        $portlet = $this->rp->find($id);
        $pag['nexid'] = $this->rp->next($id);
        $pag['preid'] = $this->rp->prev($id);
        $listPages = new listGenerates($this->rp->paginateArray($portlet->pages->toArray(),10,$request->page_a,'page_a'));
        return view('content.profilePortlet')->with(compact('portlet','pag','listPages'));
    }

    /**
     * Mostra il form per il setting della portlet
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $portlet = $this->rp->find($id);
        $structures = [''=>''];
        if (!empty($portlet->service)) {
            $service = $this->rp->setModel(Service::class)->where('class',$portlet->service)->firstOrFail();
            $structures += $this->rp->setModel(Structure::class)->where('service_id',$service->id)->where('status_id',1)->pluck()->toArray();
        }
        return view('content.settingPortlet')->with(compact('portlet','structures'));
    }

    /**
     * Aggiorna i dati della Portlet nel DB
     * @param $id
     * @param Request $request
     * @return $this
     */
    public function update($id, Request $request)  {
        $data = $request->all();
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect('admin/portlets')->withSuccess('Portlet aggiornata correttamente');
        }
    }


}
