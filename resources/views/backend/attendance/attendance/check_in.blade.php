@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
<div class="profile-content">
    <div class="d-flex flex-column flex-lg-row gap-4 gap-lg-0">
        @include('backend.partials.user_navbar')
          <div class="profile-body">
                <div
                  class="d-flex justify-content-between align-content-center mb-40"
                >
                  <h2 class="title mb-0">{{ _trans('attendance.Add Attendance') }}</h2>
                  <a
                    href="#"
                    role="button"
                    class="btn-back"
                    >{{ _trans('attendance.Attendance List') }}</a
                  >
                </div>
                <!-- profile body form start -->
                <div class="profile-body-form">
                  <div class="form-item pt-0">
                    <div
                      class="d-flex justify-content-between align-content-center"
                    >
                      <div class="align-self-center">
                        <h2 class="title">{{ _trans('attendance.Select Employee') }}</h2>
                        <p class="paragraph">{{ _trans('attendance.Robert Downey JR.') }}</p>
                      </div>
                      <div class="align-self-center">
                        <button
                          type="button"
                          class="btn-edit"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseEmployee"
                          aria-expanded="false"
                          aria-controls="collapseEmployee"
                        >
                          {{ _trans('attendance.Edit') }}
                        </button>
                      </div>
                    </div>
                    <div class="collapse" id="collapseEmployee">
                      <div class="form-box">
                        <form>
                          <input
                            name="employee"
                            type="text"
                            class="form-control"
                            placeholder="Robert Downey JR."
                          />
                          <button type="button" class="btn-update">
                            {{ _trans('attendance.Update') }}
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="form-item">
                    <div
                      class="d-flex justify-content-between align-content-center"
                    >
                      <div class="align-self-center">
                        <h2 class="title">{{ _trans('attendance.Date') }}</h2>
                        <p class="paragraph">{{ _trans('attendance.dd - mm - yyyy') }}</p>
                      </div>
                      <div class="align-self-center">
                        <button
                          type="button"
                          class="btn-edit"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseDate"
                          aria-expanded="false"
                          aria-controls="collapseDate"
                        >
                          {{ _trans("attendance.Edit") }}
                        </button>
                      </div>
                    </div>
                    <div class="collapse" id="collapseDate">
                      <div class="form-box">
                        <form>
                          <input name="date" type="date" class="form-control" />
                          <button type="button" class="btn-update">
                            {{ _trans('attendance.Update') }}
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- profile body form end -->
                <h2 class="title mt-40">{{ _trans('attendance.Check In') }}</h2>
                <!-- profile body form start -->
                <div class="profile-body-form">
                  <div class="form-item pt-0">
                    <div
                      class="d-flex justify-content-between align-content-center"
                    >
                      <div class="align-self-center">
                        <h2 class="title position-relative">
                          {{ _trans('attendance.Time') }}
                          <span class="icon-required position-absolute top-0"
                            ><i class="fa-solid fa-star-of-life"></i
                          ></span>
                        </h2>
                        <p class="paragraph">{{ _trans('attendance.23:29:30') }}</p>
                      </div>
                      <div class="align-self-center">
                        <button
                          type="button"
                          class="btn-edit"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseCheckInTime"
                          aria-expanded="false"
                          aria-controls="collapseCheckInTime"
                        >
                          {{ _trans('attendance.Edit') }}
                        </button>
                      </div>
                    </div>
                    <div class="collapse" id="collapseCheckInTime">
                      <div class="form-box">
                        <form>
                          <input
                            name="check_in_time"
                            type="time"
                            class="form-control"
                            placeholder="Robert Downey JR."
                          />
                          <button type="button" class="btn-update">
                            {{ _trans('attendance.Update') }}
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="form-item">
                    <div
                      class="d-flex justify-content-between align-content-center"
                    >
                      <div class="align-self-center">
                        <h2 class="title position-relative">
                          {{ _trans('attendance.Location') }}
                          <span class="icon-required position-absolute top-0"
                            ><i class="fa-solid fa-star-of-life"></i
                          ></span>
                        </h2>
                        <p class="paragraph">{{ _trans('attendance.Check In location') }}</p>
                      </div>
                      <div class="align-self-center">
                        <button
                          type="button"
                          class="btn-edit"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseCheckInLocation"
                          aria-expanded="false"
                          aria-controls="collapseCheckInLocation"
                        >
                          {{ _trans('attendance.Edit') }}
                        </button>
                      </div>
                    </div>
                    <div class="collapse" id="collapseCheckInLocation">
                      <div class="form-box">
                        <form>
                          <textarea
                            name="check_in_location"
                            class="form-control unset-size"
                            rows="3"
                          ></textarea>

                          <button type="button" class="btn-update">
                            {{ _trans('attendance.Update') }}
                          </button>
                        </form>
                      </div>
                    </div>
                </div>
                <!-- profile body form end -->
                <h2 class="title mt-40">{{ _trans('attendance.Check Out') }}</h2>
                <!-- profile body form start -->
                <div class="profile-body-form">
                  <div class="form-item pt-0">
                    <div
                      class="d-flex justify-content-between align-content-center"
                    >
                      <div class="align-self-center">
                        <h2 class="title position-relative">
                          {{ _trans('attendance.Time') }}
                          <span class="icon-required position-absolute top-0"
                            ><i class="fa-solid fa-star-of-life"></i
                          ></span>
                        </h2>
                        <p class="paragraph">{{ _trans('attendance.23:29:30') }}</p>
                      </div>
                      <div class="align-self-center">
                        <button
                          type="button"
                          class="btn-edit"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseCheckOutTime"
                          aria-expanded="false"
                          aria-controls="collapseCheckOutTime"
                        >
                          {{ _trans('attendance.Edit') }}
                        </button>
                      </div>
                    </div>
                    <div class="collapse" id="collapseCheckOutTime">
                      <div class="form-box">
                        <form>
                          <input
                            name="check_out_time"
                            type="time"
                            class="form-control"
                            placeholder="Robert Downey JR."
                          />
                          <button type="button" class="btn-update">
                            {{ _trans('attendance.Update') }}
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="form-item">
                    <div
                      class="d-flex justify-content-between align-content-center"
                    >
                      <div class="align-self-center">
                        <h2 class="title position-relative">
                          {{ _trans('attendance.Location') }}
                          <span class="icon-required position-absolute top-0"
                            ><i class="fa-solid fa-star-of-life"></i
                          ></span>
                        </h2>
                        <p class="paragraph">{{ _trans('attendance.Check Out location') }}</p>
                      </div>
                      <div class="align-self-center">
                        <button
                          type="button"
                          class="btn-edit"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapseCheckOutLocation"
                          aria-expanded="false"
                          aria-controls="collapseCheckOutLocation"
                        >
                          {{ _trans('attendance.Edit') }}
                        </button>
                      </div>
                    </div>
                    <div class="collapse" id="collapseCheckOutLocation">
                      <div class="form-box">
                        <form>
                          <textarea
                            name="check_out_location"
                            class="form-control unset-size"
                            rows="3"
                          ></textarea>

                          <button type="button" class="btn-update">
                            {{ _trans('attendance.Update') }}
                          </button>
                        </form>
                      </div>
                    </div>
                </div>
                <!-- profile body form end -->
            </div>
        </div>
    </div>
    <input type="hidden" id="get_user_url" value="{{ route('user.getUser') }}">
@endsection
@section('script')
    <script src="{{ url('public/backend/js/__location_get.js') }}"></script>
@endsection
