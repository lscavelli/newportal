{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Posts','/admin/posts')->add('Aggiorna post')
        ->setTcrumb($post->name)
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
                    <li class="active"><a href="#editpost" data-toggle="tab" aria-expanded="true">Contenuto post</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="editpost">

                        {!! Form::model($post, ['action' => $action,'class' => 'form-horizontal']) !!}
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nome</label>
                                <div class="col-sm-10">
                                    {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> "Nome"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="content" class="col-sm-2 control-label">Contenuto</label>
                                <div class="col-sm-10">
                                    {!! Form::textarea('content',null,['class' => 'form-control', 'placeholder'=> "Contenuto"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="summary" class="col-sm-2 control-label">Sommario</label>
                                <div class="col-sm-10">
                                    {!! Form::textarea('summary',null,['class' => 'form-control', 'placeholder'=> "Sommario"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status_id" class="col-sm-2 control-label">Stato</label>
                                <div class="col-sm-10">
                                    {!! Form::select('status_id', config('newportal.status_general') , null, ['class' => "form-control input-sm", 'id'=>"status_id"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="slug" class="col-sm-2 control-label">Abbreviazione</label>
                                <div class="col-sm-10">
                                    {!! Form::text('slug',null,['class' => 'form-control', 'placeholder'=> "Abbreviazione"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Salva</button>
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
@section('scripts')
    {!! Html::script('node_modules/ckeditor/ckeditor.js') !!}

    <script>

        var config = {
            extraPlugins: 'codesnippet',
            codeSnippet_theme: 'sunburst',
            language: '{{ config('app.locale') }}',
            filebrowserBrowseUrl: '/elfinder/ckeditor',
            height: 600
        };
        CKEDITOR.replace('content', config);
    </script>
@stop
