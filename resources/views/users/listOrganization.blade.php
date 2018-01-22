{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Lista organizzazioni'))->render() !!}
@stop

@section('content')
    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>__('Nome'),'parent_id'=>__('Filiale di'), 'type_id'=>__('Tipologia'),'numuser'=>__('Num. utenti')])
                    ->actions(['profile'=>__('Profilo'),'assignUser'=>__('Assegna utenti'),'assignFilial'=>__('Assegna filiali')])
                    ->customizes('parent_id',function($row){
                        return $row->parent()->pluck('name')->first();
                    })
                    ->customizes('type_id',function($row){
                        return (is_null($row['type_id']) ? null : config('newportal.type_organization')[$row['type_id']]);
                    })
                    ->customizes('numuser',function($row) use($user_organization){
                        return count($user_organization->where('organization_id',$row['id']));
                    })
                    ->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop