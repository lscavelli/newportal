{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Vocabolari','/admin/vocabularies/')->add('Categorie','/admin/categories/'.$vocabulary->id)
        ->add('Profilo')
        ->setTcrumb($category->name. " - Vocabolario: ".$vocabulary->name)
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
                    'subTitle' =>(($subcat = $category->parent()->pluck('name')->first()) ? 'Sottocategoria di: '.$subcat : null),
                    'listMenu'=>[
                        'Creato il'=>Carbon\Carbon::parse($category->created_at)->format('d/m/Y'),
                        'Modificato il'=>Carbon\Carbon::parse($category->updated_at)->format('d/m/Y')
                    ],
                    'urlEdit'=>url(Request::getBasePath().'/admin/categories/edit', $category->id)
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'color'=> 'green',
                    'title'=>$category->id." - ".$category->name,
                    'listMenu'=>[
                        'Vocabolari'=>url('/admin/vocabularies'),
                        'Lista categorie'=>url('/admin/categories/'.$vocabulary->id),
                        'divider'=>"divider",
                        'Modifica'=>url('/admin/categories/edit',$category->id),
                        'Assegna sottocategorie'=>url('/admin/categories/assignSubcat',$category->id)
                    ],
                    'urlNavPre'=>url('/admin/categories/profile',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/categories/profile',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#Subcats" data-toggle="tab" aria-expanded="true">Sottocategoria presenti @if(isset($listSubcat))<span class="label label-success">{{$listSubcat->count()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="Subcats">
                        @if(isset($listSubcat))
                            {!!
                                $listSubcat->columns(['id','name'=>'Nome','azioni'])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($category) {
                                    return "<a href=\"/admin/categories/removeSubcat/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">Cancella</a>";
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
                    @include('ui.treeview',['nodes' => $graphorg])
                </div>
            </div> <!-- /.box -->

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop
