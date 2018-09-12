{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Tags','/admin/tags')->add('Aggiorna tag')
        ->setTcrumb($tag->name)
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
                    <li class="active"><a href="#edittag" data-toggle="tab" aria-expanded="true">Contenuto Tag</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="edittag">

                        {!! Form::model($tag, ['url'=>url('admin/tags',$tag->id),'class' => 'form-horizontal']) !!}
                            @if(isset($tag->id))@method('PUT')@endif

                            {!! Form::slText('name','Nome') !!}
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
