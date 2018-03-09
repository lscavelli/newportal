<div class="form-group">
    {{ Form::label($name, __($label), ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        {!! Form::select($name, $value , request()->input($name) , array_merge(['class' => 'form-control','id'=>$name],$attributes)) !!}
    </div>
</div>