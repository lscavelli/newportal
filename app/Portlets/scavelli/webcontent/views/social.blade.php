<div class="pull-right">
    <ul class="list-inline">
        @foreach($items as $key=>$item)
            <li><a href="{!! $item['url'] !!}" class="btn-social btn-outline grey"><span class="sr-only">{!! $key !!}</span><i class="fa fa-fw {!! $item['icon'] !!}"></i></a></li>
        @endforeach
    </ul>
</div>

