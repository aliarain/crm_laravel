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
                        <input type="hidden" id="form_url" value="{{ @$data['url'] }}">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Subject') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control ot-form-control ot_input" min="0" step=any name="subject" id="subject" autocomplete="off" placeholder="{{ _trans('common.Subject') }}" required onkeyup="discussionValidate()"/>
                                <div class="error_show_subject"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Description') }} <span class="text-danger">*</span></label>
                                <textarea type="text" name="description" id="description" rows="5" class="form-control ot_input summernote mt-0" placeholder="{{ _trans('common.Description') }}" required onkeyup="discussionValidate()">{{  old('description') }}</textarea>
                                <div class="error_show_description"></div>
                            </div>
                            <div class="form-group d-none">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" name="show_to_customer" value="1" checked="" id="show_to_customer" onclick="discussionValidate()">
                                        <label for="show_to_customer">{{ _trans('project.Visible to Customer') }}</label>
                                    </div>
                            </div>
                            <div class="form-group text-right d-flex justify-content-end">
                                <button type="button" class="crm_theme_btn pull-right" onclick="submit_discussion()" >{{ _trans('common.Submit')}}</button>
                            </div>

                    </div>
                </div>
                  
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="error_subject" value="{{  _trans('message.Subject is required')  }}">
<input type="hidden" id="error_description" value="{{  _trans('message.Description is required')  }}">

<script>
    $('.summernote').summernote()
</script>


