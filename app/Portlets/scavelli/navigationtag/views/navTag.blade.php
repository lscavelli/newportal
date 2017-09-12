<ul class="navtag">
    @foreach ($nav->items as $title=>$item)
        <li @if(is_array($item))
                @if(isset($item['class']))
                    class="{{ $item['class'] }}@if($nav->isSelected($item)) active @endif">
                    @if($item['class']=="header"){{ $title }}
                    @elseif($item['class']=="treeview")
                        <a href="{{$nav->prefix}}{{ $item['url'] or '#' }}"><i class="fa {{ $item['icon'] or "fa-circle-o"}}"></i><span>{{ $title }}</span></a>
                    @endif
                @endif
            @else
                ><a href="{{$nav->prefix}}{{ $item }}"><i class="fa fa-circle-o"></i><span>{{ $title }}</span></a>
            @endif
        </li>
    @endforeach
</ul>
