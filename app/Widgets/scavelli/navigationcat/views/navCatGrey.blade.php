<header class="major">
    <h2>{{ $title }}</h2>
</header>
<nav id="navcat">
    <ul>
    @foreach ($nav->items as $name=>$item)
        <li @if($nav->isSelected($item))class="active"@endif>
            @if(is_array($item))
                @if(isset($item['submenu']) and count($item['submenu'])>0)
                    <span class="opener @if($nav->isSelected($item)) active @endif">{{ $name }}</span>
                    <ul>
                        @foreach ($item['submenu'] as $titsub=>$href)
                            <li @if($nav->isSelected($href)) class="active" @endif><a href="@if($href!='#'){{ $nav->prefix }}@endif{{ $href }}">{{ $titsub }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <a href="{{$nav->prefix}}{{ $item['url'] ?? '#' }}">{{ $name }}</a>
                @endif
            @else
                <a href="{{$nav->prefix}}{{ $item }}">{{ $name }}</a>
            @endif
        </li>
    @endforeach
    </ul>
</nav>
