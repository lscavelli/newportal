{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Widgets','/admin/widgets')->add('Setting Widget')
        ->setTcrumb($widget->name)
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
                    <li class="active"><a href="#edittag" data-toggle="tab" aria-expanded="true">Setting Widget</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="edittag">

                        {!! Form::model($widget, ['url'=>url('admin/widgets',$widget->id),'class' => 'form-horizontal']) !!}
                            @if(isset($widget->id))@method('PUT')@endif

                            {!! Form::slText('name','Widget',null,['disabled']) !!}
                            {!! Form::slSelect('structure_id','Struttura dati',$structures) !!}
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
