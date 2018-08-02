<?php

namespace app\Widgets\scavelli\organizations;

use App\Widgets\abstractWidget as Widget;

class listOrganizations extends Widget {

    public function init() {
        $this->rp->setModel('App\Models\Organization');
        $this->theme->addExCss($this->getPath().'css/treeview.css');
        $this->theme->addExJs($this->getPath().'js/treeview.js');
    }

    public function render() {
        $organizations = $this->rp->whereNull('parent_id')->get();
        if ($organizations->count()<1) return;
        return view('organizations::listOrganization')->with(['organizations'=>$organizations,'title'=>'test title'])->render();
    }

    public function configWidget($widget) {
        return "Under Costruction - Autore della Widget: ".$widget->author;
        // TODO: Implement configWidget() method.
    }
}