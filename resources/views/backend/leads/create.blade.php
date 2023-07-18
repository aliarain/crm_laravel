<div class="modal fade lead-modal" id="lead-modal"  aria-hidden="true">
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
                <form method="POST" action="{{ route('lead.update') }}" class="" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ @$data['show']->id }}">
                    @else
                    <form method="POST" action="{{ route('lead.store') }}" class="" enctype="multipart/form-data">
                        @endif
                        @csrf
                        <div class="row mb-3">


                            <!-- Start:: Lead type dropdown -->
                            @if($data['lead_types'])
                            <div class="col-md-6 col-lg-4 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Lead Type') }} <span class="text-danger">*</span>
                                </label>
                                <select name="lead_type_id"
                                    class="form-select select2-input ot_input mb-3 modal_select2 select2-hidden-accessible"
                                    id="_lead_types" aria-label="Default select lead">
                                    @foreach($data['lead_types'] as $row)
                                    <option value="{{ @$row->id }}" {{ @$data['show'] && @$data['show']->lead_type_id ==
                                        @$row->id ? 'selected':'' }}>{{ @$row->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <!-- End:: Lead type dropdown -->


                            <!-- Start:: Lead source dropdown -->
                            @if($data['lead_sources'])
                            <div class="col-md-6 col-lg-4 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Lead Source') }} <span class="text-danger">*</span>
                                </label>
                                <select name="lead_source_id"
                                    class="form-select select2-input ot_input mb-3 modal_select2 select2-hidden-accessible"
                                    id="_lead_sources" aria-label="Default select lead sources">
                                    @foreach($data['lead_sources'] as $row)
                                    <option value="{{ @$row->id }}" {{ @$data['show'] && @$data['show']->lead_source_id
                                        == @$row->id ? 'selected':'' }}>{{ @$row->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <!-- End:: Lead source dropdown -->


                            <!-- Start:: Lead Status dropdown -->
                            @if($data['lead_statuses'])
                            <div class="col-md-6 col-lg-4 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Lead Status') }} <span class="text-danger">*</span>
                                </label>
                                <select name="lead_status_id"
                                    class="form-select select2-input ot_input mb-3 modal_select2 select2-hidden-accessible"
                                    id="_lead_statuses" aria-label="Default select lead statuses">
                                    @foreach($data['lead_statuses'] as $row)
                                    <option value="{{ @$row->id }}" {{ @$data['show'] && @$data['show']->lead_status_id
                                        == @$row->id ? 'selected':'' }}>{{ @$row->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <!-- End:: Lead Status dropdown -->

                        </div>

                        <div class="row">

                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control ot-form-control ot_input" name="name"
                                    placeholder=" {{ _trans('common.Name') }}" required="" autocomplete="off"
                                    value="{{@$data['show']->name}}">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Title') }}<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control ot-form-control ot_input" name="title"
                                    placeholder="{{ _trans('common.Title') }}" required="" autocomplete="off"
                                    value="{{@$data['show']->title}}">
                            </div>

                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.City') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control ot-form-control ot_input" name="city"
                                    placeholder="{{ _trans('common.City') }}" required="" autocomplete="off"
                                    value="{{@$data['show']->city}}">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Zip Code') }} <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control ot-form-control ot_input" name="zip"
                                    placeholder="{{ _trans('common.Zip Code') }}" required="" autocomplete="off"
                                    value="{{@$data['show']->zip}}">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Email Address') }} <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control ot-form-control ot_input" name="email"
                                    placeholder="{{ _trans('common.Email Address') }}" required="" autocomplete="off"
                                    value="{{@$data['show']->email}}">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.State') }}<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control ot-form-control ot_input" name="state"
                                    placeholder="{{ _trans('common.State') }}" required="" autocomplete="off"
                                    value="{{@$data['show']->state}}">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Website') }}
                                </label>
                                <input type="text" class="form-control ot-form-control ot_input" name="website"
                                    placeholder="{{ _trans('common.Website') }}" autocomplete="off"
                                    value="{{@$data['show']->website}}">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Country') }} <span
                                            class="text-danger">*</span> </label>
                                    <select name="country_id" class="form-select select2 w-100" id="_country_id"
                                        required <option value="{{ @$data['show'] ? $data['show']->country : '' }}">
                                        {{ @$data['show'] ? @$data['show']->countryInfo->name : '' }}</option>
                                    </select>
                                    @if ($errors->has('country_id'))
                                    <div class="invalid-feedback d-block">{{ $errors->first('country_id') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Phone') }} <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control ot-form-control ot_input" name="phone"
                                    placeholder="{{ _trans('common.Phone') }}" required="" autocomplete="off"
                                    value="{{@$data['show']->phone}}">
                            </div>

                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label">
                                    {{ _trans('common.Address') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control ot-form-control ot_input" name="address"
                                    placeholder="{{ _trans('common.Address') }}" required="" autocomplete="off"
                                    value="{{@$data['show']->address}}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    {{ _trans('common.Description') }}
                                </label>
                                <textarea class="form-control ot-form-control ot_input mt-0" placeholder="{{ _trans('common.Description') }}"
                                    name="description">{{@$data['show']->description}}</textarea>
                            </div>
                            <div class="col-md-6 col-lg-6 mb-3 ">
                                <label class="form-label"> {{ _trans('common.Status') }} <span
                                        class="text-danger">*</span>
                                </label>
                                <select name="status_id" required
                                    class="form-select select2-input ot_input mb-3 modal_select2 select2-hidden-accessible"
                                    aria-label="Default select lead statuses">
                                    <option value="1" {{ @$data['show'] && @$data['show']->status_id == 1 ?
                                        'selected':'' }}>{{ _trans('common.Active') }}</option>
                                    <option value="4" {{ @$data['show'] && @$data['show']->status_id == 4 ?
                                        'selected':'' }}>{{ _trans('common.Inactive') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-6 mb-20 ">
                                <label class="form-label">
                                    {{ _trans('common.Next Follow Up') }}
                                </label>
                                <input type="date" class="form-control ot-form-control ot_input" name="next_follow_up"
                                    placeholder="01-01-2023" autocomplete="off"
                                    value="{{@$data['show']->next_follow_up}}">
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
<script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>