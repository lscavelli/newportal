{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Vocabolari','/admin/vocabularies')->add('Aggiorna vocabolari')
        ->setTcrumb($vocabulary->name)
        ->render() !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">Dati obbligatori</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($vocabulary, ['url'=>url('admin/vocabularies',$vocabulary->id),'class' => 'form-horizontal']) !!}
                            @if(isset($vocabulary->id))@method('PUT')@endif

                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slTextarea('description','Descrizione') !!}
                            @foreach($defaults as $default)
                                @if($loop->index>0)<hr>@endif

                                <div @if($loop->first)id="duplicable"@endif style="border-left: 1px solid #ecf0f5;">

                                    {!! Form::slSelect('services[]','Servizi disponibili',$services,['class' => "form-control input-sm services"],$default['id']) !!}

                                    <div class="form-group">
                                        <label for="type_order" class="col-sm-2 control-label">Ordine categorie</label>
                                        <div class="col-sm-5">
                                            {!! Form::select('type_order[]', $selectord['ord'] ,$default['pivot']['type_order'] , ['class' => "form-control input-sm"]) !!}
                                        </div>
                                        <div class="col-sm-5">
                                            {!! Form::select('type_dir[]', $selectord['dir'] , $default['pivot']['type_dir'] , ['class' => "form-control input-sm"]) !!}
                                        </div>
                                    </div>

                                    {!! Form::slSelect('required[]','Dato obbligatorio',$selectord['req'],['class' => "form-control input-sm"],$default['pivot']['required']) !!}

                                    @if($loop->index>0)
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <a class="del-button btn btn-danger pull-right" title="Rimuovi elemento">Remove</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @if(count($services)-1>count($defaults))
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <a class="add-element btn btn-info pull-right" title="Aggiungi elemento">Aggiungi</a>
                                    </div>
                                </div>
                            @endif
                            {!! Form::slSubmit('Salva') !!}

                        {!! Form::close() !!}
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
</section>
@stop

@section('style')
    <style>
        .borderclass {
            border-top:1px solid #ecf0f5;
            padding-top: 10px;
        }
    </style>
@endsection

@push('scripts')
<script>

    $(document).ready(function() {
        console.log("document ready");
        var rmButton = "<div class=\"form-group\"><div class=\"col-sm-offset-2 col-sm-10\"><a class=\"del-button btn btn-danger pull-right\" title=\"Rimuovi elemento\">Remove</a></div></div>";
        var prg =0;
        var maxsize = $('#duplicable .services option').length-1;
        var serv_presenti = $('.services').length;

        $('.add-element').click(function() {
            ++prg;
            var select = 'service_'+ prg;
            if (maxsize > serv_presenti) {
                $('#duplicable').after($('#duplicable').clone().attr("id", select).append(rmButton).addClass('borderclass'));
                $("#" + select).find(".services > option:first").eq(0).remove();
                serv_presenti = $('.services').length;
                if (maxsize > serv_presenti) {
                    $('.add-element').show();
                } else {
                    $('.add-element').hide();
                }
            }
            //
        });
        $('body').on('click', '.del-button', function () {
            $(this).parent().parent().parent().remove();
            serv_presenti = $('.services').length;
            if (maxsize > serv_presenti) $('.add-element').show();
            e.preventDefault();
        });
    });
</script>
@endpush
