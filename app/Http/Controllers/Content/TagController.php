<?php

namespace App\Http\Controllers\Content;

use App\Services\listGenerates;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Content\Tag;
use Validator;
use App\Repositories\RepositoryInterface;

class TagController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Tag')->setSearchFields(['name']);
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
     * Visualizza la lista dei tag, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $tags = $this->rp->paginate($request);
        $list->setPagination($tags);
        return view('content.listTag')->with(compact('tags','list'));
    }

    /**
     * Mostra il form per la creazione di un nuovo tag
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        $tag = new Tag();
        return view('content.editTag')->with(compact('tag'));
    }

    /**
     * Salva il tag nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request) {
        $data = $request->all();
        $this->validator($data)->validate();
        $this->rp->create($data);
        return redirect('admin/tags')->withSuccess('Tag creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del Tag
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $tag = $this->rp->find($id);
        return view('content.editTag')->with(compact('tag'));
    }

    /**
     * Aggiorna i dati del Tag nel DB
     * @param $id
     * @param Request $request
     * @return $this
     */
    public function update($id, Request $request)  {
        $data = $request->all();
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect('admin/tags')->withSuccess('Tag aggiornato correttamente');
        }
    }

    /**
     * Cancella il Tag - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)   {
        if ($this->rp->delete($id)) {
            return redirect()->back()->withSuccess('Tag cancellato correttamente');
        }
        return redirect()->back();
    }

    /**
     * Restituisce la lista dei content a cui è stato assegnato il tag
     * @param $TagId
     * @return mixed
     */
    public function listWebContent($TagId)  {
        return $this->rp->find($TagId)->webcontent;
    }

    public function content($TagId, Request $request, listGenerates $list) {
        $tag = $this->rp->find($TagId);
        $content = $this->listWebContent($TagId)->toArray();
        $list->setPagination($this->rp->paginateArray($content,4));
        return view('content.listTagContent')->with(compact('content','list','tag'));
    }

    public function listTag() {
        $tags = $this->rp->all();
        return response()->json($tags);
    }

}
