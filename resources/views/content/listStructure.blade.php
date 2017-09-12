{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Strutture dei contenuti')->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id'=>'Id','name'=>'Titolo','created_at'=>'Creato il','updated_at'=>'Aggiornato il' ])
                    ->sortFields(['id','name','created_at','updated_at'])
                    ->actions([url('/admin/content/create')=>'Nuovo contenuto web',url('/admin/content')=>'Lista contenuti web',url('/admin/models')=>'Lista modelli',url('/admin/models/create')=>'Crea modello'])
                    ->customizes('created_at',function($row){
                        return Carbon\Carbon::parse($row['created_at'])->format('d/m/Y');
                    })
                    ->customizes('updated_at',function($row){
                        return Carbon\Carbon::parse($row['updated_at'])->format('d/m/Y');
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop