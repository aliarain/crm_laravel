<div class="row">

    @if (@$data['dashboardMenus']['crm'])
    <!-- Dashboard Summery Card Start -->
    @foreach ($data['dashboardMenus']['crm'] as $key => $item)
    <!-- Single Dashboard Summery Card Start -->
    <div class="col-12 col-sm-6 col-xs-6 col-md-4 col-lg-3 col-xl-3 pb-24 pl-12 pr-12">
        <div class="card summery-card ot-card h-100">
            <div class="card-heading d-flex align-items-center">
                <div class="card-icon {{ @$item['color_class'] }} dashboard-card-icon">
                    <i class="{{ $item['image'] }}"></i>
                </div>
                <div class="card-content">

                    <h4> {{ @$item['title'] }}</h4>
                    <h1> {{ @$item['number'] }}</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- Single Dashboard Summery Card End -->
    @endforeach
    @endif
    <div class="col-lg-6 col-xl-6 col-xxl-4 pl-12 pr-12">
        <div class="card statistic-card ot-card crm_height_full mb-6 card_scroll_active">
            <div class="card-header d-flex flex-row justify-content-between align-items-baseline">
                <div class="card-title mb-20">
                    <h3>{{ _trans('dashboard.Summary') }}</h3>
                    <input type="hidden" name="leadSummaryCategoryWise" id="lead_summary_category_wise_url"
                        value="{{ route('ajax.lead-summary-category-wise') }}">
                </div>
            </div>
            <div class="card-body pt-0" id="lead_summary_category_wise_body">
                @foreach ($data['dashboardMenus']['today'] as $key => $item)
                <!-- single_Lead  -->
                <div class="d-flex align-items-center single_todo">
                    <div class="symbol symbol-50px">
                        <span class="symbol-label {{ @$item['color_class'] }}">
                            <i class="{{ @$item['image'] }} fs-5"></i>
                        </span>
                    </div>
                    <!--begin::Description-->
                    <div class="flex-grow-1">
                        <a href="#" class="todo_title">{{ @$item['title'] }}</a>
                        {{-- <p class="todo_para_text ">Mark, Rowling, Esther</p> --}}
                    </div>
                    <!--end::Description-->
                    <span class="badge badge-light-success fs-8 fw-bold">+{{ @$item['number'] }}</span>
                </div>
                <!-- single_Lead  -->
                @endforeach
            </div>
        </div>
    </div>

    <!-- new style card  -->
    <div class="col-lg-6 col-xl-6 col-xxl-4  pl-12 pr-12">
        <div class="card statistic-card ot-card crm_height_full mb-6 card_scroll_active">
            <div class="card-header d-flex flex-row justify-content-between align-items-baseline">
                <div class="card-title mb-20">
                    <h3>{{ _trans('dashboard.Todo Task') }}</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                @if(count($data['dashboardMenus']['task'])>0)
                @foreach ($data['dashboardMenus']['task'] as $key => $item)
                <!-- single_todo  -->
                <div class="d-flex align-items-center single_todo">
                    <!--begin::Bullet-->
                    <span class="bullet bullet-vertical h-40px bg-success"></span>
                    <!--end::Bullet-->

                    <!--begin::Checkbox-->
                    <div class="form-check-custom form-check-solid">
                        <div class="check-box">
                            <div class="form-check">
                                <input class="form-check-input checked" type="checkbox"
                                    onclick="updateTaskStatus({{ @$item['id'] }})">
                            </div>
                        </div>
                    </div>
                    <!--end::Checkbox-->

                    <!--begin::Description-->
                    <div class="flex-grow-1">
                        <a href="#" class="todo_title">{{ @$item['name'] }}</a>
                        <p class="todo_para_text ">{{ _trans('dashboard.Due In') }} {{ @$item['start_date'] }}</p>
                    </div>
                    <!--end::Description-->

                    <span class="badge badge-light-warning fs-8 fw-bold">{{ _trans('dashboard.New') }}</span>
                </div>
                <!-- single_todo  -->
                @endforeach
                @else
                @include('backend.dashboard.empty')
                @endif

            </div>
        </div>
    </div>

    <div class="col-lg-6 col-xl-6 col-xxl-4 pl-12 pr-12">
        <div class="card statistic-card ot-card crm_height_full mb-6 card_scroll_active">
            <div class="card-header d-flex flex-row justify-content-between align-items-baseline">
                <div class="card-title mb-20">
                    <h3 class="mb-0">{{ _trans('dashboard.Lead Channels') }}</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                @if(count($data['dashboardMenus']['channels'])>0)
                @foreach ($data['dashboardMenus']['channels'] as $key => $item)
                {{-- channels --}}
                <!-- single_Lead  -->
                <div class="d-flex align-items-center single_todo">
                    <div class="symbol symbol-50px">
                        <span class="symbol-label dashboard-first-character">
                            {{ mb_substr( @$item['title'], 0, 1, "UTF-8"); }} 
                        </span>
                    </div>
                    <!--begin::Description-->
                    <div class="flex-grow-1">
                        <a href="#" class="todo_title">{{ @$item['title'] }}</a>
                        <p class="todo_para_text ">{{ _trans('dashboard.Community') }}</p>
                    </div>
                    <!--end::Description-->

                </div>
                <!-- single_Lead  -->
                @endforeach

                @else

                @include('backend.dashboard.empty')
                @endif


            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 pl-12 pr-12">
        <div class="card statistic-card ot-card crm_height_full mb-6">

            <div class="card-header d-flex flex-row justify-content-between align-items-baseline">
                <div class="card-title">
                    <h3 class="mb-0">{{ _trans('dashboard.Projects') }}</h3>

                </div>
            </div>
            <div class="card-body pt-0 table-content table-basic">
                <div class="card-body pt-0 table-content table-basic">
                    <div class="table_scrollbar_active">
                        @if(count($data['dashboardMenus']['projects'])>0)
                        <table class="table table-bordered {{ @$data['class'] }}" id="table">
                            <thead class="thead">
                                <tr>
                                    <th>{{ _trans('dashboard.ITEM') }}</th>
                                    <th>{{ _trans('dashboard.PHONE') }}</th>
                                    <th>{{ _trans('dashboard.STATUS') }}</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @foreach ($data['dashboardMenus']['projects'] as $key => $item)
                                @php $path = json_decode($item->attachments); @endphp


                                <tr>
                                    <td>
                                        <div class="client_box d-flex align-items-center gap-3">
                                            <div class="thumb flex-shrink-0 mh_40 mw_40">
                                                <img class="img-fluid rounded_6px" src="{{ url(@$path[0]->path) }}"
                                                    alt="">
                                            </div>
                                            <div class="client_content">
                                                <a class="client_title" href="#">{{ @$item['title'] }}</a>
                                                <p class="client_para_text">{{ @$item['company'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ @$item['phone'] }}
                                    </td>
                                    <td>
                                        <small class="badge badge-success">{{ @$item->lead_status->title }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>


                        @else

                        @include('backend.dashboard.empty')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 pl-12 pr-12">
        <div class="card statistic-card ot-card crm_height_full mb-6">
            <div class="card-header d-flex flex-row justify-content-between align-items-baseline">
                <div class="card-title">
                    <h3 class="mb-0">{{ _trans('dashboard.Clients') }}</h3>

                </div>
            </div>
            <div class="card-body pt-0 table-content table-basic">
                <div class="table_scrollbar_active">
                    @if(count($data['dashboardMenus']['clients'])>0)
                    <table class="table table-bordered {{ @$data['class'] }}" id="table">
                        <thead class="thead">
                            <tr>
                                <th>{{ _trans('dashboard.Authors') }}</th>
                                <th>{{ _trans('dashboard.Company') }}</th>
                                <th>{{ _trans('dashboard.Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            @foreach ($data['dashboardMenus']['clients'] as $key => $item)
                            <tr>
                                <td>
                                    <div class="client_box d-flex align-items-center gap-3">
                                        <div class="thumb flex-shrink-0 mh_40 mw_40">
                                            <img class="img-fluid rounded_6px"
                                                src=" {{ uploaded_asset($item->avater_id) }} " alt="">
                                        </div>
                                        <div class="client_content">
                                            <a class="client_title" href="#"> {{ @$item->name }} </a>
                                            <p class="client_para_text">{{ @$item->city }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client_box d-flex align-items-center gap-3">
                                        <div class="client_content">
                                            <a class="client_title" href="#">{{ @$item->company->company_name }}</a>
                                            <p class="client_para_text">{{ @$item->website }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ @$item->created_at }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else

                    @include('backend.dashboard.empty')
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-6 col-xxl-4">
        <div class="card statistic-card ot-card crm_height_full mb-6 card_scroll_active">
            <div class="card-header d-flex flex-row justify-content-between align-items-baseline mb-20">
                <div class="card-title ">
                    <h3 class="mb-0">{{ _trans('dashboard.Top Lead Categories') }}</h3>
                </div>
            </div>
            <div class="card-body pt-0 table-content table-basic">
                <div class="card-body pt-0">
                    <!-- tab_content_willSHow_here  -->
                    <!-- content  -->
                    @if(count(@$data['dashboardMenus']['categories'])>0)
                    <!-- single_Lead  -->
                    @foreach ($data['dashboardMenus']['categories'] as $key => $item)
                    <div class="d-flex align-items-center single_todo">
                        <div class="symbol symbol-50px">
                            <span class="symbol-label">
                                <img src="{{ url('public/backend/img/dashboard/plurk.svg') }}"
                                class="h-50 align-self-center" alt="">
                            </span>
                        </div>
                        <!--begin::Description-->
                        <div class="flex-grow-1">
                            <a href="#" class="todo_title"> {{ @$item->title }} </a>
                        </div>
                        <!--end::Description-->
                    </div>
                    @endforeach
                    @else

                    @include('backend.dashboard.empty')
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-12 col-xxl-8 col-md-12 pl-12 pr-12">
        <div class="card statistic-card ot-card crm_height_full mb-6">
            <div class="card-header d-flex flex-row justify-content-between align-items-baseline">
                <div class="card-title">
                    <h3 class="mb-0">{{ _trans('dashboard.Leads') }}</h3>

                </div>
            </div>
            <div class="card-body pt-0 table-content table-basic table_scrollbar_active">
                @if(count(@$data['dashboardMenus']['leads'])>0)
                <table class="table table-bordered {{ @$data['class'] }}" id="table">
                    <thead class="thead d-none">
                        <tr>
                            <th>{{ _trans('dashboard.ITEM') }}</th>
                            <th>{{ _trans('dashboard.Technology') }}</th>
                            <th>{{ _trans('dashboard.STATUS') }}</th>
                            <th>{{ _trans('dashboard.VIEW') }}</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach ($data['dashboardMenus']['leads'] as $key => $item)

                        <tr>
                            <td>
                                <div class="client_box d-flex align-items-center gap-3">
                                    <div class="client_content">
                                        <a class="client_title" href="#"> {{ @$item->source->title }} </a>
                                        <p class="client_para_text">{{ _trans('dashboard.Created on') }}
                                            {{ @$item->source->created_at->format('Y-m-d') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="client_box d-flex align-items-center gap-3">
                                    <div class="client_content">
                                        <a class="client_title" href="#">{{ @$item->type->title }}</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="client_box ">
                                    <div class="client_content">
                                        <small class="badge badge-success mb-6">{{ @$item->lead_status->title }}</small>

                                    </div>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else

                @include('backend.dashboard.empty')
                @endif
            </div>
        </div>
    </div>
</div>


<script>
    if ($(".card_scroll_active .card-body").length > 0) {
        $(".card_scroll_active .card-body").mCustomScrollbar({
            setTop: 0,
            autoHideScrollbar: true,
            // mouseWheel: true,
        });
    }
    $(".table_scrollbar_active").mCustomScrollbar({
        setTop: 0,
        autoHideScrollbar: true,
        mouseWheel: true,
        axis: "x"
    });

    function updateTaskStatus(id) {
    // if id is integer then convert to array 
    if (typeof id == 'number') {
        id = [id];
    }
        $.ajax({
            url: "{{ route('task.update.status') }}",
            type: "POST",

            data: {
                _token: "{{ csrf_token() }}",
                ids: id,
                action: 'complete',
            },
            success: function(response) {
                console.log(response);
                    toastr.success(response.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
            },
            error: function(response) {
                console.log(response);
                toastr.error(response.message, 'error');
            }
        });

    }
</script>