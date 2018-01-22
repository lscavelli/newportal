{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Ruoli'),'/admin/roles')->add(__('Aggiorna ruoli'))
        ->setTcrumb($role->name)
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
                    <li class="active"><a href="#editruoli" data-toggle="tab" aria-expanded="true">{{ __("Dettagli ruolo") }}</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="editruoli">

                        {!! Form::model($role, ['action' => $action,'class' => 'form-horizontal']) !!}
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">{{ __("Nome") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> __("Nome")]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="slug" class="col-sm-2 control-label">Slug</label>
                                <div class="col-sm-10">
                                    {!! Form::text('slug',null,['class' => 'form-control', 'placeholder'=> __("Generato automaticamente se lasciato vuoto")]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="level" class="col-sm-2 control-label">{{ __("Livello") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::select('level', config('newportal.levelRole') , \Request::input('level'), ['class' => "form-control input-sm"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-2 control-label">{{ __("Descrizione") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::textarea('description',null,['class' => 'form-control', 'placeholder'=> __("Descrizione")]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">{{ __("Salva") }}</button>
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
