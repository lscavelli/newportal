{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Settaggi sito')
        ->setTcrumb('Settaggi sito')
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
                    <li class="active"><a href="#impostazioni" data-toggle="tab" aria-expanded="true">Impostazioni</a></li>
                    <li><a href="#altridati" data-toggle="tab">Altri dati</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="impostazioni">
                        {!! Form::open(['action' => $action,'class' => 'form-horizontal']) !!}
                            <div class="form-group">
                                <label for="open_registration" class="col-sm-2 control-label">Autoregistrazione utenti</label>
                                <div class="col-sm-10">
                                    {!! Form::select('open_registration', ['Disabilitata','Abilitata'] , $settings->get('open_registration'), ['class' => "form-control input-sm", 'id'=>"open_registration"]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="social_registration" class="col-sm-2 control-label">Registrazione social</label>
                                <div class="col-sm-10">
                                    {!! Form::select('social_registration', ['Dissattiva','Attiva'] , $settings->get('social_registration'), ['class' => "form-control input-sm", 'id'=>"social_registration"]) !!}
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
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="altridati">
                        {!! Form::open(['action' => $action,'class' => 'form-horizontal']) !!}

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
