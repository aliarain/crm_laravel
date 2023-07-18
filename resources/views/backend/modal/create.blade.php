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
                <form action="{{ $data['url'] }}" class="row p-0" method="post" id="modal_values"
                    enctype="multipart/form-data">
                    @csrf
                    {{-- dynamic attributes --}}
                    @if (@$data['attributes'])
                        @foreach (@$data['attributes'] as $key => $attribute)
                            <div class="{{ @$attribute['col'] }}">
                                <label class="form-label">
                                    {{ @$attribute['label'] }}
                                    @if (@$attribute['required'])
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                @if (@$attribute['type'] == 'text')
                                    <input type="text" class="{{ @$attribute['class'] }}" name="{{ @$key }}"
                                        id="{{ @$key }}" placeholder="{{ @$attribute['label'] }}"
                                        @if (@$attribute['required']) required @endif autocomplete="off"
                                        value="{{ @$attribute['value'] }}">
                                @elseif (@$attribute['type'] == 'select')
                                    <select name="{{ @$key }}" id="{{ @$attribute['id'] }}"
                                        class="{{ @$attribute['class'] }}" aria-label="Default select example"
                                        @if (@$attribute['required']) required @endif
                                        {{ @$attribute['multiple'] }}>
                                        @foreach (@$attribute['options'] as $option)
                                            <option value="{{ $option['value'] }}"
                                                {{ @$option['active'] ? 'selected' : '' }}>
                                                <?= $option['text'] ?>
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif (@$attribute['type'] == 'number')
                                    <input type="number" class="{{ @$attribute['class'] }}"
                                        name="{{ @$key }}" id="{{ @$key }}" placeholder="{{ @$attribute['label'] }}"
                                        @if (@$attribute['required']) required @endif
                                        value="{{ @$attribute['value'] }}" autocomplete="off">
                                @elseif (@$attribute['type'] == 'date')
                                    <input type="text" class="{{ @$attribute['class'] }}"
                                        name="{{ @$key }}" id="{{ @$attribute['id'] }}"
                                        @if (@$attribute['required']) required @endif
                                        value="{{ @$attribute['value'] }}" autocomplete="off">
                                @elseif (@$attribute['type'] == 'file')
                                    <input type="file" class="{{ @$attribute['class'] }}"
                                        name="{{ @$key }}" id="{{ @$attribute['id'] }}"
                                        @if (@$attribute['required']) required @endif
                                        value="{{ @$attribute['value'] }}" autocomplete="off">
                                @elseif (@$attribute['type'] == 'checkbox')
                                    <div class="form-check">
                                        <input type="checkbox" class="{{ @$attribute['class'] }}"
                                            name="{{ @$key }}" id="{{ @$key }}" value="1">
                                        <label class="form-check-label">{{ @$attribute['label'] }}</label>
                                    </div>
                                @elseif (@$attribute['type'] == 'textarea')
                                    <textarea class="{{ @$attribute['class'] }}" name="{{ @$key }}" rows="{{ @$attribute['row'] ?? 1 }}"
                                        placeholder="{{ @$attribute['label'] }}" @if (@$attribute['required']) required @endif>{{ @$data['edit'] ? $data['edit']->$key : old($key) }}</textarea>
                                @endif
                            </div>
                        @endforeach

                    @endif
                    <div class="form-group d-flex justify-content-end mt-4">
                        <button type="button"
                            class="crm_theme_btn pull-right hit_modal">{{ @$data['button'] }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('public/backend/js/fs_d_ecma/modal/__modal.min.js') }}"></script>
