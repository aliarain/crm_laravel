<div class="row">
    <div class="col-md-3">
        <div class="table-content table-basic">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="#" method="POST" id="form_{{ $data['type'] }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $data['id'] }}">
                        <input type="hidden" name="type" value="{{ $data['type'] }}">
                        <input type="hidden" name="index" value="{{ $data['index'] }}">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Title') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title"
                                        class="form-control ot-form-control ot_input"
                                        placeholder="{{ _trans('common.title') }}" value="" required="">
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="mb-10">{{ _trans('common.File') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="ot_fileUploader left-side mb-3">
                                        <input class="form-control" type="text"
                                            placeholder="{{ _trans('common.Upload File') }}" name="backend_image"
                                            readonly="" id="placeholder">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="btn btn-lg ot-btn-primary"
                                                for="fileBrouse">{{ _trans('common.Browse') }}</label>
                                            <input type="file" class="d-none form-control" name="file"
                                                id="fileBrouse">
                                        </button>
                                    </div>

                                    @if ($errors->has('file'))
                                        <span class="text-danger">{{ $errors->first('file') }}</span>
                                    @endif
                                </div>
                            </div>
                            @if (hasPermission('lead_detail_attachment'))
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button type="button" class="crm_theme_btn "
                                        onclick="leadDetailsStore('{{ $data['type'] }}')">{{ _trans('common.Create') }}</button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                {{-- card body --}}
            </div>
            {{-- card --}}
        </div>
    </div>
    <div class="col-md-9">

        <div class="table-content table-basic">
            <div class="card">
                <div class="card-body">

                    <table class="table table-responsive table_class">
                        <thead class="thead">
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>File Size</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            @foreach ($data['items'] as $key => $attachment)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-normal">{{ $attachment['title'] }}</td>
                                    <td><span class="type">
                                            @php echo getFileElement(url( $attachment['path'])); @endphp </span></td>

                                    <td>{{ @$attachment['size'] }}</td>
                                    <td>{{ @$attachment['author'] }}</td>
                                    <td class="text-normal">
                                        {{ @$attachment['date'] }}
                                    </td>
                                    <td>
                                        {{-- <a href="javascript:void(0)" class="btn btn-success btn-sm"
                                            onclick="downloadFile('{{url( $attachment['path']) }}')">
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                        </a> --}}
                                        <button class="theme_dropdown btn btn-info btn-sm"
                                            onclick="downloadOrShowFile('{{ url($attachment['path']) }}')">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>

                                        <button class="theme_dropdown btn btn-success btn-sm"
                                            onclick="downloadFile('{{ url($attachment['path']) }}')">
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
