<?php

namespace App\Portlets\scavelli\webcontent;

use App\Portlets\abstractPortlet as Portlet;
use App\Portlets\scavelli\webcontent\Controllers\ContentWebController;
use Exception;

class viewWebContent extends Portlet {

    public function init() {
        $this->rp->setModel('App\Models\Content\Content');
    }

    public function getContent() {

        $segments = $this->request->segments();

        $cw = $mt =null;
        if (!empty($this->config['comunication'])) {
            if ($this->request->has('content')) {
                $cw = $this->rp->findBySlug($this->request->content);
            } elseif (count($segments)>1) {
                $cw = $this->rp->findBySlug(end($segments));
            }
            // setto i meta tag della pagina con i dati del web content
            if (!is_null($cw)) $mt = 1;
        }
        if (isset($this->config['content_id']) && !empty($this->config['content_id']) && is_null($cw)) {
            $cw = $this->rp->find($this->config['content_id']);
        }
        // se non viene trrovato alcun contenuto mostra un messaggio di errore
        if (!isset($cw->content)) {
            if (array_get(cache('settings'), 'content_not_found')==1) {
                return view('errors.contentNotFound');
            } else return;
        }
        $data = json_decode($cw->content,true);
        $data['_title'] = $cw->name;
        $data['_data_creazione'] = \Carbon\Carbon::parse($cw->created_at)->format('d/m/Y');
        $data['_data_modifica'] = \Carbon\Carbon::parse($cw->updated_at)->format('d/m/Y');

        // verifico prima se è stato impostato un modello tramite portlet

        if (!empty($this->config['model_id'])) {
            $ModelId = $this->config['model_id'];
            $model = $this->rp->setModel('App\Models\Content\Modelli')->find($ModelId);

        // altrimenti se esiste un modello assegnato al contenuto lo utilizzo

        } elseif(!empty($cw->model->content)) {
            $model = $cw->model;

        // altrimenti verifico se esiste almeno un modello appartenente alla struttura del content

        } else {
            $model = $this->rp->setModel('App\Models\Content\Structure')->find($cw->structure_id)->models->where('type_id',1)->first();
        }

        if (!$model) return "Per il content \"$cw->name\" non risulta impostato alcun modello";

        if (str_contains($model, '$np_image')) $data['_image'] = $cw->getImage();
        if (str_contains($model, '$np_categories')) $data['_categories'] = $cw->categories;
        $data['_author_name'] = $cw->user->name; $data['_author_username'] = $cw->username; $data['_author_id'] = $cw->user_id;

        $update = null;
        // inserisco il pulsante per la modifica del contenuto
        if (auth()->check()) {
            $urlupdate = url("/admin/content/edit")."/".$cw->id;
            $update = "<a href=\"#\" class=\"toggle-form btn btn-info edit-button\" title=\"modifica contenuto web {$cw->id}\" onclick=\"window.open('$urlupdate')\" style=\"display: none; position: absolute; top: 10px; right: 145px;\"><i class=\"glyphicon glyphicon-pencil\"></i></a>";
        }
        $return = $this->applyModel($model->content,$data).$update;

        // se la comunicazione è attiva e il nome del content è presente nell'url, setto i meta Tag della pagina
        if ($mt) {
            $mt = array();
            $mt['title'] = $data['_title'];
            $mt['description'] = str_limit($cw->description,150);
            $mt['image'] = $cw->getImage();
            $this->setMetaTagPage($mt);
        }

        return $return;
    }

    public function configPortlet($portlet) {
        return (new ContentWebController($this->rp))->configPortlet($portlet, $this->request);
    }
}