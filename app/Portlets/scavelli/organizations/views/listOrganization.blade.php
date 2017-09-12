<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{$title}}</h3>
    </div>
    <div class="tree">
        @include('ui.treeview',['nodes' => $organizations])
    </div>
</div> <!-- /.box -->
