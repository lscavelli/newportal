<?php

namespace app\Portlets\scavelli\organizations;

use App\Portlets\abstractPortlet as Portlet;


class listOrganizations extends Portlet {

    public function init() {
        $this->rp->setModel('App\Models\Organization');

        $this->theme->addExCss($this->getPath().'css/treeview.css');
        $this->theme->addExJs($this->getPath().'js/treeview.js');
    }

    public function getContent() {
        $organizations = $this->rp->whereNull('parent_id')->get();
        return view('organizations::listOrganization')->with(['organizations'=>$organizations,'title'=>'test title']);
    }

    public function configPortlet($portlet) {
        return "Autore della Portlet: ".$portlet->author;
        // TODO: Implement configPortlet() method.
    }
}