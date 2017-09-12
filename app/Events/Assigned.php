<?php

namespace App\Events;

class Assigned extends eloquentEvent {

    protected $related;

    /**
     * Assigned constructor.
     * @param $parent
     * @param $related
     */
    public function __construct($parent,$related) {
        parent::__construct($parent);
        $this->related = $related;
    }

    public function getAssigned() {
        return $this->related;
    }
}
