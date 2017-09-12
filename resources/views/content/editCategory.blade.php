{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Categorie','/admin/categories/'.$vocabulary->id)->add('Vocabolari','/admin/vocabularies')->add('Aggiorna categorie')
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
                            <div class="form-group">
                                {{ Form::label('vocabulary_name', "Vocabolario:", ['class'=>"col-sm-2 control-label"]) }}
                                <div class="col-sm-10">
                                    {!! Form::text('vocabulary_name',$vocabulary->name,['class' => 'form-control', 'disabled'=>'']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nome</label>
                                <div class="col-sm-10">
                                    {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> "Nome"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="code" class="col-sm-2 control-label">Codice</label>
                                <div class="col-sm-10">
                                    {!! Form::text('code',null,['class' => 'form-control', 'placeholder'=> "Codice"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="parent_id" class="col-sm-2 control-label">Sottocategoria di</label>
                                <div class="col-sm-10">
                                    {!! Form::select('parent_id', $selectCat ,\Request::input('parent_id') , ['class' => "form-control input-sm", 'id'=>"parent_id"]) !!}
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
