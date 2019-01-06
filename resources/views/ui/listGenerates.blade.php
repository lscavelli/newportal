{{--dd($model)--}}
<!-- .header-list -->
@if($list->showAll and ($list->showButtonNew or $list->showSearch))
<div class="box-body">
    <div class="col-sm-3">
        @if($list->showButtonNew)
            <a href="{{ url(Request::path().'/create') }}" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Nuovo</a>
        @endif
        @if (count($list->splitButtons)>0)
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm">Nuovo</button>
                <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu splitButtons" role="menu">
            @foreach($list->splitButtons  as $key=>$val)
                        <li><a href="{{$key}}">{{$val}}</a></li>
            @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="col-sm-3 col-sm-offset-6">
        @if($list->showSearch)
        <form method = 'GET' action = '{{url(Request::path())}}'>
            @csrf
            @foreach(array_except(\Request::all(),['_token',$list->prefix_.'keySearch','page'.$list->prefixPage]) as $key=>$value)
                {!! Form::hidden($key,$value) !!}
            @endforeach
            <div class="input-group input-group-sm">
                <input id="{{$list->prefix_}}keySearch" name = "{{$list->prefix_}}keySearch" type="text" class="form-control validate" value="{{Request::input($list->prefix_.'keySearch')}}" placeholder="Chiave di ricerca">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-primary btn-flat">Cerca</button>
                </span>
            </div>
        </form>
        @endif
    </div>
</div>
@endif
<!-- .body-list -->
<div class="box-body">
    <div class="col-sm-12">
        <!-- /inizio table -->
        <table @if($list->attributes) {!! $list->attributes !!} @else class="table table-bordered table-striped" @endif>
            <thead>
                <tr>
                    @foreach($list->labelsList as $key=>$val)
                        <th {!! $val['attributes'] !!}>{!! $val['labels'] !!}</th>
                    @endforeach
                    @if($list->showActions and $list->showAll)
                        <th style='text-align:right'>Azioni</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            @foreach($model as $row)
                <tr {!! $list->getRowAttributes($row) !!}>
                    @foreach($list->columns as $key => $val)
                        <td {!! $list->getCellAttributes($key,$row) !!}>
                            @if(array_key_exists($key, $list->customize))
                                {!! call_user_func_array($list->customize[$key], array($row)) !!}
                            @else
                                @if($row[$key])
                                    {!! $row[$key]  !!}
                                @else
                                    @if(isset($row[$val])){!! $row[$val]  !!}@endif
                                @endif
                            @endif
                        </td>
                    @endforeach
                    @if($list->showActions and $list->showAll)
                        <td>
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-fire"></i> Azioni <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @if($list->showActionsDefault)
                                        <li><a href="{{ url(Request::path().'/'.$row['id'], 'edit') }}">Edit</a></li>
                                        <li><a href="#" class="delete" data-id="{{$row['id']}}">Delete</a></li>
                                    @endif
                                    @if(count($list->actions)>0)
                                        @foreach($list->actions as $actionUrl=>$actionLabel)
                                            @if($actionUrl=='closure')
                                                {!! call_user_func_array($actionLabel, array($row)) !!}
                                                @continue
                                            @elseif (is_array($actionLabel))
                                                @can($actionLabel[1])
                                                        <?php $actionLabel = $actionLabel[0]; ?>
                                                @else
                                                        @continue
                                                @endcan
                                            @endif
                                            <li><a href="
                                            @if(starts_with($actionUrl, 'http'))
                                                {{$actionUrl."/".$row['id']}}
                                            @elseif(is_numeric($actionUrl))
                                                {{ url(Request::path(), $row['id']) }}
                                            @else
                                                {{ url(Request::path().'/'.$actionUrl, $row['id']) }}
                                            @endif
                                                    " @if($actionLabel=="Delete")class="delete" data-id="{{$row['id']}}"@endif>{{$actionLabel}}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- /fine table -->
</div>
</div>
<!-- .footer-list -->
<div class="box-body">
<div class="col-sm-5">
    @if($list->showAll and $list->showXpage)
    <div class="dataTables_length">
        Da {!! $model->firstItem() !!} a {!! $model->lastItem() !!} / {!! $model->count() !!} - Mostra
        <label>
            <form method="GET" id="{{$list->prefix_}}xpage-form" action="{{ url(Request::path()) }}">
                @csrf
                {!! Form::select($list->prefix_.'xpage', ['5'=>'5','15'=>'15','25'=>'25','50'=>'50','100'=>'100'], \Request::input($list->prefix_.'xpage'), ['class' => "form-control input-sm", 'id'=>$list->prefix_.'xpage']) !!}
                @foreach(array_except(\Request::all(),['_token',$list->prefix_.'xpage',$list->prefix_.'page']) as $key=>$value)
                    {!! Form::hidden($key,$value) !!}
                @endforeach
            </form>
        </label>
    </div>
    @endif
</div>
<div class="col-sm-7">
    <div class='pull-right' Style="margin-top: -20px;">
        {{
            $model->appends(array_except(\Request::all(),['_token','page']))->links()
        }}
    </div>
</div>
</div>
@include('ui.confirmdelete')
@if($list->showAll)
@section('scripts')
    <script>
        $("#xpage").change(function () {
            $("#xpage-form").submit();
        });
        $('#confirmdelete').on('shown.bs.modal', function(){
        });

        $(".delete").click(function() {
            $('#confirmdelete').modal('toggle');
            $('.modal-body p').text("Sei sicuro di voler eliminare l'elemento id "+$(this).data('id'));
            $('#confirmForm').prop('action', '{{ $list->urlDelete }}/' + $(this).data('id'));
        });
    </script>
@stop
@endif
