@if ($nav->hasPages())
    <ul class="navigation">
        @foreach ($nav->items as $item)
            @if ($item == $nav->currentPage())
                <li class="active"><span>{{ $page }}</span></li>
            @else
                <li><a href="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endfor
    </ul>
@endif