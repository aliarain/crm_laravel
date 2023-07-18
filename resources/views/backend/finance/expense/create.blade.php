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
                <form action="{{ $data['url'] }}" enctype="multipart/form-data" method="post" id="attendanceForm">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Category') }}</label>
                                <select name="category" class="form-control select2">
                                    @foreach ($data['category'] as $account)
                                        <option
                                            {{ @$data['edit'] ? (@$data['edit']->income_expense_category_id == $account->id ? 'selected' : '') : '' }}
                                            value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach

                                </select>
                                @if ($errors->has('category'))
                                    <div class="error">{{ $errors->first('category') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['edit'] ? $data['edit']->date : date('Y-m-d') }}" required
                                    placeholder="{{ _trans('common.2000.00') }}">
                                @if ($errors->has('date'))
                                    <div class="error">{{ $errors->first('date') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('account.Amount') }} <span class="text-danger">*</span></label>
                                <input type="number" name="amount" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['edit'] ? $data['edit']->amount : old('amount') }}" required
                                    placeholder="{{ _trans('common.500') }}">
                                @if ($errors->has('amount'))
                                    <div class="error">{{ $errors->first('amount') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('account.Reference') }}</label>
                                <input type="text" name="ref" class="form-control ot-form-control ot_input"
                                    value="{{ @$data['edit'] ? $data['edit']->ref : old('ref') }}"
                                    placeholder="{{ _trans('account.Enter Reference') }}">
                                @if ($errors->has('ref'))
                                    <div class="error">{{ $errors->first('ref') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label
                                    class="{{ $data['edit'] ?? 'form-label' }}">{{ _trans('account.Attachment') }} <span class="text-danger">*</span></label>
                                {{-- <input type="file" name="attachment" class="form-control ot-form-control ot_input" {{ $data['edit'] ?? 'required' }}> --}}

                                <div class="ot_fileUploader left-side mb-3">
                                    <input class="form-control" type="text" placeholder="{{ _trans('account.Attachment') }}" name="" readonly="" id="placeholder">
                                    <button class="primary-btn-small-input" type="button">
                                        <label class="btn btn-lg ot-btn-primary" for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                        <input type="file" class="d-none form-control" name="attachment" id="fileBrouse">
                                    </button>
                                </div>

                                @if ($errors->has('attachment'))
                                    <div class="error">{{ $errors->first('attachment') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">{{ _trans('common.Description') }} <span class="text-danger">*</span></label>
                                <textarea type="text" name="description" rows="5" class="form-control ot_input  mt-0"
                                    placeholder="{{ _trans('common.Enter Description') }}" required>{{ @$data['edit'] ? $data['edit']->remarks : old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <div class="error">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (@$data['url'])
                        <div class="row  mt-20">
                            <div class="col-md-12">
                                <div class="text-right d-flex justify-content-end">
                                    <button class="crm_theme_btn ">{{ _trans('common.Save') }}</button>
                                </div>
                            </div>
                        </div>
                    @endif


                </form>

            </div>
        </div>

    </div>
    <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
@endsection
