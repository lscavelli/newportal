<div class="widget-chooser">
    <div class="widget-chooser-inner">
        <a href="#" class="toggler"><i class="fa fa-cog fa-spin"></i></a>
        <h4>Utilizza le widgets</h4>
        <input type="text" id="key" onkeyup="searchWidget($(this).val())" placeholder="cerca per nome...">
        <div id="listOfFields"></div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="preferencesModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Widget preferences</h4>
            </div>
            <div class="modal-body">
                <!-- Main content -->
                <div class="row">
                    <div class="col-md-12">
                        <ul role="tablist" class="nav nav-tabs flex-column flex-sm-row">
                            <li class="nav-item active"><a data-toggle="tab" href="#tabpreferences" role="tab" class="nav-link active">Web Content</a></li>
                            <li class="nav-item"><a data-toggle="tab" href="#tabcssjs" role="tab" class="nav-link">Applica Style/js</a></li>
                            <li class="nav-item"><a data-toggle="tab" href="#tabnote" role="tab" class="nav-link">Note</a></li>
                            <li class="nav-item"><a data-toggle="tab" href="#tabother" role="tab" class="nav-link">Altre impostazioni</a></li>
                        </ul>

                        <div class="tab-content" style="height: auto">
                            <div class="tab-pane active" id="tabpreferences" role="tabpanel">
                                <iframe src="" name="prefIframe" id="prefIframe"><p>Il tuo browser non supporta iframe</p></iframe>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tabcssjs" role="tabpanel">
                                <div class="box-body"><br />
                                    <form action="" method="POST" name="cssjsForm" id="cssjsForm">
                                        <div class="form-group">
                                            <label for="css" class="col-sm-2 form-label">Style</label>
                                            <div class="col-sm-10">
                                                <textarea name="css" id="css" rows="8" cols="60" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="js" class="col-sm-2 form-label">Javascript</label>
                                            <div class="col-sm-10">
                                                <textarea name="js" id="js" rows="8" cols="60" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tabnote" role="tabpanel">
                                <div class="box-body"><br />
                                    <form action="" method="POST" name="noteForm" id="noteForm">
                                        <div class="form-group">
                                            <label for="note" class="col-sm-2 form-label">Note</label>
                                            <div class="col-sm-10">
                                                <textarea name="note" id="note" rows="8" cols="60" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tabother" role="tabpanel">
                                <div class="box-body"><br />
                                    <form action="" method="POST" name="otherForm" class="form-horizontal" id="otherForm">
                                        <div class="form-group">
                                            <label for="title" class="col-sm-2 form-label">Titolo</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="title" id="title" class="form-control" placeholder="Ridefinizione titolo" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="position" class="col-sm-2 form-label">Posizione</label>
                                            <div class="col-sm-10">
                                                <select name="position" class="form-control input-sm" id="position"></select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="comunication" class="col-sm-2 form-label">Comunicazione</label>
                                            <div class="col-sm-10">
                                                <select name="comunication" class="form-control input-sm" id="comunication">
                                                    <option value="0">Non attiva</option>
                                                    <option value="1">Attiva</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="template" class="col-sm-2 form-label">Template</label>
                                            <div class="col-sm-10">
                                                <select name="template" class="form-control input-sm" id="template"></select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <input name="pivot_id" id="pivot_id" type="text" hidden="hidden" value="">
                                <input name="page_id" id="page_id" type="text" hidden="hidden" value="">
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <div class="modal-footer">
                <button id="submitPreferences" type="submit" class="btn btn-success"><span class="fa fa-check"></span> Salva</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('style')
        <!-- jQuery ui -->
<link href="{{ asset("/node_modules/jquery-ui-dist/jquery-ui.min.css") }}" rel="stylesheet">
<link href="{{ asset("css/dad.widgets.css") }}" rel="stylesheet">
@endpush

@push('scripts')
        <!-- jQuery ui -->
<script src="{{ asset("/node_modules/jquery-ui-dist/jquery-ui.min.js") }}"></script>
<script src="{{ asset("js/dad.widgets.js") }}"></script>
@endpush
