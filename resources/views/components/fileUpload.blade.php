<div style="padding: 0px 15px 15px 15px">
    <a href="#" class="btn btn-default btn-block selectFile" data-id="{!! $id !!}"><i class="fa fa-file"></i> {{ __($label) }}</a>
</div>

@include('components.fileUploadWindow')
@push('scripts')
    <script>
        $('#selectFileModal').on('shown.bs.modal', function(){
        });

        $(".selectFile").click(function() {
            $('#selectFileModal').modal('toggle');
            $('#fileForm').prop('action', '{{ $action }}');
        });

        $("input:file").change(function (){
            var label = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
            $(".filename").prop('value',label);
        });
    </script>
@endpush