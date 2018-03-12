{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Ruoli'),'/admin/roles')->add(__('Aggiorna ruoli'))
        ->setTcrumb($role->name)
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
                    <li class="active"><a href="#editruoli" data-toggle="tab" aria-expanded="true">{{ __("Dettagli ruolo") }}</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="editruoli">

                        {!! Form::model($role, ['url'=>url('admin/roles',$role->id),'class' => 'form-horizontal']) !!}
                            @if(isset($role->id))@method('PUT')@endif

                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slText('slug','Slug',null,['placeholder'=> __("Generato automaticamente se lasciato vuoto")]) !!}
                            {!! Form::slSelect('level','Livello',config('newportal.levelRole')) !!}
                            {!! Form::slTextarea('description','Descrizione') !!}
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
