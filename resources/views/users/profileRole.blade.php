{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add('Ruoli','/admin/roles')
    ->add('Profilo')
    ->setTcrumb($role->name)->render() !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-3">

            {!!
                $composer->boxProfile([
                    'subTitle' =>$role->slug,
                    'listMenu'=>[
                        'Livello'=>$role->level,
                        'Creato il'=>Carbon\Carbon::parse($role->created_at)->format('d/m/Y'),
                        'Modificato il'=>Carbon\Carbon::parse($role->updated_at)->format('d/m/Y')
                    ],
                    'description'=>$role->description,
                    'urlEdit'=>url(Request::getBasePath().'/admin/roles/edit', $role->id)
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'color'=> 'green',
                    'title'=>$role->id." - ".$role->name,
                    'listMenu'=>[
                        'Lista ruoli'=>url('/admin/roles'),
                        'divider'=>"divider",
                        'Modifica'=>url('/admin/roles/edit',$role->id),
                        'Assegna permessi'=>url('/admin/roles/assign',$role->id)
                    ],
                    'urlNavPre'=>url('/admin/roles/profile',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/roles/profile',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#users" data-toggle="tab" aria-expanded="true">Assegnato agli Utenti @if(isset($listUsers))<span class="label label-success">{{$listUsers->count()}}</span>@endif</a></li>
                    <li><a href="#groups" data-toggle="tab">Assegnato ai Gruppi @if(isset($listGroups))<span class="label label-success">{{$listGroups->count()}}</span>@endif</a></li>
                    <li><a href="#permissions" data-toggle="tab">Permessi presenti @if(isset($listPermissions))<span class="label label-success">{{$listPermissions->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="users">
                        @if(isset($listUsers))
                            {!!
                                $listUsers->columns(['id','nome','cognome','azioni'])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($role) {
                                    if ($role->users->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/users/". $row['id'] ."/removeRole/".$role->id."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-success btn-xs pull-right disabled\">Cancella</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="groups">
                        @if(isset($listGroups))
                            {!!
                                $listGroups->columns(['id','name'=>'Nome','azioni'])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($role) {
                                    return "<a href=\"/admin/groups/". $row['id'] ."/removeRole/".$role->id."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
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
                                ->customizes('azioni', function($row) use($role) {
                                    return "<a href=\"/admin/roles/". $role->id."/removePermission/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
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
