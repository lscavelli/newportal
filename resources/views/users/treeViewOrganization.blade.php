@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Organizzazioni'),'/organizations')->add(__('Visualizzazione grafica'))->render() !!}
@stop

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <i class="fa fa-code-fork"></i>
                        <h3 class="box-title">{{$title}}</h3>
                    </div>
                    <div class="tree">
                    @include('ui.treeview',['nodes' => $organizations])
                    </div>
                </div> <!-- /.box -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </section>
    <!-- /.content -->
@stop