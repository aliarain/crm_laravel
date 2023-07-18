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
                                    <label class="form-label" for="name">{{ _trans('common.To') }}<span class="text-danger">*</span></label>
                                    <input type="email" name="to_email" class="form-control ot-form-control ot_input"
                                        placeholder="{{ _trans('common.To Email') }}" value="" id="to_email" required="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.CC') }}</label>
                                    <input type="email" name="cc_email" class="form-control ot-form-control ot_input"
                                        placeholder="{{ _trans('common.CC Email') }}" value="" id="cc_email" required="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Subject') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="subject" class="form-control ot-form-control ot_input"
                                        placeholder="{{ _trans('common.Subject') }}" value="" required="" id="subject">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="name">{{ _trans('common.Message') }}<span class="text-danger">*</span></label>
                                    <textarea placeholder="{{ _trans('common.Your Message') }}" name="message" class="form-control ot-form-control ot_input" id="message"></textarea>
                                </div>
                            </div>
                            @if (hasPermission('lead_detail_email'))
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button type="button" class="crm_theme_btn " onclick="leadDetailsStore('{{ $data['type'] }}')">{{ _trans('common.Send') }}</button>
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
            <div class="card mb-3">
                <div class="card-body">
                    <table class="table table-bordered table_class">
                        <thead class="thead">
                            <tr>
                                <th>{{ _trans('common.SL') }}</th>
                                <th>{{ _trans('common.Subject') }}</th>
                                <th>{{ _trans('common.Message') }}</th>
                                <th>{{ _trans('common.Info') }}</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            @foreach($data['items'] as $key => $email)
                            <tr>
                                <td>{{ @$key + 1 }}</td>
                                <td>{{ @$email['subject'] }}</td>
                                <td class="text-normal">{{ @$email['message'] }}</td>
                                <td>
                                    {{-- <p>From : {{ @$email['from_email'] }}</p> --}}
                                    <p>To : {{ @$email['to_email'] }}</p>
                                    <p>CC : {{ @$email['cc_email'] }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>