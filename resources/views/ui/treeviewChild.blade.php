<ul>
    @foreach($children as $child)
        <li>
            @if(count($child->children))
                <span><i class="glyphicon glyphicon-minus-sign"></i> {{ $child->name }}</span>
                @include('ui.treeviewChild',['children' => $child->children])
            @else
                <span><i class="glyphicon glyphicon-ok-circle"></i> {{ $child->name }}</span>
            @endif
        </li>
    @endforeach
</ul>