<div class="form-group">
    {{ Form::label($name, __($label), ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        <?php if(is_null($default)) $default = request()->input($name); ?>
        {!! Form::select($name, $value , $default , array_merge(['class' => 'form-control input-sm','id'=>$name],$attributes)) !!}
    </div>
</div>