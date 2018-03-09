<div class="form-group">
    {{ Form::label($name, __($label), ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        {{ Form::text($name, $value, array_merge(['class' => 'form-control','id'=>$name, 'placeholder'=> __($label)],$attributes)) }}
    </div>
</div>