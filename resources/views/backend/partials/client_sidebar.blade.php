<div class="col-lg-12 col-xl-3">
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="dashboard-side-card-img mr-2 text-center">
                <img class="profile-user-img img-fluid img-circle" src="{{ uploaded_asset($data['user']->avatar_id) }}"
                    alt="User profile picture">
            </div>
            <div class="media-body user-info-header mt-2">
                <div
                    class="d-flex justify-content-center text-muted">
                    <i class="fa fa-user"></i>
                    <p class="mb-0 text-break ml-2">
                        {{ $data['user']->name }}
                    </p>
                </div>
                <div
                    class="d-flex  justify-content-center text-muted mt-2">
                    <i class="fa fa-phone"></i>
                    <p class="mb-0 ml-2">
                        {{ $data['user']->phone }}
                    </p>
                </div>

                <div
                    class="d-flex  justify-content-center mt-2">
                        @if(@$data['user']->client->status_id == 1)
                            <button class="btn btn-secondary" onclick="GlobalApproveId('{{$data['user']->id}}',`dashboard/clients/approve/`,'Deactivate');">
                                    {{ _trans('common.Deactivate')}}
                        @else
                            <button class="btn btn-success" onclick="GlobalApproveId('{{$data['user']->id}}',`dashboard/clients/approve/`,'Approve');">
                                    {{ _trans('common.Approve')}}
                        @endif
                    </button>
                </div>

            </div>
        </div>
        <!-- /.card -->

        <div class="ltn__tab-menu-list">
            <div class="nav">
                <a class="show {{menu_active_by_route('clients.show')}}" href="{{ route('clients.show',encrypt($data['user']->id)) }}">{{ _trans('common.Dashboard') }} <i
                        class="fas fa-home"></i></a>
                <a class="{{menu_active_by_route('clients.report')}}" href="{{ route('clients.report',encrypt($data['user']->id)) }}">{{ _trans('common.Reports')}}
                    <i class="fas fa-file-alt"></i></a>
                <a class="{{menu_active_by_route('clients.sticker')}}" href="{{ route('clients.sticker',encrypt($data['user']->id)) }}">{{ _trans('common.Sticker List')}} <i
                        class="fas fa-id-card"></i></a>

                <a class="{{menu_active_by_route('report.driver.liveTracking') }}" href="{{ route('report.driver.liveTracking',encrypt($data['user']->id)) }}">{{ _trans('common.Live Tracking')}}
                    <i class="fas fa-search-location"></i></a>
               
                <a class="{{menu_active_by_route('client.payment')}}" href="{{ route('client.payment',encrypt($data['user']->id)) }}">{{ _trans('common.Payment')}}
                    <i class="fas fa-money-bill-wave"></i></a>
                <a class="{{menu_active_by_route('client.accountSetting')}}" href="{{ route('client.accountSetting',encrypt($data['user']->id)) }}">{{ _trans('common.Settings')}}<i
                        class="fas fa-user-cog"></i></a>

                <a href="{{ route('client.loginAsClient',encrypt($data['user']->id)) }}">
                    {{ _trans('common.Login as  Client')}}
                    <i class="fas fa-sign-out-alt"></i>
                </a>

            </div>
        </div>
    </div>
</div>
