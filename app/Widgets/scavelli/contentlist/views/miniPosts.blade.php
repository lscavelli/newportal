<header class="major">
    <h2>{!! $title !!}</h2>
</header>
<div class="mini-posts">
    @foreach($items as $item)
        {!! $list->getItem($item) !!}
    @endforeach
</div>
<ul class="actions">
    <li><a href="#" class="button">More</a></li>
</ul>



