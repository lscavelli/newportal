{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add(__('Ruoli'),'/admin/roles')
    ->add(__('Profilo'))
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
                        __('Livello')=>$role->level,
                        __('Creato il')=>Carbon\Carbon::parse($role->created_at)->format('d/m/Y'),
                        __('Modificato il')=>Carbon\Carbon::parse($role->updated_at)->format('d/m/Y')
                    ],
                    __('description')=>$role->description,
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
                        __('Lista ruoli')=>url('/admin/roles'),
                        'divider'=>"divider",
                        __('Modifica')=>url('/admin/roles/edit',$role->id),
                        __('Assegna permessi')=>url('/admin/roles/assign',$role->id)
                    ],
                    'urlNavPre'=>url('/admin/roles/profile',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/roles/profile',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#users" data-toggle="tab" aria-expanded="true">{{ __("Assegnato agli Utenti") }} @if(isset($listUsers))<span class="label label-success">{{$listUsers->count()}}</span>@endif</a></li>
                    <li><a href="#groups" data-toggle="tab">{{ __("Assegnato ai Gruppi") }} @if(isset($listGroups))<span class="label label-success">{{$listGroups->count()}}</span>@endif</a></li>
                    <li><a href="#permissions" data-toggle="tab">{{ __("Permessi presenti") }} @if(isset($listPermissions))<span class="label label-success">{{$listPermissions->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="users">
                        @if(isset($listUsers))
                            {!!
                                $listUsers->columns(['id','nome'=>__("Nome"),'cognome'=>__("Cognome"),'azioni'=>__("Azioni")])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($role) {
                                    if ($role->users->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/users/". $row['id'] ."/removeRole/".$role->id."\" class=\"btn btn-success btn-xs pull-right\">". __("Cancella") ."</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-success btn-xs pull-right disabled\">". __("Cancella") ."</a>";
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
                                $listGroups->columns(['id','name'=>__('Nome'),'azioni'=>__("Azioni")])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($role) {
                                    return "<a href=\"/admin/groups/". $row['id'] ."/removeRole/".$role->id."\" class=\"btn btn-success btn-xs pull-right\">". __("Cancella") ."</a>";
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
                                $listPermissions->columns(['id','name'=>__('Nome'),'azioni'=>__("Azioni")])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($role) {
                                    return "<a href=\"/admin/roles/". $role->id."/removePermission/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">". __("Cancella") ."</a>";
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
