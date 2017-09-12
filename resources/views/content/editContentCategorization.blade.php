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
                    {!! Form::model($content, ['action' => $action,'class' => 'form-horizontal']) !!}

                    <div class="form-group">
                        {{ Form::label('name', "Nome:", ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {!! Form::text('name',$content->name,['class' => 'form-control', "id"=>'name', 'disabled'=>'']) !!}
                        </div>
                    </div>
                    @foreach($vocabularies as $vocabulary)
                    <div class="form-group">
                        {{ Form::label('categories'.$vocabulary->id.'[]', $vocabulary->name.":", ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10" >
                            {{ Form::select('categories'.$vocabulary->id.'[]', $vocabulary->categories()->pluck('name','id'), null, ['class' => "form-control select2-multi multicat", 'multiple' => 'multiple', 'style'=>'width:80%;', 'id'=>'categories'.$vocabulary->id]) }}
                            <button type="button" class="btn btn-info selbut" data-vid="{{ $vocabulary->id }}">Seleziona</button>
                        </div>
                    </div>
                    @endforeach
                    <div class="form-group">
                        {{ Form::label('tags', 'Tags:', ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2-multi tagsel', 'multiple' => 'multiple', 'style'=>'width:100%']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-danger" name="saveCategory" value="1">Salva</button>
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
@include('ui.treeviewDialog')
@section('style')
    {{ Html::style('/bower_components/AdminLTE/plugins/select2/select2.min.css') }}
    {{ Html::style('/css/highCheckTree.css') }}
    <Style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #555;
        }
    </Style>
@stop

@section('scripts')
    {{ Html::script('/bower_components/AdminLTE/plugins/select2/select2.min.js') }}
    {{ Html::script('/js/highchecktree.js') }}

    <script type="text/javascript">
        var $tagMulti = $('.tagsel').select2({tags: true});
        $tagMulti.val({!! $content->tags()->pluck('id') !!}).trigger('change');
        //$.each('.multicat')

        $('.multicat').each(function() {
            $(this).select2({categories: true}).val({!! $content->categories()->pluck('id') !!}).trigger('change');
        });

        $('#treeviewDialog').on('shown.bs.modal', function(){
        });

        $(".selbut").click(function() {
            var val = $("#categories"+$(this).data('vid')).val();
            if (val) val = "/"+val;
            $('#treeviewDialog').find('.dialogSel').attr('data-vid', $(this).data('vid'));
            $.getJSON("/admin/api/listcatecory/"+$(this).data('vid')+val, function () {
            })
            .done(function(data) {
                //console.log(data);
                var array = [];
                $.each(data, function(k,v) {
                    array.push(v)
                });
                //console.log(array);
                //alert(array);
                $('#tree-container').highCheckTree({
                    data: array
                });
                $('#treeviewDialog').modal('toggle');
            })
            .fail(function() {
                console.log( "error" );
            })
        });


        $(".dialogSel").click(function() {
            var chk = $("ul.checktree").find(".checked");
            var output = [];
            chk.each(function(index, item) {
                var item = $(item);
                if(typeof item.parent().attr('rel') !== typeof undefined) {
                    output.push(item.parent().attr('rel'));
                }
            })
            $(this).removeData("vid");
            $("#categories"+$(this).data('vid')).val(output).trigger("change");
            $('#treeviewDialog').modal('toggle');
        });
    </script>
@endsection
