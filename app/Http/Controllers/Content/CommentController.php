<?php

namespace App\Http\Controllers\Content;

use Illuminate\Http\Request;
use App\Libraries\listGenerates;
use App\Models\Content\Comment;
use Carbon\Carbon;
use App\Http\Requests;
use Validator;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;

class CommentController extends Controller {
    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Comment')->setSearchFields(['name','content']);
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)   {
        $forguest = [];
        $forall = [
            'content' => 'required|min:10'
        ];
        if (!auth()->check()) {
            $forguest = [
                'email' => ['required','email','max:255'],
                'author' => ['required','min:3'],
            ];
        }
        $forall = array_merge($forall,$forguest);
        return Validator::make($data, $forall );
    }

    /**
     * Visualizza la lista dei Commenti, eventualmente filtrata
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function index($service, $id=null, Request $request, listGenerates $list) {
        $services = $this->rp->setModel($this->getService($service));
        if (!empty($id)) {
            $service = $services->find($id);
            $comments = $service->comments()->orderBy('id','DESC')->paginate();
            $nameContent = $service->name;
        } else {
            $comments = $services->getModel()->comments()->paginate(4); $nameContent = null;
        }
        $list->setModel($comments);
        return view('content.listComment')->with(compact('comments','list','nameContent'));
    }

    /**
     * Mostra il form per la creazione di un nuovo commento
     * @return \Illuminate\Contracts\View\View
     */
    public function create($service,$serviceId)   {
        $post = $this->rp->setModel($this->getService($service))->find($serviceId);
        $comment = new Comment(); $action = "Content\\CommentController@store";
        return view('content.editComment')->with(compact('comment','action','post','service'));
    }

    /**
     * Salva il commento nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $data = $request->all();
        if (empty($data['name'])) $data['name'] = str_limit($data['content'],50);
        $data['user_id'] = \Auth::user()->id;
        $this->validator($data)->validate();
        $post = $this->rp->setModel('App\Models\Blog\Post')->find($request->post_id);
        $post->comments()->create($data);
        return redirect()->route('comments',['post_id' => $request->post_id])->withSuccess('Commento creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del commento
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($postId,$id) {
        $comment = $this->rp->find($id); $action = ["Blog\\CommentController@update",$id];
        return view('blog.editComment')->with(compact('comment','action','postId'));
    }

    /**
     * Aggiorna i dati del Commento nel DB
     * @param $id
     * @param Request $request
     * @return $this
     */
    public function update($id, Request $request)  {
        $data = $request->all();
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect()->route('comments',['post_id' => $request->post_id])->withSuccess('Commento aggiornato correttamente');
        }
        return redirect()->back()->withErrors('Si è verificato un  errore');
    }

    /**
     * Cancella il commento - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($postId,$id)  {
        if ($this->rp->delete($id)) {
            return redirect()->back()->withSuccess('Commento cancellato correttamente');
        }
        return redirect()->back();
    }

    private function getService($service) {
        $flip = array_flip(config('newportal.services'));
        return array_get(array_change_key_case($flip, CASE_LOWER),$service);

    }

}
