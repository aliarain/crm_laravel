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
                                <div class="col-md-6 form-group">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">
                                            {{ _trans('common.Employee') }}
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select name="assigned_id"
                                            class="form-select select2-input ot_input mb-3 modal_select2" required>
                                            @foreach ($data['users'] as $user)
                                                <option
                                                    {{ $user->id == @$data['edit']->assigned_id ? ' selected' : '' }}
                                                    value="{{ $user->id }}">{{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
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
