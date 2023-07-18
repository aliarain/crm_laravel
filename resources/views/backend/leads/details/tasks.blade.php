<div class="row">
    <div class="col-md-3">
        <div class="table-content table-basic">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="#" method="POST" id="form_{{ $data['type'] }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $data['id'] }}">
                        <input type="hidden" name="type" value="{{ $data['type'] }}">
                        <input type="hidden" name="index" value="{{ $data['index'] }}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Subject') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="subject" id="subject"
                                        class="form-control ot-form-control ot_input" placeholder="{{ _trans('common.Subject') }}"
                                        value="" required="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Message') }}<span
                                            class="text-danger">*</span></label>
                                    <textarea placeholder="{{ _trans('common.Your Message') }}" name="message" class="form-control ot-form-control ot_input"
                                        id="message"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ _trans('common.Status') }}</label>
                                    <select name="status" class="form-control ot-form-control select2">
                                        <option value="Not Started">{{ _trans('common.Not Started') }}</option>
                                        <option value="On Hold">{{ _trans('common.On Hold') }}</option>
                                        <option value="In Progress">{{ _trans('common.In Progress') }}</option>
                                        <option value="Completed">{{ _trans('common.Completed') }}</option>
                                        <option value="Cancelled">{{ _trans('common.Cancelled') }}</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <div class="error">{{ $errors->first('status') }}</div>
                                    @endif
                                </div>
                            </div>
                            @if (hasPermission('lead_detail_task'))
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
                    <table class="table table-bordered table_class">
                        <thead class="thead">
                            <tr>
                                <th>{{ _trans('common.SL') }}</th>
                                <th>{{ _trans('common.Subject') }}</th>
                                <th>{{ _trans('common.Message') }}</th>
                                <!-- '24 = Not Started , 25 = On Hold', '26 = In Progress', '27 = Completed', '28 = Cancelled' -->
                                <th>{{ _trans('common.Status') }}</th>
                                <th>{{ _trans('common.Author') }}</th>
                                <th>{{ _trans('common.Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            @foreach ($data['items'] as $key => $row)
                                <tr>
                                    <td>{{ @$key + 1 }}</td>
                                    <td class="text-normal">{{ @$row['subject'] }}</td>
                                    <td class="text-normal">{{ @$row['message'] }}</td>
                                    <td class="text-normal">{{ @$row['status'] }}</td>
                                    <td class="text-normal crm_link_active">{{ @$row['author'] }} </td>
                                    <td>{{ @$row['date'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
