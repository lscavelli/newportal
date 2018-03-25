{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista files')->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id'=>'Id','name'=>'Nome','file_name'=>'File','created_at'=>__('Creato il')])
                    ->sortFields(['id','name','file_name'])
                    ->customizes('created_at',function($row){
                        return $row['created_at']->format('d/m/Y');
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop