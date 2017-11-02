@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista utenti')->render() !!}
@stop

@section('content')
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','cognome','nome', 'email', 'created_at'=>'Registrato il'])
                    ->actions(['profile'=>'Profilo','activity'=>'Attività',
                    'assignRole'=>'Assegna ruoli','assignPerm'=>'Assegna permessi','impersonate'=>'Impersona Utente'])
                    ->customizes('created_at',function($row){
                        return Carbon\Carbon::parse($row['created_at'])->format('d/m/Y');
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
@stop