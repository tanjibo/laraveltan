<div class="media">
    <div class="avatar pull-left">
        <a href="#">
            <img class="media-object img-thumbnail" alt="{{ $notification->data['user_name'] }}" src="{{ $notification->data['user_avatar'] }}"  style="width:48px;height:48px;"/>
        </a>
    </div>

    <div class="infos">
        <div class="media-heading">
            <a href="#">{{ $notification->data['user_name'] }}</a>
            在
            <a href="{{ $notification->data['art_show_url'] }}">【{{ $notification->data['art_show'] }}】</a>
            赞了你👍：
            {{-- 回复删除按钮 --}}
            <span class="meta pull-right" title="{{ $notification->created_at }}">
                <span class="glyphicon glyphicon-clock" aria-hidden="true"></span>
                {{ $notification->created_at->diffForHumans() }}
            </span>
        </div>
        <div class="reply-content">
            {!! $notification->data['comment'] !!}
        </div>
    </div>
</div>
<hr>