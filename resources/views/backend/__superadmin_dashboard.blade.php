@extends('backend.layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="content-wrapper dashboard-wrapper mt-30" id="__index_ltn">
        <section class="content p-0 mt-4">
            <div class="col-12 col-md-8 col-lg-8 col-lg-8 welcome_title">
                <h3>{{ _trans('dashboard.Welcome to') }} {{ Auth::user()->name }}</h3>
            </div>
            <div class="col-xl-12">
                <div class=" row gutters-10">
                    @foreach ($data['dashboardMenus']['current_month'] as $key=>$item)
                        <div class="col-xl-3 col-sm-6 col-12">
        
                            <div class="card-admin">
                                <div class="card-header spacing-header">
                                    <div class="media flex-wrap justify-content-center justify-content-md-between justify-content-lg-between justify-content-xl-between ">
                                        <div class="media-aside  align-self-start">
                                            <div class="b-avatar badge-light-primary rounded-circle">
                                                <span class="b-avatar-custom" >
                                                    <img src="{{ url($item['image']) }}" alt="">
                                                </span>
                                                <!---->
                                            </div>
                                        </div>
                                        <div class="media-body my-auto text-center text-md-right text-lg-right text-xl-right text-align-center">
                                            <h4 class="font-weight-bolder mb-1" id="totalSaleAmount">
                                                {{ $item['number'] }}</h4>
                                            <p class="card-text font-small-3 mb-0"> {{ @$item['title'] }} </p>
                                        </div>
                                    </div>
        
                                </div>
        
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </section>
    </div>
    <input type="hidden" id="user_slug" value="{{ auth()->user()->role->slug }}">
    <input type="hidden" id="profileWiseDashboard" value="{{ route('dashboard.profileWiseDashboard') }}">
@endsection
@section('script')
@endsection
