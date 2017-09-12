{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Strutture','/admin/structure')->add('Lista Modelli')
    ->setTcrumb($structure->name)->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id'=>'Id','name'=>'Titolo','structure_id'=>'Struttura','updated_at'=>'Aggiornato il' ])
                    ->sortFields(['id','name','structure_id','updated_at'])
                    ->actions([url('/admin/models/duplicates')=>'Duplica modello'])
                    ->customizes('structure_id',function($row) {
                        return \App\Models\content\Structure::find($row['structure_id'])->name;
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