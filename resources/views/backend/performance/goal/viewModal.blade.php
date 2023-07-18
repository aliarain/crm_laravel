<div class="modal  fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header text-center modal-header-image mb-3">
                <h5 class="modal-title text-white">{{ @$data['title'] }} </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="designation_id" >{{ _trans('common.Title') }}</label>
                                <p>
                                    {{ @$data['edit']->name }}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name" >{{ _trans('common.Employee') }}</label>
                                <p>
                                    {{ @$data['edit']->user->name }} 
                                </p>
                            </div>
                        </div>
                       

                       
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="shift_id" >{{ _trans('common.Date') }}</label>
                                <p>
                                    {{ showDate(@$data['edit']->date) }}
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="shift_id" >{{ _trans('common.Added By') }}</label>
                                <p>
                                    {{ @$data['edit']->added->name }}
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="shift_id" >{{ _trans('common.Remarks') }}</label>
                                <p>
                                    {!! @$data['edit']->remarks !!}
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        @foreach ($data['competence_types'] as $competence_type)
                            <div class="col-md-12 mt-3">
                                <h6>{{ $competence_type->name }}</h6>
                                <hr class="mt-0">
                            </div>
                            @foreach ($competence_type->competencies as $competences)                         
    
                                 @if (@$data['edit']->rates )
                                   @php
                                        $rate =  array_values(array_filter($data['edit']->rates, function($item) use ($competences) {
                                            return @$item['id'] == $competences->id ? true : false;
                                        }));      
                                   @endphp                                 
                                 @endif
                                <div class="col-6">
                                    {{ $competences->name }}
                                </div>
                                <div class="col-6">
                                    <fieldset id="demo1" class="rating">
                                        <input disabled type="radio" {{ @$rate[0]['rating'] == 5 ? ' checked' : ''}} id="star5_{{ $competences->id }}" name="rating[{{ $competences->id }}]" value="5" />
                                        <label class="full" for="star5_{{ $competences->id }}" title="Awesome - 5 stars"></label>
                                        <input disabled type="radio" {{ @$rate[0]['rating'] == 4.5 ? ' checked' : ''}} id="star4half_{{ $competences->id }}" name="rating[{{ $competences->id }}]" value="4.5" />
                                        <label class="half" for="star4half_{{ $competences->id }}" title="Pretty good - 4.5 stars"> </label>
                                        <input disabled type="radio" {{ @$rate[0]['rating'] == 4 ? ' checked' : ''}} id="star4_{{ $competences->id }}" name="rating[{{ $competences->id }}]" value="4" />
                                        <label class="full" for="star4_{{ $competences->id }}" title="Pretty good - 4 stars"></label>
                                        <input disabled type="radio" {{ @$rate[0]['rating'] == 3.5 ? ' checked' : ''}} id="star3half_{{ $competences->id }}" name="rating[{{ $competences->id }}]" value="3.5" />
                                        <label class="half" for="star3half_{{ $competences->id }}" title="Meh - 3.5 stars"></label>
                                        <input disabled type="radio" {{ @$rate[0]['rating'] == 3 ? ' checked' : ''}} id="star3_{{ $competences->id }}" name="rating[{{ $competences->id }}]" value="3" />
                                        <label class="full" for="star3_{{ $competences->id }}" title="Meh - 3 stars"></label>
                                        <input disabled type="radio" {{ @$rate[0]['rating'] == 2.5 ? ' checked' : ''}} id="star2half_{{ $competences->id }}" name="rating[{{ $competences->id }}]" value="2.5" />
                                        <label class="half" for="star2half_{{ $competences->id }}" title="Kinda bad - 2.5 stars"></label>
                                        <input disabled type="radio" {{ @$rate[0]['rating'] == 2 ? ' checked' : ''}} id="star2_{{ $competences->id }}" name="rating[{{ $competences->id }}]" value="2" />
                                        <label class="full" for="star2_{{ $competences->id }}" title="Kinda bad - 2 stars"></label>
                                        <input disabled type="radio" {{ @$rate[0]['rating'] == 1.5 ? ' checked' : ''}} id="star1half_{{ $competences->id }}" name="rating[{{ $competences->id }}]" value="1.5" />
                                        <label class="half" for="star1half_{{ $competences->id }}" title="Meh - 1.5 stars"></label>
                                        <input disabled type="radio" {{ @$rate[0]['rating'] == 1 ? ' checked' : ''}} id="star1_{{ $competences->id }}" name="rating[{{ $competences->id }}]" value="1" />
                                        <label class="full" for="star1_{{ $competences->id }}" title="bad time - 1 star"></label>
                                    </fieldset>
                                </div>
                            @endforeach
       
                        @endforeach
                    </div>
            </div>
        </div>
    </div>
</div>
