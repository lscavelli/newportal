{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Vocabolari','/admin/vocabularies')->add('Lista categorie')->setTcrumb($vocabulary_name)->render() !!}
@stop

@section('content')
    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>'Nome','parent_id'=>'Sotto categoria di','code'=>'codice','colore'])
                    ->actions([
                        url('/admin/vocabularies/cat/profile')=>'Profilo categoria',
                        url('/admin/vocabularies/cat/assignSubcat')=>'Assegna sotto categorie'])
                    ->setUrlDelete('/admin/vocabularies/cat')
                    ->showButtonNew(true,'admin/vocabularies/cat')
                    ->customizes('parent_id',function($row){
                        return $row->parent()->pluck('name')->first();
                    })
                    ->customizes('colore',function($row){
                        return "<div style='width: 10px; border: 10px solid {$row['color']}'></div>";
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop