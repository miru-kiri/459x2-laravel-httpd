<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">会員QR</h5>
        </div>
        @if(session('user_id'))
        <div class="modal-body text-center">
          {!! QrCode::size(300)->generate(session('user_id')) !!}
        </div>
        @endif
      </div>
    </div>
</div>