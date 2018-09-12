<ul class="navigationpage">
    @foreach ($nav->items as $title=>$item)
        <li @if(is_array($item))
                @if(isset($item['class']))
                    class="{{ $item['class'] }}@if($nav->isSelected($item)) active @endif">
                    @if($item['class']=="header"){{ $title }}
                    @elseif($item['class']=="treeview")
                        <a href="{{$nav->prefix}}{{ $item['url'] ?? '#' }}"><i class="fa {{ $item['icon'] ?? "fa-circle-o"}}"></i><span>{{ $title }}</span>
                        @if(isset($item['submenu']) and count($item['submenu'])>0)
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            </a>
                            <ul class="treeview-menu">
                                @foreach ($item['submenu'] as $titsub=>$href)
                                    <li @if($nav->isSelected($href)) class="active" @endif><a href="@if($href!='#'){{ $nav->prefix }}@endif{{ $href }}"><i class="fa fa-circle-o"></i>{{ $titsub }}</a></li>
                                @endforeach
                            </ul>
                        @else
                            </a>
                        @endif
                    @endif
                @endif
            @else
                ><a href="{{$nav->prefix}}{{ $item }}"><i class="fa fa-circle-o"></i><span>{{ $title }}</span></a>
            @endif
        </li>
    @endforeach
</ul>
