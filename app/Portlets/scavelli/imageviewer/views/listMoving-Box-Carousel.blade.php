<div class="carouselred">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div class="page-header">
                    <h3>Bootstrap 3</h3>
                    <p>Responsive Moving Box Carousel Demo</p>
                </div>
                <div id="myCarousel" class="row carousel slide" data-ride="carousel">

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">

                        <div class="item active">
                            <ul class="thumbnails">
                                @foreach($items as $item)
                                    {!! $list->getItem($item) !!}
                                @endforeach
                            </ul>
                        </div><!-- /Slide3 -->
                        <div class="item">
                            <ul class="thumbnails">

                                    {!! $list->getItem($item) !!}
                                    {!! $list->getItem($item) !!}

                            </ul>
                        </div><!-- /Slide3 -->

                    </div><!-- /Wrapper for slides .carousel-inner -->


                    <!-- Control box -->
                    <div class="control-box">
                        <a data-slide="prev" href="#myCarousel" class="carousel-control left">‹</a>
                        <a data-slide="next" href="#myCarousel" class="carousel-control right">›</a>
                    </div><!-- /.control-box -->


                </div><!-- /#myCarousel -->

            </div><!-- /.col-sm-12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div>

{{ $list->theme->addExCss($list->getPath().'css/moving-box-carousel.css') }}
{{ $list->theme->addJs('$(document).ready(function() {$(\'.carousel\').carousel({interval: 6000})});') }}