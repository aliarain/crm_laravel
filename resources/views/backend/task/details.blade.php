<div class="row">
    <div class="col-lg-8 border-right project-overview-left mb-3">
        <div class="table-content table-basic">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div
                                class="d-flex align-items-center justify-content-between mb-4 flex-wrap parent-select2-width">
                                <h3 class="project-info bold mb-0">
                                </h3>
                                <div class="d-flex align-items-center flex-wrap">
                                    @if (hasPermission('task_complete') && $data['view']->status_id != 27)
                                        <div class="form-group mr-2 mb-2">
                                            <a onclick="GlobalApprove(`{{ route('task.complete', '&task_id=' . $data['view']->id) }}`, `{{ _trans('task.Task Complete') }}` )"
                                                href="javascript:;" class="crm_theme_btn  "> <i
                                                    class="fa fa-check mr-4"></i>{{ _trans('common.Completed')}}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table project-overview-table">
                                <tbody>
                                    <div class="project-overview-id">
                                        <p> {{ @$data['view']->name }} </p>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="tc-content project-overview-description font-size-13">
                        <hr class="hr-panel-heading project-area-separation">
                        <p class="bold font-size-16 project-info">
                            {{ _trans('common.Description') }}
                        </p>
                        <p><?= $data['view']->description ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 project-overview-right mb-3">
        <div class="table-content table-basic">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered ">
                            <tbody class="tbody">
                                <tr class="project-overview-status">
                                    <td class="bold">{{ _trans('common.Status') }}
                                    </td>
                                    <td>
                                        <?= '<small class="badge badge-' . @$data['view']->status->class . '">' . @$data['view']->status->name . '</small>' ?>
                                    </td>
                                </tr>
                                <tr class="project-overview-status">
                                    <td class="bold">{{ _trans('common.Priority') }}
                                    </td>
                                    <td>
                                        <?= '<small class="badge badge-' . @$data['view']->priorityStatus->class . '">' . @$data['view']->priorityStatus->name . '</small>' ?>
                                    </td>
                                </tr>
                                <tr class="project-overview-date-created">
                                    <td class="bold">
                                        {{ _trans('common.Project') }}
                                    </td>
                                    <td>{{ @$data['view']->project->name }}</td>
                                </tr>
                                <tr class="project-overview-date-created">
                                    <td class="bold">
                                        {{ _trans('common.Date Created') }}
                                    </td>
                                    <td>{{ showDate($data['view']->created_at) }}</td>
                                </tr>
                                <tr class="project-overview-start-date">
                                    <td class="bold">
                                        {{ _trans('common.Start Date') }}
                                    </td>
                                    <td>{{ showDate($data['view']->start_date) }}</td>
                                </tr>
                                <tr class="project-overview-deadline">
                                    <td class="bold">{{ _trans('common.End Date') }}
                                    </td>
                                    <td>{{ showDate($data['view']->end_date) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ _trans('task.Task Progress') }}
                                        {{ $data['view']->progress }}%
                                    </td>
                                    <td>
                                        <div class="progress no-margin progress-bar-mini">
                                            <div class="progress-bar progress-bar-success no-percent-text not-dynamic"
                                                role="progressbar" aria-valuenow="{{ $data['view']->progress }}"
                                                aria-valuemin="0" aria-valuemax="100"
                                                style="width: {{ $data['view']->progress }}%;"
                                                data-percent="{{ $data['view']->progress }}">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
    
    
                                <tr class="project-overview-deadline">
                                    <td class="bold">{{ _trans('performance.Goal') }}
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
    </div>
</div>
