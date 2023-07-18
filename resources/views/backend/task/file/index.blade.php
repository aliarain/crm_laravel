@if ($data['is_table'])
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
                                <select class="form-select d-inline-block" id="entries" onchange="fileTaskTable()">
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
                                @if (hasPermission('task_files_create'))
                                <a href="javascript:;"
                                    onclick="viewModal(`{{ route('task.file.create', 'task_id=' . $data['view']->id) }}`)"
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
                            <input type="hidden" id="daterange-input" onchange="fileTaskTable()">
                        </div>

                        <!-- search -->
                        <div class="align-self-center">
                            <div class="search-box d-flex">
                                <input class="form-control" placeholder="{{ _trans('common.Search') }}" name="search"
                                    onkeyup="fileTaskTable()" autocomplete="off">
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
                    {{ @$data['file']->subject }}
                </h3>
                <hr>
                <div class="row">
                    <div class="col-lg-3 col-sm-12 col-12">
                        <div class="single-post card ot-card">
                            <div class="post-thumbnail">
                                <img src="{{ uploaded_asset($data['file']->attachment) }}" alt="">
                            </div>
                            <div class="news-cnt mt-20">
                                <a href="{{ route('task.file.download', 'task_id=' . $data['view']->id) . '&file_id=' . $data['file']->id }}"
                                    class="link-black text-sm mt-20 text-primary"><i class="fa fa-download"></i>
                                    {{ @$data['file']->subject }} </a>
                                <br>
                                <span class="">{{ _trans('project.Posted on') }}
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data['file']->created_at)
                                    }}</span>
                                <br>
                                <span>{{ _trans('project.Posted by') }}
                                    {{ $data['file']->user->name }}</span>
                                <p class="dry-color">{{ _trans('project.Total Comments') }}:
                                    {{ $data['file']->comments->count() }}</p>

                            </div>
                        </div>
                    </div>
                </div>


                <hr>
                <div id="discussion-comments" class="tc-content jquery-comments">
                    <div class="">
                        <div class="tab-pane">
                            @if ($data['file']->comments->count() > 0)
                            @foreach ($data['file']->comments as $comment)
                            @if (is_null($comment->comment_id))
                            <div class="post">
                                <div class="user-block d-flex align-items-center ">
                                    <img class="img-circle img-bordered-sm me-2"
                                        src="{{ uploaded_asset($comment->user->avatar_id) }}" alt="user image">
                                    <div class="d-flex flex-column">
                                        <p class="username mb-0">
                                            <a href="javascript:;">{{ $comment->user->name }}</a>
                                        </p>
                                        <p class="description">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',
                                            $comment->created_at)->diffForHumans() }}
                                        </p>
                                    </div>

                                </div>

                                <p class="mb-2">
                                    <?= $comment->description ?>
                                </p>
                                <p class="d-flex justify-content-end">
                                    <a href="javascript:;" class=" text-right"
                                        onclick="showComments({{ $comment->id }})">
                                        {{ $comment->childComments->count() }}
                                        {{ _trans('common.Answers') }}
                                    </a>
                                </p>
                            </div>

                            @if (@$comment->childComments)
                            @foreach ($comment->childComments as $childComment)
                            <div class="post ml-5 dis-{{ $comment->id }} c-none clearfix ml-70">
                                <div class="user-block d-flex align-items-center">
                                    <img class="img-circle img-bordered-sm me-2"
                                        src="{{ uploaded_asset($childComment->user->avatar_id) }}" alt="user image">
                                    <div class="d-flex flex-column">
                                        <span class="username ">
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
                            @if (hasPermission('task_file_comment'))
                            <div class="dis-{{ $comment->id }} c-none ml-70 mb-20">
                                <div class="form-group">
                                    <textarea id="comment-{{ $comment->id }}" class="form-control ck_editor ot_input"
                                        row="5" col="5"> </textarea>

                                </div>
                                <div class="error-message-{{ $comment->id }}">
                                </div>
                                <div class="form-group float-right d-flex justify-content-end mt-3">
                                    <button class="crm_theme_btn "
                                        onclick="commentReply( {{ $comment->id }}, `{{ route('task.file.comment', 'task_id=' . $data['view']->id) . '&file_id=' . $data['file']->id }}` )">{{
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
                    @if (hasPermission('task_file_comment'))
                    <input type="hidden" id="error_message_comment"
                        value="{{ _trans('message.Please enter a comment') }}">
                    <div class="form-group">
                        <textarea id="comment-" class="form-control ck_editor ot_input" row="5" col="5"> </textarea>
                    </div>
                    <div class="error-message-"></div>
                    <div class="form-group float-right d-flex justify-content-end mt-3">
                        <button class="crm_theme_btn "
                            onclick="commentReply(null, `{{ route('task.file.comment', 'task_id=' . $data['view']->id) . '&file_id=' . $data['file']->id }}` )">{{
                            _trans('common.Send') }}</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endif