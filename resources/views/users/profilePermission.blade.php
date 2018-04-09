{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add(__('Permessi'),'/admin/permissions')
    ->add(__('Profilo'))
    ->setTcrumb($permission->name)
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
                    'subTitle' =>$permission->slug,
                    'listMenu'=>[
                        __('Creato il')=>Carbon\Carbon::parse($permission->created_at)->format('d/m/Y'),
                        __('Modificato il')=>Carbon\Carbon::parse($permission->updated_at)->format('d/m/Y')
                    ],
                    'description'=>$permission->description,
                    'urlEdit'=>url(Request::getBasePath().'/admin/permissions/'.$permission->id.'/edit')
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'color'=> 'green',
                    'title'=>$permission->id." - ".$permission->name,
                    'listMenu'=>[
                        __('Lista permessi')=>url('/admin/permissions'),
                        'divider'=>"divider",
                        __('Modifica')=>url('/admin/permissions/'.$permission->id.'/edit'),
                    ],
                    'urlNavPre'=>url('/admin/permissions',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/permissions',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#roles" data-toggle="tab" aria-expanded="true">{{ __('Fa parte dei Ruoli') }} @if(isset($listRoles))<span class="label label-success">{{$listRoles->count()}}</span>@endif</a></li>
                    <li><a href="#users" data-toggle="tab">{{ __('Assegnato agli Utenti') }} @if(isset($listUsers))<span class="label label-success">{{$listUsers->count()}}</span>@endif</a></li>
                    <li><a href="#groups" data-toggle="tab">{{ __('Assegnato ai Gruppi') }} @if(isset($listGroups))<span class="label label-success">{{$listGroups->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="roles">
                        @if(isset($listRoles))
                            {!!
                                $listRoles->columns(['id','name'=>__('Nome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($permission) {
                                    return "<a href=\"/admin/roles/". $row['id']."/removePermission/".$permission->id."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
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
                                $listUsers->columns(['id','nome'=>_('Nome'),'cognome'=>_('Cognome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($permission) {
                                    if ($permission->users->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/users/". $row['id'] ."/removePermission/".$permission->id."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-success btn-xs pull-right disabled\">".__('Cancella')."</a>";
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
                                $listGroups->columns(['id','name'=>__('Nome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($permission) {
                                    if ($permission->groups->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/groups/". $row['id'] ."/removePermission/".$permission->id."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
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
