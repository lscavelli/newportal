<div class="content_div">
    @foreach($items as $item)
        {!! $list->getItem($item) !!}
    @endforeach
</div>

@if($items->count()>($list->get('perPage') ?? 1))
    <div class="row"  Style="padding-bottom: 20px;" >
        <div class="col-lg-12">
            <div class='pull-right' Style="margin-top: 0px; padding-right: 20px">
                {{
                    $items->appends(array_except(\Request::all(),['_token']))->links()
                }}
            </div>
        </div>
    </div>
@endif