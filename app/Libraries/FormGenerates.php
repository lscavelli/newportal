<?php

namespace App\Libraries;

use Illuminate\Support\Facades\View;


class FormGenerates
{
    public $form = null;
    private $schema = null;
    private $initialData = null;
    public $formBuilder;
    public $fieldsCKEditor = [];
    private $listLabel = [];
    private $listType = [];

    /**
     * for controller
     * @return mixed
     */
    public function __construct($jsonSchema = null, $initialData = null) {
        $this->formBuilder = app('Collective\Html\FormBuilder');
        if (!empty($jsonSchema))
            $this->setFormData($jsonSchema, $initialData);
    }

    public function setFormData($jsonSchema, $initialData = null) {
        $this->schema = json_decode($jsonSchema);
        if (!empty($initialData)) {
            $this->initialData = json_decode($initialData);
        }
        return $this;
    }

    /**
     * Per il template ui
     * @return mixed
     */
    public function rows() {
        return $this->schema->rows;
    }

    /**
     * Per il template ui
     * @return mixed
     */
    public function fields($column) {
        return $this->schema->columns->$column->fields;
    }

    public function classCol($row) {
        $cols = [1,2,3,4,6,12];
        $numcol = count($row->columns);
        if (!in_array($numcol, $cols)) return 12;
        return 12 / $numcol;
    }

    public function field($field) {
        $field = $this->schema->fields->$field;
        $type = $this->getType($field);
        if (!method_exists($this,"$type")) return null;
        $id = $field->id;
        $value = (isset($field->attrs->value)) ? $field->attrs->value : null;
        if (is_null($value) and isset($field->content)) $value = $field->content;
        foreach ($field->attrs as $key=>$val) $attrs[$key] = $val; // convert stdClass Object to Array
        $attrs['class'] = (isset($attrs['class'])) ? $attrs['class'].' form-control' : 'form-control';
        if (!empty($this->initialData->$id)) $value = $this->initialData->$id;
        return $this->$type($field,$attrs,$value);
    }

    public function text($field,$attrs, $value) {
        return $this->formBuilder->text($field->id, $value, $attrs);
    }

    public function number($field,$attrs, $value) {
        return $this->formBuilder->number($field->id, $value, $attrs);
    }

    public function textarea($field,$attrs, $value) {
        return $this->formBuilder->textarea($field->id, $value, $attrs);
    }

    public function ckeditor($field,$attrs, $value) {
        $this->fieldsCKEditor[] = $field->id;
        return $this->textarea($field,$attrs, htmlspecialchars($value));
    }

    public function date($field,$attrs,$value) {
        $attrs = ['class'=>'form-control pull-right date-picker'];
        $data = "<div class=\"input-group date\"><div class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></div>";
        $data .= $this->formBuilder->date($field->id, $value, $attrs);
        $data .= "</div>";
        return $data;
    }

    public function select($field,$attrs,$value) {
        $list = [];
        if (isset($field->options)) {
            foreach ($field->options as $obj) {
                $list[$obj->value] = $obj->label;
            }
        }
        $selected = $value;
        return $this->formBuilder->select($field->id, $list, $selected , $attrs);
    }

    public function radio($field,$attrs,$value=null) {
        $data = null;
        if (isset($field->options)) {
            foreach ($field->options as $obj) {
                $selected = false;
                if (is_null($value)) {
                    $selected = $obj->selected;
                } elseif ($value==$obj->value) $selected = true;
                $data .= "<div class='radio'><label>";
                $data .= $this->formBuilder->radio($field->id, $obj->value , $selected);
                $data .= "$obj->label</label>";
                $data .= "</div>";
            }
        }
        return $data;
    }

    public function checkbox($field,$attrs,$value) {
        $data = null;
        if (isset($field->options)) {
            foreach ($field->options as $obj) {
                $selected = false;
                if (is_null($value)) {
                    $selected = $obj->selected;
                } elseif ($value==$obj->value) $selected = true;
                $data .= "<div class='checkbox'><label>";
                $data .= $this->formBuilder->checkbox($obj->value, $obj->value , $selected);
                $data .= "$obj->label</label>";
                $data .= "</div>";
            }
        }
        return $data;
    }

    public function label($field) {
        $field = $this->schema->fields->$field;
        $options = ['class' => "col-sm-2 control-label"];
        return $this->formBuilder->label($field->id, $field->config->label, $options);
    }

    /**
     * for view and controller
     * @param null $viewp
     * @return mixed
     */
    public function render($viewp = null) {
        //print "<pre>";
        //print_r($this->schema); exit;
        $view = "ui.formGenerates";
        if (!empty($viewp)) $view = $viewp;
        return View::make($view, [
            'form' => $this,
        ])->render();
    }

    private function getType($field) {
        $type = $field->tag;
        if (!empty($field->attrs->type)) $type = $field->attrs->type;
        return $type;
    }

    public function setList() {
        if(!isset($this->schema->fields)) return;
        foreach ($this->schema->fields as $field) {
            $this->listLabel[$field->id] = $field->config->label;
            $this->listType[$field->id] = $this->getType($field);
        }
    }

    public function listLabel() {
        if (count($this->listLabel)<1) $this->setList();
        return $this->listLabel;
    }

    public function listType() {
        if (count($this->listType)<1) $this->setList();
        return $this->listType;
    }


}