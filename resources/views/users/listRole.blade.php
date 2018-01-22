{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add(__('Lista ruoli'))->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>__('Nome'),'slug','level'=>__('Livello')])
                    ->actions(['profile'=>__('Profilo'),'assign'=>__('Assegna permessi')])
                    ->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop