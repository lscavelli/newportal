@if(isset($items[0]))
    {!! $list->getItem($items[0]) !!}
@endif

{{ $list->theme->addExCss('/node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css') }}
{{ $list->theme->addExJs('/node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.js') }}