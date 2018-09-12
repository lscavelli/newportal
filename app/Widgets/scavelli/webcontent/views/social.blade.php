<div class="row">
    <div class="col-lg-12">
        <ul class="list-inline  pull-right">
            @foreach($items as $key=>$item)
                <li><a href="{!! $item['url'] !!}" class="btn-social btn-outline grey {{ $item['class'] }}"><span class="sr-only">{!! $key !!}</span><i class="fa fa-fw {!! $item['icon'] !!}"></i></a></li>
            @endforeach
        </ul>
    </div>
</div>

