@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista vocabolari')->render() !!}
@stop

@section('content')
    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>'Nome','updated_at'=>'Data di Aggiornamento'])
                    ->actions([url('/admin/categories/create/')=>'Nuova categoria',url('/admin/categories/')=>'Lista categorie'])
                    ->customizes('updated_at',function($row){
                        return Carbon\Carbon::parse($row['updated_at'])->format('d/m/Y');
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop