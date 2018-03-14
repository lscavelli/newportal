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
        $services = $this->getService($service);
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
    public function create($service,$postId)   {
        $post = $this->getService($service)->find($postId);
        $comment = new Comment(); $action = "Content\\CommentController@store";
        return view('content.editComment')->with(compact('comment','action','post','service'));
    }

    /**
     * Salva il commento nel database dopo aver validato i dati
     * @param Request $request
     */
    public function store(Request $request) {
        $data = $request->all();
        if (!$request->has('service') or !$request->has('post_id')) return;
        if (empty($data['name'])) $data['name'] = str_limit($data['content'],50);
        $data['author_ip'] = $request->ip();
        if (auth()->check()) {
            $data['user_id'] = auth()->user()->id;
        }
        $this->validator($data)->validate();

        $post = $this->getService($request->service)->find($request->post_id);
        $post->comments()->create($data);
        return redirect()->route('comments',['service' => $request->service,'post_id' => $request->post_id])->withSuccess('Commento creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del commento
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($service,$postId,$id) {
        $comment = $this->rp->find($id);
        $post = $this->getService($service)->find($postId);
        $action = ["Content\\CommentController@update",$id];
        return view('content.editComment')->with(compact('comment','action','post','service'));
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
            return redirect()->route('comments',['service' => $request->service,'post_id' => $request->post_id])->withSuccess('Commento aggiornato correttamente');
        }
        return redirect()->back()->withErrors('Si è verificato un  errore');
    }

    /**
     * Cancella il commento - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($service,$postId,$id)  {
        if ($this->rp->delete($id)) {
            return redirect()->route('comments',['service' => $service,'post_id' => $postId])->withSuccess('Commento cancellato correttamente');
        }
        return redirect()->route('comments',['service' => $service,'post_id' => $postId]);
    }

    /**
     * Cambia lo stato del commento
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function state($service,$postId,$id)  {
        $comment = $this->rp->find($id);
        $stateId = ($comment->approved) ? 0 : 1;
        $this->rp->update($id,['approved'=>$stateId]);
        return redirect('admin/comments/'.$service.'/'.$postId);
    }

    /**
     * deternmina il model
     * @param $service
     * @return mixed
     */
    private function getService($service) {
        $flip = array_flip(config('newportal.services'));
        return $this->rp->setModel(array_get(array_change_key_case($flip, CASE_LOWER),$service));
    }

}
