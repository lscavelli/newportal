@extends('layouts.master')

@section('body')
    <section class="content";>
        <div class="row">

            <div class="col-md-12">
                <form method="POST" id="preferencePortlet">

                    <div class="box box-info">
                        <div class="box-header with-border" style="background-color: ghostwhite ">
                            <h3 class="box-title">Impostazioni lista</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <label for="structure_id">Strutture di dati</label>
                                {!! Form::select('structure_id', $cList->structures , $cList->get('structure_id') , ['class' => "form-control", "id"=>'structure_id']) !!}
                            </div>
                            <div class="form-group">
                                <label for="model_id">Modello applicato alle righe</label>
                                {!! Form::select('model_id', $cList->models , $cList->get('model_id') , ['class' => "form-control", "id"=>'model_id']) !!}
                            </div>
                            <div class="form-group">
                                <label for="listView">Modello applicato alla lista</label>
                                {!! Form::select('listView', $cList->listView , $cList->get('listView') , ['class' => "form-control", "id"=>'listView']) !!}
                            </div>

                        </div>
                    </div>

                    <div class="box box-info">
                        <div class="box-header with-border" style="background-color: ghostwhite ">
                            <h3 class="box-title">Selezione multipla</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="perPage">Contenuti per pagina</label>
                                {!! Form::text('perPage', $cList->get('perPage') , ['class' => "form-control", "id"=>'perPage']) !!}
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
                        </div>
                    </div>

                    <div class="box box-info"><a id="assignFile"></a>
                        <div class="box-header with-border" style="background-color: ghostwhite ">
                            <h3 class="box-title">Seleziona immagine singola</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group" style="margin-top: 15px; margin-bottom: 15px">
                                <label for="id" class="col-sm-2 control-label">Selected</label>
                                <div class="col-sm-1">
                                    {!! Form::text('file_id', $cList->get('file_id'), ['class' => "form-control input-sm", 'id'=>"file_id"]) !!}
                                </div>
                                <div class="col-sm-8">
                                    {!! Form::text('file_name', $cList->get('file_name'), ['class' => "form-control input-sm", 'id'=>"file_name"]) !!}
                                </div>
                                <div class="col-sm-1">
                                    <a href="#assignFile" class="btn btn-danger btn-xs pull-right deleteFile">Cancella</a>
                                </div>
                            </div>

                        </div>
                            {!!
                                $list->columns(['id'=>'Id','thumb'=>__('Anteprima'),'name'=>'Titolo','updated_at'=>__('Aggiornato il'),'azioni'])
                                ->sortFields(['id','name'])
                                ->ShowActions(false)
                                ->ShowSearch(false)
                                ->showButtonNew(false)
                                ->customizes('updated_at',function($row){
                                    return Carbon\Carbon::parse($row['updated_at'])->format('d/m/Y');
                                })
                                ->customizes('azioni', function($row) {
                                    return "<a href=\"#assignFile\" data-id=".$row['id']. " data-name=\"".$row['name']."\" class=\"btn btn-warning btn-xs pull-right assignFile\">Assegna</a>";
                                })
                                ->customizes('thumb',function($row){
                                    $file = $row['path']."/".config('lfm.thumb_folder_name')."/".$row['file_name'];
                                    if(file_exists(public_path($file))) {
                                        return '<div style="text-align:center"><img src=\''.$file.'\' alt=\''.$row['name'].'\' style="width: 100%; max-width: 45px; height: auto; border-radius: 50%;"></div>';
                                    }
                                })->render()
                            !!}

                    </div>

                </form>

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
        $.getJSON ("/admin/imageviewer/listmodels/"+$('#structure_id').val(), function ( res ) {
        }).done(function(data) {
            $.each( data, function( key, val ) {
                $('#model_id').append('<option value=' + key + '>' + val + '</option>');
            });
        });
    });

    $(".assignFile").click(function() {
        //var id = $(this).data('id');
        $("#file_id").val($(this).data('id'));
        $("#file_name").val($(this).data('name'));
    });

    $(".deleteFile").click(function() {
        $('#file_id, #file_name').val('');
    });

</script>
@endpush