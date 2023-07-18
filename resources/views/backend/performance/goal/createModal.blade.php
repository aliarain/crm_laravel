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
                                        <input type="text" name="subject" id="subject" class="form-control ot-form-control ot_input" required placeholder="{{_trans('common.Subject')}}"
                                            value="{{ old('subject') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                         <label for="#" class="form-label">
                                             {{ _trans('performance.Goal Type') }}
                                             <span class="text-danger">*</span>
                                        </label>                            
                                        <select name="goal_type_id" id="goal_type_id" class="form-select select2-input ot_input mb-3 modal_select2" required>
                                            @foreach ($data['goal_types'] as $goal_type)
                                                <option value="{{ $goal_type->id }}">{{ $goal_type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
        
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">
                                            {{ _trans('performance.Target Achievement') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="target" id="target" class="form-control ot-form-control ot_input" required
                                            value="{{ old('target') }}">
                                    </div>
                                </div>
        
                             
        
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                         <label for="#" class="form-label">
                                             {{ _trans('common.Start Date') }}
                                             <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="start_date"  id="start_date"  class="form-control ot-form-control ot_input" required
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
        
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                         <label for="#" class="form-label">
                                             {{ _trans('common.End Date') }}
                                             <span class="text-danger">*</span>
                                        </label>   
                                        <input type="date" name="end_date" id="end_date" class="form-control ot-form-control ot_input" required
                                            value="{{ date('Y-m-d', strtotime('+1 month')) }}">
                                    </div>
                                </div>
        
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group mb-3">
                                        <label for="#" class="form-label">
                                            {{ _trans('common.Description') }}
                                            <span class="text-danger">*</span>
                                        </label>   
                                        <textarea class="form-control mt-0 ot_input" name="description" id="description" rows="5" required placeholder="{{_trans('common.Description')}}"></textarea>
                                    </div>
                                </div>
        
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
