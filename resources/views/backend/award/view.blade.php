@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    {!! breadcrumb([
        'title' => @$data['title'],
        route('admin.dashboard') => _trans('common.Dashboard'),
        '#' => @$data['title'],
    ]) !!}
        <div class="card ot-card">
                <div class="row">
                    <div class="col-lg-8 border-right project-overview-left mb-3">
                        @if (@$data['view']->attachment)
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table project-overview-table">
                                        <tbody>
                                            <div class="project-overview-id">
                                                <div class="mb-2">
                                                    <img class="img-responsive"
                                                        src="{{ uploaded_asset($data['view']->attachment) }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="tc-content project-overview-description font-size-13">
                            <p class="bold font-size-16 project-info">
                                {{ _trans('common.Description') }}
                            </p>
                            <p>
                                <?= $data['view']->description ?>
                            </p>
                           
                        </div>

                    </div>
                    <div class="col-lg-4 project-overview-right mb-3">
                        <div class="table-content table-basic">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table project-overview-table">
                                            <tbody class="tbody">
                                                <tr class="project-overview-status">
                                                    <td class="bold">{{ _trans('common.Employee') }}</td>
                                                    <td>{{ @$data['view']->user->name }}</td>
                                                </tr>
                                                <tr class="project-overview-status">
                                                    <td class="bold">{{ _trans('award.Award Type') }}</td>
                                                    <td>{{ @$data['view']->type->name }}</td>
                                                </tr>
    
                                                <tr class="project-overview-status">
                                                    <td class="bold">{{ _trans('award.Gift') }}
                                                    </td>
                                                    <td>
                                                        {{ @$data['view']->gift }}
                                                    </td>
                                                </tr>
                                                <tr class="project-overview-date-created">
                                                    <td class="bold">
                                                        {{ _trans('common.Date') }}
                                                    </td>
                                                    <td>{{ showDate($data['view']->created_at) }}</td>
                                                </tr>
                                                <tr class="project-overview-start-date">
                                                    <td class="bold">
                                                        {{ _trans('common.Amount') }}
                                                    </td>
                                                    <td>{{ showAmount($data['view']->amount) }}</td>
                                                </tr>
                                                <tr class="project-overview-status">
                                                    <td class="bold">{{ _trans('award.Gift Info') }}
                                                    </td>
                                                    <td>
                                                        {{ @$data['view']->gift_info }}
                                                    </td>
                                                </tr>
    
                                                <tr class="project-overview-status">
                                                    <td class="bold">{{ _trans('common.Added By') }}
                                                    </td>
                                                    <td>
                                                        {{ @$data['view']->added->name }}
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
        </div>
    <input type="text" hidden id="{{ @$data['url_id'] }}" value="{{ @$data['table_url'] }}">
@endsection
@section('script')
    <script src="{{ asset('public/backend/js/pages/__project.js') }}"></script>
    <script src="{{ asset('public/backend/js/pages/__task.js') }}"></script>
@endsection
