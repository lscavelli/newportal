@extends('layouts.master')

@section('body')
    <section class="content";>
        <div class="row">

            <div class="col-md-12" Style="float: left; width: 99%">
                <div class="box box-default" style="padding-top: 20px;">
                    <div class="box-body">

                        <form method="POST" id="preferencePortlet">
                            <div class="form-group">
                                <label for="service">Servizi disponibili</label>
                                {!! Form::select('service', $services , $conf['service'] , ['class' => "form-control", "id"=>'service']) !!}
                            </div>
                            <div class="form-group">
                                <label for="structure_id">Strutture di dati</label>
                                {!! Form::select('structure_id', $structures , $conf['structure_id'] , ['class' => "form-control", "id"=>'structure_id']) !!}
                            </div>
                            <div class="form-group">
                                <label for="model_id">Modello applicato alle righe</label>
                                {!! Form::select('model_id', $models , $conf['model_id'] , ['class' => "form-control", "id"=>'model_id']) !!}
                            </div>
                            <div class="form-group">
                                <label for="listView">Modello applicato alla lista</label>
                                {!! Form::select('listView', $listView , $conf['listView'] , ['class' => "form-control", "id"=>'listView']) !!}
                            </div>
                            <div class="form-group">
                                <label for="inpage">Visualizza in</label>
                                {!! Form::select('inpage', $pages , $conf['inpage'] , ['class' => "form-control"]) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('tags', 'Tags:') }}
                                {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2-multi tagsel', 'multiple' => 'multiple', 'style'=>'width:100%']) }}
                            </div>
                            @foreach($vocabularies as $vocabulary)
                                <div class="form-group form-toggle">
                                    {{ Form::label('categories'.$vocabulary->id.'[]', $vocabulary->name.":") }}
                                    {{ Form::select('categories'.$vocabulary->id.'[]', $vocabulary->categories()->pluck('name','id'), null, ['class' => "form-control select2-multi multicat", 'multiple' => 'multiple', 'style'=>'width:100%;', 'id'=>'categories'.$vocabulary->id]) }}
                                </div>
                            @endforeach
                            <div class="form-group">
                                <label for="type_order">Ordina per</label>
                                {!! Form::select('ord', $selectOrder['ord'] , $conf['ord'] , ['class' => "form-control"]) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::select('dir', $selectOrder['dir'] , $conf['dir'] , ['class' => "form-control"]) !!}
                            </div>
                            <div class="form-group">
                                <label for="type_order">Scrolling</label>
                                {!! Form::select('scrolling', [''=>'','nextf'=>'Prossimo contenuto','prevf'=>'Precedente contenuto',] , $conf['scrolling'] , ['class' => "form-control"]) !!}
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('style')
{{ Html::style('/bower_components/AdminLTE/plugins/select2/select2.min.css') }}
<Style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #555;
    }
</Style>
@endpush

@push('scripts')
{{ Html::script('/bower_components/AdminLTE/plugins/select2/select2.min.js') }}
<script type="text/javascript">
    var $tagMulti = $('.tagsel').select2({tags: true});
    $tagMulti.val([{!! $tags_reg !!}]).trigger('change');

    $('.multicat').each(function() {
        $(this).select2({categories: true}).val([{!! $cats_reg !!}]).trigger('change');
    });


    $("#service").change(function(e) {
        e.preventDefault();
        $(".form-toggle").toggle();
    });
    $("#structure_id").change(function(e) {
        e.preventDefault();
        $('#model_id').empty();
        $.getJSON ("/admin/contentlist/listmodels/"+$('#structure_id').val(), function ( res ) {
        }).done(function(data) {
            $.each( data, function( key, val ) {
                $('#model_id').append('<option value=' + key + '>' + val + '</option>');
            });
        });
    });
</script>
@endpush