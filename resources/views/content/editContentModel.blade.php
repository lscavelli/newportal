<!--?php dd(json_encode($content->tags()->pluck('id'))); ?>-->

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista contenuti web','/admin/content')->add('Aggiorna contenuto')
        ->setTcrumb($content->name)
        ->render() !!}
@stop

@section('content')
    <div class="row">

        <div class="col-md-9">
            <div class="box box-info" style="padding-top: 20px;">
                <div class="box-body">
                    {!! Form::model($content, ['action' => $action,'class' => 'form-horizontal']) !!}

                    <div class="form-group">
                        {{ Form::label('structure_name', "Struttura dati:", ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {!! Form::text('structure_name',$structure_name,['class' => 'form-control', "id"=>'structure_name', 'disabled'=>'']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('model_id', "Modello:", ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {!! Form::select('model_id', $listModels , \Request::input('model_id'), ['class' => "form-control input-sm", 'id'=>"model_id"]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-danger" value="1">Salva</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

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

    </div>
@stop