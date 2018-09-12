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
                            {!! Form::slSelect('open_registration','Autoregistrazione utenti',['Disabilitata','Abilitata'],[],$settings->get('open_registration')) !!}
                            {!! Form::slSelect('social_registration','Registrazione social',['Disattivata','Attivata'],[],$settings->get('social_registration')) !!}
                            {!! Form::slSelect('content_not_found','Messaggio per contenuto non trovato',['Non visualizzare','Visualizza'],[],$settings->get('content_not_found')) !!}
                            {!! Form::slSelect('tag_dynamic','Abilita Tag dinamico',['Disabilitato','Abilitato'],[],$settings->get('tag_dynamic')) !!}
                            {!! Form::slSelect('2fa_activation','Auth. a due fattori',['Disattivata','Attivata'],[],$settings->get('2fa_activation')) !!}
                            {!! Form::slSubmit('Salva') !!}
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
