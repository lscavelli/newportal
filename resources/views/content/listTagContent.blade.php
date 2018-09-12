{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add('Lista tag','/admin/tags')->add("Lista web Content")
->setTcrumb('Tag '.$tag->name)->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>'nome','created_at'=>'Creato il','user_id'=>'utente'])
                    ->showButtonNew(false)
                    ->customizes('created_at',function($row){
                        return Carbon\Carbon::parse($row['created_at'])->format('d/m/Y');
                    })
                    ->customizes('user_id',function($row){
                        if(isset($row['user_id']))
                            return \User::find($row['user_id'])->name;
                    })
                    ->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop