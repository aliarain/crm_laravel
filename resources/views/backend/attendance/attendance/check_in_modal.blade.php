<div class="modal fade lead-modal" id="lead-modal"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content data">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title text-white">{{ @$data['title'] }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row pb-4 text-align-center">
                    <div class="col-md-12">
                        <div class="form-group">
                            <p id="interim"></p>
                            <p id="final"></p>
                            <p class="text-center mb-0">
                                {{ _trans('common.Choose your option') }}
                            </p>
                            <div class="place-switch">
                                <div class="switch-field">

                                    <input type="radio" id="place_office" name="place_mode" value="1"
                                        checked="">
                                    <label for="place_office">
                                        <i class="fas fa-building"></i>
                                        <p class="on-half-expanded">{{ _trans('common.Office') }}</p>
                                    </label>

                                    <input type="radio" id="place_home" name="place_mode" value="0">
                                    <label for="place_home">
                                        <i class="fas fa-home"></i>
                                        <p class="on-half-expanded">{{ _trans('common.Home') }}</p>
                                    </label>

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="timer-field pt-2 pb-2">
                                <h1 class="text-center">
                                    <div class="clock company_name_clock fs-16 clock" id="clock"
                                        onload="currentTime()">{{ _trans('common.00:00:00') }}</div>
                            </div>
                        </div>
                        @if (@$data['reason'][0] == 'L')
                            <div class="form-group w-100 mx-auto mb-3">
                                <label class="form-label float-left">{{ _trans('common.Late Reason') }} <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" name="reason" id="reason" rows="3" class="form-control mt-0 ot_input" required
                                    onkeyup="textAreaValidate(this.value, 'error_show_reason')">{{ old('reason') }}</textarea>
                                <small class="error_show_reason text-left text-danger">

                                </small>
                            </div>
                        @endif

                        <div class="form-group button-hold-container">
                            <button class="button-hold" id="button-hold">
                                <div>
                                    <svg class="progress" viewBox="0 0 32 32">
                                        <circle r="8" cx="16" cy="16" />
                                    </svg>
                                    <svg class="tick" viewBox="0 0 32 32">
                                        <polyline points="18,7 11,16 6,12" />
                                    </svg>
                                </div>
                            </button>
                        </div>
                        <input type="hidden" id="form_url" value="{{ @$data['url'] }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('public/backend/js/fs_d_ecma/components/__attendance.js') }}"></script>
