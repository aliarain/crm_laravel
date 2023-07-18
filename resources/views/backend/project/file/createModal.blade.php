<div class="modal  fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header modal-header-image text-center">
                <h5 class="modal-title text-white">{{ @$data['title'] }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-0">
                    <div class="col-md-12">
                        <form action="{{ $data['url'] }}" enctype="multipart/form-data" method="POST"
                            id="modal_values">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">{{ _trans('common.Subject') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control ot-form-control ot_input"
                                    id="subject" placeholder="{{ _trans('common.Subject') }}" required>
                                <div class="error_show_subject"></div>
                            </div>
                            <div class="form-group pt-3">
                                <label class="form-label">{{ _trans('common.Attach File') }} <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="attach_file" id="attach_file"
                                    class="form-control file_note ot-form-control ot_input"
                                    placeholder="{{ _trans('common.Choose File') }}" required>
                                <div class="error_show_attach_file"></div>
                            </div>
                            <div class="form-group text-right pt-3 d-flex justify-content-end">
                                <button type="submit"
                                    class="crm_theme_btn pull-right modal_btn">{{ @$data['button'] }}</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
