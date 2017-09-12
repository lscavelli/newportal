{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Organizzazioni','/admin/organizations')->add('Aggiorna organizzazioni')
        ->setTcrumb($organization->name)
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
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">Dati obbligatori</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($organization, ['action' => $action,'class' => 'form-horizontal']) !!}
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nome</label>
                                <div class="col-sm-10">
                                    {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> "Nome"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status_id" class="col-sm-2 control-label">Stato</label>
                                <div class="col-sm-10">
                                    {!! Form::select('status_id', config('newportal.status_general') , \Request::input('xpage'), ['class' => "form-control input-sm", 'id'=>"status_id"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type_id" class="col-sm-2 control-label">Tipo organizzazione</label>
                                <div class="col-sm-10">
                                    {!! Form::select('type_id', config('newportal.type_organization') ,\Request::input('xpage') , ['class' => "form-control input-sm", 'id'=>"type_id"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="code" class="col-sm-2 control-label">Codice</label>
                                <div class="col-sm-10">
                                    {!! Form::text('code',null,['class' => 'form-control', 'placeholder'=> "Codice"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="parent_id" class="col-sm-2 control-label">Filiale di</label>
                                <div class="col-sm-10">
                                    {!! Form::select('parent_id', $selectOrg ,\Request::input('parent_id') , ['class' => "form-control input-sm", 'id'=>"parent_id"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Salva</button>
                                </div>
                            </div>
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
