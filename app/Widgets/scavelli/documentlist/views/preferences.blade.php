@extends('layouts.master')

@section('body')
    <section class="content";>
        <div class="row">

            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border" style="background-color: ghostwhite ">
                        <h3 class="box-title">Impostazioni lista</h3>
                    </div>
                    <div class="box-body">

                        <form method="POST" id="preferenceWidget">

                            <div class="form-group">
                                <label for="structure_id">Struttura dati</label>
                                {!! Form::select('structure_id', $cList->structures , $cList->get('structure_id') , ['class' => "form-control", "id"=>'structure_id']) !!}
                            </div>
                            <div class="form-group">
                                <label for="model_id">Modello di struttura</label>
                                {!! Form::select('model_id', $cList->models , $cList->get('model_id') , ['class' => "form-control", "id"=>'model_id']) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('tags', 'Tags:') }}
                                {{ Form::select('tags[]', $cList->tags, null, ['class' => 'form-control select2-multi tagsel', 'multiple' => 'multiple', 'style'=>'width:100%']) }}
                            </div>
                            @foreach($cList->vocabularies as $vocabulary)
                                <div class="form-group form-toggle">
                                    {{ Form::label('categories'.$vocabulary->id.'[]', $vocabulary->name.":") }}
                                    {{ Form::select('categories'.$vocabulary->id.'[]', $vocabulary->categories()->pluck('name','id'), null, ['class' => "form-control select2-multi multicat", 'multiple' => 'multiple', 'style'=>'width:100%;', 'id'=>'categories'.$vocabulary->id]) }}
                                </div>
                            @endforeach
                            <div class="form-group">
                                <label for="type_order">Ordina per</label>
                                {!! Form::select('ord', $cList->selectOrder['ord'] , $cList->get('ord') , ['class' => "form-control"]) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::select('dir', $cList->selectOrder['dir'] , $cList->get('dir') , ['class' => "form-control"]) !!}
                            </div>
                            <div class="form-group">
                                <label for="type_order">Scrolling</label>
                                {!! Form::select('scrolling', [''=>'','nextf'=>'Prossimo contenuto','prevf'=>'Precedente contenuto',] , $cList->get('scrolling') , ['class' => "form-control"]) !!}
                            </div>
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </section>
@endsection

@push('style')
{{ Html::style('/node_modules/select2/dist/css/select2.min.css') }}
<Style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #555;
    }
</Style>
@endpush

@push('scripts')
{{ Html::script('/node_modules/select2/dist/js/select2.min.js') }}
<script type="text/javascript">
    var $tagMulti = $('.tagsel').select2({tags: true});
    $tagMulti.val([{!! $cList->tags_reg !!}]).trigger('change');

    $('.multicat').each(function() {
        $(this).select2({categories: true}).val([{!! $cList->cats_reg !!}]).trigger('change');
    });

    $("#structure_id").change(function(e) {
        e.preventDefault();
        $('#model_id').empty();
        $.getJSON ("/admin/documentlist/listmodels/"+$('#structure_id').val(), function ( res ) {
        }).done(function(data) {
            $.each( data, function( key, val ) {
                $('#model_id').append('<option value=' + key + '>' + val + '</option>');
            });
        });
    });
</script>
@endpush
