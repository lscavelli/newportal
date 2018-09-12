{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Dynamic Data List','/admin/ddl')->add('Aggiorna Dynamic Data')
        ->setTcrumb($dynamicData->name)
        ->render() !!}
@stop


@section('content')
    @include('ui.messages')
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary" style="padding-top: 20px;">
                <div class="box-body">

                    {!! Form::model($dynamicData, ['url'=>url('admin/ddl',$dynamicData->id),'id'=>'structureForm','class' => 'form-horizontal']) !!}
                        @if(isset($dynamicData->id))@method('PUT')@endif

                        {!! Form::slText('name','Nome') !!}
                        {!! Form::slTextarea('description','Descrizione') !!}
                        {!! Form::slSelect('status_id','Stato',config('newportal.status_general')) !!}
                        {!! Form::slSelect2('structure_id','Strutture dati',$structureOptions,$dynamicData->structure_id,'/admin/ddl/structure/') !!}
                        {!! Form::slSubmit('Salva') !!}

                    {!! Form::close() !!}

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@stop

