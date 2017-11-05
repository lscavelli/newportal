{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('comments','/admin/comments/'.$service)->add('Aggiorna commento')
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

                        {!! Form::model($comment, ['action' => $action,'class' => 'form-horizontal']) !!}
                            <input type="hidden" value="{{ $post->id }}" name="post_id">
                            <input type="hidden" value="{{ $service }}" name="service">

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Titolo</label>
                                <div class="col-sm-10">
                                    {!! Form::text('name',null,['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="content" class="col-sm-2 control-label">Contenuto</label>
                                <div class="col-sm-10">
                                    {!! Form::textarea('content',null,['class' => 'form-control', 'placeholder'=> "Contenuto"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    {!! Form::email('email',null,['id' => 'email','class' => 'form-control', 'placeholder'=> "Email"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="author" class="col-sm-2 control-label">Autore</label>
                                <div class="col-sm-10">
                                    {!! Form::text('author',null,['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="approved" class="col-sm-2 control-label">Stato</label>
                                <div class="col-sm-10">
                                    {!! Form::select('approved', ['Non Approvato','Approvato'] , null, ['class' => 'form-control']) !!}
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
