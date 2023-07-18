@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="card ot-card">
        <div class="card-body">
            <form method="POST" action="{{ route('user.permission_update',  $data['show']->id) }}"enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-content table-basic">
                            <div class="table-responsive">
                                <table class="table role-create-table role-permission " id="permissions-table">
                                    <thead class="thead">
                                        <tr>
                                            <th class="border-bottom-0" class="" scope="col">{{ _trans('common.Module') }}/
                                                {{ _trans('common.Sub Module') }}</th>
                                            <th class="border-bottom-0 text-right" scope="col">{{ _trans('common.Permissions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @foreach ($data['permissions'] as $permission)
                                        <tr class="bg-transparent border-bottom-0">
                                            <td colspan="5" class="p-0 border-bottom-0">
                                                <div class="accordion accordion-role mb-3">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#toggle-{{ $permission->id }}"
                                                                aria-expanded="false"
                                                                aria-controls="toggle-{{ $permission->id }}">
                                                                <div class="input-check-radio">
                                                                    <div class="form-check">
                                                                        <input type="checkbox"
                                                                            class="form-check-input mt-0 read check_all outer-check-item"
                                                                            name="check_all" id="check_all">
                                                                        <label class="form-check-label ml-6"
                                                                            for="check_all">
                                                                            <span>
                                                                                {{ Str::title(str_replace('_', ' ', __(@$permission->attribute))) }}
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="toggle-{{ $permission->id }}"
                                                            class="accordion-collapse collapse">
                                                            <div class="accordion-body d-flex flex-wrap">
                                                                @foreach ($permission->keywords as $key => $keyword)
                                                                    <div class="input-check-radio mr-16">
                                                                        <div class="form-check">
                                                                            @if ($keyword != '')
                                                                                <input type="checkbox"
                                                                                    class="form-check-input mt-0 read inner-check-item"
                                                                                    id="{{ $keyword }}"
                                                                                    name="permissions[]"
                                                                                    value="{{ $keyword }}"
                                                                                    {{ in_array($keyword, @$data['show']->permissions) ? 'checked' : '' }}>
                                                                                <label class="form-check-label ml-6"
                                                                                    for="{{ $keyword }}">{{ Str::title(str_replace('_', ' ', __($key))) }}</label>
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
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-12">
                    <div class="col-md-12 text-right mt-3 mb-3 mr-5">
                        <div class="form-group d-flex justify-content-end">
                            @if (hasPermission('user_create'))
                                <button type="submit" class="crm_theme_btn ">{{ _trans('common.Update') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
        </div>
        </form>
    </div>
    </div>
@endsection
@section('script')
    <script src="{{ url('public/backend/js/pages/__profile.js') }}"></script>
@endsection
