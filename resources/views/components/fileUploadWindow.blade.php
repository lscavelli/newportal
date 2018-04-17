<div class="modal fade" id="selectFileModal" tabindex="-1" role="dialog" aria-labelledby="selectFileFormLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Seleziona File</h4>
            </div>
            <form id="fileForm" enctype="multipart/form-data" action="" method="post">
                @csrf
                <div class="modal-body">
                    <p class="text_img">Seleziona localmente il tuo file</p>
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-primary">
                        Browse&hellip;<input type="file" style="display: none;" name="fileUploaded">
                                </span>
                            </label>
                            <input type="text" class="form-control filename">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Carica</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-remove"></span> Annulla</button>
                </div>
            </form>
        </div>
    </div>
</div>