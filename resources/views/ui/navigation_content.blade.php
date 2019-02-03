<!-- Content Menu -->
<ul class="nav nav-pills nav-stacked">
    @foreach ($nav->items as $title=>$item)
        <li @if(is_array($item))
               {{--dd($item)--}}
                @if($nav->isSelected($item))class="active"@endif>
                <a href="{{ $item['url'] ?? '#' }}" @if(isset($item['class']))class="{{$item['class']}}"@endif><i class="fa {{ $item['icon'] ?? "fa-circle-o"}}"></i><span>{{ $title }}</span>
                @if(isset($item['submenu']) and count($item['submenu'])>0)
                    <span class="pull-right-container">
                         <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul>
                        @foreach ($item['submenu'] as $titsub=>$href)
                            <li @if($nav->isSelected($href)) class="active" @endif><a href="{{ $href }}"><i class="fa fa-circle-o"></i>{{ $titsub }}</a></li>
                        @endforeach
                    </ul>
                @else
                    </a>
                @endif
            @else
                ><a href="{{ $item }}"><i class="fa fa-circle-o"></i><span>{{ $title }}</span></a>
            @endif
        </li>
    @endforeach
</ul>
