<!--?php dd(json_encode($content->tags()->pluck('id'))); ?>-->

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista contenuti web','/admin/content')->add('Aggiorna contenuto')
        ->setTcrumb($content->name)
        ->render() !!}
@stop

@section('content')
    <div class="row">

        <div class="col-md-9">
            <div class="box box-info" style="padding-top: 20px;">
                <div class="box-body">
                    {!! Form::model($content, ['action' => $action,'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}

                    <div class="form-group">
                        {{ Form::label('name', "Nome", ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {!! Form::text('name',$content->name,['class' => 'form-control', "id"=>'name', 'disabled'=>'']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                            {{ Form::label('description', "Estratto", ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {!! Form::textarea('description',null,['class' => 'form-control', 'id'=>'description']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('urlImage', "URL image", ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {!! Form::text('urlImage',$content->image,['class' => 'form-control', "id"=>'urlImage']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2" style="margin-top: 10px;">
                            <p>Oppure</p>
                        </div>
                    </div>
                    <p class="text_img"></p>
                    <div class="form-group">
                        {{ Form::label('image', "Immagine", ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            <div class="input-group" style="margin-bottom: 1em">
                                <label class="input-group-btn">
                                    <span class="btn btn-primary">
                            Browse&hellip;<input type="file" style="display: none;" name="image">
                                    </span>
                                </label>
                                <input type="text" class="form-control filename">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="imageDefault" name="setImageDefault"> Imposta Immagine predefinita
                                </label>
                            </div>
                            <div class="pull-left image">
                                <img src="{{ $content->getImage() }}" alt="Content Image" style="border-radius: 0.375em; margin-top:1em">
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-danger pull-right" name="saveExtract" value="1">Salva</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border" style="background-color: #f8f8f8; border-radius: 3px">
                    <h3 class="box-title">Web Content menu</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding" style="display: block;">
                    @include('content.navigation_content')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>

    </div>
@stop

@section('scripts')
    <script>
        $("input:file").change(function (){
            var label = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
            //input = $(this).parents('.input-group').find(':text');
            //$(".filename").val(label);
            $(".filename").prop('value',label);
            $(".imageDefault").prop('checked', false); // Unchecks it
        });

        $(".imageDefault").click( function(){
            if( $(this).is(':checked') ) {
                $(".filename").prop('value',null);
            };
        });
</script>
@stop