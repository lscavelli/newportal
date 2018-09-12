{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista pagine')->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id'=>'Id','name'=>'Nome','slug'=>'Abbreviazione','parent_id'=>'Figlia di'])
                    ->sortFields(['id','name','slug'])
                    ->actions(['create'=>'Crea pagina figlia','duplicates'=>'Duplica pagina','Profilo','addLayout'=>'Layout'])
                    ->customizes('parent_id',function($row){
                        return $row->parent()->pluck('name')->first();
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop