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
                                <div class="col-md-12 form-group mb-3">
                                    <div class="form-group">
                                        <label for="#" class="form-label">
                                            {{ _trans('common.Title') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="title" id="title"
                                            class="form-control ot-form-control ot_input" required
                                            value="{{ @$data['edit'] ? @$data['edit']->name : old('title') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">
                                            {{ _trans('project.Employee') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="user_id" class="form-select mb-3 modal_select2" required>
                                            @foreach ($data['users'] as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $data['edit']->user_id == $user->id ? ' selected="selected"' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">
                                            {{ _trans('common.Date') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="date" id="date"
                                            class="form-control ot-form-control ot_input" required
                                            value="{{ @$data['edit']->date ? $data['edit']->date : date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">
                                            {{ _trans('common.Remarks') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control mt-0 ot_input" name="remarks" id="remarks" rows="5" required>
                                            {{ @$data['edit']->remarks }}
                                        </textarea>
                                    </div>
                                </div>



                            </div>
                            <div class="row">
                                @foreach ($data['competence_types'] as $competence_type)
                                    <div class="col-md-12 mt-3">
                                        <h6>{{ $competence_type->name }}</h6>
                                    </div>
                                    <hr class="p-0 mt-8">
                                    @foreach ($competence_type->competencies as $competences)
                                        @if (@$data['edit']->rates)
                                            @php
                                                $rate = array_values(
                                                    array_filter($data['edit']->rates, function ($item) use ($competences) {
                                                        return @$item['id'] == $competences->id ? true : false;
                                                    }),
                                                );
                                            @endphp
                                        @endif
                                        <div class="col-6">
                                            <p class="primary-color"> {{ $competences->name }} </p>
                                        </div>
                                        <div class="col-6">
                                            <fieldset id="demo1" class="rating">
                                                <input type="radio" {{ @$rate[0]['rating'] == 5 ? ' checked' : '' }}
                                                    id="star5_{{ $competences->id }}"
                                                    name="rating[{{ $competences->id }}]" value="5" />
                                                <label class="full" for="star5_{{ $competences->id }}"
                                                    title="Awesome - 5 stars"></label>
                                                <input type="radio"
                                                    {{ @$rate[0]['rating'] == 4.5 ? ' checked' : '' }}
                                                    id="star4half_{{ $competences->id }}"
                                                    name="rating[{{ $competences->id }}]" value="4.5" />
                                                <label class="half" for="star4half_{{ $competences->id }}"
                                                    title="Pretty good - 4.5 stars"> </label>
                                                <input type="radio" {{ @$rate[0]['rating'] == 4 ? ' checked' : '' }}
                                                    id="star4_{{ $competences->id }}"
                                                    name="rating[{{ $competences->id }}]" value="4" />
                                                <label class="full" for="star4_{{ $competences->id }}"
                                                    title="Pretty good - 4 stars"></label>
                                                <input type="radio"
                                                    {{ @$rate[0]['rating'] == 3.5 ? ' checked' : '' }}
                                                    id="star3half_{{ $competences->id }}"
                                                    name="rating[{{ $competences->id }}]" value="3.5" />
                                                <label class="half" for="star3half_{{ $competences->id }}"
                                                    title="Meh - 3.5 stars"></label>
                                                <input type="radio" {{ @$rate[0]['rating'] == 3 ? ' checked' : '' }}
                                                    id="star3_{{ $competences->id }}"
                                                    name="rating[{{ $competences->id }}]" value="3" />
                                                <label class="full" for="star3_{{ $competences->id }}"
                                                    title="Meh - 3 stars"></label>
                                                <input type="radio"
                                                    {{ @$rate[0]['rating'] == 2.5 ? ' checked' : '' }}
                                                    id="star2half_{{ $competences->id }}"
                                                    name="rating[{{ $competences->id }}]" value="2.5" />
                                                <label class="half" for="star2half_{{ $competences->id }}"
                                                    title="Kinda bad - 2.5 stars"></label>
                                                <input type="radio" {{ @$rate[0]['rating'] == 2 ? ' checked' : '' }}
                                                    id="star2_{{ $competences->id }}"
                                                    name="rating[{{ $competences->id }}]" value="2" />
                                                <label class="full" for="star2_{{ $competences->id }}"
                                                    title="Kinda bad - 2 stars"></label>
                                                <input type="radio"
                                                    {{ @$rate[0]['rating'] == 1.5 ? ' checked' : '' }}
                                                    id="star1half_{{ $competences->id }}"
                                                    name="rating[{{ $competences->id }}]" value="1.5" />
                                                <label class="half" for="star1half_{{ $competences->id }}"
                                                    title="Meh - 1.5 stars"></label>
                                                <input type="radio" {{ @$rate[0]['rating'] == 1 ? ' checked' : '' }}
                                                    id="star1_{{ $competences->id }}"
                                                    name="rating[{{ $competences->id }}]" value="1" />
                                                <label class="full" for="star1_{{ $competences->id }}"
                                                    title="bad time - 1 star"></label>
                                            </fieldset>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                            <div class="form-group d-flex justify-content-end">
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