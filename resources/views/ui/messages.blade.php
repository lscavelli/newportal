
@if (isset($errors)&& count($errors)>0)
    <div class="callout callout-danger">
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
