{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Categorie','/admin/vocabularies/cat/'.$vocabulary->id)->add('Vocabolari','/admin/vocabularies')->add('Aggiorna categorie')
        ->setTcrumb('Vocabolario: '.$vocabulary->name)
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

                        {!! Form::model($category, ['action' => $action,'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('vocabulary_id',$vocabulary->id) !!}

                            {!! Form::slText('vocabulary_name','Vocabolario',$vocabulary->name,['class' => 'form-control', 'disabled'=>'']) !!}
                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slText('code','Codice') !!}
                            {!! Form::slColorPicker('color','Colore',$category->id) !!}
                            {!! Form::slSelect('parent_id','Sottocategoria di',$selectCat) !!}
                            {!! Form::slSubmit('Salva') !!}

                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane input-sm-->
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