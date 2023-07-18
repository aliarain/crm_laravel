<div class="row">
    <div class="col-md-3">
        <div class="table-content table-basic">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="#" method="POST"  id="form_{{ $data['type'] }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $data['id'] }}">
                        <input type="hidden" name="type" value="{{ $data['type'] }}">
                        <input type="hidden" name="index" value="{{ $data['index'] }}">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Call Date') }}<span class="text-danger">*</span></label>
                                    <input type="date" name="call_date" id="call_date" class="form-control ot-form-control ot_input" placeholder="{{ _trans('common.Call Date') }}" value="" required="">
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Number') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="number" id="number" class="form-control ot-form-control ot_input" placeholder="{{ _trans('common.Number') }}" value="" required="">
                                </div>
                            </div> 
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ _trans('common.Type') }}</label>
                                    <select name="call_type" class="form-control select2">
                                            <option value="Incoming">{{ _trans('common.Incoming') }}</option>
                                            <option value="Out Going">{{ _trans('common.Outgoing') }}</option>
                                    </select>
                                    @if ($errors->has('type'))
                                        <div class="error">{{ $errors->first('type') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Duration(Minute)') }}<span class="text-danger">*</span></label>
                                    <input type="number" name="duration" id="duration" class="form-control ot-form-control ot_input" placeholder="{{ _trans('common.Duration') }}" value="" required="">
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Subject') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="subject" id="subject" class="form-control ot-form-control ot_input" placeholder="{{ _trans('common.Subject') }}" value="" required="">
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Message') }}<span class="text-danger">*</span></label>
                                    <textarea placeholder="{{ _trans('common.Your Message') }}" name="body" class="form-control ot-form-control ot_input" id="body" ></textarea>
                                </div>
                            </div> 
                            @if (hasPermission('lead_detail_call'))
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button type="button" class="crm_theme_btn " onclick="leadDetailsStore('{{ $data['type'] }}')">{{ _trans('common.Create') }}</button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                {{-- card body  --}}
            </div>
            {{-- card --}} 
        </div>
    </div>
    {{-- date, duration, type, subject, body, number --}}
    <div class="col-md-9">

        <div class="table-content table-basic">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table_class">
                        <thead class="thead">
                            <tr>
                                <th>{{ _trans('common.SL') }}</th>
                                <th>{{ _trans('common.Call date') }}</th>
                                <th>{{ _trans('common.Subject') }}</th>
                                <th>{{ _trans('common.Number') }}</th>
                                <th>{{ _trans('common.Type') }}</th> 
                                <th>{{ _trans('common.Duration(Minute)') }}</th> 
                                <th>{{ _trans('common.Message') }}</th> 
                                <th>{{ _trans('common.Author') }}</th> 
                                <th>{{ _trans('common.Date') }}</th>
                            </tr>
                        </thead>
                        <tbody  class="tbody">
                            @foreach($data['items'] as $key => $row) 
                            <tr>
                                <td>{{ @$key + 1 }}</td>
                                <td  class="text-normal">{{ @$row['call_date'] }}</td>
                                <td  class="text-normal">{{ @$row['subject'] }}</td>
                                <td  class="text-normal">{{ @$row['number'] }}</td>
                                <td  class="text-normal">{{ @$row['call_type'] }}</td>
                                <td  class="text-normal">{{ @$row['duration'] }}</td>
                                <td  class="text-normal">{{ @$row['body'] }}</td>
                                <td  class="text-normal crm_link_active">{{ @$row['author'] }}</td>
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