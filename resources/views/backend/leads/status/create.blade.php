<div class="modal fade status-modal" id="status-modal"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title text-white">{{ @$data['title'] }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                @if(@$data['show'] != "")
                <form method="POST" action="{{ route('status.update') }}" class="" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ @$data['show']->id }}">
                    @else
                    <form method="POST" action="{{ route('status.store') }}" class="" enctype="multipart/form-data">
                        @endif
                        @csrf

                        <div class="row">

                            <div class="col-md-12 col-lg-12 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Title') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['show']->title }}" name="title"
                                    placeholder="{{ _trans('common.Title') }}" required="" autocomplete="off" value="">
                            </div>


                            <div class="col-md-12 col-lg-12 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Serial') }} <span class="text-danger"></span>
                                </label>
                                <input type="number" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['show']->order }}" name="order"
                                    placeholder="{{ _trans('common.Serial') }}" autocomplete="off">
                            </div>

                            <div class="col-md-12 col-lg-12 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('lead.Border Color') }} <span class="text-danger">*</span>
                                </label>
                                <input type="color" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['show']->border_color }}" name="border_color"
                                    placeholder="Border Color" required="" autocomplete="off" value="">
                            </div>
                            <div class="col-md-12 col-lg-12 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('lead.Background Color') }} <span class="text-danger">*</span>
                                </label>
                                <input type="color" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['show']->background_color }}" name="background_color"
                                    placeholder="Background Color" required="" autocomplete="off" value="">
                            </div>
                            <div class="col-md-12 col-lg-12 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('lead.Text Color') }} <span class="text-danger">*</span>
                                </label>
                                <input type="color" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['show']->text_color }}" name="text_color" placeholder="Text Color"
                                    required="" autocomplete="off" value="">
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-20">
                                    <label class="form-label" for="name">{{ _trans('common.Status') }} <span
                                            class="text-danger">*</span></label>
                                    <select name="status" class="form-select select2-input ot_input mb-3 modal_select">
                                        <option value="" disabled>{{ _trans('common.Choose One') }}</option>
                                        <option value="1" {{ @$data['show'] ? ($data['show']->status_id == 1 ?
                                            'selected' : '') : '' }}>
                                            {{ _trans('common.Active') }}</option>
                                        <option value="4" {{ @$data['show'] ? ($data['show']->status_id == 4 ?
                                            'selected' : '') : '' }}>
                                            {{ _trans('common.In-active') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="crm_theme_btn ">
                                {{ _trans('common.Save') }}
                            </button>
                        </div>
                        <!-- /.card-body -->
                    </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('public/backend/js/fs_d_ecma/modal/__modal.min.js') }}"></script>