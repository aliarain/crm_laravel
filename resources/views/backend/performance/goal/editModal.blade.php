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
                <div class="row p-0">
                    <div class="col-md-12">
                        <form action="{{ $data['url'] }}" method="POST" id="modal_values">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                         <label for="#" class="form-label">
                                             {{ _trans('common.Subject') }}
                                             <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="subject" id="subject"
                                            class="form-control ot-form-control ot_input" required
                                            value="{{ @$data['edit']->subject }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">
                                            {{ _trans('performance.Goal Type') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="goal_type_id" id="goal_type_id"
                                            class="form-select select2-input ot_input mb-3 modal_select2" required>
                                            @foreach ($data['goal_types'] as $goal_type)
                                                <option
                                                    {{ @$data['edit']->goal_type_id == $goal_type->id ? ' selected' : '' }}
                                                    value="{{ $goal_type->id }}">{{ $goal_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 ">
                                    <div class="form-group mb-3">
                                         <label for="#" class="form-label">
                                             {{ _trans('performance.Target Achievement') }}
                                             <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="target" id="target"
                                            class="form-control ot-form-control ot_input" required  value="{{ @$data['edit']->target }}">
                                    </div>
                                </div>



                                <div class="col-lg-6 ">
                                    <div class="form-group mb-3">
                                         <label for="#" class="form-label">
                                             {{ _trans('common.Start Date') }}
                                             <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="start_date" id="start_date"
                                            class="form-control ot-form-control ot_input" required value="{{ @$data['edit']->start_date }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 ">
                                    <div class="form-group mb-3">
                                         <label for="#" class="form-label">
                                             {{ _trans('common.End Date') }}
                                             <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="end_date" id="end_date"
                                            class="form-control ot-form-control ot_input" required
                                            value="{{ @$data['edit']->end_date ?  @$data['edit']->end_date : date('Y-m-d', strtotime('+1 month')) }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 ">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">
                                            {{ _trans('common.Status') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="status" id="status" class="form-select select2-input ot_input mb-3 modal_select2" required>
                                            <option value="24" {{ $data['edit']->status_id == 24 ? ' selected' : '' }}>
                                                {{ _trans('project.Not Started') }}</option>
                                            <option value="26" {{ $data['edit']->status_id == 26 ? ' selected' : '' }}>
                                                {{ _trans('project.In Progress') }} </option>
                                            <option value="27" {{ $data['edit']->status_id == 27 ? ' selected' : '' }}>
                                                {{ _trans('project.Completed') }}</option>
                                        </select>
                                    </div>
                                </div>
        
                                <div class="col-lg-6 ">
                                    <div class="form-group mb-3">
                                         <label for="#" class="form-label">
                                             {{ _trans('common.Rating') }}
                                             <span class="text-danger">*</span>
                                        </label>
                                        <br>
                                        <fieldset class="rating">
                                            <input type="radio" {{ $data['edit']->rating == 5 ? ' checked' : '' }}
                                                id="star5" name="rating" value="5" />
                                            <label class="full" for="star5" title="Awesome - 5 stars"></label>
                                            <input type="radio" {{ $data['edit']->rating == 4.5 ? ' checked' : '' }}
                                                id="star4half" name="rating" value="4.5" />
                                            <label class="half" for="star4half" title="Pretty good - 4.5 stars"> </label>
                                            <input type="radio" {{ $data['edit']->rating == 4 ? ' checked' : '' }}
                                                id="star4" name="rating" value="4" />
                                            <label class="full" for="star4" title="Pretty good - 4 stars"></label>
                                            <input type="radio" {{ $data['edit']->rating == 3.5 ? ' checked' : '' }}
                                                id="star3half" name="rating" value="3.5" />
                                            <label class="half" for="star3half" title="Meh - 3.5 stars"></label>
                                            <input type="radio" {{ $data['edit']->rating == 3 ? ' checked' : '' }}
                                                id="star3" name="rating" value="3" />
                                            <label class="full" for="star3" title="Meh - 3 stars"></label>
                                            <input type="radio" {{ $data['edit']->rating == 2.5 ? ' checked' : '' }}
                                                id="star2half" name="rating" value="2.5" />
                                            <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
                                            <input type="radio"{{ $data['edit']->rating == 2 ? ' checked' : '' }}
                                                id="star2" name="rating" value="2" />
                                            <label class="full" for="star2" title="Kinda bad - 2 stars"></label>
                                            <input type="radio" {{ $data['edit']->rating == 1.5 ? ' checked' : '' }}
                                                id="star1half" name="rating" value="1.5" />
                                            <label class="half" for="star1half" title="Meh - 1.5 stars"></label>
                                            <input type="radio" {{ $data['edit']->rating == 1 ? ' checked' : '' }}
                                                id="star1" name="rating" value="1" />
                                            <label class="full" for="star1" title="bad time - 1 star"></label>
        
                                        </fieldset>
        
                                    </div>
                                </div>
        
                                <div class="col-lg-12 ">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">{{ _trans('project.Progress') }}
                                            <small id="progress_percentage"> {{ @$data['edit']->progress }}% </small> </label>
                                        <input type="range" name="progress" id="progress" class="" min="0"
                                            max="100" oninput="progressValue(this.value)"
                                            onchange="progressValue(this.value)" value="{{ @$data['edit']->progress }}">
                                    </div>
                                </div>
        

                                <div class="col-lg-12 ">
                                    <div class="form-group mb-3">
                                         <label for="#" class="form-label">
                                             {{ _trans('common.Description') }}
                                             <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control ot_input" name="description" id="description" rows="5" required>{{ @$data['edit']->description }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group d-flex justify-content-end ">
                                <button type="button"
                                    class="crm_theme_btn pull-right hit_modal">{{ @$data['button'] }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('public/backend/js/fs_d_ecma/modal/__modal.min.js') }}"></script>
