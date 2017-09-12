{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista Portlets','/admin/portlets')->add('Upload Portlet')
        ->render() !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">Upload file</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($portlet, ['action' => $action,'enctype'=>'multipart/form-data','class' => 'form-horizontal']) !!}
                                <p class="text_img">Seleziona localmente la tua portlet</p>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <label class="input-group-btn">
                                    <span class="btn btn-primary">
                            Browse&hellip;<input type="file" style="display: none;" name="filePortlet">
                                    </span>
                                            </label>
                                            <input type="text" class="form-control filename">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12" style="text-align: right">
                                        <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Carica</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-remove"></span> Annulla</button>
                                    </div>
                                </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop
