{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Strutture dei contenuti')->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id'=>'Id','name'=>'Titolo','service_id'=>'Service','updated_at'=>'Aggiornato il' ])
                    ->sortFields(['id','name','created_at','updated_at'])
                    ->addSplitButtons($optionsSel,false)
                    ->actions([url('/admin/models')=>'Lista modelli',url('/admin/models/create')=>'Crea modello'])
                    ->customizes('created_at',function($row){
                        return Carbon\Carbon::parse($row['created_at'])->format('d/m/Y');
                    })
                    ->customizes('service_id',function($row){
                        $color = !is_null($row->service->color) ? $row->service->color : "#39cccc";
                        return "<span class=\"label\" style=\"background-color:$color !important\">".$row->service->name."</span>";
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop