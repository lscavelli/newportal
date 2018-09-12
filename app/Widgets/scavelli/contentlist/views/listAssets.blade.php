<div class="content_div">
    <ul class="content_list">
        @foreach($items as $item)
            {!! $list->getItem($item) !!}
        @endforeach
    </ul>
</div>
<div class="row"  Style="padding-bottom: 20px;" >
    <div class="col-lg-12">
    @if ( $list->get('feedUrl') )
        <div class='pull-left'>
            <a href="{!! $list->get('feedUrl') !!}" class="btn-social btn-outline grey"><span class="sr-only">Feed Rss</span><i class="fa fa-fw fa-rss"></i></a>
        </div>
    @endif
    <div class='pull-right' Style="margin-top: 0px; padding-right: 20px">
        {{
            $items->links()
        }}
    </div>
    </div>
</div>