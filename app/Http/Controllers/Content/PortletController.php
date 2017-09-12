<?php

namespace App\Http\Controllers\Content;

use App\Libraries\listGenerates;
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
            'name' => 'required|min:2|max:80',
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
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, PortletManage $pm) {
        if ($request->file('filePortlet')) {
            $pm->uploadPortlet($this->rp);
            return redirect()->route('portlets')->withSuccess('Portlets installate correttamente');
        }
        return redirect()->route('portlets');
    }

    /**
     * Cancella la Portlet - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, PortletManage $pm)   {
        if ($pm->uninstallPortlet($id,$this->rp)) {
            return redirect()->back()->withSuccess('Portlet cancellata correttamente');
        }
        return redirect()->back();
    }

    public function listPortletDisp() {
        return  response()->json($this->rp->all());
    }
}
