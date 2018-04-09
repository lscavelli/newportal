<div class="build-form clearfix"></div>

@push('style')
    <style>
        .form-actions {
            display: none!important;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset("/node_modules/formeo/dist/formeo.min.js") }}"></script>
    <script>
        $(document).ready(function () {
            let container = document.querySelector('.build-form');
            let formeoOpts = {
                container: container,
                svgSprite: '{{ asset("/vendor/formeo/assets/formeo-sprite.svg") }}',
                debug: false,
                i18n: {
                    location: '{{ asset("/lang/formeo") }}/',
                    langs: [
                        'it-IT'
                    ],
                    locale: 'it-IT'
                },
                controls: {
                    sortable: false,
                    disable: {
                        elements: ['button'],
                        groups: ['layout','html']
                    },
                    elements: [{
                        tag: 'textarea',
                        attrs: {
                            type: 'ckeditor',
                            maxlength: 700,
                            className: 'form-control'
                        },
                        config: {
                            label: 'Visual editor'
                        },
                        meta: {
                            group: 'common',
                            icon: 'textarea',
                            id: 'visual-editor'
                        }
                    }]
                },
                events: {
                    /*
                    onSave: function (e) {
                        console.log(JSON.stringify(e.formData));
                        $.ajax({
                            url: '/admin/content/store',
                            data: { data: JSON.stringify(e.formData) },
                            type: 'POST',
                            dataType: 'json',
                        }).done(function (response) {
                            console.log(response);
                            //alert('dati salvati');
                        }).fail(function(){
                            //alert("Chiamata fallita!!!");
                        });
                    }
                    */
                },
                sessionStorage: false,
                editPanelOrder: ['attrs', 'options']
            };
                    @if(empty($structure->content))
            var formData = '';
                    @else
            var formData = {!! $structure->content !!} ;
                    @endif

            const formeo = new window.Formeo(formeoOpts,formData);

            $("#{!! $saveButton !!}").click( function(){
                $("#{!! $hiddenContent !!}").prop('value',formeo.formData);
                $("#{!! $formId !!}").submit();
            });
        });
    </script>
@endpush