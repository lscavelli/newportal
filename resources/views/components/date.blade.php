<div class="form-group">
    {{ Form::label($name, __($label), ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <?php $date = (isset($value->date)?$value->date->format('d/m/Y'): null); ?>
            {{ Form::text($name, $date, array_merge(['class' => 'form-control pull-right date-picker','id'=>$name],$attributes)) }}
        </div>
    </div>
</div>

@section('dateStyle')
    <link rel="stylesheet" href="{{ asset("/node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/css/custom.datetimepicker.css") }}">
@stop
@section('dateScript')
    <script src="{{ asset("/node_modules/moment/min/moment.min.js") }}"></script>
    <script src="{{ asset("/node_modules/moment/locale/it.js") }}"></script>
    <script src="{{ asset("/node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}"></script>
    <script>
        //moment.locale('it');
        //Date Time Picker
        if ($('.date-time-picker')[0]) {
            $('.date-time-picker').datetimepicker();
        }
        //Time
        if ($('.time-picker')[0]) {
            $('.time-picker').datetimepicker({
                format: 'LT'
            });
        }
        //Date
        if ($('.date-picker')[0]) {
            $('.date-picker').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        }
    </script>
@stop