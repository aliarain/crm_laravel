@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ @$data['title'] }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">{{ _trans('common.Dashboard') }}</a></li>
                            @if (hasPermission('travel_list'))
                                <li class="breadcrumb-item"><a
                                        href="{{ route('travel.index') }}">{{ _trans('common.List') }}</a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active">{{ @$data['title'] }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">


                                <form action="{{ $data['url'] }}" enctype="multipart/form-data" method="post"
                                    id="attendanceForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="name"
                                                    class="form-label">{{ _trans('common.Department') }} <span class="text-danger">*</span></label>
                                                @if (hasPermission('department_create'))
                                                    <a href="{{ route('department.create') }}"
                                                        target="_blank">{{ _trans('common.Add department') }} </a>
                                                @endif

                                                <select name="department_id" class="form-control" required>
                                                    <option value="" disabled>{{ _trans('common.Choose One') }}
                                                    </option>
                                                    @foreach ($data['departments'] as $department)
                                                        <option value="{{ $department->id }}">{{ $department->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('department_id'))
                                                    <div class="error">{{ $errors->first('department_id') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="designation_id"
                                                    class="form-label">{{ _trans('common.Designation') }} <span class="text-danger">*</span></label>
                                                @if (hasPermission('designation_create'))
                                                    <a href="{{ route('designation.create') }}"
                                                        target="_blank">{{ _trans('common.Add designation') }}</a>
                                                @endif
                                                <select name="designation_id" class="form-control" required>
                                                    <option value="" disabled>{{ _trans('common.Choose One') }}
                                                    </option>
                                                    @foreach ($data['designations'] as $designation)
                                                        <option value="{{ $designation->id }}">{{ $designation->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('designation_id'))
                                                    <div class="error">{{ $errors->first('designation_id') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="shift_id"
                                                    class="form-label">{{ _trans('common.Shift') }} <span class="text-danger">*</span></label>
                                                <select name="shift_id" class="form-control" required>
                                                    <option value="" disabled>{{ _trans('common.Choose One') }}
                                                    </option>
                                                    @foreach ($data['shifts'] as $shift)
                                                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('shift_id'))
                                                    <div class="error">{{ $errors->first('shift_id') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="#"
                                                    class="form-label">{{ _trans('performance.Title') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="title" class="form-control" required
                                                    value="{{ old('title') }}">
                                                @if ($errors->has('title'))
                                                    <div class="error">{{ $errors->first('title') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-lg-12">
                                            
                                            <div class="form-group">
                                                @foreach ($data['competences'] as $competences)
                                                <fieldset class="rating">
                                                        {{ $competences->name }}

                                                        <input type="radio" id="star5" name="rating" value="5" />
                                                        <label class = "full" for="star5" title="Awesome - 5 stars"></label>
                                                        <input type="radio" id="star4half" name="rating" value="4.5" />
                                                        <label class="half" for="star4half" title="Pretty good - 4.5 stars"> </label>
                                                        <input type="radio" id="star4" name="rating" value="4" />
                                                        <label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                                                        <input type="radio"  id="star3half" name="rating" value="3.5" />
                                                        <label class="half" for="star3half" title="Meh - 3.5 stars"></label>
                                                        <input type="radio"  id="star3" name="rating" value="3" />
                                                        <label class = "full" for="star3" title="Meh - 3 stars"></label>
                                                        <input type="radio"  id="star2half" name="rating" value="2.5" />
                                                        <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
                                                        <input type="radio" id="star2" name="rating" value="2" />
                                                        <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                                                        <input type="radio"  id="star1half" name="rating" value="1.5" />
                                                        <label class="half" for="star1half" title="Meh - 1.5 stars"></label>
                                                        <input type="radio"  id="star1" name="rating" value="1" />
                                                        <label class = "full" for="star1" title="bad time - 1 star"></label>
                                                        
                                                    </fieldset>
                                               @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    @if (@$data['url'])
                                        <div class="row  mt-20">
                                            <div class="col-md-12">
                                                <div class="text-right">
                                                    <button class="btn btn-primary">{{ _trans('common.Save') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
@endsection
@section('script')
    <script src="{{ asset('public/backend/js/pages/__award.js') }}"></script>
    <script src="{{ asset('public/frontend/assets/js/iziToast.js') }}"></script>
    <script src="{{ asset('public/backend/js/image_preview.js') }}"></script>
    <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('public/ckeditor/config.js') }}"></script>
    <script src="{{ asset('public/ckeditor/styles.js') }}"></script>
    <script src="{{ asset('public/ckeditor/build-config.js') }}"></script>
    <script src="{{ asset('public/backend/js/global_ckeditor.js') }}"></script>
@endsection
