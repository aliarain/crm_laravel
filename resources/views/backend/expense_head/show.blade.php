<div class="modal modal-blur fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_title">{{_trans('comon.View Event') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" />
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" /></svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body d-flex flex-column">

          <div class="row">
            <div class="col-lg-12">
              <h3 class="text-left">{{ $data['show']->name }}</h3>
            </div>
            <div class="col-lg-12">
              <p>{{ $data['show']->description }}</p>
              <small class="text-italic float-right">{{ main_date_format(@$data['show']->created_at) }}</small>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ _trans('common.Cancel') }}</button>
      </div>
    </div>
  </div>
</div>