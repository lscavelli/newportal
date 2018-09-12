<section class="content-header">
    @if($list->showTitle())
        <h1>
            {{ $list->tcrumb['title'] }}
            @if(isset($list->tcrumb['desc']))<small> {{$list->tcrumb['desc']}} </small>@endif
        </h1>
    @endif
    <ol class="breadcrumb">
        <li>
            <a href="{{$list->getUrlHome()}}"><i class="fa fa-dashboard"></i> Home</a>
        </li>
        <?php $i=1;?>

        @foreach($list->getCrumbs() as $name => $href)
            <li
                @if(!empty($href))
                    >
                    <a href="{{$href}}">{{$name}}</a>
                @else
                    class="active" >
                    {{$name}}
                @endif
            </li>
            <?php $i++; ?>
        @endforeach
    </ol>
</section>