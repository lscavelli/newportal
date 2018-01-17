{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Utenti'),'/admin/users')->add(__('Assegna permessi'))
        ->setTcrumb($user->name)
        ->render() !!}
@stop

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('Permessi diponibili') }}</h3>
                    </div>
                    {!!
                        $list->setModel($permissionDis)
                            ->columns(['id','name'=>__('Nome'),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('RTYX_')
                            ->customizes('azioni', function($row) use($user) {
                                return "<a href=\"/admin/users/". $user->id."/addPermission/".$row['id']."\" class=\"btn btn-warning btn-xs pull-right\">".__('Assegna')."</a>";
                            })->render()
                    !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
            <div class="col-md-7">

                {!!
                    $composer->boxNavigator([
                        'type'=> 'primary',
                        'title'=>$user->id." - ".$user->name,
                        'listMenu'=>[
                            __('Lista utenti')=>url('/admin/users'),
                            'divider'=>"divider",
                            __('Aggiorna')=>url('/admin/users/edit',$user->id),
                            __('Attività')=>url('/admin/users/activity',$user->id),
                            __('Assegna ruoli')=>url('/admin/users/assignRole',$user->id),
                            __('Profilo')=>url('/admin/users/profile',$user->id),
                        ],
                        'urlNavPre'=>url('/admin/users/assignPerm',$pag['preid']->id),
                        'urlNavNex'=>url('/admin/users/assignPerm',$pag['nexid']->id),
                        ])->render()
                 !!}

                <div class="box box-default">
                    {!!
                         $list->setModel($permissionAss)
                            ->columns(['id','name'=>__('Nome'),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('HGYU_')
                            ->customizes('azioni', function($row) use($user) {
                                return "<a href=\"/admin/users/". $user->id."/removePermission/".$row['id']."\" class=\"btn btn-danger btn-xs pull-right\">".__('Cancella')."</a>";
                            })->render()
                     !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </section>
    <!-- /.content -->
@stop
@push('scripts')
    <script>
        $("#RTYX_xpage").change(function () {
            $("#RTYX_xpage-form").submit();
        });
        $("#HGYU_xpage").change(function () {
            $("#HGYU_xpage-form").submit();
        });
    </script>
@endpush