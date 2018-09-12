{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Widgets','/admin/widgets')
        ->add('Profilo')
        ->setTcrumb($widget->name)
        ->render() !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-3">

            {!!
                $composer->boxProfile([
                    'title'=>'Widget id: '. $widget->id,
                    'listMenu'=>[
                        __('Creata il')=>Carbon\Carbon::parse($widget->created_at)->format('d/m/Y'),
                        __('Modificata il')=>Carbon\Carbon::parse($widget->updated_at)->format('d/m/Y'),
                        __('Stato')=>config('newportal.status_general')[$widget->status_id],
                    ],
                    'urlEdit'=>['url'=>url(Request::getBasePath().'/admin/widgets'),'label'=>'Lista widget']
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'color'=> 'green',
                    'title'=>$widget->id." - ".$widget->name,
                    'listMenu'=>[
                        'Lista widgets'=>url('/admin/widgets'),
                    ],
                    'urlNavPre'=>url('/admin/widgets',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/widgets',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#informazioni" data-toggle="tab" aria-expanded="true">{{ __('Informazioni principali') }}</a></li>
                    <li><a href="#pages" data-toggle="tab">{{ __('Pagine contenenti la widget') }} @if(isset($listPages))<span class="label label-success">{{$listPages->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="informazioni">
                        <div class="box-body">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Direttiva</th>
                                            <th>Valore</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Descrizione</td>
                                            <td>{{ $widget->description }}</td>
                                        </tr>
                                        <tr>
                                            <td>Init</td>
                                            <td>{{ $widget->init }}</td>
                                        </tr>
                                        <tr>
                                            <td>Path</td>
                                            <td>{{ $widget->path }}</td>
                                        </tr>
                                        <tr>
                                            <td>Autore</td>
                                            <td>{{ $widget->author }}</td>
                                        </tr>
                                        <tr>
                                            <td>Revisione</td>
                                            <td>{{ $widget->revision }}</td>
                                        </tr>
                                        <tr>
                                            <td>Data</td>
                                            <td>{{ $widget->date }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tipo</td>
                                            <td>{{ $widget->type_id }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-informazioni -->
                    <div class="tab-pane" id="pages">
                        @if(isset($listPages))
                            {!!
                                $listPages->columns(['id','name'=>__('Titolo pagina'),'pivot'=>'Frame'])
                                ->showAll(false)
                                ->customizes('pivot', function($row) {
                                    return $row['pivot']['frame'];
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pages -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop
