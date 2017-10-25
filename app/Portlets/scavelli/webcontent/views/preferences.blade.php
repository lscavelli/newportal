@extends('layouts.master')

@section('body')
<fieldset>
    <legend style="font-size: 14px!important; border-bottom: 2px solid #3c8dbc;!important; margin-bottom: 0px!important;"><span Style="color: white; background-color: #3c8dbc; padding: 3px;">Contenuto web selezionato:</span></legend>
    <div class="box-body">
        <form method="POST" id="preferencePortlet">
            {!! Form::hidden('content_id', $content->id, ['id'=>"content_id"]) !!}
            <div class="form-group">
                <label for="id" class="col-sm-2 control-label">Contenuto</label>
                <div class="col-sm-1">
                    {!! Form::text('id', $content->id, ['class' => "form-control input-sm", 'id'=>"id", 'disabled'=>'']) !!}
                </div>
                <div class="col-sm-8">
                    {!! Form::text('content_name', $content->name, ['class' => "form-control input-sm", 'id'=>"content_name", 'disabled'=>'']) !!}
                </div>
                <div class="col-sm-1">
                    <a href="#" data-id=".{{ $content->id }}. " class="btn btn-danger btn-xs pull-right deleteContent">Cancella</a>
                </div>
            </div><br /><br />
            <div class="form-group">
                <label for="model_id" class="col-sm-2 control-label">Modello</label>
                <div class="col-sm-10">
                    {!! Form::select('model_id', $listModels ,$modelId , ['class' => "form-control input-sm", 'id'=>"model_id"]) !!}
                </div>
            </div>
        </form>
    </div>
</fieldset>

<fieldset style="margin-top: 5px!important;">
    <legend style="font-size: 14px!important; border-bottom: 2px solid #3c8dbc;!important;"><span Style="color: white; background-color: #3c8dbc; padding: 3px;">Seleziona contenuto web:</span></legend>
        <div class="form-group">
            <form action="/admin/pages/{{ $portlet->pivot->page_id }}/configPortlet/{{ $portlet->pivot->id }}" method="POST" id="selectStructure">
                {{ csrf_field() }}
                <label for="structure_id" class="col-sm-2 control-label">Struttura</label>
                <div class="col-sm-10">
                    {!! Form::select('structure_id', $listStructure ,\Request::input('structure_id') , ['class' => "form-control input-sm", 'id'=>"structure_id"]) !!}
                </div>
            </form>
        </div><br />
        {!!
            $list->columns(['id'=>'Id','name'=>'Titolo','azioni' ])
            ->sortFields(['id','name'])
            ->ShowActions(false)
            ->showSearch(false)
            ->showButtonNew(false)
            ->customizes('updated_at',function($row){
                return Carbon\Carbon::parse($row['updated_at'])->format('d/m/Y');
            })
            ->customizes('azioni', function($row) {
                return "<a href=\"#\" data-id=".$row['id']. " data-name=\"".$row['name']."\" class=\"btn btn-warning btn-xs pull-right assignContent\">Assegna</a>";
            })->render()
        !!}
</fieldset>
@endsection

@push('style')
@endpush

@push('scripts')
<script>
    $(".assignContent").click(function() {
        var id = $(this).data('id');
        var title = $(this).data('name');
        $('#model_id').empty();
        $.getJSON ("/admin/webcontent/listmodels/"+id, function ( res ) {
            $.each( res, function( key, val ) {
                $('#model_id').append('<option value=' + key + '>' + val + '</option>');
            });
        }).done(function() {
            $("#content_id, #id").val(id);
            $("#content_name").val(title);
        });
    });
    $("#structure_id").change(function() {
        var ble = $( this ).val();
        if (ble.length>0) {
            $( "#selectStructure" ).submit();
        }
    });
    $(".deleteContent").click(function() {
        var id = $(this).data('id');
        $('#id, #content_name').val('');
    });
</script>
@endpush