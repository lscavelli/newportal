{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Organizzazioni'),'/admin/organizations')
        ->add(__('Profilo'))
        ->setTcrumb($organization->name)
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
                    'subTitle' =>(($filial = $organization->parent()->pluck('name')->first()) ? __('Filiale di').': '.$filial : null),
                    'listMenu'=>[
                        __('Creato il')=>Carbon\Carbon::parse($organization->created_at)->format('d/m/Y'),
                        __('Modificato il')=>Carbon\Carbon::parse($organization->updated_at)->format('d/m/Y')
                    ],
                    'description'=>$organization->description,
                    'urlEdit'=>url(Request::getBasePath().'/admin/organizations/'.$organization->id.'/edit')
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'color'=> 'green',
                    'title'=>$organization->id." - ".$organization->name,
                    'listMenu'=>[
                        __('Lista organizzazioni')=>url('/admin/organizations'),
                        'divider'=>"divider",
                        __('Modifica')=>url('/admin/organizations/'.$organization->id.'/edit'),
                        __('Assegna utenti')=>url('/admin/organizations/assignUser',$organization->id),
                        __('Assegna filiali')=>url('/admin/organizations/assignFilial',$organization->id)
                    ],
                    'urlNavPre'=>url('/admin/organizations',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/organizations',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#filials" data-toggle="tab" aria-expanded="true">{{ __('Filiali presenti') }} @if(isset($listFilials))<span class="label label-success">{{$listFilials->count()}}</span>@endif</a></li>
                    <li><a href="#users" data-toggle="tab">{{ __("Membri dell'organizzazione") }} @if(isset($listUsers))<span class="label label-success">{{$listUsers->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="filials">
                        @if(isset($listFilials))
                            {!!
                                $listFilials->columns(['id','name'=>__('Nome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($organization) {
                                    return "<a href=\"/admin/organizations/removeFilial/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="users">
                        @if(isset($listUsers))
                            {!!
                                $listUsers->columns(['id','nome'=>__('Nome'),'cognome'=>__('Cognome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($organization) {
                                    return "<a href=\"/admin/organizations/". $organization->id."/removeUser/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->

            <div class="box">
                <div class="box-header">
                    <i class="fa fa-code-fork"></i>
                    <h3 class="box-title">{{$titleGraph}}</h3>
                </div>
                <div class="tree">
                    @include('ui.treeview',['nodes' => $graphorg])
                </div>
            </div> <!-- /.box -->

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop
