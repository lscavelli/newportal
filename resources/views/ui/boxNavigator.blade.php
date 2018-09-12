<div class="box box-solid bg-{{$items->box['color']}}-gradient" style="margin-bottom: 10px">
    <div class="box-header ui-sortable-handle">
        <i class="fa fa-calendar"></i><h3 class="box-title">{{$items->box['title']}}</h3>
        <!-- tools box -->
        <div class="pull-right box-tools">
            @if($items->box['listMenu'])
            <!-- button with a dropdown -->
            <div class="btn-group">
                <button type="button" class="btn btn-{{$items->box['type']}} btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bars"></i></button>
                <ul class="dropdown-menu pull-right" role="menu">
                    @foreach($items->box['listMenu'] as $label => $href)
                        @if($href!="divider")
                            <li><a href="{{$href}}">{{$label}}</a></li>
                        @else
                            <li class="divider"></li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif
            <button type="button" class="btn btn-{{$items->box['type']}} btn-sm" onclick="location.href='{{$items->box['urlNavPre']}}';"><i class="fa fa-chevron-left"></i></button>
            <button type="button" class="btn btn-{{$items->box['type']}} btn-sm" onclick="location.href='{{$items->box['urlNavNex']}}';"><i class="fa fa-chevron-right"></i></button>
        </div>
        <!-- /. tools -->
    </div>
</div>