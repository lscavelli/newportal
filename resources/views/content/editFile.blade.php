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

        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">{{ __("Dati obbligatori") }}</a></li>
                    <li><a href="#categorization" data-toggle="tab">{{ __("Categorizzazione") }}</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($file, ['url' => url('admin/files',$file->id),'class' => 'form-horizontal']) !!}
                            @if(isset($file->id))@method('PUT')@endif
                            {!! Form::slText('name','Titolo') !!}
                            {!! Form::slText('file_name','Nome file',null,['disabled'=>'']) !!}
                            {!! Form::slTextarea('description','Descrizione') !!}
                            {!! Form::slText('slug','Slug',null,['placeholder'=> __("Generato automaticamente se lasciato vuoto")]) !!}
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

                    <div class="tab-pane" id="categorization">

                        {!! Form::model($file, ['url' => url('admin/files/categories',$file->id),'class' => 'form-horizontal']) !!}
                            {!! Form::slText('name','Titolo',null,['disabled'=>'']) !!}
                            {!! Form::slCategory($vocabularies,$tags,$file) !!}
                            {!! Form::slSubmit('Salva',['name'=>'saveCategory']) !!}
                        {!! Form::close() !!}

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->

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

                        <div class="image" style="text-align: center; padding: 15px 15px 0 15px">
                            @if($file->isImage())
                                <img src="{{ $file->getPath() }}" alt="Content Image" style="border-radius: 0.375em; width:100%;">
                            @else
                                <div style="border: 1px solid lightgrey; padding: 80px 0 80px 0; border-radius: 0.375em;" ><i class="fa {{ $file->getIcon() }} fa-5x"></i></div>
                            @endif
                        </div>
                        <div style="padding: 15px"><a href="/admin/files/download/{!! $file->id !!}" class="btn btn-default btn-block"><i class="fa fa-download"></i> {{ __("Download file") }}</a></div>
                        <div style="padding: 0px 15px 15px 15px"><a href="/admin/files/view/{!! $file->id !!}" class="btn btn-default btn-block"><i class="fa fa-eye"></i> {{ __("Visualizza file") }}</a></div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>

    </div>
    <!-- /.row -->
</section>
@stop
