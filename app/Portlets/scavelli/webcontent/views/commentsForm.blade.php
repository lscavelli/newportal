
<div class="row" style="margin-bottom: 20px">
    <div class="col-lg-12">
        <h2>Comments</h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        @include('ui.messages')
        <form name="commentForm" id="commentForm" method="post" novalidate action="{{ $action }}#comments">
            {{ csrf_field() }}
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" placeholder="Titolo non obbligatorio" name="name" id="name">
                </div>
            </div>
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label for="author">Autore</label>
                    <input type="text" class="form-control" placeholder="Autore" name="author" id="author">
                </div>
            </div>
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                </div>
            </div>
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label for="message">Commento</label>
                    <textarea rows="5" class="form-control" placeholder="Contenuto commento" name="message" id="message"></textarea>
                </div>
            </div>
            <br>
            <div id="success"></div>
            <div class="row">
                <div class="form-group col-xs-12">
                    <button type="submit" class="btn btn-primary btn-lg" name="sendComment" value="1">Invia</button>
                </div>
            </div>
        </form>
    </div>
</div>
