<a name="listcomments"></a>
<section class="comments">
@foreach($comments as $comment)
    <article class="comment">
        <a class="comment-img" href="mailto:{{$comment->email}}">
            <img src="/img/avatar.png" alt="" width="50" height="50">
        </a>
        <div class="comment-body">
            <div class="text">
                <p>{{$comment->content}}</p>
            </div>
            <p class="attribution">by <a href="mailto:{{$comment->email}}">{{$comment->author}}</a> {{$comment->created_at}}</p>
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