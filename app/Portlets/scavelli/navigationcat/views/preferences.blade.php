@extends('layouts.master')

@section('body')
<section class="content";>
    <div class="row">

        <div class="col-md-12" Style="float: left; width: 99%">
            <div class="box box-default" style="padding-top: 20px;">
                <div class="box-body">

                    <form method="POST" id="preferencePortlet">
                        <div class="form-group">
                            <label for="service">Servizi disponibili</label>
                            {!! Form::select('service', $services , $conf['service'] , ['class' => "form-control", "id"=>'service']) !!}
                        </div>
                        <div class="form-group">
                            <label for="inpage">Visualizza in</label>
                            {!! Form::select('inpage', $pages , $conf['inpage'] , ['class' => "form-control"]) !!}
                        </div>
                        <div class="form-group">
                            <label for="categories">Vocabolari</label>
                            {{ Form::select('categories[]', $vocabularies, null, ['class' => 'form-control select2-multi vocsel', 'multiple' => 'multiple', 'style'=>'width:100%']) }}
                        </div>
                        <div class="form-group">
                            <label for="type_order">Ordina categorie per</label>
                            {!! Form::select('ord', $selectOrder['ord'] , $conf['ord'] , ['class' => "form-control"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::select('dir', $selectOrder['dir'] , $conf['dir'] , ['class' => "form-control"]) !!}
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

        var $vocMulti = $('.vocsel').select2({vocabularies: true});
        $vocMulti.val([{!! $cats_reg !!}]).trigger('change');


        $("#service").change(function(e) {
            e.preventDefault();
            //$(".form-toggle").toggle();
        });

    </script>
@endpush