<div class="msg leftt-msg">
    <div class="msg-img">
        <img src="{{ asset($license->user->profile_pic_avatar) }}" class="img-fluid profile-avatar " />
    </div>
    <div class="msg-bubble">
        <div class="msg-info">
            <div class="msg-info-name">{{$activity->user->name}}</div>
            <div class="msg-info-time">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</div>
        </div>
        <div class="msg-text">
            {{ $activity->message }}
        </div>
    </div>
</div>