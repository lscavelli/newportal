<div class="content_div">
    <ul class="content_list">
        @foreach($items as $item)
            {!! $list->getItem($item) !!}
        @endforeach
    </ul>
</div>

@if ( $list->get('feedUrl') )
    <div class='pull-left' Style="margin-top: 0px; padding-right: 20px">
        <a href="{!! $list->get('feedUrl') !!}" class="btn-social btn-outline grey"><span class="sr-only">Feed Rss</span><i class="fa fa-fw fa-rss"></i></a>
    </div>
@endif
<div class='pull-right' Style="margin-top: 0px; padding-right: 20px">
    {{
        $items->appends(array_except(\Request::all(),['_token','page']))->links()
    }}
</div>
