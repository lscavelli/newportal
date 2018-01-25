@extends('layouts.master')

@section('body')
<fieldset>
    <legend style="font-size: 14px!important; border-bottom: 2px solid #3c8dbc;!important; margin-bottom: 20px!important;"><span Style="color: white; background-color: #3c8dbc; padding: 3px;">Web Content selezionato:</span></legend>
    <div class="box-body" style="margin-right: 22px">

        <form method="POST" id="preferencePortlet" class="form-horizontal">
            {!! Form::hidden('content_id', $webContent->get('content_id'), ['id'=>"content_id"]) !!}
            {!! Form::hidden('modelContent', $webContent->get('modelContent'), ['id'=>"modelContent"]) !!}
            {!! Form::hidden('modelPortletId', $webContent->get('modelPortletId'), ['id'=>"modelPortletId"]) !!}
            {!! Form::hidden('modelPortlet', $webContent->get('modelPortlet'), ['id'=>"modelPortlet"]) !!}
            {!! Form::hidden('socialshare', $webContent->get('socialshare'), ['id'=>"socialshare"]) !!}
            {!! Form::hidden('activecomments', $webContent->get('activecomments'), ['id'=>"activecomments"]) !!}
            {!! Form::hidden('sethits', $webContent->get('sethits'), ['id'=>"sethits"]) !!}
            {!! Form::hidden('syntax', $webContent->get('syntax'), ['id'=>"syntax"]) !!}

            <div class="form-group">
                <label for="id" class="col-sm-2 control-label">Web content</label>
                <div class="col-sm-1">
                    {!! Form::text('id', $webContent->get('content_id'), ['class' => "form-control input-sm", 'id'=>"id",'disabled'=>'']) !!}
                </div>
                <div class="col-sm-8">
                    {!! Form::text('content_name', $webContent->get('content_name'), ['class' => "form-control input-sm", 'id'=>"content_name",'disabled'=>'']) !!}
                </div>
                <div class="col-sm-1">
                    <a href="#" class="btn btn-danger btn-xs pull-right deleteContent">Cancella</a>
                </div>
            </div>
            <div class="form-group">
                <label for="model_content_name" class="col-sm-2 control-label">Modello content</label>
                <div class="col-sm-10">
                    {!! Form::text('model_content', $webContent->get('modelContent'), ['class' => "form-control input-sm", 'id'=>"model_content",'disabled'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="model_portlet_id" class="col-sm-2 control-label">Modello portlet</label>
                <div class="col-sm-1">
                    {!! Form::text('model_portlet_id', $webContent->get('modelPortletId'), ['class' => "form-control input-sm", 'id'=>"model_portlet_id", 'disabled'=>'']) !!}
                </div>
                <div class="col-sm-8">
                    {!! Form::text('model_portlet', $webContent->get('modelPortlet'), ['class' => "form-control input-sm", 'id'=>"model_portlet", 'disabled'=>'']) !!}
                </div>
                <div class="col-sm-1">
                    <a href="#" class="btn btn-danger btn-xs pull-right deleteModelPortlet">Cancella</a>
                </div>
            </div>
        </form>

    </div>
</fieldset>

<fieldset style="margin-top: 5px!important;">
    <legend style="font-size: 14px!important; border-bottom: 2px solid #3c8dbc;!important; margin-bottom: 20px!important;" ><span Style="color: white; background-color: #3c8dbc; padding: 3px;">Seleziona contenuto web:</span></legend>
    <div class="box-body" style="margin-right: 22px">
        <form action="/admin/pages/{{ $webContent->get('portlet')->pivot->page_id }}/configPortlet/{{ $webContent->get('portlet')->pivot->id }}" method="POST" id="selectStructure" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="structure_id" class="col-sm-2 control-label">Struttura</label>
                <div class="col-sm-10">
                    {!! Form::select('structure_id', $webContent->get('listStructure') ,\Request::input('structure_id') , ['class' => "form-control input-sm", 'id'=>"structure_id"]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="model_id" class="col-sm-2 control-label">Modello</label>
                <div class="col-sm-9">
                    {!! Form::select('model_id', $webContent->get('listModels') , $webContent->get('modelId') , ['class' => "form-control input-sm", 'id'=>"model_id"]) !!}
                </div>
                <div class="col-sm-1">
                    <a href="#" class="btn btn-warning btn-xs pull-right assignModelPortlet">Aggiungi</a>
                </div>
            </div>
        </form>
    </div>
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
                return "<a href=\"#\" data-id=".$row['id']. " data-name=\"".$row['name']."\" data-model=\"".$row['model']['name']."\" class=\"btn btn-warning btn-xs pull-right assignContent\">Assegna</a>";
            })->render()
        !!}
</fieldset>

<fieldset>
    <legend style="font-size: 14px!important; border-bottom: 2px solid #3c8dbc;!important; margin-bottom: 20px!important;"><span Style="color: white; background-color: #3c8dbc; padding: 3px;">Altre impostazioni:</span></legend>
    <div class="box-body" style="margin-right: 22px">

        <form method="POST" id="socialshare_form" class="form-horizontal">
            <div class="form-group">
                <label for="socialshare_add" class="col-sm-2 control-label">Social share</label>
                <div class="col-sm-10">
                    {!! Form::select('socialshare_add', ['Disabilitata','Abilitata'] , $webContent->get('socialshare') , ['class' => "form-control input-sm", 'id'=>"socialshare_add"]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="activecomments_add" class="col-sm-2 control-label">Commenti</label>
                <div class="col-sm-10">
                    {!! Form::select('activecomments_add', ['Disabilitati','Abilitati'] , $webContent->get('activecomments') , ['class' => "form-control input-sm", 'id'=>"activecomments_add"]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="sethits_add" class="col-sm-2 control-label">Conteggio visite</label>
                <div class="col-sm-10">
                    {!! Form::select('sethits_add', ['Disabilitato','Abilitato'] , $webContent->get('sethits') , ['class' => "form-control input-sm", 'id'=>"sethits_add"]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="syntax_high" class="col-sm-2 control-label">Evidenzia sintassi</label>
                <div class="col-sm-10">
                    {!! Form::select('syntax_high', ['Disabilitata','Abilitata'] , $webContent->get('syntax') , ['class' => "form-control input-sm", 'id'=>"syntax_high"]) !!}
                </div>
            </div>
        </form>

    </div>
</fieldset>

@endsection

@push('style')
@endpush

@push('scripts')
<script>
    $(".assignContent").click(function() {
        //var id = $(this).data('id');
        $("#content_id, #id").val($(this).data('id'));
        $("#content_name").val($(this).data('name'));
        $("#model_content_name").val($(this).data('model'));

        //$('#model_id').empty();
        /*$.getJSON ("/admin/webcontent/listmodels/"+id, function ( res ) {
            $.each( res, function( key, val ) {
                $('#model_id').append('<option value=' + key + '>' + val + '</option>');
            });
        }).done(function() {
        });*/
    });
    $("#structure_id").change(function() {
        var ble = $( this ).val();
        if (ble.length>0) {
            $( "#selectStructure" ).submit();
        }
    });
    $(".deleteContent").click(function() {
        $('#id, #content_name, #content_id, #modelContent, #model_content').val('');
    });
    $(".assignModelPortlet").click(function() {
        $("#model_portlet_id, #modelPortletId").val($("#model_id").find('option:selected').val());
        $("#model_portlet, #modelPortlet").val($("#model_id").find('option:selected').text());
    });
    $(".deleteModelPortlet").click(function() {
        var id = $(this).data('id');
        $('#model_portlet_id, #model_portlet, #modelPortlet, #modelPortletId').val('');
    });

    $("#socialshare_add").change(function() {
        $("#socialshare").val($("#socialshare_add").find('option:selected').val());
    });

    $("#activecomments_add").change(function() {
        $("#activecomments").val($("#activecomments_add").find('option:selected').val());
    });

    $("#sethits_add").change(function() {
        $("#sethits").val($("#sethits_add").find('option:selected').val());
    });

    $("#syntax_high").change(function() {
        $("#syntax").val($("#syntax_high").find('option:selected').val());
    });


</script>
@endpush