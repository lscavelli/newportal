{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista gruppi')->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id'=>'Id','name'=>'Nome','slug'=>'Abbreviazione','description'=>'Descrizione','numuser'=>'Num Utenti'])
                    ->sortFields(['id','name','slug'])
                    ->actions(['profile'=>'Profilo','assign'=>'Assegna utenti','assignPerm'=>'Assegna permessi','assignRole'=>'Assegna ruoli'])
                    ->customizes('numuser',function($row) use($user_group){
                        return count($user_group->where('group_id',$row['id']));
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop