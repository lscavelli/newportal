<?php

namespace App\Widgets\scavelli\imageviewer;

use App\Widgets\abstractWidget as Widget;
use App\Widgets\scavelli\imageviewer\Controllers\imageController;

class imageViewer extends Widget {

    public $conf;

    public function init() {
        $model = "App\\Models\\Content\\File";
        $this->rp->setModel($model);
        $this->conf = $this->config; // necessario per la chiamata getItem() della view

        if ($this->theme)
            $this->theme->addExCss($this->getPath().'css/imageViewer.css');
    }

    public function getContent() {
        if (empty($this->config('model_id'))) return;

        $builder = $this->rp->getModel();

        if (!empty($this->config['file_id'])) {
            $items = collect([$this->rp->find($this->config['file_id'])]);
        } else {

            // considero solo i contenuti attivi e le immagini
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            $builder = $builder->where('status_id',1)->where('mime_type', 'LIKE', 'image/%');

            // ordered
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            if ($this->config('ord') || $this->config('dir')) {
                $ord = ['id','name','created_at','updated_at','hits'];
                $dir = ['asc','desc'];
                $dirkey = (!is_null($this->config('dir'))) ? $this->config('dir') : 0;
                $ordkey = (!is_null($this->config('ord'))) ? $this->config('ord') : 0;
                $builder = $builder->orderBy($ord[$ordkey], $dir[$dirkey]);
            }

            // inizializzo le variabili $categories e $tags
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            $categories = null;
            $tags = null;
            $file = null;

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

            }
            // considero i valori settati se tags e cats sono vuoti
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            if (!$tags and !$categories) {
                $tags = $this->config('tags');
                $categories = $this->config('categories');
            }

            // se è impostata la variabile tags applico i filtri sui tags
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            // in AND
            if ($tags) {
                foreach ($tags as $tag) {
                    $builder = $builder->whereHas('tags', function ($q) use ($tag) {
                        $q->where('tag_id', '=', $tag['tag']);
                    });
                }
            }

            // se categories non è nulla applico i filtri sulle categorie
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            // in AND
            if ($categories) {
                foreach ($categories as $category) {
                    $builder = $builder->whereHas('categories', function ($q) use ($category) {
                        $q->where('category_id', $category['category']);
                    });
                }
            }

            // se l'url contiene author applico il filtro sull'autore
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            if ($this->config('comunication') and $this->request->has('author')) {
                $builder = $builder->where('user_id',$this->request->author);
            }


            $items = [];
            // controllo se è richiesta la navigazione del contenuto
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            if ($this->config('scrolling')) {
                if ($file) {
                    $builder1 = clone $builder;
                    if ($this->config('scrolling')=='nextf') {
                        $items = $builder->where('id', '>', $file->id)->orderBy('id','asc')->paginate(1);
                        if($items->count()<1){
                            $items = $builder1->orderBy('id','asc')->paginate(1);
                        }
                    } elseif ($this->config('scrolling')=='prevf') {
                        $items = $builder->where('id', '<', $file->id)->orderBy('id','desc')->paginate(1);
                        if($items->count()<1)
                            $items = $builder1->orderBy('id','desc')->paginate(1);
                    }
                } else {
                    return;
                }
            } else {
                $perpage = $this->config('perPage') ?: 4;
                $items = $builder->paginate($perpage,['*'],'pagepid'.$this->get('id'));
            }

        }

        if ($items->count()<1) return;

        //dd($builder->toSql());

        // TODO:  INSERIRE IL VALORE DEL PAGINATE NEL SETTING

        //$template = 'listAssets';
        //if (!empty($this->config('structure_id')))
        $listView = $this->config('listView') ?:  'listAssets';

        if (!$this->config('template') && view()->exists("imageviewer::$listView")) {
            return view("imageviewer::$listView")->with([
                'items' => $items,
                'title' => $this->config('title'),
                'list'  => $this
            ]);
        } else {
            $file = [];
            foreach($items as $item) {
                $file[] = $this->getItem($item);
            }
            return implode('',$file);
        }
    }

    /**
     * Restituisce il contenuto della singola voce che compone la lista dei file
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
                if (str_contains($model, '$np_categories')) $data['_categories'] = $rec->categories;
                if (str_contains($model, '$np_page')) $data['_page'] = $this->request->segment(1);
                if (str_contains($model, '$np_description')) $data['_description'] = $rec->description;
                if (str_contains($model, '$np_extension')) $data['_extension'] = strtoupper($rec->extension);
                if (str_contains($model, '$np_size')) $data['_size'] = strtoupper($this->formatBytes($rec->size));
                if (str_contains($model, '$np_href')) $data['_href'] = "web/".$rec->slug;
                if (str_contains($model, '$np_file_name')) $data['_file_name'] = $rec->file_name;
                if (str_contains($model, '$np_mime_type')) $data['_mime_type'] = $rec->mime_type;
                if (str_contains($model, '$np_fullpath')) $data['_fullpath'] = $rec->path."/".config('lfm.thumb_folder_name')."/".$rec->file_name;
                if (str_contains($model, '$np_class_icon')) $data['_class_icon'] = "fa ".$rec->getIcon();
                if (str_contains($model, '$np_modify_url')) $data['_modify_url'] = url('/admin/files/'.$rec->id.'/edit');
                if (str_contains($model, '$np_modify_icon')) $data['_modify_icon'] = auth()->check() ? "<div style='position: relative'><a href=\"#\" class=\"pencil-update\" title=\"modifica file {$rec->name}\" onclick=\"window.open('".url('/admin/files/'.$rec->id.'/edit')."')\"><i class=\"glyphicon glyphicon-pencil\"></i></a></div>" : null;
                $data['_author_name'] = $rec->user->name; $data['_author_username'] = $rec->username; $data['_author_id'] = $rec->user_id;
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
    public function get($key=null) {
        return is_null($key) ? $this->conf : array_get($this->conf,$key);
    }

    public function configWidget($widget) {
        return (new imageController($this->rp))->configWidget($widget, $this);
    }

    private function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}