
@if (isset($errors)&& count($errors)>0)
    <div class="callout callout-danger msgDisabled">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4>Attenzione</h4>
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if(Session::get('success', false))
    <?php $msgs = Session::get('success'); ?>
    @if (is_array($msgs))
        @foreach ($msgs as $msg)
            <div class="callout callout-success msgDisabled">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Registrato</h4>
                <p>{{ $msg }}</p>
            </div>
        @endforeach
    @else
        <div class="callout callout-success msgDisabled">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Registrato</h4>
            {{ $msgs }}
        </div>
    @endif
    <?php Session::forget('success'); ?>
@endif

@push('scripts')
<script>
    $.fn.removeWin = function() {
        $('.msgDisabled').slideUp(800, function () {
            $(this).remove();
        })
    }
    $('.close').on('click', function(e){
        e.preventDefault();
        $('.msgDisabled').remove();
    });
    setTimeout($(this).removeWin, 2000);
</script>
@endpush
