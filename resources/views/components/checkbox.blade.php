<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label>
                {!! Form::checkbox($name, $value, request()->input($name)), $label !!}
            </label>
        </div>
    </div>
</div>