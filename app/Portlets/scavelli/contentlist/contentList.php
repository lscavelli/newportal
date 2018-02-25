<?php

namespace App\Portlets\scavelli\contentlist;

use App\Portlets\abstractPortlet as Portlet;
use App\Portlets\scavelli\contentlist\Controllers\assetController;
use App\Libraries\feeds;
use App\Libraries\sl_text;

class contentList extends Portlet {

    public $conf;

    public function init() {
        $model = "App\\Models\\Content\\Content";
        if ($this->config('service')) $model = $this->config('service');
        $this->rp->setModel($model);
        $this->conf = $this->config; // necessario per la chiamata getItem() della view

        if ($this->theme)
            $this->theme->addExCss($this->getPath().'css/assetpublisher007.css');
    }

    public function getContent() {
        if (empty($this->config('model_id'))) return;

        $builder = $this->rp->getModel();

        // considero solo i contenuti attivi
        $builder = $builder->where('status_id',1);

        // ordered
        if ($this->config('ord')) {
            $ord = ['id','name','created_at','updated_at','hits'];
            $dir = ['asc','desc'];
            $dirkey = (!is_null($this->config('dir'))) ? $this->config('dir') : 0;
            $builder = $builder->orderBy($ord[$this->config('ord')], $dir[$dirkey]);
        }

        // inizializzo le variabili $categories e $tags
        $categories = null;
        $tags = null;
        $content = null;

        // se la comunicazione è attiva
        // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        if ($this->config('comunication')) {
            // imposta $tags o/e $categories se presenti nell'url
            if ($this->request->has('tag')) {
                $tags = ['tags'=>['tag'=>$this->request->tag]];
            }
            if ($this->request->has('category')) {
                $categories = ['categories'=>['category'=>$this->request->category]];
            }
            // se tags e categories non sono impostate verifico il content passato nell'url
            if (!$tags and !$categories) {
                $segments = $this->request->segments(); $qwc = null;
                if ($this->request->has('content')) {
                    $qwc = $this->request->content;
                } elseif (count($segments)>1) {
                    $qwc = end($segments);
                }
                if (!is_null($qwc)) {
                    // escludo il content web dell'URL dalla lista
                    $builder = $builder->where('slug','<>',$qwc);

                    // prelevo tutti i tag e le categorie del content
                    $content = $this->rp->findBySlug($qwc);

                    // inizialmente considero i tags e le categorie del content
                    // passato nella url
                    if ($content && (!$this->config('scrolling'))) {
                        foreach ($content->categories->pluck('id')->toArray() as $id) {
                            $categories[] = ['category'=>$id];
                        }
                        foreach ($content->tags->pluck('id')->toArray() as $id) {
                            $tags[] = ['tag'=>$id];
                        }
                    }
                }
            }

        }
        if (!$tags and !$categories) {
            // altrimenti imposto i tags o/e categories se presenti nel setting
            $tags = $this->config('tags');
            $categories = $this->config('categories');
        }

        // se è impostata la variabile tags applico i filtri sui tags
        // in AND
        if ($tags) {
            foreach ($tags as $tag) {
                $builder = $builder->whereHas('tags', function ($q) use ($tag) {
                    $q->where('tag_id', '=', $tag['tag']);
                });
            }
        }

        // se è impostata la variabile categories applico i filtri sulle categorie
        // in AND
        if ($categories) {
            foreach ($categories as $category) {
                $builder = $builder->whereHas('categories', function ($q) use ($category) {
                    $q->where('category_id', $category['category']);
                });
            }
        }

        // se il servizio è di tipo "content web" verifico se risultano impostati la struttura e il modello
        if ($this->config('structure_id')) {
            $builder = $builder->where('structure_id',$this->config('structure_id'));
        }

        // se l'url contiene author applico il filtro sull'autore
        if ($this->config('comunication') and $this->request->has('author')) {
            $builder = $builder->where('user_id',$this->request->author);
        }

        if ($this->config('sitemap') && is_null($this->theme)) {
            return $builder->get();
        }

        // verifico se è attivo il feed Rss
        if ($this->config('feed')) {
            //dd($this->config);
            // imposto l'url e Ctype dei feed
            $this->conf['feedUrl'] = $this->request->url().'?'.$this->config('feed.feed_format')."&portletid=".$this->config('id');
            $this->conf['feedJsonUrl'] = $this->request->url().'?json'."&portletid=".$this->config('id');
            $this->conf['feedCtype'] = ($this->config('feed.feed_format')=='atom') ? 'application/atom+xml' : 'application/rss+xml';

            if ($this->request->portletid==$this->config('id')) {
                if($this->request->has($this->config('feed.feed_format'))) {
                    $builder = $builder->take($this->config('feed.feed_size'));
                    $feed = $this->buildFeed($builder->get());
                    header("Content-Type: ".$this->conf['feedCtype']);
                    echo $feed;
                    exit;
                } elseif ( $this->request->has('json') ) {
                    $builder = $builder->take($this->config('feed.feed_size'));
                    $feed = $this->buildFeedJson($builder->get());
                    header("Content-Type: application/json");
                    echo json_encode($feed);
                    exit;
                }
            }

            // imposto il link nella pagina
            // TODO Consentire link multipli
            $feedLink['feedLink'][] = ['url'=>$this->conf['feedUrl'],'cType'=>$this->conf['feedCtype']];
            $feedLink['feedLink'][] = ['url'=>$this->conf['feedJsonUrl'],'cType'=>'application/json'];
            $this->setConfigTheme($feedLink);

        }

        // controllare - sembra che esegua due volte la chiamata alla portlet
        // echo "<br /><br /><br /><br /><br /><br />";
        // var_dump($this->config('scrolling'));


        $items = [];
        // controllo se è richiesta la navigazione del contenuto
        if ($this->config('scrolling')) {
            if ($content) {
                $builder1 = clone $builder;
                if ($this->config('scrolling')=='nextf') {
                    $items = $builder->where('id', '>', $content->id)->orderBy('id','asc')->paginate(1);
                    if($items->count()<1){
                        $items = $builder1->orderBy('id','asc')->paginate(1);
                    }
                } elseif ($this->config('scrolling')=='prevf') {
                    $items = $builder->where('id', '<', $content->id)->orderBy('id','desc')->paginate(1);
                    if($items->count()<1)
                        $items = $builder1->orderBy('id','desc')->paginate(1);
                }
            } else {
                return;
            }
        } else {
            $items = $builder->paginate(4);
        }


        if ($items->count()<1) return;

        //dd($builder->toSql());

        // TODO:  INSERIRE IL VALORE DEL PAGINATE NEL SETTING

        //$template = 'listAssets';
        //if (!empty($this->config('structure_id')))
        $listView = $this->config('listView') ?:  'listAssets';

        if (!$this->config('template')) {
            return view("contentlist::$listView")->with([
                'items' => $items,
                'title' => $this->config('title'),
                'list'  => $this
            ]);
        } else {
            $content = [];
            foreach($items as $item) {
                $content[] = $this->getItem($item);
            }
            return implode('',$content);
        }
    }

    /**
     * Restituisce il contenuto della singola voce che compone la lista dei contenuti
     * @param $rec
     * @return null|string
     * @throws \Exception
     * @throws \Symfony\Component\Debug\Exception\FatalThrowableError
     */
    public function getItem($rec) {
        if ($rec) {
            $data = json_decode($rec->content,true);
            if (!empty($this->conf['model_id'])) {
                $model = $this->rp->setModel('App\Models\Content\Modelli')->find($this->conf['model_id'])->content;
                if (str_contains($model, '$np_title')) $data['_title'] = $rec->name;
                if (str_contains($model, '$np_image')) $data['_image'] = $rec->getImage();
                if (str_contains($model, '$np_categories')) $data['_categories'] = $rec->categories;
                if (str_contains($model, '$np_page')) $data['_page'] = $this->request->segment(1);
                $data['_author_name'] = $rec->user->name; $data['_author_username'] = $rec->username; $data['_author_id'] = $rec->user_id;
                $url = (!empty($this->conf['inpage'])) ?  url($this->conf['inpage']) : url()->current();
                //$data['_href'] = $url.'?'.http_build_query(['content'=>$rec->slug]);
                $data['_href'] = $url."/".$rec->slug;
                $data['_data_creazione'] = $rec->created_at->format('d/m/Y');
                $data['_data_modifica'] = \Carbon\Carbon::parse($rec->updated_at)->format('d/m/Y');
                return $this->applyModel($model,$data);
            }
        }
        return null;
    }

    /**
     * restituisce il valore della key se esistente
     * @param $key
     * @return mixed
     */
    public function get($key) {
        return array_get($this->conf,$key);
    }

    public function configPortlet($portlet) {
        return (new assetController($this->rp))->configPortlet($portlet, $this);
    }

    /**
     * return feed rss - atom
     * @param $items
     * @return string|void
     */
    private function buildFeed($items) {
        if ($items->count()<1) return;

        $feed = (new feeds($this->config('feed.feed_format')))
            ->title($this->config('feed.feed_name'))
            ->link($this->request->url())
            ->linkFeed($this->get('feedUrl'))
            ->date(now())
            ->description($this->config('feed.feed_name'))
            ->language(config('app.locale'));

        $enclosure = [];
        foreach ($items as $item) {
            $data = json_decode($item->content,true);
            $url = ($this->get('inpage')) ?: url()->current();
            $author = "";
            if(!empty($item->user)){
                $author = $item->user->name;
            }
            $enclosure['url'] = $item->getImage();
            $enclosure['length'] = '28000';
            $enclosure['type'] = getimagesize($enclosure['url'])['mime'];

            $feed->addItem(
                        $item->id,
                        url($url."/".$item->slug),
                        $item->name,
                        $item->created_at,
                        "<p><img src=\"{$item->getImage()}\" /></p>",
                        head($data),
                        $author,
                        $enclosure
                    );
        };
        return $feed->render($this->config('feed.feed_format'));
    }

    /**
     * return feed json
     * @param $items
     * @return array|void
     */
    private function buildFeedJson($items) {
        if ($items->count()<1) return;

        $data = [
            'version' => 'https://jsonfeed.org/version/1',
            'title' => $this->config('feed.feed_name'),
            'home_page_url' => $this->request->url(),
            'feed_url' => $this->get('feedUrl'),
            'favicon' => url("/favicon.ico"),
            'items' => [],
        ];

        foreach ($items as $key => $item) {
            $content = json_decode($item->content,true);
            $url = ($this->get('inpage')) ?: url()->current();
            $author = "";
            if(!empty($item->user)){
                $author = $item->user->name;
            }
            $data['items'][$key] = [
                'id' => $item->id,
                'title' => htmlspecialchars(strip_tags($item->name), ENT_COMPAT, 'UTF-8'),
                'url' => url($url."/".$item->slug),
                'image' => $item->getImage(),
                'content_html' => sl_text::sommario(head($content)),
                'date_created' => $item->created_at->tz('UTC')->toRfc3339String(),
                'date_modified' => $item->updated_at->tz('UTC')->toRfc3339String(),
                'author' => [
                    'name' => $author
                ],
            ];
        }
        return $data;
    }
}