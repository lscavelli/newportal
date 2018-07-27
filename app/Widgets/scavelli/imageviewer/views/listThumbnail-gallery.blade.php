@if(!empty($list->get('title')))
    <h1 class="my-4 text-center">{{ $list->get('title') }}</h1>
@endif
<div class="row text-center">
    @foreach($items as $item)
        {!! $list->getItem($item) !!}
    @endforeach
</div>


@if($items->total()>$list->get('perPage'))
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