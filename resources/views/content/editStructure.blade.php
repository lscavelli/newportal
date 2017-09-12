{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Struttura dei contenuti','/admin/structure')->add('Aggiorna struttura')
        ->setTcrumb($structure->name)
        ->render() !!}
@stop


@section('content')
    @include('ui.messages')
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary" style="padding-top: 20px;">
                <div class="box-body">
                    {!! Form::model($structure, ['action' => $action,'id'=>'structureForm']) !!}

                    <div class="col-md-6">
                        <input type="hidden" name="content" id="content"  value="">
                        <div class="form-group">
                            <label for="name">Nome</label>
                            {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> "Nome", "id"=>'name']) !!}
                        </div>
                        <div class="form-group">
                            <label for="status_id">Stato</label>
                            {!! Form::select('status_id', config('newportal.status_general') , \Request::input('status_id'), ['class' => "form-control input-sm", 'id'=>"status_id"]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('created_at', "Creato il:") }}
                            {!! Form::text('created_at',Carbon\Carbon::parse($structure->created_at)->format('d/m/Y - H:i'),['class' => 'form-control', "id"=>'created_at', 'disabled'=>'']) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('updated_at', "Aggiornato il:") }}
                            {!! Form::text('updated_at',Carbon\Carbon::parse($structure->updated_at)->format('d/m/Y - H:i'),['class' => 'form-control', "id"=>'updated_at', 'disabled'=>'']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Descrizione</label>
                            {!! Form::textarea('description',null,['class' => 'form-control', 'placeholder'=> "Descrizione"]) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-danger" id="saveStructure">Salva</button>
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
            <div class="build-form clearfix"></div><br />

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@stop
@push('style')
<style>
    .form-actions {
        display: none!important;
    }
</style>
@endpush
@push('scripts')
    <script src="{{ asset("/bower_components/formeo/dist/formeo.min.js") }}"></script>
    <script>
        $(document).ready(function () {
            let container = document.querySelector('.build-form');
            let formeoOpts = {
                container: container,
                svgSprite: '{{ asset("/bower_components/formeo/dist/formeo-sprite.svg") }}',
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

            $("#saveStructure").click( function(){
                $("#content").prop('value',formeo.formData);
                $("#structureForm").submit();
            });
        });
    </script>
@endpush

