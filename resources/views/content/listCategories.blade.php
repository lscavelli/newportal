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
                    $list->columns(['id','name'=>'Nome','parent_id'=>'Sotto categoria di','code'=>'codice'])
                    ->onlyActions([
                        url('/admin/categories/edit')=>'Edit',
                        url('#')=>'Delete',
                        url('/admin/categories/profile')=>'Profilo categoria',
                        url('/admin/categories/assignSubcat')=>'Assegna sotto categorie'])
                    ->setUrlDelete('/admin/categories')
                    ->showButtonNew(true,'admin/categories')
                    ->customizes('parent_id',function($row){
                        return $row->parent()->pluck('name')->first();
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop