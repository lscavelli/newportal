<header class="major">
    <h2>{{ $title }}</h2>
</header>
<nav id="navpage">
    <ul>
    @foreach ($nav->items as $name=>$item)
        <li @if($nav->isSelected($item))class="active"@endif>
            @if(is_array($item))
                @if(isset($item['submenu']) and count($item['submenu'])>0)
                    <span class="opener @if($nav->isSelected($item)) active @endif">{{ $name }}</span>
                    @include('navigationpages::subPagesGrey',['items' => $item['submenu'],'nav'=>$nav])
                @else
                    <a href="{{$nav->prefix}}{{ $item['url'] or '#' }}">{{ $name }} @if(isset($item['external_link'])) <i class="fa fa-external-link"></i> @endif</a>
                @endif
            @else
                <a href="{{$nav->prefix}}{{ $item }}">{{ $name }}</a>
            @endif
        </li>
    @endforeach
    </ul>
</nav>
