<?php

namespace App\Widgets\scavelli\webcontent;

use App\Models\Content\Modelli;
use App\Widgets\abstractWidget as Widget;
use App\Widgets\scavelli\webcontent\Controllers\ContentWebController;
use Exception;
use App\Notifications\NewComment;
use Illuminate\Contracts\Support\Renderable;

class viewWebContent extends Widget implements Renderable {

    public function init() {
        $this->rp->setModel('App\Models\Content\Content');
        if ($this->config('socialshare')) {
            $this->theme->addExJs($this->getPath().'js/socialshare.js');
        }
        if ($this->theme && $this->config('syntax')) {
            $this->theme->addExJs($this->getPath().'js/prism.js');
            $this->theme->addExCss($this->getPath().'css/prism.css');
        }

    }

    public function getContent() {
        return $this->render();
    }

    public function render() {

        $segments = $this->request->segments();

        $cw = $mt =null;
        if (!empty($this->config['comunication'])) {
            if ($this->request->has('content')) {
                $cw = $this->rp->findBySlug($this->request->content);
            } elseif ($this->request->has('id')) {
                $cw = $this->rp->find($this->request->id);
            } elseif (count($segments)>1) {
                $cw = $this->rp->findBySlug(end($segments));
            }
            // setto i meta tag della pagina con i dati del web content
            if (!is_null($cw)) $mt = 1; // $mt mi assicura che ci sia il content con la comunicazione attiva

            // se è richiesto il formato json
            if ($mt && $this->request->has('json')) {
                $feed = $this->buildFeedJson($cw);
                header("Content-Type: application/json");
                header("Access-Control-Allow-Origin: *");
                echo json_encode($feed);
                exit;
            }
        }

        if (isset($this->config['content_id']) && !empty($this->config['content_id']) && is_null($cw)) {
            $cw = $this->rp->find($this->config['content_id']);
        }
        // se non viene trovato alcun contenuto mostra un messaggio di errore
        if (!isset($cw->content)) {
            if (array_get(cache('settings'), 'content_not_found')==1) {
                return view('errors.contentNotFound');
            } else return; // non mostra nulla
        }
        $data = json_decode($cw->content,true);

        $data['_title'] = $cw->name;
        $data['_data_creazione'] = \Carbon\Carbon::parse($cw->created_at)->format('d/m/Y');
        $data['_data_modifica'] = \Carbon\Carbon::parse($cw->updated_at)->format('d/m/Y');


        // verifico prima se è stato impostato un modello tramite widget
        // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        if (!empty($this->config['modelWidgetId'])) {
            $ModelId = $this->config['modelWidgetId'];
            $model = $this->rp->setModel(Modelli::class)->find($ModelId);

        // altrimenti se esiste un modello assegnato al contenuto lo utilizzo

        } elseif(!empty($cw->model->content)) {
            $model = $cw->model;

        // altrimenti verifico se esiste almeno un modello appartenente alla struttura del content
        // considero il primo

        } else {
            $model = $this->rp->setModel('App\Models\Content\Structure')->find($cw->structure_id)->models->where('type_id',1)->first();
        }

        if (!$model) return "Per il content \"$cw->name\" non risulta impostato alcun modello";

        if (str_contains($model, '$np_image')) $data['_image'] = $cw->getImage();
        if (str_contains($model, '$np_categories')) $data['_categories'] = $cw->categories;
        $data['_author_name'] = $cw->user->name;
        $data['_author_username'] = $cw->username;
        $data['_author_id'] = $cw->user_id;

        // inserisco il pulsante per la modifica del contenuto
        // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        $update = null;
        if (auth()->check()) {
            $urlupdate = url('/admin/content/'.$cw->id.'/edit');
            //sostituire con view()
            $update = "<a href=\"#\" class=\"toggle-form btn btn-info edit-button\" title=\"modifica contenuto web {$cw->id}\" onclick=\"window.open('$urlupdate')\" style=\"display: none; position: absolute; top: 10px; right: 145px;\"><i class=\"glyphicon glyphicon-pencil\"></i></a>";
        }
        $return = $this->applyModel($model->content,$data).$update;

        // se la comunicazione è attiva e il nome del content è presente
        // nell'url, setto i meta Tag della pagina con i dati del content
        // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        if ($mt) {
            $mt = array();
            $mt['title'] = $data['_title'];
            if (!empty($cw->description)) {
                $mt['description'] = str_limit($cw->description,160);
            } else {
                $mt['description'] = str_limit(htmlspecialchars(strip_tags(head($data)), ENT_COMPAT, 'UTF-8'),160);
            }
            $mt['image'] = $cw->getImage();
            $this->setConfigTheme($mt);
        }

        $items = [];

        // Se socialshare è true
        // inserisce i pulsanti per condividere il contenuto sui social
        // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        if ($this->config('socialshare')) {
            if (!empty($this->config('providers'))) {
                foreach($this->config('providers') as $provider=>$param) {
                    $items[$provider]['url'] = array_get($param,'uri').urlencode(request()->getUri());
                    $items[$provider]['class'] = 'openwinsocial';
                    if (isset($param['text'])) {
                        $items[$provider]['url'] .= '&text='.urlencode($data['_title']);
                    }
                    if (isset($param['original_referer'])) {
                        $items[$provider]['url'] .= '&original_referer='.urlencode(request()->getUri());
                    }
                    $items[$provider]['icon'] = $param['icon'];
                    $items[$provider]['icon'] = $param['icon'];
                }
            }
        }

        // Se activecomments è true
        // inserisce il pulsante per inserire un commento e mostra l'elenco
        // dei commenti presenti
        // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        $formcomment = $listcomments = null;
        if ($this->config('activecomments')) {
            $items['comment']['icon'] = 'fa-comments-o';
            $items['comment']['url'] = '#comments';
            $items['comment']['class'] = 'showcommentform';
            $formcomment = view('webcontent::commentsForm')->with([
                'action'=>$this->request->fullUrl(),
            ]);
            $this->theme->addExJs($this->getPath().'js/comments.js');

            if ($this->request->has('sendComment')) {
                $this->storeComment($cw);
            }
            // mostro l'elenco paginato dei commenti
            $comments = $cw->comments()->where('approved',1)->orderBy('created_at','DESC')->paginate(4);
            if ($comments->count()>0) {
                $this->theme->addExCss($this->getPath().'css/comments.css');
                $listcomments = view('webcontent::commentsList')->with(compact('comments'));
            }
        }
        if (count($items)>0) {
            $return .= view('webcontent::social')->with(compact('items')).$formcomment.$listcomments;
        }



        // se sethits è true aumento incremento di 1 hits
        // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        if ($this->config('sethits')) {
            $cw->increment('hits');
        }

        return $return;
    }

    public function configWidget($widget) {
        return (new ContentWebController($this->rp))->configWidget($widget, $this->request);
    }


    private function storeComment($contentWeb) {

        $data = $this->request->all();
        if (empty($data['name'])) $data['name'] = str_limit($data['message'],50);
        $data['content'] = $data['message']; // se lascio content entra in contrasto con il content del contentweb
        unset($data['message']);
        $data['author_ip'] = $this->request->ip();
        if (auth()->check()) {
            $data['user_id'] = auth()->user()->id;
        }
        $this->validator($data)->validate();
        $comment = $contentWeb->comments()->create($data);
        session()->flash('success', 'Commento inserito correttamente. A breve sarà visibile on-line');
        $contentWeb->user->notify(new NewComment("Hai ricevuto un nuovo commento sul content: ". $contentWeb->name, $comment));
    }

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
        return validator()->make($data, $forall );
    }

    /**
     * return feed json
     * @param $item
     * @return array
     */
    private function buildFeedJson($item) {

        $data = [
            'version' => 'https://jsonfeed.org/version/1',
            'title' => 'Feed Json Content Web',
            'home_page_url' => $this->request->url(),
            'feed_url' => $this->request->url().'?json',
            'favicon' => url("/favicon.ico"),
            'items' => [],
        ];

        $author = "";
        if(!empty($item->user)){
            $author = $item->user->name;
        }
        $data['items'][0] = [
            'id' => $item->id,
            'title' => htmlspecialchars(strip_tags($item->name), ENT_COMPAT, 'UTF-8'),
            'url' => url(url()->current()."/".$item->slug),
            'image' => $item->getImage(),
            'content_html' => head(json_decode($item->content,true)),
            'summary'=> htmlspecialchars(strip_tags($item->description), ENT_COMPAT, 'UTF-8'),
            'date_created' => $item->created_at->tz('UTC')->toRfc3339String(),
            'date_modified' => $item->updated_at->tz('UTC')->toRfc3339String(),
            'author' => [
                'name' => $author
            ],
        ];

        return $data;
    }
}