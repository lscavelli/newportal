<?php

namespace app\Widgets\scavelli\organizations;

use App\Widgets\abstractWidget as Widget;

class cardOrganizations extends Widget {

    public function init() {
        $this->rp->setModel('App\Models\Organization');
    }

    public function render() {
        $organization = $this->rp->where('id',$this->request('organizationId'))->get();
        return view('organizations::cardOrganization')->with(['organization'=>$organization])->render();
    }

    public function configWidget($widget) {
        return "Under Costruction - Autore della Widget: ".$widget->author;
        // TODO: Implement configWidget() method.
    }
}