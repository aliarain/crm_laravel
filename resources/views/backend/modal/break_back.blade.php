<div class="modal fade lead-modal" id="lead-modal"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content data">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title text-white">{{ @$data['title'] }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body ">
                <div class="row pb-4 text-align-center">
                    <div class="col-md-12 pt-40 pb-40 pl-20">

                        @php
                            $date1 = new DateTime($data->break_time);
                            $date2 = new DateTime($data->back_time);
                            $difference = $date1->diff($date2);
                        @endphp
                        <p>
                            <strong>{{ _trans('common.You have taken ') }}</strong> :
                            {{ $difference->format('%h hours %i minutes %s seconds') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('public/backend/js/fs_d_ecma/modal/__modal.min.js') }}"></script>
