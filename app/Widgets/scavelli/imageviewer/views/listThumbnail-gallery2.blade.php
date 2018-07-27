@if(!empty($list->get('title')))
    <h1 class="my-4 text-center">{{ $list->get('title') }}</h1>
@endif

<div class="row">
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

{{ $list->theme->addCss('
img {
    -webkit-box-shadow: 0px 1px 6px 1px rgba(0,0,0,0.75);
    -moz-box-shadow: 0px 1px 6px 1px rgba(0,0,0,0.75);
    box-shadow: 0px 1px 6px 1px rgba(0,0,0,0.75);
    margin-bottom:20px;
}

img:hover {
    filter: gray; /* IE6-9 */
    -webkit-filter: grayscale(1); /* Google Chrome, Safari 6+ & Opera 15+ */
}

.row {
    display: block;
    flex-wrap: nowrap;
    margin: 0;
') }}