<div class="form-group">
    <div id="cp-{{$id}}">
        {{ Form::label($name, __($label), ['class' => 'col-sm-2 control-label']) }}
        <div class="col-sm-10 input-group colorpicker-element">
            {{ Form::text($name, $value, array_merge(['class' => 'form-control','id'=>$name], $attributes)) }}
            <span class="input-group-addon"><i></i></span>
        </div>
    </div>
</div>

@push('style')
    <link href="{{ asset("/node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css") }}" rel="stylesheet">
    <style>
        .input-group[class*=col-] {
            padding-right: 15px;
            padding-left: 15px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset("/node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js") }}"></script>
    <script>
        $(function () {
            $('#cp-{{$id}}').colorpicker();
        });
    </script>
@endpush