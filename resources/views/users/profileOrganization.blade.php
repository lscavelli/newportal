{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Organizzazioni','/admin/organizations')
        ->add('Profilo')
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
                    'subTitle' =>(($filial = $organization->parent()->pluck('name')->first()) ? 'Filiale di: '.$filial : null),
                    'listMenu'=>[
                        'Creato il'=>Carbon\Carbon::parse($organization->created_at)->format('d/m/Y'),
                        'Modificato il'=>Carbon\Carbon::parse($organization->updated_at)->format('d/m/Y')
                    ],
                    'description'=>$organization->description,
                    'urlEdit'=>url(Request::getBasePath().'/admin/organizations/edit', $organization->id)
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
                        'Lista organizzazioni'=>url('/admin/organizations'),
                        'divider'=>"divider",
                        'Modifica'=>url('/admin/organizations/edit',$organization->id),
                        'Assegna utenti'=>url('/admin/organizations/assignUser',$organization->id),
                        'Assegna filiali'=>url('/admin/organizations/assignFilial',$organization->id)
                    ],
                    'urlNavPre'=>url('/admin/organizations/profile',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/organizations/profile',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#filials" data-toggle="tab" aria-expanded="true">Filiali presenti @if(isset($listFilials))<span class="label label-success">{{$listFilials->count()}}</span>@endif</a></li>
                    <li><a href="#users" data-toggle="tab">Membri dell'organizzazione @if(isset($listUsers))<span class="label label-success">{{$listUsers->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="filials">
                        @if(isset($listFilials))
                            {!!
                                $listFilials->columns(['id','name'=>'Nome','azioni'])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($organization) {
                                    return "<a href=\"/admin/organizations/removeFilial/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
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
                                $listUsers->columns(['id','nome','cognome','azioni'])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($organization) {
                                    return "<a href=\"/admin/organizations/". $organization->id."/removeUser/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
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
