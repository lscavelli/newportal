<div class="form-group">
    {{ Form::label($name, __($label), ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        <?php if(is_null($default)) $default = request()->input($name); ?>
        {!! Form::select($name, $value , $default , array_merge(['class' => "js-example-basic-single js-states form-control",'style'=>"width: 100%",'aria-hidden'=>"true",'id'=>$name],$attributes)) !!}
    </div>
</div>

@push('style')
    {{ Html::style('/node_modules/select2/dist/css/select2.min.css') }}
    <style>
        .skin-purple-light, .main-header, .navbar {
            background-color: #2C3E50!important;
        }
        .skin-blue, .main-header, .logo {
            background-color: #FFF!important;
            color: #333!important;
        }
    </style>
@endpush
@push('scripts')
    {{ Html::script('/node_modules/select2/dist/js/select2.min.js') }}
    <script>
        $(".js-example-basic-single").select2({
            minimumInputLength: 3,
            ajax: {
                url: '/admin/users/cities/',
                data: function (params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                },
                dataType: 'json',
                delay: 250
            }
        });
    </script>
@endpush