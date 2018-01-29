{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Gruppi'),'/admin/groups')
        ->add(__('Profilo'))
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
                        __('Creato il')=>Carbon\Carbon::parse($group->created_at)->format('d/m/Y'),
                        __('Modificato il')=>Carbon\Carbon::parse($group->updated_at)->format('d/m/Y')
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
                        __('Lista gruppi')=>url('/admin/groups'),
                        'divider'=>"divider",
                        __('Modifica')=>url('/admin/groups/edit',$group->id),
                        __('Assegna utenti')=>url('/admin/groups/assign',$group->id),
                        __('Assegna permessi')=>url('/admin/groups/assignPerm',$group->id),
                        __('Assegna ruoli')=>url('/admin/groups/assignRole',$group->id)
                    ],
                    'urlNavPre'=>url('/admin/groups/profile',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/groups/profile',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#users" data-toggle="tab" aria-expanded="true">{{ __('Membri del gruppo') }} @if(isset($listUsers))<span class="label label-success">{{$listUsers->count()}}</span>@endif</a></li>
                    <li><a href="#roles" data-toggle="tab">{{ __('Ruoli assegnati') }} @if(isset($listRoles))<span class="label label-success">{{$listRoles->count()}}</span>@endif</a></li>
                    <li><a href="#permissions" data-toggle="tab">{{ __('Permessi assegnati') }} @if(isset($listPermissions))<span class="label label-success">{{$listPermissions->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="users">
                        @if(isset($listUsers))
                            {!!
                                $listUsers->columns(['id','nome'=>__('Nome'),'cognome'=>__('Cognome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($group) {
                                    return "<a href=\"/admin/groups/".$group->id ."/removeUser/". $row['id'] ."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
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
                                $listRoles->columns(['id','name'=>__('Nome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($group) {
                                    return "<a href=\"/admin/groups/". $group->id ."/removeRole/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
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
                                $listPermissions->columns(['id','name'=>__('Nome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($group) {
                                    if ($group->permissions->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/groups/". $group->id ."/removePermission/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-success btn-xs pull-right disabled\">".__('Cancella')."</a>";
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
