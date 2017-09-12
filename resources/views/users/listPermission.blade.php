{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add('Lista permessi')->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>'nome','slug'=>'abbreviazione','description'=>'descrizione'])
                    ->actions(['profile'=>'Profilo'])
                    ->customizes('description',function($row){
                        return \App\Libraries\sl_text::sommario($row['description'],50);
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop