@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Dynamic DataList','/admin/ddl')
    ->add($dataList->name,'/admin/ddl/content/'.$dataList->id)
    ->add('Aggiorna dati dinamici')->render() !!}
@stop

@section('content')
    @include('ui.messages')
    <div class="box" style="padding-top: 20px;">
        <div class="box-body">
            {!! Form::model($content, ['url' => $url,'class' => 'form-horizontal']) !!}
                <input type="hidden" value="{{ $dataList->id }}" name="dynamicdatalist_id">
                {!!

                $form->render()

                !!}
                {!! Form::slSubmit('Salva') !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
