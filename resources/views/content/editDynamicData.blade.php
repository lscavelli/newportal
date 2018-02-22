{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Dynamic Data List','/admin/ddl')->add('Aggiorna Dynamic Data')
        ->setTcrumb($dynamicData->name)
        ->render() !!}
@stop


@section('content')
    @include('ui.messages')
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary" style="padding-top: 20px;">
                <div class="box-body">

                    {!! Form::model($dynamicData, ['action' => $action,'id'=>'structureForm','class' => 'form-horizontal']) !!}
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Nome</label>
                            <div class="col-sm-10">
                                {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> "Nome", "id"=>'name']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">Descrizione</label>
                            <div class="col-sm-10">
                                {!! Form::textarea('description',null,['class' => 'form-control', 'placeholder'=> "Descrizione"]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status_id" class="col-sm-2 control-label">Stato</label>
                            <div class="col-sm-10">
                                {!! Form::select('status_id', config('newportal.status_general') , \Request::input('status_id'), ['class' => "form-control input-sm", 'id'=>"status_id"]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="structure_id" class="col-sm-2 control-label">Strutture dati</label>
                            <div class="col-sm-10">
                                {!! Form::select('structure_id', $structureOptions , $dynamicData->structure_id, ['class' => "js-example-basic-single js-states form-control", 'id'=>"structure_id", 'style'=>"width: 100%", 'aria-hidden'=>"true"]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-danger">Salva</button>
                            </div>
                        </div>
                    {!! Form::close() !!}

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@stop
@push('style')
    {{ Html::style('/node_modules/select2/dist/css/select2.min.css') }}
@endpush
@push('scripts')
    {{ Html::script('/node_modules/select2/dist/js/select2.min.js') }}
    <script>
        $(".js-example-basic-single").select2({
            minimumInputLength: 3,
            ajax: {
                url: '/admin/ddl/structure/',
                data: function (params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                },
                dataType: 'json',
                delay: 250
            }
        });
    </script>
@endpush

