
@php
    $flashMessage = session()->get('site_success');
@endphp

<div class="modal fade" id="ModalMessage" tabindex="-1" role="dialog" aria-labelledby="ModalMessage" aria-hidden="true">

  <div class="modal-dialog notification-dialog  modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">

          <div class="row">
            <div class="col-12">

              <div class="noti-header"><h1 class="text-center  noti-success-header" > @if(isset($flashMessage['title'])) {{ $flashMessage['title'] }} @endif </h1></div>

              <div class="col light">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 noti-text noti-success text-center" >
                    @if(isset($flashMessage['content']))
                     {!! $flashMessage['content'] !!}
                    @endif
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 noti-content" >
                  @if(isset($flashMessage['action']))
                  <a href="{{ $flashMessage['link'] }}" class="btn btn-action">
                      {{ $flashMessage['action'] }}
                  </a>
                  @else
                  <button type="button" class="btn btn-modal"  data-dismiss="modal"  onclick="dismissModal()" >Dismiss</button>
                  @endif
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
