<a name="listcomments"></a>
<div class="row" style="margin-bottom: 20px">
    <div class="col-lg-12">
        <h2>Comments</h2>
    </div>
</div>
<section class="comments">
@foreach($comments as $comment)
    <article class="comment">
        <a class="comment-img" href="mailto:{{$comment->email}}">
            <img src="@if(!is_null($comment->user_id)) {{ $comment->autore->getAvatar() }} @else {{ url('img/avatar.png') }} @endif" alt="" width="50" height="50">
        </a>
        <div class="comment-body">
            <div class="text">
                <p>{{$comment->content}}</p>
            </div>
            <p class="attribution">by <a href="mailto:@if(!is_null($comment->user_id)) {{ $comment->autore->email }} ">{{ $comment->autore->name }} @else {{$comment->email}} ">{{$comment->author}} @endif </a> {{ Carbon\Carbon::parse($comment->created_at)->format('d-m-Y H:i') }}</p>
        </div>
    </article>
@endforeach
</section>
<div class="col-sm-7">
    <div class='pull-right' Style="margin-top: -20px;">
        {{
            $comments->fragment('listcomments')->links()
        }}
    </div>
</div>