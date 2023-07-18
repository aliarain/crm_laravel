
<div class="">
    <div class="col-lg-12 __superadmin_dashboard_box card-admin ">
        <div class="card-header-admin">
            <h4 class="card-title-admin">
                {{ __('Statistics') }}
            </h4>
            <p class="card-text font-small-2 mr-25 mb-0"> {{ __('Updated 1 month ago') }} </p>
        </div>
        <div class="card-body new-card-body">
            <div class="row">
                <div class="col-sm-4 col-xl-2 mb-2 mb-xl-0">
                    <div class="media">
                        <div class="media-aside mr-4 align-self-start">
                            <div class="b-avatar badge-light-primary rounded-circle">
                                <span class="b-avatar-custom">
                                    <img src="{{ url('public/images/location.png') }}" alt="">
                                </span>
                                <!---->
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"> {{ __('230k') }} </h4>
                            <p class="card-text font-small-3 mb-0"> {{ __('Total Companies') }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xl-2 mb-2 mb-xl-0">
                    <div class="media">
                        <div class="media-aside mr-4 align-self-start">
                            <div class="b-avatar badge-light-info rounded-circle">
                                <span class="b-avatar-custom">
                                    <img src="{{ url('public/images/salary.png') }}" alt="">
                                </span>
                                <!---->
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"> {{ __('230k') }} </h4>
                            <p class="card-text font-small-3 mb-0"> {{ __('Total Income') }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xl-2 mb-2 mb-xl-0">
                    <div class="media">
                        <div class="media-aside mr-4 align-self-start">
                            <div class="b-avatar badge-light-danger rounded-circle">
                                <span class="b-avatar-custom">
                                    <img src="{{ url('public/images/people.png') }}" alt="">
                                </span>
                                <!---->
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"> {{ __('230k') }} </h4>
                            <p class="card-text font-small-3 mb-0"> {{ __('Total Users') }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xl-2 mb-2 mb-xl-0">
                    <div class="media">
                        <div class="media-aside mr-4 align-self-start">
                            <div class="b-avatar badge-light-success rounded-circle">
                                <span class="b-avatar-custom">
                                    <img src="{{ url('public/images/building.png') }}" alt="">
                                </span>
                                <!---->
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"> {{ __('230k') }} </h4>
                            <p class="card-text font-small-3 mb-0"> {{ __('Visits') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xl-2 mb-2 mb-xl-0">
                    <div class="media">
                        <div class="media-aside mr-4 align-self-start">
                            <div class="b-avatar badge-light-purple rounded-circle">
                                <span class="b-avatar-custom">
                                    <img src="{{ url('public/images/add.png') }}" alt="">
                                </span>
                                <!---->
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"> {{ __('230k') }} </h4>
                            <p class="card-text font-small-3 mb-0"> {{ __('New Registration') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xl-2 mb-2 mb-xl-0">
                    <div class="media">
                        <div class="media-aside mr-4 align-self-start">
                            <div class="b-avatar badge-light-pink rounded-circle">
                                <span class="b-avatar-custom">
                                    <img src="{{ url('public/images/comment.png') }}" alt="">
                                </span>
                                <!---->
                            </div>
                        </div>
                        <div class="media-body my-auto">
                            <h4 class="font-weight-bolder mb-0"> {{ __('230k') }} </h4>
                            <p class="card-text font-small-3 mb-0"> {{ __('New Message') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card-admin">
                        <div class="card-header">
                            <h6 class="card-title-admin">
                                {{ __('Users') }}
                            </h6>
                            <h4 class="font-weight-bolder mb-1"> {{ __('230k') }} </h4>
                            <div id="barChart">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-admin">
                        <div class="card-header">
                            <h6 class="card-title-admin">
                                {{ __('Profits') }}
                            </h6>
                            <h4 class="font-weight-bolder mb-1"> {{ __('230k') }} </h4>
                            <div id="lineChart">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-admin">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-7">
                                    <h4 class="card-title-admin">
                                        {{ __('Earnings') }}
                                    </h4>
                                    <p class="card-text font-small-2 mr-25 mb-0"> {{ __('This Month') }} </p>
                                    <h5 class="mb-3"> {{ __('$4055.56') }} </h5>
                                    <p class="card-text text-muted font-small-2">
                                        <span class="font-weight-bolder">{{ __('68.2%') }}</span>
                                        <span> {{ __('more earnings than last month.') }}</span>
                                    </p>
                                </div>
                                <div class="col-lg-5">
                                    <div id="pieChart"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



        </div>
        <div class="col-lg-8">
            <div class="card-admin">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="revenue-border-right">
                            <div class="card-header">
                                <h4 class="card-title-admin">
                                    {{ __('Revenue Report') }}
                                </h4>
                                <div id="revenueChart"></div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-admin-header">
                            <div class="card-body">
                                <div class="dropdown text-center mb-4">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        {{ __('2022') }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#">{{ __('2021') }}</a>
                                        <a class="dropdown-item" href="#">{{ __('2020') }}</a>
                                        <a class="dropdown-item" href="#">{{ __('2019') }}</a>
                                    </div>
                                </div>
                                <h4 class="text-center"> {{ __('$25,852') }} </h4>
                                <div class="d-flex justify-content-center">
                                    <span class="font-weight-bolder mr-1">{{ __('Budget:') }}</span>
                                    <span>{{ __('56,800') }}</span>
                                </div>
                            </div>
                            <div id="dashDotted">

                            </div>
                            <div class="text-center">
                                <div class="btn btn-primary btn-sm ">
                                    {{ __('Increase Budget') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- start row -->
<div class="row">
    <div class="col-lg-4">
        <div class="card user-card">
            <div class="card-title">
                <h4 class="pt-4 pl-3">{{ __('Recent Users') }}</h4>
            </div>
            <div class="card-body">
                <div class="card-details">
                    <div class="row">
                        <div class="col-lg-8 mb-2">
                            <div class="d-flex user-details">
                                <div class="sl-circle">
                                    <div class="user-week">
                                        <p>{{ __('Sun') }}</p>

                                    </div>
                                    <div class="user-date">
                                        <h6>{{ __('18') }}</h6>

                                    </div>
                                </div>
                                <div class="card-user-info ml-4">
                                    <div class="card-user-name">
                                        <h6>{{ __('John Doe') }}</h6>

                                    </div>
                                    <div class="user-timing  d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p class="ml-2">{{ __('house 148 road 13b banani') }}</p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="user-status">
                                <ul>
                                    <li class="li-danger">
                                        <div class="d-flex icon-align align-items-center">
                                            <i class="fas fa-circle mr-2"></i>
                                            {{ __('Inactive') }}
                                        </div>
                                    </li>

                                </ul>
                                <div class="user-timing d-flex align-items-center">
                                    <i class="fas fa-clock"></i>
                                    <p class="ml-2 ">{{ __('08 Am - 12 Pm') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="d-flex user-details">
                                <div class="sl-circle green">
                                    <div class="user-week week-green">
                                        <p>{{ __('Sun') }}</p>

                                    </div>
                                    <div class="user-date date-green">
                                        <h6>{{ __('18') }}</h6>

                                    </div>
                                </div>
                                <div class="card-user-info ml-4">
                                    <div class="card-user-name">
                                        <h6>{{ __('John Doe') }}</h6>

                                    </div>
                                    <div class="user-timing  d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p class="ml-2">{{ __('house 148 road 13b banani') }}</p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="user-status">
                                <ul>
                                    <li class="li-success">
                                        <div class="d-flex icon-align align-items-center">
                                            <i class="fas fa-circle mr-2"></i>
                                            {{ __('Active') }}
                                        </div>
                                    </li>

                                </ul>
                                <div class="user-timing d-flex align-items-center">
                                    <i class="fas fa-clock"></i>
                                    <p class="ml-2 ">{{ __('08 Am - 12 Pm') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card user-card">
            <div class="card-title">
                <h4 class="pt-4 pl-3">{{ __('Recent Users') }}</h4>
            </div>
            <div class="card-body">
                <div class="card-details">
                    <div class="row">
                        <div class="col-lg-8 mb-2">
                            <div class="d-flex user-details">
                                <div class="sl-circle">
                                    <div class="user-week">
                                        <p>{{ __('Sun') }}</p>

                                    </div>
                                    <div class="user-date">
                                        <h6>{{ __('18') }}</h6>

                                    </div>
                                </div>
                                <div class="card-user-info ml-4">
                                    <div class="card-user-name">
                                        <h6>{{ __('John Doe') }}</h6>

                                    </div>
                                    <div class="user-timing  d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p class="ml-2">{{ __('house 148 road 13b banani') }}</p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="user-status">
                                <ul>
                                    <li class="li-danger">
                                        <div class="d-flex icon-align align-items-center">
                                            <i class="fas fa-circle mr-2"></i>
                                            {{ __('Inactive') }}
                                        </div>
                                    </li>

                                </ul>
                                <div class="user-timing d-flex align-items-center">
                                    <i class="fas fa-clock"></i>
                                    <p class="ml-2 ">{{ __('08 Am - 12 Pm') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="d-flex user-details">
                                <div class="sl-circle green">
                                    <div class="user-week week-green">
                                        <p>{{ __('Sun') }}</p>

                                    </div>
                                    <div class="user-date date-green">
                                        <h6>{{ __('18') }}</h6>

                                    </div>
                                </div>
                                <div class="card-user-info ml-4">
                                    <div class="card-user-name">
                                        <h6>{{ __('John Doe') }}</h6>

                                    </div>
                                    <div class="user-timing  d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p class="ml-2">{{ __('house 148 road 13b banani') }}</p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="user-status">
                                <ul>
                                    <li class="li-success">
                                        <div class="d-flex icon-align align-items-center">
                                            <i class="fas fa-circle mr-2"></i>
                                            {{ __('Active') }}
                                        </div>
                                    </li>

                                </ul>
                                <div class="user-timing d-flex align-items-center">
                                    <i class="fas fa-clock"></i>
                                    <p class="ml-2 ">{{ __('08 Am - 12 Pm') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card user-card">
            <div class="card-title">
                <h4 class="pt-4 pl-3">{{ __('Recent Users') }}</h4>
            </div>
            <div class="card-body">
                <div class="card-details">
                    <div class="row">
                        <div class="col-lg-8 mb-2">
                            <div class="d-flex user-details">
                                <div class="sl-circle">
                                    <div class="user-week">
                                        <p>{{ __('Sun') }}</p>

                                    </div>
                                    <div class="user-date">
                                        <h6>{{ __('18') }}</h6>

                                    </div>
                                </div>
                                <div class="card-user-info ml-4">
                                    <div class="card-user-name">
                                        <h6>{{ __('John Doe') }}</h6>

                                    </div>
                                    <div class="user-timing  d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p class="ml-2">{{ __('house 148 road 13b banani') }}</p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="user-status">
                                <ul>
                                    <li class="li-danger">
                                        <div class="d-flex icon-align align-items-center">
                                            <i class="fas fa-circle mr-2"></i>
                                            {{ __('Inactive') }}
                                        </div>
                                    </li>

                                </ul>
                                <div class="user-timing d-flex align-items-center">
                                    <i class="fas fa-clock"></i>
                                    <p class="ml-2 ">{{ __('08 Am - 12 Pm') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="d-flex user-details">
                                <div class="sl-circle green">
                                    <div class="user-week week-green">
                                        <p>{{ __('Sun') }}</p>

                                    </div>
                                    <div class="user-date date-green">
                                        <h6>{{ __('18') }}</h6>

                                    </div>
                                </div>
                                <div class="card-user-info ml-4">
                                    <div class="card-user-name">
                                        <h6>{{ __('John Doe') }}</h6>

                                    </div>
                                    <div class="user-timing  d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p class="ml-2">{{ __('house 148 road 13b banani') }}</p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="user-status">
                                <ul>
                                    <li class="li-success">
                                        <div class="d-flex icon-align align-items-center">
                                            <i class="fas fa-circle mr-2"></i>
                                            {{ __('Active') }}
                                        </div>
                                    </li>

                                </ul>
                                <div class="user-timing d-flex align-items-center">
                                    <i class="fas fa-clock"></i>
                                    <p class="ml-2 ">{{ __('08 Am - 12 Pm') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Recent Companis</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                {{ _trans('common.Name') }}
                                <th>{{ _trans('common.Email') }}</th>
                                <th>{{ _trans('common.Phone') }}</th>
                                <th>Company</th>
                                <th>{{ _trans('common.Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <a href="javascript: void(0);" class="text-body font-weight-semibold">
                                        John Deo
                                    </a>
                                    <span class="text-muted font-13">
                                        <i class="mdi mdi-map-marker-outline mr-1"></i>
                                        New York, USA
                                    </span>
                                </td>
                                <td>
                                    <a href="javascript: void(0);" class="text-body font-weight-semibold">
                                        x@gmail.com </a>
                                </td>
                                <td>
                                    <a href="javascript: void(0);" class="text-body font-weight-semibold">
                                        +123456789
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript: void(0);" class="text-body font-weight-semibold">
                                        XYZ Company
                                    </a>
                                </td>
                                <td>
                                    <h5 class="font-14 mb-1">
                                        <a href="javascript: void(0);" class="text-body font-weight-semibold">
                                            <span class="badge badge-danger">Inactive</span>
                                        </a>
                                    </h5>
                                </td>

                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <a href="javascript: void(0);" class="text-body font-weight-semibold">
                                        John Deo
                                    </a>
                                    <span class="text-muted font-13">
                                        <i class="mdi mdi-map-marker-outline mr-1"></i>
                                        New York, USA
                                    </span>
                                </td>
                                <td>
                                    <a href="javascript: void(0);" class="text-body font-weight-semibold"> x@gmail.com
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript: void(0);" class="text-body font-weight-semibold">
                                        +123456789
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript: void(0);" class="text-body font-weight-semibold">
                                        XYZ Company
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript: void(0);" class="text-body font-weight-semibold">
                                        <span class="badge badge-success">Active</span>
                                    </a>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div> --}}
</div>
<!-- end row -->
<script src="{{ asset('/') }}public/frontend/js/__apexChart.js"></script>
<script>
    // Bar Chart
    var optionBar = {
        series: [{
            data: [40, 50, 60, 30, 70]
        }],
        chart: {
            toolbar: {
                show: false,
            },
            type: "bar",
            height: 150
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%',
                distributed: true,
                startingShape: "rounded",
                endingShape: "rounded",
                colors: {
                    backgroundBarColors: ["#eee"],
                    backgroundBarOpacity: 1,
                    backgroundBarRadius: 7
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false,
        },
        grid: {
            yaxis: {
                lines: {
                    show: false
                }
            }
        },
        xaxis: {
            show: false,
            labels: {
                show: false,
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },

        },
        yaxis: {
            show: false,
            labels: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            crosshairs: {
                show: false,
            },
            tooltip: {
                enabled: false,
            },

        },
        colors: [

            "#fad388"

        ],

    };
    var chart = new ApexCharts(document.querySelector("#barChart"), optionBar);
    chart.render();
    // end
    // Line Chart
    var optionBar = {
        series: [{
            data: [40, 50, 60, 30, 70]
        }],
        stroke: {
            width: 4
        },
        chart: {
            toolbar: {
                show: false,
            },
            type: "line",
            height: 150
        },

        dataLabels: {
            enabled: false
        },
        legend: {
            show: false,
        },

        grid: {
            yaxis: {
                lines: {
                    show: false
                }
            }
        },
        xaxis: {
            show: false,
            labels: {
                show: false,
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },

        },
        yaxis: {
            show: false,
            labels: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            crosshairs: {
                show: false,
            },
            tooltip: {
                enabled: false,
            },

        },
        colors: [

            "#4CD6EB"

        ],
        markers: {
            size: 5
        }

    };
    var chart = new ApexCharts(document.querySelector("#lineChart"), optionBar);
    chart.render();
    // end

    var option = {
        series: [{

            data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
        }],
        chart: {
            toolbar: {
                show: false,
            },
            type: "bar",
            height: 350
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%',
                distributed: true,
                startingShape: "rounded",
                endingShape: "rounded",
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false,
            position: 'top'
        },
        grid: {
            yaxis: {
                lines: {
                    show: false
                }
            }
        },
        xaxis: {
            categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            labels: {
                show: true,
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },
        },
        yaxis: {
            show: true,
            labels: {
                show: true,
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            crosshairs: {
                show: false,
            },
            tooltip: {
                enabled: false,
            },

        },
        colors: [

            "#fad388"

        ],

    };
    var chart = new ApexCharts(document.querySelector("#revenueChart"), option);
    chart.render();

    // start
    var optionBar = {
        series: [{
                data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
            },
            {
                data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]
            }
        ],
        stroke: {
            width: 4
        },
        chart: {
            toolbar: {
                show: false,
            },
            type: "line",
            height: 150
        },

        dataLabels: {
            enabled: false
        },
        legend: {
            show: false,
        },

        grid: {
            yaxis: {
                lines: {
                    show: false
                }
            }
        },
        xaxis: {
            show: false,
            labels: {
                show: false,
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },

        },
        yaxis: {
            show: false,
            labels: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            crosshairs: {
                show: false,
            },
            tooltip: {
                enabled: false,
            },

        }

    };
    var chart = new ApexCharts(document.querySelector("#dashDotted"), optionBar);
    chart.render();
    // end 
    // end
    // start
    var options = {
        series: [44, 55, 41, 60],
        labels: ["Transport", "Shopping", "Energy use", "Food"],
        dataLabels: {
            enabled: false
        },
        chart: {
            type: 'donut',
            height: 150
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            fontSize: '12px',
                            color: '#000',
                            formatter: () => '55'
                        }
                    },
                    value: {
                        show: true,
                        fontSize: '10px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 400,
                        color: undefined,
                        offsetY: 16,
                        formatter: function(val) {
                            return val
                        }
                    },
                }
            }
        },
        tooltip: {
            style: {
                fontSize: '10px',
                fontFamily: undefined
            }
        },
        legend: {
            show: false,
        },
        fill: {
            colors: ['#28c76f', '#28c76f66', '#28c76f33']
        }
    };

    var chart = new ApexCharts(document.querySelector("#pieChart"), options);
    chart.render();
    // end
</script>
