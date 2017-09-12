{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Gruppi','/admin/groups')
        ->add('Profilo')
        ->setTcrumb($group->name)
        ->render()
    !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-3">

            {!!
                $composer->boxProfile([
                    'subTitle' =>$group->slug,
                    'listMenu'=>[
                        'Creato il'=>Carbon\Carbon::parse($group->created_at)->format('d/m/Y'),
                        'Modificato il'=>Carbon\Carbon::parse($group->updated_at)->format('d/m/Y')
                    ],
                    'description'=>$group->description,
                    'urlEdit'=>url(Request::getBasePath().'/admin/groups/edit', $group->id)
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'color'=> 'green',
                    'title'=>$group->id ." - ".$group->name,
                    'listMenu'=>[
                        'Lista gruppi'=>url('/admin/groups'),
                        'divider'=>"divider",
                        'Modifica'=>url('/admin/groups/edit',$group->id),
                        'Assegna utenti'=>url('/admin/groups/assign',$group->id),
                        'Assegna permessi'=>url('/admin/groups/assignPerm',$group->id),
                        'Assegna ruoli'=>url('/admin/groups/assignRole',$group->id)
                    ],
                    'urlNavPre'=>url('/admin/groups/profile',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/groups/profile',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#users" data-toggle="tab" aria-expanded="true">Membri del gruppo @if(isset($listUsers))<span class="label label-success">{{$listUsers->count()}}</span>@endif</a></li>
                    <li><a href="#roles" data-toggle="tab">Ruoli assegnati @if(isset($listRoles))<span class="label label-success">{{$listRoles->count()}}</span>@endif</a></li>
                    <li><a href="#permissions" data-toggle="tab">Permessi assegnati @if(isset($listPermissions))<span class="label label-success">{{$listPermissions->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="users">
                        @if(isset($listUsers))
                            {!!
                                $listUsers->columns(['id','nome','cognome','azioni'])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($group) {
                                    return "<a href=\"/admin/groups/".$group->id ."/removeUser/". $row['id'] ."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="roles">
                        @if(isset($listRoles))
                            {!!
                                $listRoles->columns(['id','name'=>'Nome','azioni'])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($group) {
                                    return "<a href=\"/admin/groups/". $group->id ."/removeRole/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="permissions">
                        @if(isset($listPermissions))
                            {!!
                                $listPermissions->columns(['id','name'=>'Nome','azioni'])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($group) {
                                    if ($group->permissions->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/groups/". $group->id ."/removePermission/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-success btn-xs pull-right disabled\">Cancella</a>";
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
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop
