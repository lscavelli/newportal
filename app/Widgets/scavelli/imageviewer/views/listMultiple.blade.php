<div class="content_div">
    @foreach($items as $item)
        {!! $list->getItem($item) !!}
    @endforeach
</div>