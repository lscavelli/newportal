{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add('Organizzazioni','/admin/organizations')->add('Assegna utenti')
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
                        <h3 class="box-title">Utenti diponibili</h3>
                    </div>
                    {!!
                        $list->setModel($usersDis)
                            ->columns(['id','nome','cognome','azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('RTYX_')
                            ->customizes('azioni', function($row) use($organization) {
                                return "<a href=\"/admin/organizations/". $organization->id."/addUser/".$row['id']."\" class=\"btn btn-warning btn-xs pull-right\">Assegna</a>";
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
                            'Lista Organizzazioni'=>url('/admin/organizations'),
                            'divider'=>"divider",
                            'Modifica'=>url('/admin/organizations/edit',$organization->id),
                            'Assegna filiali'=>url('/admin/organizations/assignFilial',$organization->id),
                            'Profilo'=>url('/admin/organizations/profile',$organization->id),
                        ],
                        'urlNavPre'=>url('/admin/organizations/assignUser',$pag['preid']->id),
                        'urlNavNex'=>url('/admin/organizations/assignUser',$pag['nexid']->id),
                        ])->render()
                 !!}

                <div class="box box-default">
                    {!!
                         $list->setModel($usersAss)
                            ->columns(['id','nome','cognome','azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('HGYU_')
                            ->customizes('azioni', function($row) use($organization) {
                                return "<a href=\"/admin/organizations/". $organization->id."/removeUser/".$row['id']."\" class=\"btn btn-danger btn-xs pull-right\">Cancella</a>";
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