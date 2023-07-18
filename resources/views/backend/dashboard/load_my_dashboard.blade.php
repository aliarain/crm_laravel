<div class="row">
    <!-- Dashboard Summery Card Start -->
    @foreach ($data['dashboardMenus']['today'] as $key => $item)
        <!-- Single Dashboard Summery Card Start -->
        <div class="col-12 col-md-6 col-lg-3 col-xl-3 pb-24">
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

    <div class="col-md-6 pb-30">
        <div class="card ot-card">
            <div class="card-header d-flex flex-row justify-content-between align-items-baseline">
                <div class="card-title">
                    <h3>{{ _trans('dashboard.Project Status')}}</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="project_summary_chart" class="custom-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 pb-30">
        <div class="card ot-card">
            <div class="card-header d-flex flex-row justify-content-between align-items-baseline">
                <div class="card-title">
                    <h3>{{ _trans('dashboard.Task Status') }}</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="task_summary_chart" class="custom-chart"></div>
            </div>
        </div>
    </div>
  
 
</div>
