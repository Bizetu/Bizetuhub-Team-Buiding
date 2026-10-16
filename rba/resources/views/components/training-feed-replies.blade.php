@unless(empty($activity))
    @if ((new \Jenssegers\Agent\Agent())->isDesktop())
        <div class="card-body d-flex flex-row pt-0 feed-response" id="response-{{ $activity->id }}">
            <div class="col-12">
                <div class="row">
                    <section class="msger">
                        <div id="chat-messages" class="msger-chat">
                            @if($activity->user_id)
                                <x-my-comments :activity="$activity"/>
                            @endif
                        </div>
                    </section>

                    <div class="msg-bubbler" data-activity-id="{{ $activity->id }}">
                        <p class="msg-text" class="comment-count" style="text-align: center;">
                            @if($license->getSubCommentCount($activity->id, $license->course->id, $activity->id) > 1)
                                {{ $license->getSubCommentCount($activity->id, $license->course->id, $activity->id) }} Replies
                            @elseif( $license->getSubCommentCount($activity->id, $license->course->id, $activity->id) == 1)
                                {{ $license->getSubCommentCount($activity->id, $license->course->id, $activity->id) }} Reply                            
                            @else
                                Reply
                            @endif
                        </p>
                    </div>
                      
                    <div class="col-10 col-sm-11 comment-response" data-activity-id="{{ $activity->id }}" style="display: none !important;">
                        <small class="float-left">
                            <div class="comment-click" onclick="showHide('response-reply-{{ $activity->id }}');" style="display: none;">
                                <h5 class="innerstats"><i class="progress-icon icon-comment training-icon" title="clock"></i><span>Comments</span></h5>
                                <p>{{ $license->getSubCommentCount($activity->id, $license->course->id, $activity->id) }} Replies</p>
                            </div>
                        </small>
                        
                        @foreach ($license->getSubComment($activity->id, $license->course->id, $activity->id) as $reply)
                            <x-training-feed-subcomment :activity="$reply", :license="$license"/>
                        @endforeach

                        <form action="{{ route('profile.myComment') }}" method="POST" class="msger-inputarea">
                            @csrf
                            <input type='hidden' name="follow" value="{{ $license->id }}" />
                            <input type='hidden' name="course" value="{{ $license->course->id }}" />
                            <input type='hidden' name="unique" value="{{ $activity->id }}" />

                            <div class="reply-reply"id="response-reply-{{ $activity->id }}" style="display:none ">
                                <div class="container-fluid mt-3" id="response-reply-{{ $activity->id }}" style="display: block;">
                                    <div class="main  d-flex">
                                        <div class="card " style="border: 0px;width:100%">
                                            <div class="row no-gutters">
                                                <div class="col-2 col-md-1">
                                                    <img src="{{ asset($license->user->profile_pic_avatar) }}" class="img-fluid profile-avatar " />
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body card-small pb-0">
                                                        <div class="card-text">
                                                            <input type="text" name="id" value="5" hidden="" id="replycomment_id">
                                                            <textarea id="replycomment_reply" class="form-control text-input" name="comment" placeholder="Reply" data-emojiable="true" data-emoji-input="unicode"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2 ">
                                                    <div class="submitbuttons">
                                                        <button type="submit" class="msger-send-btn">Reply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card-body d-flex flex-row pt-0 feed-response" id="response-{{ $activity->id }}">
            <div class="col-12">
                <div class="row">
                    <section class="msger">
                    
                        <div id="chat-messages" class="msger-chat">  
                            @if($activity->user_id)
                                <x-my-comments :activity="$activity"/>
                            @endif
                        </div>

                    </section>
                    <div class="msg-bubbler" data-activity-id="{{ $activity->id }}">
                        <p class="msg-text" class="comment-count" style="text-align: center;">
                            @if($license->getSubCommentCount($activity->id, $license->course->id, $activity->id) > 1)
                                {{ $license->getSubCommentCount($activity->id, $license->course->id, $activity->id) }} Replies
                            @elseif( $license->getSubCommentCount($activity->id, $license->course->id, $activity->id) == 1)
                                {{ $license->getSubCommentCount($activity->id, $license->course->id, $activity->id) }} Reply                            
                            @else
                                Reply
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-10 col-sm-11 comment-response" data-activity-id="{{ $activity->id }}" style="display: none !important;">
                        <small class="float-left">
                            <div class="comment-click" onclick="showHide('response-reply-{{ $activity->id }}');" style="display: none;">
                                <h5 class="innerstats"><i class="progress-icon icon-comment training-icon" title="clock"></i><span>Comments</span></h5>
                                <p>{{ $license->getSubCommentCount($activity->id, $license->course->id, $activity->id) }} Replies</p>
                            </div>
                        </small>
                        
                        @foreach ($license->getSubComment($activity->id, $license->course->id, $activity->id) as $reply)
                            <x-training-feed-subcomment :activity="$reply", :license="$license"/>
                        @endforeach

                        <form action="{{ route('profile.myComment') }}" method="POST" class="msger-inputarea">
                            @csrf
                            <input type='hidden' name="follow" value="{{ $license->id }}" />
                            <input type='hidden' name="course" value="{{ $license->course->id }}" />
                            <input type='hidden' name="unique" value="{{ $activity->id }}" />

                            <div class="reply-reply"id="response-reply-{{ $activity->id }}" style="display:none ">
                                <div class="container-fluid mt-3" id="response-reply-{{ $activity->id }}" style="display: block;">
                                    <div class="main  d-flex">
                                        <div class="card " style="border: 0px;width:100%">
                                            <div class="row no-gutters">
                                                <div class="col-2 col-md-1">
                                                    <img src="{{ asset($license->user->profile_pic_avatar) }}" class="img-fluid profile-avatar" />
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body card-small pb-0">
                                                        <div class="card-text">
                                                            <input type="text" name="id" value="5" hidden="" id="replycomment_id">
                                                            <textarea id="replycomment_reply" class="form-control text-input" name="comment" placeholder="Reply" data-emojiable="true" data-emoji-input="unicode"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2 ">
                                                    <div class="submitbuttons">
                                                        <button type="submit" class="msger-send-btn">Reply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endunless
