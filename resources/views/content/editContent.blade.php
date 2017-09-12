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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nome</label>
                                <div class="col-sm-10">
                                    {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> "Nome"]) !!}
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    {!!

                    $form->render()

                    !!}
                    <div class="form-group">
                        <label for="slug" class="col-sm-2 control-label">Abbreviazione</label>
                        <div class="col-sm-10">
                            {!! Form::text('slug',null,['class' => 'form-control', 'placeholder'=> "Lasciare vuoto per generare automaticamente"]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-danger">Salva</button>
                        </div>
                    </div>
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
