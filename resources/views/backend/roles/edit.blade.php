@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
{!! breadcrumb([
'title' => @$data['title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['title'],
]) !!}
<div class="table-content table-basic">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.update', $data['role']->id) }}" class="form-validate" method="POST">
                @csrf
                @method('PATCH')
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('permission.update') }}" class="crm_theme_btn me-3"> <i
                                    class="fa fa-sync"></i> {{ _trans('settings.Sync Permissions') }}</a>
                            @if (hasPermission('role_read'))
                            <a href="{{ route('user.index') }}" class="crm_theme_btn "> <i class="fa fa-arrow-left"></i>
                                {{ _trans('common.Back') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-12 mt-3">
                            <div class="form-group mb-3">
                                <label class="form-label" for="fv-full-name">{{ _trans('common.Name') }} <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control ot-form-control ot_input" id="fv-full-name"
                                        name="name" required placeholder="{{ _trans('common.Name') }}"
                                        value="{{ $data['role']->name }}">
                                </div>
                                @if ($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="fv-email">{{ _trans('common.Status') }} <span
                                        class="text-danger">*</span></label>
                                <div class="form-control-wrap">
                                    <select name="status_id" id="status_id" class="form-select ot_input" required>
                                        <option value="" disabled>{{ _trans('common.Choose One') }}
                                        </option>
                                        <option value="1" {{ $data['role']->status_id == 1 ? 'selected' : '' }}>
                                            {{ _trans('common.Active') }}</option>
                                        <option value="4" {{ $data['role']->status_id == 4 ? 'selected' : '' }}>
                                            {{ _trans('common.In-active') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="role-permisssion-control">
                            <div class="table-content table-basic">
                                <div class="table-responsive">
                                    <table class="table role-create-table role-permission ">
                                        <thead class="thead">
                                            <tr>
                                                <th class="" scope="col">
                                                    {{ _trans('common.Module') }}/
                                                    {{ _trans('common.Sub Module') }}</th>
                                                <th scope="col">{{ _trans('common.Permissions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            <tr>
                                                <td>{{ _trans('common.Check all') }}</td>
                                                <td>
                                                    <div class="input-check-radio">
                                                        <div class="form-check">
                                                            <input type="checkbox"
                                                                class="form-check-input read check_all" name="check_all"
                                                                id="check_all">
                                                            <label class="form-check-label ml-6"
                                                                for="check_all">{{ _trans('common.Check all') }}</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
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
                                                                    <div class="input-check-radio mr-16">
                                                                        <div class="form-check">
                                                                            <input type="checkbox"
                                                                                class="form-check-input read check_all outer-check-item"
                                                                                name="check_all" id="check_all">
                                                                            <label class="form-check-label ml-6"
                                                                                for="check_all"><span>{{ Str::title(Str::replace('_', ' ', $permission->attribute)) }}</span>
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
                                                                            @if ($keyword != '' &&
                                                                            $data['role']->permissions)
                                                                            <input type="checkbox"
                                                                                class="form-check-input read common-key"
                                                                                name="permissions[]"
                                                                                value="{{ $keyword }}"
                                                                                id="{{ $keyword }}"
                                                                                {{ in_array($keyword, $data['role']->permissions) ? 'checked' : '' }}>
                                                                            <label class="form-check-label ml-6"
                                                                                for="{{ $keyword }}">{{ Str::title(Str::replace('_', ' ', $key)) }}</label>
                                                                            @else
                                                                            <input type="checkbox"
                                                                                class="form-check-input read common-key"
                                                                                name="permissions[]"
                                                                                value="{{ $keyword }}"
                                                                                id="{{ $keyword }}">
                                                                            <label class="form-check-label ml-2"
                                                                                for="{{ $keyword }}">{{ Str::title(Str::replace('_', ' ', $key)) }}</label>
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
                    <div class="col-md-12 text-right mt-3 mb-3">
                        <div class="d-flex justify-content-end">
                            @if (hasPermission('role_update'))
                            <div class="input-check-radio mr-16">
                                <div class="form-check" title="If selected, It will overwrite all user custom permission.">
                                    <input type="checkbox" class="form-check-input read common-key" name="apply_for_all"
                                        value="1" id="apply_for_all">
                                    <label class="form-check-label ml-6" for="apply_for_all">{{ _trans('common.Update Permissions For') }}<b> {{ $data['role']->name }} </b> {{ _trans('common.Users Also') }}</label>
                                </div>
                            </div>
                            <button type="submit" class="crm_theme_btn" >{{ _trans('common.Update') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

        </div>
    </section>
</div>
@endsection
@section('script')
<script src="{{ asset('public/backend/js/_roles.js') }}"></script>
@endsection