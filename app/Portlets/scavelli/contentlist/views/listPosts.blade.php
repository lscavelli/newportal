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

<div class='pull-right' Style="margin-top: 0px; padding-right: 20px">
    {{
        $items->appends(array_except(\Request::all(),['_token','page']))->links()
    }}
</div>
