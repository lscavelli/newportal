<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="pull-{{ $pull }}">
            {{ Form::submit(__($label), array_merge(['class' => 'btn btn-'.$color],$attributes)) }}
        </div>
    </div>
</div>