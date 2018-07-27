{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add('Pagina','/admin/pages')->add('Assegna Widget')
    ->setTcrumb($page->name)
    ->render() !!}
@stop


@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-5">

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Gestione Layout</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                        {!! Form::model($page, ['action' => $action,'class' => 'form-horizontal']) !!}
                            @if(isset($page->id))@method('PUT')@endif

                            {!! Form::slSelect('layout','Layout',$listLayouts) !!}
                            {!! Form::slSubmit('Salva',[],'right','default') !!}

                        {!! Form::close() !!}
                        <table class="table table-striped">
                            <thead><tr><th>#</th><th>Frames disponibili</th></tr></thead>
                            <tbody>
                            @foreach($listFrames as $framedisp)
                                <tr><th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        @if ($framedisp==$frame['name'])
                                            <span class="label label-primary" style="font-size: 13px!important;">{{ $framedisp }}</span>
                                        @else
                                           {!! link_to('/admin/pages/addLayout/'.$page->id."/".$loop->index, $framedisp, $attributes = array()) !!}
                                        @endif
                                    </td></tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div> <!-- /.box -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Widgets disponibili</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="display: block;">
                    {!!
                         $listWidgetsDisp->columns(['id','name'=>'Nome widget','azioni'])
                            ->sortFields(['id','name'])
                            ->showAll(false)
                            ->setPrefix('RTYX_')
                            ->customizes('azioni', function($row) use($frame) {
                                return "<a href=\"/admin/pages/". $frame['pageId']."/addWidget/".$row['id']."/".$frame['name']."\" class=\"btn btn-warning btn-xs pull-right\">Aggiungi</a>";
                            })->render()
                     !!}
                    </div>
                    <!-- /.box-body -->
                </div> <!-- /.box -->

            </div> <!-- /.col -->

            <div class="col-md-7">
                {!!
                   $composer->boxNavigator([
                       'type'=>'primary',
                       'title'=>'Frame:  ' .$frame['name'],
                       'listMenu'=>'',
                       'urlNavPre'=>url('/admin/pages/addLayout/'.$page->id,$frame['preid']),
                       'urlNavNex'=>url('/admin/pages/addLayout/'.$page->id,$frame['nexid']),
                       ])->render()
                !!}
                <div class="box box-default">
                    {!!
                         $listWidgetsAssign->columns(['id','name'=>'Widgets inserite','azioni'])
                            ->sortFields(['id','name'])
                            ->showActions(false)
                            ->showButtonNew(false)
                            ->setPrefix('HGYU_')
                            ->customizes('id', function($row) {
                                return $row['pivot']['id'];
                            })
                            ->customizes('name', function($row) {
                                if (!empty($row['pivot']['title'])) { return $row['pivot']['title'];} else {return $row['name'];}
                            })
                            ->customizes('azioni', function($row) use($frame) {
                                return "
                                <a href=\"/admin/pages/".$frame['pageId']."/removePivotId/".$row['pivot']['id']."\" class=\"btn btn-danger btn-xs pull-right\"><span class=\"glyphicon glyphicon-trash\"></span></a>
                                <a href=\"#\" data-pivotid=".$row['pivot']['id']." data-pageid=".$frame['pageId']." class=\"btn btn-primary btn-xs pull-right widgetpref\" Style=\"margin-right:3px;\"><span class=\"glyphicon glyphicon-pencil\"></span></a>";
                            })->render()
                     !!}
                </div> <!-- /.box -->
            </div>

        </div> <!-- /.row -->
    </section>
    <!-- /.content -->

    <div class="modal fade bs-example-modal-lg" id="preferencesModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Widget preferences</h4>
                </div>
                <div class="modal-body">
                    <!-- Main content -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tabpreferences" data-toggle="tab" aria-expanded="true">Web Content</a></li>
                                    <li><a href="#tabcss" data-toggle="tab">Applica Style</a></li>
                                    <li><a href="#tabjs" data-toggle="tab">Javascript</a></li>
                                    <li><a href="#tabother" data-toggle="tab">Altre impostazioni</a></li>
                                </ul>
                                <div class="tab-content" style="height: auto">
                                    <div class="tab-pane active" id="tabpreferences">
                                        <iframe src="" name="prefIframe" id="prefIframe"><p>Il tuo browser non supporta iframe</p></iframe>
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="tabcss">
                                        <div class="box-body">
                                            <form action="" method="POST" name="cssForm" id="cssForm">
                                                <div class="form-group">
                                                    <label for="css" class="col-sm-2 control-label">Style avanzato</label>
                                                    <div class="col-sm-10">
                                                        <textarea name="css" id="css" rows="8" cols="60"></textarea>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="tabjs">
                                        <div class="box-body">
                                            <form action="" method="POST" name="jsForm" id="jsForm">
                                                <div class="form-group">
                                                    <label for="js" class="col-sm-2 control-label">Javascript</label>
                                                    <div class="col-sm-10">
                                                        <textarea name="js" id="js" rows="8" cols="60"></textarea>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="tabother">
                                        <div class="box-body">
                                            <form action="" method="POST" name="otherForm" class="form-horizontal" id="otherForm">
                                                <div class="form-group">
                                                    <label for="title" class="col-sm-2 control-label">Titolo</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="title" id="title" class="form-control" placeholder="Ridefinizione titolo" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="position" class="col-sm-2 control-label">Posizione</label>
                                                    <div class="col-sm-10">
                                                        <select name="position" class="form-control input-sm" id="position"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="comunication" class="col-sm-2 control-label">Comunicazione</label>
                                                    <div class="col-sm-10">
                                                        <select name="comunication" class="form-control input-sm" id="comunication">
                                                            <option value="0">Non attiva</option>
                                                            <option value="1">Attiva</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="template" class="col-sm-2 control-label">Template</label>
                                                    <div class="col-sm-10">
                                                        <select name="template" class="form-control input-sm" id="template"></select>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <input name="pivot_id" id="pivot_id" type="text" hidden="hidden" value="">
                                        <input name="page_id" id="page_id" type="text" hidden="hidden" value="">
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
                </div>
                <div class="modal-footer">
                    <button id="submitPreferences" type="submit" class="btn btn-success"><span class="fa fa-check"></span> Salva</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-remove"></span> Annulla</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('style')
<style>
    #prefIframe  {
        width: 100%;
        height: 520px;
        border: 0;
        overflow:auto;
        -webkit-overflow-scrolling: touch;
    }
</style>
@endpush
@push('scripts')
<script>

    $("#RTYX_xpage").change(function () {
        $("#RTYX_xpage-form").submit();
    });
    $("#HGYU_xpage").change(function () {
        $("#HGYU_xpage-form").submit();
    });
    $('#preferencesModal').on('shown.bs.modal', function(){});

    $(".widgetpref").click(function() {
        var url = "/admin/pages/"+$(this).data('pageid')+"/configWidget/"+$(this).data('pivotid');
        $('#prefIframe').attr('src', url);
        /**
         * prelevo tutti gli altri dati relativi alla widget
         * css, js, lista delle porzioni di template, posizione e titolo
         * inserisco i contenuti nei relativi campi del model
        **/
        //$.get( "/pages/"+$(this).data('pageid')+"/configWidget/"+$(this).data('pivotid'), function( data ) {
        //    $('#tabpreferences').html(data);
        //});
        $('#pivot_id').attr('value', $(this).data('pivotid'));
        $('#page_id').attr('value', $(this).data('pageid'));
        $.getJSON ("/admin/pages/getpref/"+$(this).data('pivotid'), function ( res ) {
            if(res.css) $('#css').text(res.css);
            if(res.js) $('#js').text(res.js);
            if(res.title) $('#title').val(res.title);
            $('#position').empty();
            for(i = 1; i <= res.numwidgets; i++) {
                $('#position').append($('<option>', {
                    value: i,
                    text : i
                }));
            }
            $('#position').val(res.position);

            $('#template').empty();
            $.each(res.templates, function( index, value ){
                $('#template').append($('<option>', {
                    value: value,
                    text : value
                }));
            });
            $('#template').val(res.template);

            $('#comunication').val(res.comunication);
        })
        $('#preferencesModal').modal('toggle');
    });
    $("#submitPreferences").click(function() {
        var data = [];
        var formWidget = $("#prefIframe").contents().find('#preferenceWidget');
        if (formWidget.length>0) {
            data = formWidget.serializeArray();
        }

        data = $.merge($('#otherForm').serializeArray(),data);
        data = $.merge($('#jsForm').serializeArray(),data);
        data = $.merge($('#cssForm').serializeArray(),data);
        data = $.merge($('#pivot_id').serializeArray(),data);
        data = $.merge($('#page_id').serializeArray(),data);

        //console.log(data);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $.ajax({
            url: '/admin/pages/savepref',
            data: {data: JSON.stringify(data)},
            type: 'POST',
            dataType: 'json',
        }).done(function (response) {
            //console.log(response);
            $('#preferencesModal').modal('toggle');
            window.location.reload(true);
        }).fail(function(response){
            console.log(JSON.stringify(response)+' - Chiamata fallita');
        });

        //$("#prefIframe").contents().find('#slectContentForm')

    });
</script>
@endpush