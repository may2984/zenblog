@props(['comment'])
<div class="comment-meta d-flex align-items-baseline">
    <h6 class="me-2">{{ $comment->name }}</h6>
    <span class="text-muted">{{ Carbon\Carbon::now()->shortAbsoluteDiffForHumans($comment->created_at) }}</span>
    <span class="text-muted"><a class="reply-to-comments" id="{{ $comment->id }}" href="#reply">&nbsp;Reply</a></span>
</div>
<div class="comment-body">{{ $comment->message }}</div>