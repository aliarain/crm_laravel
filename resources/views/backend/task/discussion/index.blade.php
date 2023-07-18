@if (@$data['is_table'])
<div class="table-content table-basic">
    <div class="card">
        <div class="card-body">
            <!-- toolbar table start -->
            <div
                class="table-toolbar d-flex flex-wrap gap-2 flex-xl-row justify-content-center justify-content-xxl-between align-content-center pb-3">
                <div class="align-self-center">
                    <div class="d-flex flex-wrap gap-2  flex-lg-row justify-content-center align-content-center">
                        <!-- show per page -->
                        <div class="align-self-center">
                            <label>
                                <span class="mr-8">{{ _trans('common.Show') }}</span>
                                <select class="form-select d-inline-block" id="entries"
                                    onchange="DiscussionTaskTable()">
                                    <option selected value="10">{{ _trans('10') }}</option>
                                    <option value="25">{{ _trans('25') }}</option>
                                    <option value="50">{{ _trans('50') }}</option>
                                    <option value="100">{{ _trans('100') }}</option>
                                </select>
                                <span class="ml-8">{{ _trans('common.Entries') }}</span>
                            </label>
                        </div>


                        <div class="align-self-center d-flex flex-wrap gap-2">
                            <!-- add btn -->
                            <div class="align-self-center">
                                @if (hasPermission('task_discussion_create'))
                                <a href="javascript:;"
                                    onclick="viewModal(`{{ route('task.discussion.create', 'task_id=' . $data['view']->id) }}`)"
                                    role="button" class="btn-add" data-bs-toggle="tooltip" data-bs-placement="right"
                                    data-bs-title="{{ _trans('common.Create') }}">
                                    <span><i class="fa-solid fa-plus"></i> </span>
                                    <span class="d-none d-xl-inline"> {{ _trans('common.Create') }}</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="align-self-center">
                            <button type="button" class="btn-daterange" id="daterange" data-bs-toggle="tooltip"
                                data-bs-placement="right" data-bs-title="{{ _trans('common.Date Range') }}">
                                <span class="icon"><i class="fa-solid fa-calendar-days"></i>
                                </span>
                                <span class="d-none d-xl-inline">{{ _trans('common.Date Range') }}</span>
                            </button>
                            <input type="hidden" id="daterange-input" onchange="DiscussionTaskTable()">
                        </div>

                        <!-- search -->
                        <div class="align-self-center">
                            <div class="search-box d-flex">
                                <input class="form-control" placeholder="{{ _trans('common.Search') }}" name="search"
                                    onkeyup="DiscussionTaskTable()" autocomplete="off">
                                <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </div>
                        </div>

                        <!-- dropdown action -->
                        <div class="align-self-center">
                            <div class="dropdown dropdown-action" data-bs-toggle="tooltip" data-bs-placement="right"
                                data-bs-title="{{ _trans('common.Action') }}">
                                <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            onclick="tableAction('delete', `{{ @$data['delete_url'] }}`)">
                                            <span class="icon mr-16"><i class="fa-solid fa-trash-can"></i></span>
                                            {{ _trans('common.Delete') }} <span class="count">(0)</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- export -->
                <div class="align-self-center">
                    <div class="d-flex justify-content-center justify-content-xl-end align-content-center">

                        <div class="dropdown dropdown-export" data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-title="{{ _trans('common.Export') }}">
                            <button type="button" class="btn-export" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>

                                <span class="d-none d-xl-inline">{{ _trans('common.Export') }}</span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#"
                                        onclick="selectElementContents(document.getElementById('table'))">
                                        <span class="icon mr-8"><i class="fa-solid fa-copy"></i>
                                        </span>
                                        {{ _trans('common.Copy') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="exportJSON">
                                        <span class="icon mr-8">
                                            <i class="fa-solid fa-code"></i>
                                        </span>
                                        {{ _trans('common.Json File') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" id="btnExcelExport" href="#" aria-current="true"><span
                                            class="icon mr-10"><i class="fa-solid fa-file-excel"></i></span>
                                        {{ _trans('common.Excel File') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="exportCSV">
                                        <span class="icon mr-14"><i class="fa-solid fa-file-csv"></i></span>
                                        {{ _trans('common.Csv File') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" id="exportPDF">
                                        <span class="icon mr-14"><i class="fa-solid fa-file-pdf"></i></span>
                                        {{ _trans('common.Pdf File') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- toolbar table end -->
            <!--  table start -->
            <div class="table-responsive">

                @include('backend.partials.table')

            </div>
            <!--  table end -->
        </div>
    </div>
</div>
@else
<div class="table-content table-basic">
    <div class="card">
        <div class="card-body">
            <div class="panel-body font-size-13">
                <h3 class="bold no-margin font-size-14">
                    {{ @$data['discussion']->subject }}
                </h3>
                <hr>
                <p>
                    <?= $data['discussion']->description ?>
                </p>

                <p class="font-size-13">{{ _trans('project.Posted on') }}
                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['discussion']->created_at) }}</p>
                <p class="no-margin font-italic"> {{ _trans('project.Posted by') }}
                    {{ $data['discussion']->user->name }}</p>
                <p>{{ _trans('project.Total Comments') }}:
                    {{ $data['discussion']->comments->count() }}</p>

                <hr>
                <div id="discussion-comments" class="tc-content jquery-comments">
                    <div class="">
                        <div class="tab-pane">
                            @if ($data['discussion']->comments->count() > 0)
                            @foreach ($data['discussion']->comments as $comment)
                            @if (is_null($comment->comment_id))
                            <div class="post">
                                <div class="user-block d-flex align-items-center">
                                    <img class="img-circle img-bordered-sm me-2"
                                        src="{{ uploaded_asset($comment->user->avatar_id) }}" alt="user image">
                                    <div class="d-flex flex-column">
                                        <span class="username">
                                            <a href="#">{{ $comment->user->name }}</a>

                                        </span>
                                        <span class="description">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',
                                            $comment->created_at)->diffForHumans() }}
                                        </span>
                                    </div>

                                </div>

                                <p>
                                    <?= $comment->description ?>
                                </p>
                                <p class="mb-0 d-flex justify-content-end">
                                    <a href="javascript:;" class="mr-2 float-right"
                                        onclick="showComments({{ $comment->id }})">
                                        {{ $comment->childComments->count() }}
                                        {{ _trans('common.Answers') }}
                                    </a>
                                </p>
                            </div>

                            @if (@$comment->childComments)
                            @foreach ($comment->childComments as $childComment)
                            <div class="post ml-70 dis-{{ $comment->id }} c-none clearfix">
                                <div class="user-block d-flex align-items-center">
                                    <img class="img-circle img-bordered-sm me-2"
                                        src="{{ uploaded_asset($childComment->user->avatar_id) }}" alt="user image">
                                    <div class="d-flex flex-column">
                                        <span class="username">
                                            <a href="#">{{ $childComment->user->name }}</a>

                                        </span>
                                        <span class="description">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',
                                            $childComment->created_at)->diffForHumans() }}
                                        </span>
                                    </div>

                                </div>

                                <p>
                                    <?= $childComment->description ?>
                                </p>
                            </div>
                            @endforeach
                            @if (hasPermission('task_discussion_comment'))
                            <div class="dis-{{ $comment->id }} c-none ml-70 mb-3">
                                <div class="form-group">
                                    <textarea id="comment-{{ $comment->id }}" class="form-control ck_editor ot_input"
                                        row="5" col="5"> </textarea>

                                </div>
                                <div class="error-message-{{ $comment->id }}">
                                </div>
                                <div class="form-group float-right d-flex justify-content-end mt-3">
                                    <button class="crm_theme_btn "
                                        onclick="commentReply( {{ $comment->id }}, `{{ route('task.discussion.comment', 'task_id=' . $data['view']->id) . '&discussion_id=' . $data['discussion']->id }}`)">{{
                                        _trans('common.Send') }}</button>
                                </div>
                            </div>
                            @endif
                            @endif
                            @endif
                            @endforeach

                            @endif

                        </div>


                    </div>
                    @if (hasPermission('task_discussion_comment'))
                    <input type="hidden" id="error_message_comment"
                        value="{{ _trans('message.Please enter a comment') }}">
                    <div class="form-group ">
                        <textarea id="comment-" class="form-control ck_editor mt-0 ot_input content" row="5"
                            col="5"> </textarea>
                    </div>
                    <div class="error-message-"></div>
                    <div class="form-group float-right d-flex justify-content-end mt-3">
                        <button class="crm_theme_btn "
                            onclick="commentReply(null, `{{ route('task.discussion.comment', 'task_id=' . $data['view']->id) . '&discussion_id=' . $data['discussion']->id }}`)">{{
                            _trans('common.Send') }}</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endif