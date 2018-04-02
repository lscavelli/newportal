<div class="content_div">
    <ul class="content_list">
        @foreach($items as $item)
            {!! $list->getItem($item) !!}
        @endforeach
    </ul>
</div>
<div class="row"  Style="padding-bottom: 20px;" >
    <div class="col-lg-12">
        <div class='pull-right' Style="margin-top: 0px; padding-right: 20px">
            {{
                $items->appends(array_except(\Request::all(),['_token','page']))->links()
            }}
        </div>
    </div>
</div>