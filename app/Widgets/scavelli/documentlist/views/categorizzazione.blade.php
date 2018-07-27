@extends('layouts.master')

@section('body')
<section class="content";>
    <div class="row">

        <div class="col-md-9" Style="float: left; width: 75%">
            <div class="box box-default" style="padding-top: 20px;">
                <div class="box-body">

                    <form method="POST" id="preferenceWidget" class="form-horizontal">
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
                    </form>

                </div>
            </div>
        </div>

        <div class="col-md-3"  Style="float: left; width: 25%">
        <div class="box box-solid">
                <div class="box-header with-border" style="background-color: #f8f8f8; border-radius: 3px">
                    <h3 class="box-title">Menu</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding" style="display: block;">
                    @include('assetpublisher::menuAsset')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
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
        $('.tagsel').select2({tags: true});
        $('.multicat').each(function() {
            $(this).select2({categories: true});//.val().trigger('change');
        });
        $("#service").change(function(e) {
            e.preventDefault();
            $(".form-toggle").toggle();
        });
    </script>
@endpush