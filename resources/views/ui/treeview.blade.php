<ul id="tree1">
    @foreach($nodes as $treeNode)
        <li>
            @if(count($treeNode->children))
                <span class="root"><i class="glyphicon glyphicon-folder-open"></i> {{ $treeNode->id ." ". $treeNode->name }}</span>
                @include('ui.treeviewChild',['children' => $treeNode->children])
            @else
                <span class="root"><i class="glyphicon glyphicon-tag"></i> {{ $treeNode->name }} </span>
            @endif
        </li>
    @endforeach
</ul>
@push('style')
    {{ Html::style('/css/treeview.css') }}
@endpush
@push('scripts')
    {{ Html::script('/js/treeview.js') }}
@endpush