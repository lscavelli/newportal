@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__("Lista utenti"))->render() !!}
@stop

@section('content')
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>__("Nome"), 'status_id'=>'Stato','email', 'created_at'=>__("Registrato il")])
                    ->actions([__('Profilo'),'activity'=>__('Attività'),
                    'assignRole'=>__('Assegna ruoli'),'assignPerm'=>__('Assegna permessi'),'impersonate'=>__('Impersona Utente')])
                    ->customizes('name',function($row){
                        return $row->name;
                    })
                    ->customizes('status_id',function($row){
                        return  '<span class="label" style="background-color:'.config('newportal.status_color')[$row->status_id]. '">'. config('newportal.status_user')[$row->status_id] .'</span>';
                    })
                    ->customizes('created_at',function($row){
                        return $row['created_at']->format('d/m/Y');
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
@stop
