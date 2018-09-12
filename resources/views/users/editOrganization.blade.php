{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Organizzazioni'),'/admin/organizations')->add(__('Aggiorna organizzazioni'))
        ->setTcrumb($organization->name)
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
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">{{ __("Dati obbligatori") }}</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($organization, ['url'=>url('admin/organizations',$organization->id),'class' => 'form-horizontal']) !!}
                            @if(isset($organization->id))@method('PUT')@endif

                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slSelect('status_id','Stato',config('newportal.status_general')) !!}
                            {!! Form::slSelect('type_id','Tipo organizzazione', config('newportal.type_organization')) !!}
                            {!! Form::slText('code','Codice') !!}
                            {!! Form::slSelect('parent_id','Filiale di',$selectOrg) !!}
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
