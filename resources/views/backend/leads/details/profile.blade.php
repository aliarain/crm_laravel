<div class="row">
    <div class="col-md-3">
        <div class="table-content table-basic">
            <div class="card mb-4">
                <div class="card-body">

                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Full Name') }}</label>



                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ @$data['items']->name }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Company') }}</label>



                        <div class="col-lg-8 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">{{ @$data['items']->company }}</span>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">
                            {{ _trans('common.Phone') }}
                        </label>

                        <div class="col-lg-8 d-flex align-items-center">
                            <span class="fw-bold fs-6 text-gray-800 me-2">{{ @$data['items']->phone }}</span>
                        </div>
                    </div>



                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">
                            {{ _trans('common.Email') }}
                        </label>

                        <div class="col-lg-8 d-flex align-items-center">
                            <span class="fw-bold fs-6 text-gray-800 me-2">{{ @$data['items']->email }}</span>
                        </div>
                    </div>


                    <div class="row mb-10">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Source') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold fs-6 text-gray-800">{{ @$data['items']->source->title }}</span>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Type') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold fs-6 text-gray-800">{{ @$data['items']->type->title }}</span>
                        </div>
                    </div>
                    <div class="row mb-10">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Lead Status') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold fs-6 text-gray-800">{{ @$data['items']->lead_status->title
                                }}</span>
                        </div>
                    </div>


                    <div class="row mb-10">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Created At') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold fs-6 text-gray-800">{{ @$data['items']->created_at }}</span>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Author') }}</label>
                        <div class="col-lg-8">
                            <span class="fw-semibold fs-6 text-gray-800">{{ @$data['items']->author->name }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">

        <div class="table-content table-basic">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Title') }}</label>
                        <div class="col-lg-8">
                            <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{
                                @$data['items']->title }}</a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Details') }}</label>
                        <div class="col-lg-8">
                            <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{
                                @$data['items']->description }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="table-content table-basic">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Company Site') }}</label>
                        <div class="col-lg-8">
                            <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{
                                @$data['items']->website }}</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Next Follow Up') }}</label>
                        <div class="col-lg-8">
                            <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{
                                @$data['items']->next_follow_up?? "N/A" }}</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.address') }}</label>
                        <div class="col-lg-8">
                            <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{
                                @$data['items']->address }}</a>
                        </div>
                    </div>



                    <div class="row mb-3">
                        <label class="col-lg-4 fw-semibold text-muted">{{ _trans('common.Address in Full Form') }}</label>
                        <div class="col-lg-8">
                            {{ @$data['items']->city }} - {{ @$data['items']->zip }}, {{ @$data['items']->country }} ,
                            {{ @$data['items']->state }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>