{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add(__('Gruppi'),'/admin/groups')->add(__('Assegna ruoli'))
    ->setTcrumb($group->name)
    ->render() !!}
@stop

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('Ruoli diponibili') }}</h3>
                    </div>
                    {!!
                        $list->setPagination($roleDis)
                            ->columns(['id','name'=>__('Nome'),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('RTYX_')
                            ->customizes('azioni', function($row) use($group) {
                                return "<a href=\"/admin/groups/". $group->id."/addRole/".$row['id']."\" class=\"btn btn-warning btn-xs pull-right\">".__('Assegna')."</a>";
                            })->render()
                    !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
            <div class="col-md-7">

                {!!
                    $composer->boxNavigator([
                        'type'=> 'primary',
                        'title'=>$group->id ." - ".$group->name,
                        'listMenu'=>[
                            __('Lista gruppi')=>url('/admin/groups'),
                            'divider'=>"divider",
                            __('Modifica')=>url('/admin/groups/'.$group->id.'/edit'),
                            __('Assegna utenti')=>url('/admin/groups/assign',$group->id),
                            __('Assegna permessi')=>url('/admin/groups/assignPerm',$group->id),
                            __('Profilo')=>url('/admin/groups',$group->id),
                        ],
                        'urlNavPre'=>url('/admin/groups/assignRole',$pag['preid']->id),
                        'urlNavNex'=>url('/admin/groups/assignRole',$pag['nexid']->id),
                        ])->render()
                 !!}

                <div class="box box-default">
                    {!!
                         $list->setPagination($roleAss)
                            ->columns(['id','name'=>__('Nome'),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('HGYU_')
                            ->customizes('azioni', function($row) use($group) {
                                return "<a href=\"/admin/groups/". $group->id."/removeRole/".$row['id']."\" class=\"btn btn-danger btn-xs pull-right\">".__('Cancella')."</a>";
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
