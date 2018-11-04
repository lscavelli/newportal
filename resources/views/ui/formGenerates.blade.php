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
        {!! Html::script('node_modules/ckeditor/ckeditor.js') !!}

        <script>

            var config = {
                extraPlugins: 'codesnippet',
                codeSnippet_theme: 'sunburst',
                language: '{{ config('app.locale') }}',
                filebrowserImageBrowseUrl: '/lfm?type=Images',
                filebrowserImageUploadUrl: '/lfm/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/lfm?type=Files',
                filebrowserUploadUrl: '/lfm/upload?type=Files&_token=',
                allowedContent: true,
                extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
            };

            // Set your configuration options below.

            // Examples:
            // config.language = 'pl';
            // config.skin = 'jquery-mobile';

            // CKFinder.define( configFinder );

            @foreach ($form->fieldsCKEditor as $nameField)
                config['height'] = 400;
                CKEDITOR.replace('{{$nameField }}', config);
                CKEDITOR.dtd.$removeEmpty.i = 0;
            @endforeach
        </script>
    @stop
@endif
