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
                            <label for="type_order">Ordina tag per</label>
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

@push('scripts')
    <script type="text/javascript">

        $("#service").change(function(e) {
            e.preventDefault();
            //$(".form-toggle").toggle();
        });

    </script>
@endpush