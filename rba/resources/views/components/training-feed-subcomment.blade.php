@if($reply->user_id)
    <x-others-comments :activity="$reply"/>
@endif