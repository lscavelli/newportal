{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Pagine','/admin/pages')->add('Aggiorna pagine')
        ->setTcrumb($page->name)
        ->render() !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#editpages" data-toggle="tab" aria-expanded="true">Pagine</a></li>
                    <li><a href="#othersettings" data-toggle="tab">Altri dati</a></li>
                    @isset($page->id)<li><a href="#tags" data-toggle="tab">Tags</a></li>@endisset
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="editpages">
                        {!! Form::model($page, ['url'=>url('admin/pages',$page->id),'class' => 'form-horizontal']) !!}
                            @if(isset($page->id))@method('PUT')@endif

                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slText('slug','Slug',null,['placeholder'=> __("Generato automaticamente se lasciato vuoto")]) !!}
                            {!! Form::slCheckbox('hidden_','Nascondi questa pagina nella navigazione', 1) !!}
                            {!! Form::slSelect('status_id','Stato',config('newportal.status_general')) !!}
                            {!! Form::slSelect('parent_id','Figlia di',$optionsSel) !!}
                            {!! Form::slSelect('theme','Tema',$listThemes) !!}
                            {!! Form::slSubmit('Salva') !!}

                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="othersettings">
                        {!! Form::model($page, ['url'=>url('admin/pages',$page->id),'class' => 'form-horizontal']) !!}
                            @if(isset($page->id))@method('PUT')@endif

                            {!! Form::slSelect('type_id','Tipo pagina',config('newportal.type_page')) !!}
                            <div class="urlpage">{!! Form::slText('url','Url',null,['placeholder'=> __("Url esterno o a pagina e documento interno")]) !!}</div>
                            {!! Form::slTextarea('javascript','Javascript',null,['rows'=>5]) !!}
                            {!! Form::slSeparator('Ottimizzazione per i motori di ricerca (SEO)') !!}
                            {!! Form::slSelect('sitemap','SiteMap',['No','Si']) !!}
                            {!! Form::slTextarea('description','Descrizione',null,['rows'=>5]) !!}
                            {!! Form::slTextarea('keywords','Parole chiave',null,['rows'=>5]) !!}
                            {!! Form::slTextarea('robots','Robots',null,['rows'=>5]) !!}
                            {!! Form::slSubmit('Salva',['value'=>1,'name'=>"sButtonOther"]) !!}

                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                    @isset($page->id)
                        <!-- tab-pane -->
                        <div class="tab-pane" id="tags">

                            {!! Form::model($page, ['url' => url('admin/pages',$page->id),'class' => 'form-horizontal']) !!}
                                @method('PUT')
                                {!! Form::slText('name','Titolo',null,['disabled'=>'']) !!}
                                {!! Form::slTags($tags,$page) !!}
                                {!! Form::slSubmit('Salva',['name'=>'saveTags']) !!}
                            {!! Form::close() !!}

                        </div>
                        <!-- /.tab-pane -->
                    @endisset
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

@push('scripts')
<script>
    //$(document).ready(function() {
        showUrl();

        $('#type_id').on('change', function(e){
            e.preventDefault();
            showUrl();
        });

        function showUrl() {
            $type = $( "#type_id" ).val();
            if ($type==1) {
                $('.urlpage').show();
            } else {
                $('.urlpage').hide();
            }
        }
    //});
</script>
@endpush
