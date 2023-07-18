<div class="modal  fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header text-center modal_headerBg_color">
                <h5 class="modal-title text-white">{{@$data['title']}} </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-5">
                    <div class="col-md-12">
                        <input type="hidden" id="form_url" value="{{ @$data['url'] }}">
                            <div class="form-group">
                                <label class="form-label">{{ _trans('common.Subject') }}</label>
                                <input type="text" class="form-control" min="0" step=any name="subject" id="subject" autocomplete="off" placeholder="{{_trans('common.Subject')}}" required onkeyup="discussionValidate()"/>
                                <div class="error_show_subject"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ _trans('common.Description') }} <span class="text-danger">*</span></label>
                                <textarea type="text" name="description" id="description" rows="5" class="form-control" placeholder="{{_trans('common.Description')}}" required onkeyup="discussionValidate()">{{  old('description') }}</textarea>
                                <div class="error_show_description"></div>
                            </div>
                            <div class="form-group d-none">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" name="show_to_customer" value="1" checked="" id="show_to_customer" onclick="discussionValidate()">
                                        <label for="show_to_customer">{{ _trans('project.Visible to Customer') }}</label>
                                    </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-primary pull-right" onclick="submit_discussion()" >{{ _trans('common.Submit')}}</button>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="error_subject" value="{{  _trans('message.Subject is required')  }}">
<input type="hidden" id="error_description" value="{{  _trans('message.Description is required')  }}">


