<?php

namespace app\Services;

use Illuminate\Support\Facades\View;


class ComposerView {

    public $box;


    public function __construct() {
        $this->box = [
            'type'=>'success',
            'srcImag'=>null,
            'title'=>'Altri dati',
            'subTitle'=>'',
            'description'=>'',
            'created_at'=>date('Y/m/d'),
            'updated_at'=>date('Y/m/d'),
            'color'=>"blue",
        ];
    }

    public function boxProfile($attribute, $val=null) {
        $this->box['View'] = "boxProfile";
        $this->add($attribute, $val);
        return $this;
    }

    public function boxNavigator($attribute, $val=null) {
        $this->box['View'] = "boxNavigator";
        $this->add($attribute, $val);
        return $this;
    }

    private function add($attribute, $val=null) {
        if (!empty($attribute)) {
            $attr = (is_array($attribute)) ? $attribute : [$attribute => $val];
            $this->box = array_merge($this->box,$attr);
        }
    }

    /**
     * @return $this
     */
    public function removeAll() {
        $this->box = [];
        return $this;
    }

    /**
     * @param null $viewp
     * @return mixed
     */
    public function render($viewp = null) {
        $view = "ui.".$this->box['View'];
        if (!empty($viewp)) $view = $viewp;
        return View::make($view, [
            'items' => $this
        ])->render();
    }
}