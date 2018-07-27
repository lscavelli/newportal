<ul>
    @foreach ($items as $titsub=>$item)
        <li @if($nav->isSelected($item)) class="active" @endif>
            @if(is_array($item) and isset($item['submenu']) and count($item['submenu'])>0)
                <span class="opener @if($nav->isSelected($item)) active @endif">{{ $titsub }}</span>
                @include('navigationpages::subPagesGrey',['items' => $item['submenu'],'nav'=>$nav])
            @else
                <a href="@if($item!='#'){{ $nav->prefix }}@endif{{ $item }}">{{ $titsub }}</a>
            @endif
        </li>
    @endforeach
</ul>
