<h3 class="control-sidebar-heading">Same page public</h3>
<ul class="control-sidebar-menu">
    <li>
        <a href="#" onclick="window.open( '{{ url('/') }}' ,'_blank').focus();">
            <i class="menu-icon fa fa-external-link bg-red"></i>
            <div class="menu-info">
                <h4 class="control-sidebar-subheading">Welcome page</h4>
                <p>DEFAULT</p>
            </div>
        </a>
    </li>
@foreach($cspages as $page)
    <li>
        <a href="#" onclick="window.open( '{{ url($page->slug) }}' ,'_blank').focus();">
            <i class="menu-icon fa fa-external-link bg-yellow"></i>
            <div class="menu-info">
                <h4 class="control-sidebar-subheading">{{ $page->name }}</h4>
                <p>{{ Carbon\Carbon::parse($page->created_at)->format('d-m-Y @i:h')  }}</p>
            </div>
        </a>
    </li>
@endforeach
</ul>
<!-- /.control-sidebar-menu -->