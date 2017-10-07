{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Pagine','/admin/pages')->add('Aggiorna pagine')
        ->setTcrumb($page->name)
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
                    <li class="active"><a href="#editpages" data-toggle="tab" aria-expanded="true">Pagine</a></li>
                    <li><a href="#themepane" data-toggle="tab">Altri dati</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="editpages">
                        {!! Form::model($page, ['action' => $action,'class' => 'form-horizontal']) !!}
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nome</label>
                                <div class="col-sm-10">
                                    {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> "Nome"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="slug" class="col-sm-2 control-label">Slug pagina</label>
                                <div class="col-sm-10">
                                    {!! Form::text('slug',null,['class' => 'form-control', 'placeholder'=> "Lasciare vuoto per generarlo automaticamente"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-2 control-label">Descrizione</label>
                                <div class="col-sm-10">
                                    {!! Form::textarea('description',null,['class' => 'form-control', 'placeholder'=> "Descrizione"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="checkbox">
                                        <label>
                                           {!! Form::checkbox('hidden_', 1, \Request::input('hidden_')) !!} Nascosta
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status_id" class="col-sm-2 control-label">Stato</label>
                                <div class="col-sm-10">
                                    {!! Form::select('status_id', config('newportal.status_general') , \Request::input('status_id'), ['class' => "form-control input-sm", 'id'=>"status_id"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="parent_id" class="col-sm-2 control-label">Figlia di</label>
                                <div class="col-sm-10">
                                    {!! Form::select('parent_id', $optionsSel ,\Request::input('parent_id') , ['class' => "form-control input-sm", 'id'=>"parent_id"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="theme" class="col-sm-2 control-label">Tema</label>
                                <div class="col-sm-10">
                                    {!! Form::select('theme', $listThemes , \Request::input('theme'), ['class' => "form-control input-sm", 'id'=>"theme"]) !!}
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
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="themepane">
                        {!! Form::model($page, ['action' => $action,'class' => 'form-horizontal']) !!}
                        <div class="form-group">
                            <label for="level" class="col-sm-2 control-label">Tipo pagina</label>
                            <div class="col-sm-10">
                                {!! Form::select('type_id', config('newportal.type_page') , \Request::input('type_id'), ['class' => "form-control input-sm", "id"=>'type_id']) !!}
                            </div>
                        </div>
                        <div class="form-group urlpage">
                            <label for="slug" class="col-sm-2 control-label">Url</label>
                            <div class="col-sm-10">
                                {!! Form::text('url',null,['class' => 'form-control', 'placeholder'=> "url esterno o a pagina e documento interno"]) !!}
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

@push('scripts')
<script>
    $(document).ready(function() {
        showUrl();

        $('#type_id').on('change', function(e){
            e.preventDefault();
            showUrl();
        });

        function showUrl() {
            $type = $( "#type_id" ).val();
            if ($type==1) {
                $('.urlpage').show();
            } else {
                $('.urlpage').hide();
            }
        }
    });
</script>
@endpush
