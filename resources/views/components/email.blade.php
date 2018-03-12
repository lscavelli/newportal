<div class="form-group">
    {{ Form::label($name, __($label), ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        <?php if( Auth()->check() && !(Auth()->user()->isUserManager() )) $attributes+=['disabled'=>'']; ?>
        {{ Form::email($name, $value, array_merge(['class' => 'form-control','id'=>$name, 'placeholder'=> __($label)], $attributes)) }}
    </div>
</div>