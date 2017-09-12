<!-- Profile -->
<div class="box box-{{$items->box['type']}}">
    <div class="box-body box-profile">
        @if (!empty($items->box['srcImage']))
            <img class="profile-user-img img-responsive img-circle" src="{{ asset($items->box['srcImage'])}}" alt="profile picture">
        @endif
        <h3 class="profile-username text-center">{{$items->box['title']}}</h3>
        <p class="text-muted text-center">{{ $items->box['subTitle'] }}</p>
        @if(isset($items->box['listMenu']) && (count($items->box['listMenu'])>0))
            <ul class="list-group list-group-unbordered">
                @foreach($items->box['listMenu'] as $label => $value)
                <li class="list-group-item">
                    <b>{{$label}}</b> <p class="text-muted pull-right">{{$value}}</p>
                </li>
                @endforeach
            </ul>
        @endif
        @if (!empty($items->box['description']))
            <strong><i class="margin-r-5"></i>Descrizione</strong>
            <p class="text-muted" style="margin-bottom: 30px;">
                {{ $items->box['description'] }}
            </p>
        @endif
        <a href="{{ $items->box['urlEdit'] }}" class="btn btn-default btn-block"><b>Modifica</b></a>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->