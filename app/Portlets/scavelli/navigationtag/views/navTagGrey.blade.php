<header class="major">
    <h2>{{ $title }}</h2>
</header>
<nav id="navtag">
    <ul>
    @foreach ($nav->items as $name=>$item)
        <li @if($nav->isSelected($item))class="active"@endif>
            @if(is_array($item))
                <a href="{{$nav->prefix}}{{ $item['url'] or '#' }}">{{ $name }}</a>
            @else
                <a href="{{$nav->prefix}}{{ $item }}">{{ $name }}</a>
            @endif
        </li>
    @endforeach
    </ul>
</nav>
