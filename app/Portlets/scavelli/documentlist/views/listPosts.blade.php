@if ($title)
    <header class="major">
        <h2>{!! $title !!}</h2>
    </header>
@endif
<div class="posts">
    @foreach($items as $item)
        {!! $list->getItem($item) !!}
    @endforeach
</div>
@if ($list->get('feedUrl'))
    <div class='pull-left' Style="margin-top: 0px; padding-right: 20px">
        <ul class="list-inline">
            <li><a href="{!! $list->get('feedUrl') !!}" class="btn-social btn-outline grey" title="Feed Rss"><span class="sr-only">Feed Rss</span><i class="fa fa-fw fa-rss"></i></a></li>
            <li><a href="{!! $list->get('feedJsonUrl') !!}" class="btn-social btn-outline grey" title="Feed Json"><span class="sr-only">Feed Json</span><i class="fa fa-fw fa-code"></i></a></li>
        </ul>
    </div>
@endif
<div class='pull-right' Style="margin-top: 0px; padding-right: 20px">
    {{
        $items->appends(array_except(\Request::all(),['_token','page']))->links()
    }}
</div>
