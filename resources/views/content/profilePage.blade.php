{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Pagine','/admin/pages')
        ->add('Profilo')
        ->setTcrumb($page->name)
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
                    'subTitle' =>(($figlia = $page->parent()->pluck('name')->first()) ? 'Figlia di: '.$figlia : null),
                    'listMenu'=>[
                        'Creato il'=>Carbon\Carbon::parse($page->created_at)->format('d/m/Y'),
                        'Modificato il'=>Carbon\Carbon::parse($page->updated_at)->format('d/m/Y')
                    ],
                    'description'=>$page->description,
                    'urlEdit'=>url(Request::getBasePath().'/admin/pages/edit', $page->id)
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'color'=> 'green',
                    'title'=>$page->id." - ".$page->name,
                    'listMenu'=>[
                        'Lista pagine'=>url('/admin/pages'),
                        'divider'=>"divider",
                        'Modifica'=>url('/admin/pages/edit',$page->id),
                        'Crea pagina figlia'=>url('/admin/pages/create',$page->id),
                        'Duplica pagina'=>url('/admin/pages/duplicates',$page->id),
                        'divider1'=>"divider",
                        'Layout'=>url('/admin/pages/addLayout',$page->id),
                    ],
                    'urlNavPre'=>url('/admin/pages/profile',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/pages/profile',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#figlie" data-toggle="tab" aria-expanded="true">Sottopagine presenti @if(isset($listChildren))<span class="label label-success">{{$listChildren->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="figlie">
                        @if(isset($listChildren))
                            {!!
                                $listChildren->columns(['id','name'=>'Nome','azioni'])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($page) {
                                    return "<a href=\"/admin/pages/removeChild/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
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

            <div class="box">
                <div class="box-header">
                    <i class="fa fa-code-fork"></i>
                    <h3 class="box-title">{{$titleGraph}}</h3>
                </div>
                <div class="tree">
                    @include('ui.treeview',['nodes' => $graphPage])
                </div>
            </div> <!-- /.box -->

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop
