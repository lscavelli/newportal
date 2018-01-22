{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Lista gruppi'))->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id'=>'Id','name'=>__('Nome'),'slug','description'=>__('Descrizione'),'numuser'=>__('Num. utenti')])
                    ->sortFields(['id','name','slug'])
                    ->actions(['profile'=>__('Profilo'),'assign'=>__('Assegna utenti'),'assignPerm'=>__('Assegna permessi'),'assignRole'=>__('Assegna ruoli')])
                    ->customizes('numuser',function($row) use($user_group){
                        return count($user_group->where('group_id',$row['id']));
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop