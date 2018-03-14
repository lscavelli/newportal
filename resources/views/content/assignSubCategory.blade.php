{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Vocabolari','/admin/vocabularies/')->add('Categorie','/admin/vocabularies/cat/'.$vocabulary->id)->add('Assegna sottocategorie')
        ->setTcrumb($category->name. " - Vocabolario: ".$vocabulary->name)
        ->render() !!}
@stop

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Categorie diponibili</h3>
                    </div>
                    {!!
                        $list->setModel($subcatDis)
                            ->columns(['id','name'=>'Nome','azioni'])
                            ->showActions(false)
                            ->showButtonNew(false)
                            ->setPrefix('RTYX_')
                            ->customizes('azioni', function($row) use($category) {
                                return "<a href=\"/admin/vocabularies/cat/". $category->id."/addSubcat/".$row['id']."\" class=\"btn btn-warning btn-xs pull-right\">Assegna</a>";
                            })->render()
                    !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
            <div class="col-md-7">

                {!!
                    $composer->boxNavigator([
                        'type'=>'primary',
                        'title'=>$category->id ." - ".$category->name,
                        'listMenu'=>[
                            'Vocabolari'=>url('/admin/vocabularies'),
                            'Lista categorie'=>url('/admin/vocabularies/cat/'.$vocabulary->id),
                            'divider'=>"divider",
                            'Modifica'=>url('/admin/vocabularies/cat/'.$vocabulary->id.'/'.$category->id.'/edit'),
                            'Profilo'=>url('/admin/vocabularies/cat/profile',$category->id),
                        ],
                        'urlNavPre'=>url('/admin/vocabularies/cat/assignSubcat',$pag['preid']->id),
                        'urlNavNex'=>url('/admin/vocabularies/cat/assignSubcat',$pag['nexid']->id),
                        ])->render()
                 !!}

                <div class="box box-default">
                    {!!
                         $list->setModel($subcatAss)
                            ->columns(['id','name'=>'Nome','azioni'])
                            ->showActions(false)
                            ->showButtonNew(false)
                            ->setPrefix('HGYU_')
                            ->customizes('azioni', function($row) use($category) {
                                return "<a href=\"/admin/vocabularies/cat/removeSubcat/".$row['id']."\" class=\"btn btn-danger btn-xs pull-right\">Cancella</a>";
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