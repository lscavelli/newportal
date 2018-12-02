<div class="form-group">
    @if($label)
        {{ Form::label($name, __($label), ['class' => 'col-sm-2 control-label']) }}
    @endif
    <div class="@if($label) col-sm-10 @else col-sm-12 @endif">
        {{ Form::text($name, $value, array_merge(['class' => 'form-control','id'=>$name, 'placeholder'=> __($label)], $attributes)) }}
    </div>
</div>
