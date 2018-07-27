<ul class="treeview-menu" style="display: none;">
    @foreach ($items as $titsub=>$item)
        <li @if($nav->isSelected($item)) class="active" @endif>
            @if(is_array($item))
                <a href="{{$nav->prefix}}{{ $item['url'] or '#' }}"><i class="fa {{ $item['icon'] or "fa-circle-o"}}"></i><span>{{ $titsub }}</span>
                @if(isset($item['submenu']) and count($item['submenu'])>0)
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    @include('navigationpages::subPages',['items' => $item['submenu'],'nav'=>$nav])
                @endif
            @else
                <a href="@if($item!='#'){{ $nav->prefix }}@endif{{ $item }}"><i class="fa fa-circle-o"></i><span>{{ $titsub }}</span></a>
            @endif
        </li>
    @endforeach
</ul>


