<?php

namespace App\Portlets\scavelli\socialsharing;

use App\Portlets\abstractPortlet as Portlet;
use App\Portlets\scavelli\socialsharing\Controllers\SocialSharingController;

class viewSocialSharing extends Portlet {

    public $item;

    public function init() {
        //
    }

    public function getContent() {
        if (!empty($this->config('providers'))) {
            foreach($this->config('providers') as $provider=>$param) {
                if ($this->config($provider)) {
                    $url = array_get($param,'uri').urlencode(request()->getUri());
                }
            }
        };
        return;
    }

    public function configPortlet($portlet) {
        //return (new SocialSharingController($this->rp))->configPortlet($portlet, $this->request);
    }
}