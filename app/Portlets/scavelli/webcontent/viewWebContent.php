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

        if (!empty($this->config['comunication']) and $this->request->has('content')) {
            $content = $this->rp->findBySlug($this->request->content);
        } else {
            if (!isset($this->config['content_id']) or empty($this->config['content_id'])) return;
            $content = $this->rp->find($this->config['content_id']);
        }

        if (!isset($content->content)) return;
        $data = json_decode($content->content,true);
        $data['_title'] = $content->name;
        $data['_data_creazione'] = \Carbon\Carbon::parse($content->created_at)->format('d/m/Y');
        $data['_data_modifica'] = \Carbon\Carbon::parse($content->updated_at)->format('d/m/Y');

        // verifico se è stato impostato un modello tramite portlet

        if (!empty($this->config['model_id'])) {
            $ModelId = $this->config['model_id'];
            $model = $this->rp->setModel('App\Models\Content\Modelli')->find($ModelId);

        // verifico se è stato assegnato un modello al contenuto

        } elseif(!empty($content->model->content)) {
            $model = $content->model;

        // verifico se esiste almeno un modello appartenente alla struttura del content

        } else {
            $model = $this->rp->setModel('App\Models\Content\Structure')->find($content->structure_id)->models->where('type_id',1)->first();
        }

        if (!$model) return "Per il content \"$content->name\" non risulta impostato alcun modello";

        if (str_contains($model, '$np_image')) $data['_image'] = $content->getImage();
        if (str_contains($model, '$np_categories')) $data['_categories'] = $content->categories;
        $data['_author_name'] = $content->user->name; $data['_author_username'] = $content->username; $data['_author_id'] = $content->user_id;

        return $this->applyModel($model->content,$data);
    }

    public function configPortlet($portlet) {
        return (new ContentWebController($this->rp))->configPortlet($portlet, $this->request);
    }
}