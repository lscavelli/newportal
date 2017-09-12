@extends('layouts.master')

@section('body')
<section class="content";>
    <div class="row">

        <div class="col-md-12" Style="float: left; width: 99%">
            <div class="box box-default" style="padding-top: 20px;">
                <div class="box-body">

                    <form method="POST" id="preferencePortlet">
                        <div class="form-group">
                            <label for="page">Ramo</label> (Se vuoto, mostra tuttle le pagine visibili)
                            {!! Form::select('page', $pages , $conf['page'] , ['class' => "form-control"]) !!}
                        </div>
                        <div class="form-group">
                            <label for="theme">Temi</label>
                            {{ Form::select('theme', $themes, $conf['theme'], ['class' => 'form-control', 'id'=>'theme']) }}
                        </div>
                        <div class="form-group">
                            <label for="layout">Layout</label>
                            {!! Form::select('layout', $layouts , $conf['layout'] , ['class' => "form-control", "id"=>'layout']) !!}
                        </div>
                        <div class="form-group">
                            <label for="type_order">Ordina categorie per</label>
                            {!! Form::select('ord', $selectOrder['ord'] , $conf['ord'] , ['class' => "form-control"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::select('dir', $selectOrder['dir'] , $conf['dir'] , ['class' => "form-control"]) !!}
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
    <script type="text/javascript">
        $("#theme").change(function(e) {
            e.preventDefault();
            $('#layout').empty();
            $.getJSON ("/admin/navigationpages/listlayout/"+$('#theme').val(), function ( res ) {
            }).done(function(data) {
                $.each( data, function( key, val ) {
                    $('#layout').append('<option value=' + key + '>' + val + '</option>');
                });
            });
        });
    </script>
@endpush