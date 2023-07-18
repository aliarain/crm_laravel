<div class="modal  fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header modal-header-image text-center">
                <h5 class="modal-title text-white">{{@$data['title']}} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-0">
                    <div class="col-md-12">
                        <form action="{{ $data['url'] }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Request Amount') }}</label>
                                <input type="number" name="request_amount" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['approve'] ? $data['approve']->request_amount : old('amount') }}" disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Approve Amount') }} <span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['approve']->amount ? $data['approve']->amount : $data['approve']->request_amount }}" required >
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-control modal_select2" required>
                                    <option {{ @$data['approve']->status_id == 2 ? 'selected' : ''}} value="2"> {{ _trans('common.Pending') }} </option>
                                    <option {{ @$data['approve']->status_id == 5 ? 'selected' : ''}} value="5"> {{ _trans('common.Approved') }} </option>
                                    <option {{ @$data['approve']->status_id == 6 ? 'selected' : ''}} value="6"> {{ _trans('common.Rejected') }} </option>
                                </select> 
                                
                            </div>
                            <div class="form-group text-right d-flex justify-content-end">
                                <button type="submit" class="crm_theme_btn pull-right"><b>{{ @$data['button'] }}</b></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script  src="{{ asset('public/backend/js/fs_d_ecma/modal/__modal.min.js') }}"></script>
