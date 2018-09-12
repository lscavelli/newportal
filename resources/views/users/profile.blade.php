{{--dd($listActivity)--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('lista utenti'),'/admin/users')
        ->add(__('Profilo'))
        ->setTcrumb($user->name)
        ->render() !!}
@stop

@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-3">
            <?php $status_id = (!empty($user->status_id) ? $user->status_id: 1); ?>
            {!!
                $composer->boxProfile([
                    'type'=> 'primary',
                    'srcImage'=>$user->getAvatar(),
                    'title'=>$user->name,
                    'subTitle' =>$user->email,
                    'listMenu'=>[
                        __('Creato il')=>Carbon\Carbon::parse($user->created_at)->format('d/m/Y'),
                        __('Modificato il')=>Carbon\Carbon::parse($user->updated_at)->format('d/m/Y'),
                        __('Stato')=>config('newportal.status_user')[$status_id],
                    ],
                    'urlEdit'=>url(Request::getBasePath().'/admin/users/'.$user->id.'/edit')
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'type'=> 'primary',
                    'title'=>$user->id." - ".$user->name,
                    'listMenu'=>[
                        __('Lista utenti')=>url('/admin/users'),
                        'divider'=>"divider",
                        __('Modifica')=>url('/admin/users/'.$user->id.'/edit'),
                        __('Attività')=>url('/admin/users/activity',$user->id),
                        __('Assegna permessi')=>url('/admin/users/assignPerm',$user->id),
                        __('Assegna ruoli')=>url('/admin/users/assignRole',$user->id)
                    ],
                    'urlNavPre'=>url('/admin/users',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/users',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#groups" data-toggle="tab" aria-expanded="true">{{ __('Membro dei gruppi') }} @if(isset($listGroups))<span class="label label-primary">{{$listGroups->total()}}</span>@endif</a></li>
                    <li><a href="#organizations" data-toggle="tab">{{ __('Membro delle org.ni') }} @if(isset($listOrganizations))<span class="label label-primary">{{$listOrganizations->total()}}</span>@endif</a></li>
                    <li><a href="#roles" data-toggle="tab">{{ __('Ruoli assegnati') }} @if(isset($listRoles))<span class="label label-primary">{{$listRoles->total()}}</span>@endif</a></li>
                    <li><a href="#permissions" data-toggle="tab">{{ __('Permessi assegnati') }} @if(isset($listPermissions))<span class="label label-primary">{{$listPermissions->total()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="groups">
                        @if(isset($listGroups))
                            {!!
                                $listGroups->columns(['id','name'=>__('Nome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($user) {
                                    return "<a href=\"/admin/groups/". $row['id'] ."/removeUser/".$user->id."\" class=\"btn btn-primary btn-xs pull-right\">".__('Cancella')."</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="organizations">
                        @if(isset($listOrganizations))
                            {!!
                                $listOrganizations->columns(['id','name'=>__('Nome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($user) {
                                    return "<a href=\"/admin/organizations/". $row['id'] ."/removeUser/".$user->id."\" class=\"btn btn-primary btn-xs pull-right\">".__('Cancella')."</a>";
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
                                ->customizes('azioni', function($row) use($user) {
                                    if ($user->roles->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/users/". $user->id ."/removeRole/".$row['id']."\" class=\"btn btn-primary btn-xs pull-right\">".__('Cancella')."</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-primary btn-xs pull-right disabled\">".__('Cancella')."</a>";
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
                                ->customizes('azioni', function($row) use($user) {
                                    if ($user->permissions->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/users/". $user->id ."/removePermission/".$row['id']."\" class=\"btn btn-primary btn-xs pull-right\">".__('Cancella')."</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-primary btn-xs pull-right disabled\">".__('Cancella')."</a>";
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
            <!-- About Me Box -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_altridati" data-toggle="tab" aria-expanded="true">{{ __('Altri dati') }}</a></li>
                    <li class=""><a href="#tab_activities" data-toggle="tab" aria-expanded="false">{{ __('Attività') }} @if(isset($listActivity))<span class="label label-primary">{{$listActivity->total()}}</span>@endif</a></li>
                    <li class=""><a href="#tab_sessions" data-toggle="tab" aria-expanded="false">{{ __('Sessioni attive') }} @if(isset($listSessions))<span class="label label-primary">{{$listSessions->total()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_altridati">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <strong><i class="fa fa-book margin-r-5"></i> {{ __('Contatti') }}</strong>
                            <p class="text-muted">@if( !empty($user->telefono)) {{__('Telefono').": ".$user->telefono}} @endif</p>
                            <hr>
                            <strong><i class="fa fa-map-marker margin-r-5"></i> {{ __('Indirizzo') }}</strong>
                            <p class="text-muted">{{ $user->indirizzo }}</p>
                            <hr>
                            <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>
                            <p class="text-muted">{{ $user->note }}</p>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_activities">
                        {!!
                            $listActivity->columns(['id','created_at'=>__('Data'),'description'=>__('Attività'),'ip_address'=>'Ip'])
                            ->showActions(false)
                            ->showButtonNew(false)
                            ->customizes('created_at',function($row){
                                return Carbon\Carbon::parse($row['created_at'])->format('d-m-Y H:i');
                            })->appends(['tab_activities'=>1])->render()
                        !!}
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_sessions">
                        {!!
                             $listSessions->columns(['ip_address'=>'Ip','user_agent','last_activity'=>__('Ultima attività'),'azioni'=>__('Azioni')])
                             ->showButtonNew(false)
                             ->showActions(false)
                             ->showSearch(false)
                             ->showXpage(false)
                             ->customizes('last_activity',function($row){
                                 return Carbon\Carbon::createFromTimestamp($row['last_activity'])->format('d-m-Y H:i');
                             })
                             ->customizes('azioni',function($row){
                                 return "<a href=\"#\" class=\"btn btn-danger btn-xs pull-right delete\" data-id=\"{$row['id']}\">".__('Cancella')."</a>";
                             })->appends(['tab_sessions'=>1])->render()
                        !!}
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>
@stop
@push('scripts')
    <script>
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if (results==null){
                return null;
            }
            else{
                return results[1] || 0;
            }
        }
        if ($.urlParam('tab_activities')==1) {
            $('.nav-tabs a[href="#tab_activities"]').trigger('click');
        }
        if ($.urlParam('tab_sessions')==1) {
            $('.nav-tabs a[href="#tab_sessions"]').trigger('click');
        }
    </script>
@endpush
