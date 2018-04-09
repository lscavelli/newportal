{!!
    $navigation->add([
        'Servizi'=>['icon'=>'fa-file-text-o','url'=>url('/admin/pages/'.$portlet->pivot->page_id.'/configPortlet',$portlet->pivot->id)],
        'Categorizzazione'=>['icon'=>'fa-tags','url'=>url('/admin/content/categorization',2)],
        'test'=>['icon'=>'fa-calendar','url'=>'#'],
    ])->render("ui.navigation_content")
!!}