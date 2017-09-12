{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista Portlets')->render() !!}
@stop

@section('content')
    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>'Nome','description'=>'Descrizione','type_id'=>'Tipologia','created_at'=>'Registrata il'])
                    ->addSplitButtons(['#'=>'Carica Portlets'],false)
                    ->customizes('type_id',function($row){
                        return (is_null($row['type_id']) ? null : config('newportal.type_portlets')[$row['type_id']]);
                    })
                    ->customizes('description',function($row) {
                        return str_limit(strip_tags($row['description']), 100);
                    })
                    ->customizes('created_at',function($row){
                        return Carbon\Carbon::parse($row['created_at'])->format('d/m/Y');
                    })
                    ->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@include('content.portletUpload')
@endsection
@push('scripts')
<script>
    $('#selectPortletModal').on('shown.bs.modal', function(){
    });
    $(".dropdown-menu li a").click(function() {
        var text = $(this).html();
        if (text=='Carica Portlets') {
            $('#selectPortletModal').modal('toggle');
        }
    });

    $("input:file").change(function (){
        var label = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
        //input = $(this).parents('.input-group').find(':text');
        //$(".filename").val(label);
        $(".filename").prop('value',label);
    });
</script>
@endpush