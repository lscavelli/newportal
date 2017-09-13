{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Ruoli','/admin/roles')->add('Assegna permessi')
        ->setTcrumb($role->name)
        ->render() !!}
@stop

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Permessi diponibili</h3>
                    </div>
                    {!!
                        $list->setModel($permissionDis)
                            ->columns(['id','name'=>'Nome','azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('RTYX_')
                            ->customizes('azioni', function($row) use($role) {
                                return "<a href=\"/admin/roles/". $role->id."/addPermission/".$row['id']."\" class=\"btn btn-warning btn-xs pull-right\">Assegna</a>";
                            })->render()
                    !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
            <div class="col-md-7">

                {!!
                    $composer->boxNavigator([
                        'type'=> 'primary',
                        'title'=>$role->id." - ".$role->name,
                        'listMenu'=>[
                            'Lista ruoli'=>url('/admin/roles'),
                            'divider'=>"divider",
                            'Modifica'=>url('/admin/roles/edit',$role->id),
                            'Profilo'=>url('/admin/roles/profile',$role->id),
                        ],
                        'urlNavPre'=>url('/admin/roles/assign',$pag['preid']->id),
                        'urlNavNex'=>url('/admin/roles/assign',$pag['nexid']->id),
                        ])->render()
                 !!}

                <div class="box box-default">
                    {!!
                         $list->setModel($permissionAss)
                            ->columns(['id','name'=>'Nome','azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('HGYU_')
                            ->customizes('azioni', function($row) use($role) {
                                return "<a href=\"/admin/roles/". $role->id."/removePermission/".$row['id']."\" class=\"btn btn-danger btn-xs pull-right\">Cancella</a>";
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