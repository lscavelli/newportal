
<div id="carousel-generic-{{ $list->get('id') }}" class="carousel slide" data-ride="carousel">

    <!-- Indicators -->
    <ol class="carousel-indicators">
        @foreach( $items as $item )
            <li data-target="#carousel-generic-{{ $list->get('id') }}" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
        @endforeach
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        @foreach( $items as $item )
            <div class="item {{ $loop->first ? ' active' : '' }}" >
                {!! $list->getItem($item) !!}
            </div>
        @endforeach
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-generic-{{ $list->get('id') }}" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Precedente</span>
    </a>
    <a class="right carousel-control" href="#carousel-generic-{{ $list->get('id') }}" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Successivo</span>
    </a>

</div>



{{ $list->theme->addJs('$(document).ready(function() {$(\'.carousel\').carousel({interval: 4000})});') }}