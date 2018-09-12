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
                    <li class="active"><a href="#editpost" data-toggle="tab" aria-expanded="true">Commento</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="editpost">

                        {!! Form::model($comment, ['action' => $action,'class' => 'form-horizontal']) !!}
                            <input type="hidden" value="{{ $post->id }}" name="post_id">
                            <input type="hidden" value="{{ $service }}" name="service">

                            {!! Form::slText('name','Titolo',null,['placeholder'=> "Titolo non obbligatorio"]) !!}
                            {!! Form::slTextarea('content','Contenuto') !!}
                            {!! Form::slEmail('email','Email') !!}
                            {!! Form::slText('author','Autore') !!}
                            {!! Form::slSelect('approved','Stato',['Non Approvato','Approvato']) !!}
                            {!! Form::slSubmit('Salva') !!}

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
