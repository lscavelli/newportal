@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__("File"),'/admin/files')->add(__("Aggiorna files"))
        ->setTcrumb($file->name)
        ->render() !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">{{ __("Dati obbligatori") }}</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($file, ['url' => url('admin/files',$file->id),'class' => 'form-horizontal']) !!}
                            @if(isset($file->id))@method('PUT')@endif
                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slText('file_name','Nome file',null,['disabled'=>'']) !!}
                            {!! Form::slTextarea('description','Descrizione') !!}
                            {!! Form::slSelect('status_id','Stato',config('newportal.status_general')) !!}
                            {!! Form::slText('size','Dimensione',null,['disabled'=>'']) !!}
                            {!! Form::slText('mime_type','Tipo',null,['disabled'=>'']) !!}
                            {!! Form::slText('path','Percorso',null,['disabled'=>'']) !!}
                            {!! Form::slText('hits','Visualizzazioni',null,['disabled'=>'']) !!}
                            {!! Form::slText('created_at','Creato',Carbon\Carbon::parse($file->created_at)->format('d/m/Y - H:i'),['disabled'=>'']) !!}
                            {!! Form::slText('updated_at','Modificato',Carbon\Carbon::parse($file->updated_at)->format('d/m/Y - H:i'),['disabled'=>'']) !!}
                            {!! Form::slSubmit('Salva') !!}
                        {!! Form::close() !!}

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop
