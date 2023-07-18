@if (@$data['dashboardMenus']['today'])
{{-- <h3 class="__dashboard_profile_title">Welcome to {{Auth::user()->company->name}} Dashboard</h3> --}}
<div class="row" id="profile_statistic">
    <div class="col-xl-12">
        <div class=" row gutters-10">
            @foreach ($data['dashboardMenus']['today'] as $key=>$item)

                <div class="col-xl-3 col-sm-6 col-12">

                    <div class="card-admin">
                        <div class="card-header spacing-header">
                            <div class="media flex-wrap justify-content-center justify-content-md-between justify-content-lg-between justify-content-xl-between ">
                                <div class="media-aside  align-self-start">
                                    <div class="b-avatar badge-light-primary rounded-circle">
                                        <span class="b-avatar-custom">
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
</div>
@endif


<div class="row ">
    <div class="col-xl-8 mb-primary mb-4">
        <div class="card card-with-shadow border-0 h-100">
            <div class="border-bottom bg-transparent p-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ __('Expense of') }} {{ date('M Y') }}</h5>
            </div>
            <div class="card-body min-height-300 ">
                <div class="mb-primary">
                    <div class="Chart">
                        <div class="">
                            <div id="lineChart" class="chartjs-render-monitor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-primary mb-4">

        <div class="card card-with-shadow border-0 h-100">
            <div class="border-bottom bg            -transparent p-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ __('Today Attendance Summary') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-primary">
                    <div class="Chart">
                        <div class="">
                            <div class="chart-container-1">
                                <div id="employeeActivityChart2" ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



