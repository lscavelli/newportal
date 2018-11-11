<div class="carouselred">
    <div class="row">
        <div class="col-sm-12">

            <div class="page-header">
                <h3>{{ $list->get('title') }}</h3>
                <p>Responsive Moving Box Carousel Demo</p>
            </div>


            <div id="carousel-generic-{{ $list->get('id') }}" class="carousel slide"  data-ride="carousel">

                <!-- Indicators -->
                <ol class="carousel-indicators">
                    @foreach( $items->chunk($list->get('perPage')) as $item )
                        <li data-target="#carousel-generic-{{ $list->get('id') }}" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                    @endforeach
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">

                    @foreach($items->chunk($list->get('perPage')) as $count => $item)

                        <div class="item {{ $count == 0 ? 'active' : '' }}">
                            @foreach($item as $event)
                                <div class="col-sm-{{ $list->get('columns') }}" style="padding: 3px"><div class="thumbnail">{!! $list->getItem($event) !!}</div></div>
                            @endforeach
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

        </div><!-- /.col-sm-12 -->
    </div><!-- /.row -->
</div><!-- /.carouselred -->

{{ $list->theme->addExCss($list->getPath().'css/moving-box-carousel.css') }}
{{ $list->theme->addJs('$(document).ready(function() {$(\'.carousel\').carousel({interval: 6000})});') }}
