{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista modelli','/admin/models/'.$structure->id)->add('Aggiorna Modello')
        ->setTcrumb($modello->name)
        ->render() !!}
@stop


@section('content')
    @include('ui.messages')
    {!! Form::model($modello, ['action' => $action,'id'=>'modelloForm']) !!}
    <input type="hidden" name="structure_id" id="structure_id"  value="{{$structure->id}}">
    <div class="box box-primary" style="padding-top: 20px;">
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> "Nome", "id"=>'name']) !!}
                    </div>
                    <div class="form-group">
                        {{ Form::label('structure_name', "Struttura:") }}
                        {!! Form::text('structure_name',$structure->name,['class' => 'form-control', "id"=>'structure_name', 'disabled'=>'']) !!}
                    </div>
                    <div class="form-group">
                        {{ Form::label('created_at', "Creato il:") }}
                        {!! Form::text('created_at',Carbon\Carbon::parse($modello->created_at)->format('d/m/Y - H:i'),['class' => 'form-control', "id"=>'created_at', 'disabled'=>'']) !!}
                    </div>
                    <div class="form-group">
                        {{ Form::label('updated_at', "Aggiornato il:") }}
                        {!! Form::text('updated_at',Carbon\Carbon::parse($modello->updated_at)->format('d/m/Y - H:i'),['class' => 'form-control', "id"=>'updated_at', 'disabled'=>'']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Descrizione</label>
                        {!! Form::textarea('description',null,['class' => 'form-control', 'id'=>'description', 'style'=>'height: 7.7em;']) !!}
                    </div>
                    <div class="form-group">
                        <label for="type_id">Tipo modello</label>
                        {!! Form::select('type_id', [1=>'base',2=>'lista'] , null, ['class' => "form-control"]) !!}
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="content">Schema modello</label>
                        <pre id="ace_content"></pre>
                        {!! Form::textarea('content',null,['class' => 'form-control', 'id'=>'content']) !!}
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="selectVariable">Variabili disponibili</label>
                        {!! Form::select('selectVariable', $listVariable , \Request::input('selectVariable'), ['class' => "form-control", 'id'=>"selectVariable"]) !!}
                    </div>
                    <div class="form-group">
                        <label for="selectWidgets">Widgets disponibili</label>
                        {!! Form::select('selectWidgets', $listWidgets , \Request::input('selectWidgets'), ['class' => "form-control", 'id'=>"selectWidgets"]) !!}
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-danger" id="saveStructure">Salva</button>
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->
    {!! Form::close() !!}
@stop

@section('style')
    <style>
        #ace_content {
            min-height: 300px;
            font-size: 14px!important;
        }
    </style>
@stop
@section('scripts')

    <script src="{{ asset("/node_modules/ace-builds/src-noconflict/ace.js") }}"></script>

    <script>
        var content = $("#content");
        content.hide();
        var editor = ace.edit("ace_content");
        editor.setTheme("ace/theme/twilight");
        editor.getSession().setMode("ace/mode/html");
        editor.setShowInvisibles(true);
        editor.setHighlightActiveLine(true);
        editor.$blockScrolling = Infinity;
        editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true
        });

        // Aggiorno Ace Editor
        editor.getSession().setValue(content.val());

        // Aggiorno Textarea
        editor.getSession().on('change', function(){
            content.val( editor.getSession().getValue() )
        });


        $( "#selectVariable").change(function(e) {
            var ble = $( this ).val();
            if (ble.length>0) {
                //editor.setValue(); // sostituisce tutto editor.getValue();
                e.preventDefault();
                editor.insert( '\{\!! $'+ ble + ' \!!\}');
            }
        });
    </script>
@stop


