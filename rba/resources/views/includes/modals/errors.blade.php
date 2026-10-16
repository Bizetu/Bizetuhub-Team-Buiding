@php
    $flashMessage = session()->get('site_errors');
@endphp

<div class="modal fade" id="ModalErrors" tabindex="-1" role="dialog" aria-labelledby="ModalErrors" aria-hidden="true">

  <div class="modal-dialog notification-dialog  modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">

          <div class="row">
            <div class="col-12">
              <div class="noti-header"><h1 class="text-center  noti-error-header" > @if(isset($flashMessage['title'])) {{ $flashMessage['title'] }} @endif </h1></div>

              <div class="col light">
                <div class="col-12 noti-text noti-error text-center text-xl" >
                    @if(isset($flashMessage['content']))
                    {!! $flashMessage['content'] !!}
                   @endif
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 noti-content" >
                  <button type="button" class="btn btn-modal"  data-dismiss="modal"  onclick="dismissErrorModal()" >Dismiss</button>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
