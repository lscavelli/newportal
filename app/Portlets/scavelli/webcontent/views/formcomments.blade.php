
<div class="row" style="margin-bottom: 20px">
    <div class="col-lg-12">
        <h2>Comments</h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        @include('ui.messages')
        <form name="commentForm" id="commentForm" method="post" novalidate action="{{ $action }}">
            <input type="hidden" value="{{ $post_id }}" name="post_id">
            <input type="hidden" value="{{ $service }}" name="service">
            {{ csrf_field() }}
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" placeholder="Title" name="name" id="name">
                </div>
            </div>
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label for="author">Autore</label>
                    <input type="text" class="form-control" placeholder="Your name" name="author" id="author">
                </div>
            </div>
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" placeholder="Email address." name="email" id="email">
                </div>
            </div>
            <div class="row control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label for="content">Commento</label>
                    <textarea rows="5" class="form-control" placeholder="Comment" name="content" id="content"></textarea>
                </div>
            </div>
            <br>
            <div id="success"></div>
            <div class="row">
                <div class="form-group col-xs-12">
                    <button type="submit" class="btn btn-primary btn-lg">Invia</button>
                </div>
            </div>
        </form>
    </div>
</div>
