<?php

namespace App\Libraries;

class FormComponents
{
    public $form = null;

    public function __construct() {
        $this->form = app('Collective\Html\FormBuilder');
        $this->makeComponent();
    }

    public function makeComponent() {
        $this->form->component('slText', 'components.text', ['name', 'label', 'value' => null, 'attributes' => []]);
        $this->form->component('slTextarea', 'components.textarea', ['name', 'label', 'value' => null, 'attributes' => []]);
        $this->form->component('slSubmit', 'components.submit', ['label', 'attributes' => []]);
        $this->form->component('slSelect', 'components.select', ['name', 'label', 'value' => [], 'attributes' => [], 'default'=>null]);
        $this->form->component('slDate', 'components.date', ['name', 'label', 'value' => null, 'attributes' => []]);
        $this->form->component('slEmail', 'components.email', ['name', 'label', 'value' => null, 'attributes' => []]);
        $this->form->component('slPassword', 'components.password', ['name', 'label', 'value' => null, 'attributes' => []]);
        $this->form->component('slSelect2', 'components.select2', ['name', 'label', 'value' => [], 'default'=>null, 'attributes' => []]);
    }
}

