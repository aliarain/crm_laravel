<div class="modal  fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title text-white">{{@$data['title']}} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-0">
                    <div class="col-md-12">
                            <div class="col-md-12 form-group mb-3">
                                <label class="form-label">{{ _trans('common.Department') }}</label>
                                <select id="department" class="form-select mb-3 modal_select2">
                                    <option value="">{{ _trans('common.Department') }}</option>
                                    <option value="0">{{ _trans('common.All') }}</option>
                                    @foreach ($data['departments'] as $department)
                                        <option value="{{ $department->id }}">{{ $department->title }}</option>
                                    @endforeach
                                </select>
                                <div class="error_show_department"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{ _trans('common.Date') }}</label>
                                <input type="date" class="form-control ot-form-control ot_input" id="month" />
                                <div class="error_show_month"></div>
                                
                            </div>
                            <div class="form-group text-right mt-3 d-flex justify-content-end">
                                <button type="button" onclick="makeGenerate()" class="crm_theme_btn pull-right"><b>{{ @$data['button'] }}</b></button>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="select_department" value="{{  _trans('message.Select an department')  }}">
<input type="hidden" id="error_department" value="{{  _trans('message.Please select a department')  }}">
<input type="hidden" id="error_month" value="{{  _trans('message.Please select a month')  }}">
<input type="hidden" id="__generate" value="{{  @$data['url'] }}">
<script src="{{ asset('public/backend/js/payroll/__salary_generate.js') }}"></script>

