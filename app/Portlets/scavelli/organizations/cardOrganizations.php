<?php

namespace app\Portlets\scavelli\organizations;

use App\Portlets\abstractPortlet as Portlet;


class cardOrganizations extends Portlet {

    public function init() {
        $this->rp->setModel('App\Models\Organization');
    }

    public function getContent() {
        $organization = $this->rp->where('id',$this->request('organizationId'))->get();
        return view('organizations::cardOrganization')->with(['organization'=>$organization])->render();
    }

    public function configPortlet($portlet) {
        return "Under Costruction - Autore della Portlet: ".$portlet->author;
        // TODO: Implement configPortlet() method.
    }
}