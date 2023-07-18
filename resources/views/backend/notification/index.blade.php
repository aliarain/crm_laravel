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
                <!-- toolbar table end -->
                <!--  table start -->
                <div class="table-responsive">
                    <table class="table table-bordered {{ @$data['class'] }}" id="table">
                        <thead class="thead">
                            <tr>
                                <th class="sorting_desc">{{ _trans('common.Message')}}</th>
                                <th class="sorting_desc">{{ _trans('common.Created_at')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (auth()->user()->unreadNotifications as $notification)
                                @php
                                    $sender = App\Models\User::find($notification->data['sender_id']);
                                @endphp
                                <tr class="border-bottom" data-notification_row_id="{{ $notification->id }}">
                                    <td width="50%">
                                        <a href="#" data-notification_id="{{ $notification->id }}"
                                            class="d-flex  align-items-center text-decoration-none text-secondary unread_notification_from_all">
                                            {{-- <i class="notification-icon fa fa-dropbox"> </i> --}}
                                            {{-- {{ uploaded_asset($sender->avatar_id) }} --}}
                                            <div class="notification-content">
                                                <h6
                                                    class="notification-title font-weight-bold">{{ @$notification->data['title'] }}</h6>
                                                <small class="muted">{!! @$notification->data['body'] !!}</small>
                                            </div>
                                        </a>

                                    </td>
                                    <td width="25%">
                                        <div class="notification-time text-left">
                                            {{ @$notification->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tbody>
                                    <tr class="odd">
                                        <td valign="top" colspan="4" class="dataTables_empty">
                                            <div class="no-data-found-wrapper text-center ">
                                                <img src="{{ asset('public/assets/images/noDataFound.png') }}" alt="" class="mb-primary" width="100">
                                                <p class="mb-0 text-center">{{ _trans('common.No data found !') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                <!--  table end -->
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('backend.partials.table_js')
@endsection
