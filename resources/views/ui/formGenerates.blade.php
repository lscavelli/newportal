@foreach ($form->rows() as $row)
    <div class="row">
        @foreach ($row->columns as $column)
            <div class="col-md-{{ $form->classCol($row) }}">
                @foreach ($form->fields($column) as $field)
                    <div class="form-group">
                        {{ $form->label($field) }}
                        <div class="col-sm-10">
                            {!! $form->field($field) !!}
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- /.col -->
        @endforeach
    </div>
    <!-- /.row -->
@endforeach

@if(count($form->fieldsCKEditor)>0)
    @section('scripts')
        {!! Html::script('bower_components/ckeditor/ckeditor.js') !!}

        <script>

            var config = {
                extraPlugins: 'codesnippet',
                codeSnippet_theme: 'sunburst',
                language: '{{ config('app.locale') }}',
                filebrowserBrowseUrl: '/elfinder/ckeditor',
                allowedContent: true,
                extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
            };

            @foreach ($form->fieldsCKEditor as $nameField)
                config['height'] = 400;
                CKEDITOR.replace('{{$nameField }}', config);
                CKEDITOR.dtd.$removeEmpty.i = 0;
            @endforeach
        </script>
    @stop
@endif
