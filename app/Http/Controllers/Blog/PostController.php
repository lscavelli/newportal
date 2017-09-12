<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use App\Libraries\listGenerates;
use App\Models\Blog\Post;
use Carbon\Carbon;
use App\Http\Requests;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;


class PostController extends Controller  {

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Blog\Post')->setSearchFields(['name','summary','content']);
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)   {
        return Validator::make($data, [
            'name' => 'sometimes|required|min:3|max:255',
            'content' => 'sometimes|required|min:10'
        ]);
    }

    /**
     * Visualizza la lista dei post, eventualmente filtrata
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $posts = $this->rp->paginate($request); $list->setModel($posts);
        return view('blog.listPost')->with(compact('posts','list'));
    }

    /**
     * Mostra il form per la creazione di un nuovo Post
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        $post = new Post(); $action = "Blog\\PostController@store";
        return view('blog.editPost')->with(compact('post','action'));
    }

    /**
     * Salva il Post nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $data = $request->all();
        $data['user_id'] = \Auth::user()->id; $data['username'] = \Auth::user()->username;
        $this->validator($data)->validate();
        $this->rp->create($data);
        return redirect()->route('posts')->withSuccess('Post creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del Post
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $post = $this->rp->find($id); $action = ["Blog\\PostController@update",$id];
        return view('blog.editPost')->with(compact('post','action'));
    }

    /**
     * Aggiorna i dati del Post nel DB
     * @param $id
     * @param Request $request
     * @return $this
     */
    public function update($id, Request $request)  {
        $data = $request->all();
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect()->route('posts')->withSuccess('Post aggiornato correttamente');
        }
        return redirect()->back()->withErrors('Si è verificato un  errore');
    }

    /**
     * Cancella il Post - chiede conferma prima della cancellazione
     * cancella anche i tags, le categorie e i commenti associati
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        $post = $this->rp->find($id);
        if ($post->tags()->count()>0) $this->rp->detach($post->tags(), $post->tags()->pluck('id'));
        if ($post->categories()->count()>0) $this->rp->detach($post->categories(), $post->categories()->pluck('id'));
        if ($post->comments()->count()>0) $post->comments()->delete();
        if ($this->rp->delete($id)) {
            return redirect()->back()->withSuccess('Post cancellato correttamente');
        }
        return redirect()->back();
    }
}
