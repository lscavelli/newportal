<?php

namespace App\Http\Controllers\Content;

use App\Libraries\listGenerates;
use App\Models\Content\Service;
use App\Models\Content\Structure;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Content\Widget;
use Validator;
use App\Repositories\RepositoryInterface;
use App\Libraries\WidgetManage;

class WidgetController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Widget')->setSearchFields(['name']);
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
     * Visualizza la lista delle widget, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $widgets = $this->rp->paginate($request);
        $list->setModel($widgets);
        return view('content.listWidget')->with(compact('widgets','list'));
    }

    /**
     * Salva il tag nel database dopo aver validato i dati
     * @param Request $request
     * @param WidgetManage $pm
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request, WidgetManage $pm) {
        if ($request->file('fileWidget')) {
            $pm->uploadWidget($this->rp);
            return redirect('admin/widgets')->withSuccess('Widgets installate correttamente');
        } else {
            return redirect('admin/widgets')->withErrors('Non è stata selezionata alcuna widget');
        }
    }

    /**
     * Cancella la Widget - chiede conferma prima della cancellazione
     * @param $id
     * @param WidgetManage $pm
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id, WidgetManage $pm)   {
        if ($pm->uninstallWidget($id,$this->rp)) {
            return redirect()->back()->withSuccess('Widget cancellata correttamente');
        }
        return redirect()->back();
    }

    /**
     * restituisce in formato json la lista delle widget installate
     * @return \Illuminate\Http\JsonResponse
     */
    public function listWidgetDisp() {
        return  response()->json($this->rp->all());
    }

    /**
     * mostra le informazioni sulla widget installata
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request) {
        $widget = $this->rp->find($id);
        $pag['nexid'] = $this->rp->next($id);
        $pag['preid'] = $this->rp->prev($id);
        $listPages = new listGenerates($this->rp->paginateArray($widget->pages->toArray(),10,$request->page_a,'page_a'));
        return view('content.profileWidget')->with(compact('widget','pag','listPages'));
    }

    /**
     * Mostra il form per il setting della widget
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $widget = $this->rp->find($id);
        $structures = [''=>''];
        if (!empty($widget->service)) {
            $service = $this->rp->setModel(Service::class)->where('class',$widget->service)->firstOrFail();
            $structures += $this->rp->setModel(Structure::class)->where('service_id',$service->id)->where('status_id',1)->pluck()->toArray();
        }
        return view('content.settingWidget')->with(compact('widget','structures'));
    }

    /**
     * Aggiorna i dati della Widget nel DB
     * @param $id
     * @param Request $request
     * @return $this
     */
    public function update($id, Request $request)  {
        $data = $request->all();
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect('admin/widgets')->withSuccess('Widget aggiornata correttamente');
        }
    }


}
