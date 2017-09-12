<div class="modal fade" id="treeviewDialog" tabindex="-1" role="dialog" aria-labelledby="treeviewDialogLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Seleziona categoria</h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <div class="treeviewsl" tabindex="0">
                    <div id="tree-container"></div>
                </div>
            </div>
            <div class="modal-footer">
                <form id="confirmForm" action="" method="post">
                    {!! csrf_field() !!}
                    <button type="button" class="btn btn-danger dialogSel" data-vid=""><span class="fa fa-check"></span> Seleziona</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-remove"></span> Annulla</button>
                </form>
            </div>
        </div>
    </div>
</div>