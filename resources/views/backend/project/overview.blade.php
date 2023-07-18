<div class="panel_s ">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6 border-right project-overview-left mb-3">
                <div class="table-content table-basic">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="">
                                        <div
                                            class="d-flex align-items-center justify-content-between mb-4 flex-wrap parent-select2-width">
                                            <h3 class="project-info bold mb-0">
                                            </h3>
                                            <div
                                                class="d-flex align-items-center flex-wrap">
                                                @if (hasPermission('project_complete') && $data['view']->status_id != 27)
                                                    <div class="form-group mr-2 mb-2">
                                                        <a onclick="GlobalApprove(`{{ route('project.complete', '&project_id=' . $data['view']->id) }}`, `{{ _trans('project.Project Complete') }}` )"
                                                            href="javascript:;"
                                                            class="crm_theme_btn "> <i
                                                                class="fa fa-check mr-4"></i>{{ _trans('common.Complete')}}</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-content table-basic">
                                        <div class="table-responsive">
                                            <table class="table project-overview-table table-bordered ">
                                                <tbody class="tbody">
                                                    <tr class="project-overview-id">
                                                        <td class="fw-bold">
                                                            {{ _trans('project.Project') }}
                                                            #
                                                        </td>
                                                        <td> {{ @$data['view']->name }}
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="project-overview-customer">
                                                        <td class="fw-bold">{{ _trans('common.Client')}}
                                                        </td>
                                                        <td>
                                                            {{ @$data['view']->client->name }}
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="project-overview-billing">
                                                        <td class="fw-bold">{{ _trans('project.Billing Type')}}
                                                        </td>
                                                        <td>
                                                            {{ @$data['view']->billing_type == 'fixed' ? _trans('project.Fixed Rate') : _trans('project.Project Hours') }}
                                                        </td>
                                                    </tr>
                                                    @if (@$data['view']->billing_type == 'fixed')
                                                        <tr
                                                            class="project-overview-amount">
                                                            <td class="fw-bold">
                                                                {{ _trans('project.Total Rate') }}
                                                            </td>
                                                            <td> {{ showAmount(@$data['view']->total_rate) }}
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr
                                                            class="project-overview-amount">
                                                            <td class="fw-bold">
                                                                {{ _trans('project.Per Rate') }}
                                                            </td>
                                                            <td> {{ showAmount(@$data['view']->per_rate) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr class="project-overview-status">
                                                        <td class="fw-bold">
                                                            {{ _trans('common.Status') }}
                                                        </td>
                                                        <td>
                                                            <?= '<small class="badge badge-' . @$data['view']->status->class . '">' . @$data['view']->status->name . '</small>' ?>
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="project-overview-date-created">
                                                        <td class="fw-bold">
                                                            {{ _trans('common.Date Created') }}
                                                        </td>
                                                        <td>{{ showDate($data['view']->created_at) }}
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="project-overview-start-date">
                                                        <td class="fw-bold">
                                                            {{ _trans('common.Start Date') }}
                                                        </td>
                                                        <td>{{ showDate($data['view']->start_date) }}
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="project-overview-deadline">
                                                        <td class="fw-bold">
                                                            {{ _trans('project.Deadline') }}
                                                        </td>
                                                        <td>{{ showDate($data['view']->end_date) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">
                                                            {{ _trans('project.Project Progress') }}
                                                            {{ $data['view']->progress }}%
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="progress no-margin progress-bar-mini">
                                                                <div class="progress-bar progress-bar-success no-percent-text not-dynamic"
                                                                    role="progressbar"
                                                                    aria-valuenow="{{ $data['view']->progress }}"
                                                                    aria-valuemin="0"
                                                                    aria-valuemax="100"
                                                                    style="width: {{ $data['view']->progress }}%;"
                                                                    data-percent="{{ $data['view']->progress }}">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="project-overview-deadline">
                                                        <td class="fw-bold">
                                                            {{ _trans('performance.Goal') }}
                                                        </td>
                                                        <td>{{ @$data['view']->goal->subject ?? _trans('performance.No Goal Set') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div
                                class="tc-content project-overview-description font-size-13">
                                <hr class="hr-panel-heading project-area-separation">
                                <h4 class="bold font-size-16 project-info">
                                    {{ _trans('common.Description') }}
                                </h4>
                                <p><?= $data['view']->description ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 project-overview-right">
                <div class="card ot-card">
                    <div class="row">
                        <div class="col-md-6 project-progress-bars">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-box info-box-card ot-card p-4">
                                        
                                        <div class="info-box-content">
                                            <p class="mb-8">
                                                <i
                                                class="fa fa-check-circle"
                                                aria-hidden="true"></i>
                                                <span class="info-box-text">{{ _trans('project.Tasks')}}</span>
                                                <span class="info-box-number">
                                                    <span dir="ltr">{{ @$data['view']->tasks->where('status_id',27)->count() }} / {{ @$data['view']->tasks->count() }}</span>
                                                </span>
                                            </p>
                                            @php
                                                $total = @$data['view']->tasks->count();
                                                $completed = @$data['view']->tasks->where('status_id',27)->count();
                                                $progress = $total > 0 ? ($completed / $total) * 100 : 0;
                                            @endphp
                                            <div class="progress">
                                                <div class="progress-bar"
                                                    style="width: {{ $progress }}%">
                                                </div>
                                            </div>
                                            <p class="progress-description mt-8">
                                                {{ number_format($progress,2) }}% {{ _trans('common.Completed')}}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-md-6 project-progress-bars project-overview-days-left">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="info-box info-box-card ot-card p-4">
                                        
                                        <div class="info-box-content">
                                            <div>
                                                <p class="mb-8"> 
                                                    <i class="far fa-calendar-check"></i>
                                                    <span class="info-box-text">{{ _trans('project.Days Left') }}</span>
                                                    <span class="info-box-number">
                                                        <span dir="ltr">
                                                            {{ date_diff_days($data['view']->start_date) }}
                                                            /
                                                            {{ date_diff_days($data['view']->end_date) }}</span>
                                                    </span>
                                                </p>
                                              
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar"
                                                    style="width: {{ date_diff_days($data['view']->start_date, $data['view']->end_date) - date_diff_days($data['view']->end_date) }}%">
                                                </div>
                                            </div>
                                            <p class="progress-description mt-8">
                                                {{ date_diff_days($data['view']->start_date, $data['view']->end_date) - date_diff_days($data['view']->end_date) }}%
                                                {{ _trans('common.Increase')}}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="hr-panel-heading">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="badge badge-success mb-3">
                            <span
                                class="info-box-text">{{ _trans('project.Total Expenses') }}</span>
                            <span class="info-box-number">
                                {{ showAmount($data['view']->amount) }}
                            </span>
                        </div>
                        <div class="badge badge-warning mb-3">
                            <span
                                class="info-box-text">{{ _trans('project.Billed Expenses') }}</span>
                            <span class="info-box-number">
                                {{ showAmount($data['view']->paid) }}
                            </span>
                        </div>
                        <div class="badge badge-danger mb-3">
                            <span
                                class="info-box-text">{{ _trans('project.Unbilled Expenses') }}</span>
                            <span class="info-box-number">
                                {{ showAmount($data['view']->due) }}
                            </span>
                        </div>

                    </div>
                </div>
              

            </div>
        </div>
    </div>
</div>