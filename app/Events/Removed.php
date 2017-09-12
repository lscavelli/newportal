<?php

namespace App\Events;

class Removed extends eloquentEvent {

    protected $children;

    /**
     * Removed constructor.
     * @param $parent
     * @param null $children
     */
    public function __construct($parent,$children=null) {
        parent::__construct($parent);
        $this->children = $children;
    }

    public function getRemoved() {
        return $this->children;
    }
}
