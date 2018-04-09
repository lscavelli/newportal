@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista contenuti web','/admin/content')->add('Aggiorna contenuto')
        ->setTcrumb($content->name)
        ->render() !!}
@stop

@section('content')
    @include('ui.messages')
    <div class="row">

        <div class="col-md-@if(isset($content->id)){{9}}@else{{12}}@endif">
            <div class="box" style="padding-top: 20px;">
                <div class="box-body">
                    {!! Form::model($content, ['action' => $action,'class' => 'form-horizontal']) !!}
                        @if(isset($structureId))<input type="hidden" value="{{ $structureId }}" name="structureId">@endif

                        {!! Form::slText('name','Nome') !!}

                        {!!

                        $form->render()

                        !!}

                        {!! Form::slText('slug','Slug',null,['placeholder'=> __("Generato automaticamente se lasciato vuoto")]) !!}
                        {!! Form::slSubmit('Salva') !!}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        @if(isset($content->id))
        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border" style="background-color: #f8f8f8; border-radius: 3px">
                    <h3 class="box-title">Web Content menu</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding" style="display: block;">
                    @include('content.navigation_content')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        @endif
    </div>
@stop
