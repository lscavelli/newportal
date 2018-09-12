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
                    {!! Form::model($structure, ['url'=>url('admin/structure',$structure->id),'id'=>'structureForm','class' => 'form-horizontal']) !!}
                        @if(isset($structure->id))@method('PUT')@endif

                        <div class="col-md-6">
                            <input type="hidden" name="content" id="content"  value="">
                            <input type="hidden" name="service_id" id="service_id"  value="{{ $service->id }}">
                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slSelect('status_id','Stato',config('newportal.status_general')) !!}
                            {!! Form::slText('created_at','Creato',Carbon\Carbon::parse($structure->created_at)->format('d/m/Y - H:i'),['disabled'=>'']) !!}
                            {!! Form::slText('updated_at','Modificato',Carbon\Carbon::parse($structure->updated_at)->format('d/m/Y - H:i'),['disabled'=>'']) !!}
                            {!! Form::slText('Service','Servizio',$service->name,['disabled'=>'']) !!}
                        </div>
                        <div class="col-md-6">
                            {!! Form::slTextarea('description','Descrizione') !!}
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
            {!! Form::slFormeo($structure,'structureForm','saveStructure','content') !!}

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@stop

