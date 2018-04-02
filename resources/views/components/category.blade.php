
@foreach($vocabularies as $vocabulary)
    <div class="form-group">
        {{ Form::label('categories'.$vocabulary->id.'[]', __($vocabulary->name), ['class' => 'col-sm-2 control-label']) }}
        <div class="col-sm-10">
            {!! Form::select('categories'.$vocabulary->id.'[]', $vocabulary->categories()->pluck('name','id') , null, array_merge(['class' => "form-control select2-multi multicat", 'multiple' => 'multiple', 'style'=>'width:80%;', 'id'=>'categories'.$vocabulary->id],$attributes)) !!}
            <button type="button" class="btn btn-info selbut" data-vid="{{ $vocabulary->id }}">Seleziona</button>
        </div>
    </div>
@endforeach
    <div class="form-group">
        {{ Form::label('tags[]', 'Tags:', ['class' => 'col-sm-2 control-label']) }}
        <div class="col-sm-10">
            {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2-multi tagsel', 'multiple' => 'multiple', 'style'=>'width:100%']) }}
        </div>
    </div>

@push('style')
    {{ Html::style('/node_modules/select2/dist/css/select2.min.css') }}
    {{ Html::style('/css/highCheckTree.css') }}
    <Style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #555;
        }
    </Style>
@endpush
@push('scripts')
    @include('ui.treeviewDialog')
    {{ Html::script('/node_modules/select2/dist/js/select2.min.js') }}
    {{ Html::script('/js/highchecktree.js') }}

    <script type="text/javascript">
        var $tagMulti = $('.tagsel').select2({tags: true});
        $tagMulti.val({!! $model->tags()->pluck('id') !!}).trigger('change');
        //$.each('.multicat')

        $('.multicat').each(function() {
            $(this).select2({categories: true}).val({!! $model->categories()->pluck('id') !!}).trigger('change');
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
@endpush