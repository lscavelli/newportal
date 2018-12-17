{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add(__('Organizzazioni'),'/admin/organizations')->add(__('Assegna utenti'))
    ->setTcrumb($organization->name)
    ->render() !!}
@stop


@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __("Utenti diponibili") }}</h3>
                    </div>
                    {!!
                        $list->setPagination($usersDis)
                            ->columns(['id','nome'=>__("Nome"),'cognome'=>__("Cognome"),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('RTYX_')
                            ->customizes('azioni', function($row) use($organization) {
                                return "<a href=\"/admin/organizations/". $organization->id."/addUser/".$row['id']."\" class=\"btn btn-warning btn-xs pull-right\">".__('Assegna')."</a>";
                            })->render()
                    !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
            <div class="col-md-7">

                {!!
                    $composer->boxNavigator([
                        'type'=>'primary',
                        'title'=>$organization->id ." - ".$organization->name,
                        'listMenu'=>[
                            __('Lista Organizzazioni')=>url('/admin/organizations'),
                            'divider'=>"divider",
                            __('Modifica')=>url('/admin/organizations/'.$organization->id.'/edit'),
                            __('Assegna filiali')=>url('/admin/organizations/assignFilial',$organization->id),
                            __('Profilo')=>url('/admin/organizations',$organization->id),
                        ],
                        'urlNavPre'=>url('/admin/organizations/assignUser',$pag['preid']->id),
                        'urlNavNex'=>url('/admin/organizations/assignUser',$pag['nexid']->id),
                        ])->render()
                 !!}

                <div class="box box-default">
                    {!!
                         $list->setPagination($usersAss)
                            ->columns(['id','nome'=>__("Nome"),'cognome'=>__("Cognome"),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('HGYU_')
                            ->customizes('azioni', function($row) use($organization) {
                                return "<a href=\"/admin/organizations/". $organization->id."/removeUser/".$row['id']."\" class=\"btn btn-danger btn-xs pull-right\">".__('Cancella')."</a>";
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
