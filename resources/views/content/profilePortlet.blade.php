{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Portlets','/admin/portlets')
        ->add('Profilo')
        ->setTcrumb($portlet->name)
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
                    'title'=>'Portlet id: '. $portlet->id,
                    'listMenu'=>[
                        __('Creata il')=>Carbon\Carbon::parse($portlet->created_at)->format('d/m/Y'),
                        __('Modificata il')=>Carbon\Carbon::parse($portlet->updated_at)->format('d/m/Y'),
                        __('Stato')=>config('newportal.status_general')[$portlet->status_id],
                    ],
                    'urlEdit'=>['url'=>url(Request::getBasePath().'/admin/portlets'),'label'=>'Lista portlet']
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'color'=> 'green',
                    'title'=>$portlet->id." - ".$portlet->name,
                    'listMenu'=>[
                        'Lista portlets'=>url('/admin/portlets'),
                    ],
                    'urlNavPre'=>url('/admin/portlets',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/portlets',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#informazioni" data-toggle="tab" aria-expanded="true">{{ __('Informazioni principali') }}</a></li>
                    <li><a href="#pages" data-toggle="tab">{{ __('Pagine contenenti la portlet') }} @if(isset($listPages))<span class="label label-success">{{$listPages->count()}}</span>@endif</a></li>
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
                                            <td>{{ $portlet->description }}</td>
                                        </tr>
                                        <tr>
                                            <td>Init</td>
                                            <td>{{ $portlet->init }}</td>
                                        </tr>
                                        <tr>
                                            <td>Path</td>
                                            <td>{{ $portlet->path }}</td>
                                        </tr>
                                        <tr>
                                            <td>Autore</td>
                                            <td>{{ $portlet->author }}</td>
                                        </tr>
                                        <tr>
                                            <td>Revisione</td>
                                            <td>{{ $portlet->revision }}</td>
                                        </tr>
                                        <tr>
                                            <td>Data</td>
                                            <td>{{ $portlet->date }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tipo</td>
                                            <td>{{ $portlet->type_id }}</td>
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
