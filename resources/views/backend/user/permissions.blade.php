<thead class="thead">
    <tr>
        <th class="border-bottom-0" scope="col">{{ _trans('common.Module') }}/{{ _trans('common.Sub-module') }}</th>
        <th class="border-bottom-0" scope="col">{{ _trans('common.Permissions') }}</th>
    </tr>
</thead>
<tbody class="tbody">
    @foreach ($data['permissions'] as $permission)
        <tr class="bg-transparent border-bottom-0">
            <td colspan="5" class="p-0 border-bottom-0">
                <div class="accordion accordion-role mb-3">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#toggle-{{ $permission->id }}" aria-expanded="false"
                                aria-controls="toggle-{{ $permission->id }}">
                                <div class="input-check-radio">
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input mt-0 read check_all outer-check-item"
                                            name="check_all" id="check_all">
                                        <label class="form-check-label ml-6"
                                            for="#"><span>{{ __($permission->attribute) }}</span>
                                        </label>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="toggle-{{ $permission->id }}" class="accordion-collapse collapse">
                            <div class="accordion-body d-flex flex-wrap">
                                @foreach ($permission->keywords as $key => $keyword)
                                    <div class="input-check-radio mr-16">
                                        <div class="form-check">
                                            @if ($keyword != '')
                                                <input type="checkbox"
                                                    class="form-check-input mt-0 read common-key inner-check-item"
                                                    name="permissions[]" value="{{ $keyword }}"
                                                    id="{{ $keyword }}"
                                                    {{ in_array($keyword, $data['role_permissions']) ? 'checked' : '' }}>
                                                <label class="form-check-label ml-6"
                                                    for="{{ $keyword }}">{{ __($key) }}</label>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach

</tbody>


<script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>