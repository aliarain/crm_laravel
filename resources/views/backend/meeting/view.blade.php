@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
    <div class="table-content table-basic">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel_s pt-16">
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-6 border-right project-overview-left">
                                <div class="table-content table-basic">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-content table-basic">
                                                        <div class="table-responsive">
                                                            <table class="table project-overview-table">
                                                                <tbody class="tbody">
                                                                    <tr
                                                                        class="project-overview-customer">
                                                                        <td class="fw-bold">
                                                                            {{ _trans('common.Title') }}
                                                                        </td>
                                                                        <td>
                                                                            {{ @$data['view']->title }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="project-overview-status">
                                                                        <td class="fw-bold">
                                                                            {{ _trans('common.Status') }}
                                                                        </td>
                                                                        <td>
                                                                            <?= '<small class="badge badge-' . @$data['view']->status->class . '">' . @$data['view']->status->name . '</small>' ?>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr
                                                                        class="project-overview-start-date">
                                                                        <td class="fw-bold">
                                                                            {{ _trans('common.Date') }}
                                                                        </td>
                                                                        <td>{{ showDate($data['view']->date) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr
                                                                        class="project-overview-deadline">
                                                                        <td class="fw-bold">
                                                                            {{ _trans('project.Start Time') }}
                                                                        </td>
                                                                        <td>{{ showTime($data['view']->start_at?? "00:00:00") }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr
                                                                        class="project-overview-deadline">
                                                                        <td class="fw-bold">
                                                                            {{ _trans('project.End Time') }}
                                                                        </td>
                                                                        <td>{{ showTime($data['view']->end_at ?? "00:00:00") }}
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
                            <div class="col-md-6 project-overview-right">
                                <div class="card ot-card">
                                    <div class="row">
                                        <div class="col-md-12 project-progress-bars">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="info-box info-box-card ot-card p-4">
                                                        
                                                        <div class="info-box-content">
                                                            <p class="mb-8">
                                                                <i
                                                                class="fa fa-users"
                                                                aria-hidden="true"></i>
                                                                <span class="info-box-text">{{ _trans('common.Participants') }}</span>
                                                            </p>
                                                            <p class="progress-description mt-8">
                                                                {!!  teams($data['view']->meetingParticipants)  !!}
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 project-progress-bars project-overview-days-left">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="info-box info-box-card ot-card p-4">
                                                        
                                                        <div class="info-box-content">
                                                            <div>
                                                                <p class="mb-8"> 
                                                                    <i class="far fa-file"></i>
                                                                    <span class="info-box-text">{{ _trans('common.Attachment') }}</span>
                                                                </p>
                                                              
                                                            </div>
                                                            <p class="progress-description mt-8">
                                                                {!!  attachment($data['view']->attachment_file_id)  !!}
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              

                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
