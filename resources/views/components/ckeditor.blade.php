<div class="form-group">
    {{ Form::label($name, __($label), ['class' => 'col-sm-2 control-label']) }}
    <div class="col-sm-10">
        {{ Form::textarea($name,$value,array_merge(['class' => 'form-control','id'=>$name],$attributes)) }}
    </div>
</div>
@push('style')
@endpush
@push('scripts')
    {!! Html::script('node_modules/ckeditor/ckeditor.js') !!}

    <script type="text/javascript">
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

        config['height'] = 400;
        CKEDITOR.replace('{{ $name }}', config);
        CKEDITOR.dtd.$removeEmpty.i = 0;
    </script>
@endpush