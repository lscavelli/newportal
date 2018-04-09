<div class="btn-group pull-right">
    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
        <i class="glyphicon glyphicon-fire"></i> Azioni <span class="caret"></span>
    </button>

    <ul class="dropdown-menu" role="menu">
        <li><a href="{{ url(Request::path().'/'.$id, 'edit') }}">Edit</a></li>
        <li><a href="#" class="delete" data-id="{{$id}}">Delete</a></li>
        @if(isset($actions))
            @foreach($actions as $action)
                <li><a href="
                @if(starts_with($action['azione'], 'http'))
                    {{$action['azione']."/".$id}}
                @else
                    {{ url(Request::path().'/'.$action['azione'], $id) }}
                @endif
                ">{{$action['label']}}</a></li>
            @endforeach
        @endif
    </ul>
</div>