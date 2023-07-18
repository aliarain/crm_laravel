<div class="modal  fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header modal-header-image text-center">
                <h5 class="modal-title text-white">{{ @$data['title'] }} </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-5">
                    <div class="col-md-12">
                        <form action="{{ $data['url'] }}" method="POST">
                            @csrf
                            <input type="hidden" name="request_type" value="{{ @$data['type'] }}">
                            <input type="hidden" name="user_id" value="{{ @$data['user_id'] }}">
                            <div class="form-group">
                                {{ Form::label('set_up_id', 'Commission', ['class' => 'form-label required select2']) }}
                                <select name="set_up_id" class="form-control" required>
                                    @foreach ($data['list'] as $item)
                                        <option
                                            {{ $item->id == @$data['repository']->commission_id ? ' selected="selected"' : '' }}
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                {{ Form::label('type', 'Type', ['class' => 'form-label required select2']) }}
                                <select name="type" class="form-control" required>
                                    <option {{ @$data['repository']->amount_type == 1 ? ' selected="selected"' : '' }}
                                        value="1">{{ _trans('common.Fixed') }}</option>
                                    <option {{ @$data['repository']->amount_type == 2 ? ' selected="selected"' : '' }}
                                        value="2">{{ _trans('common.Percentage') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                {{ Form::label('status_id', 'Status', ['class' => 'form-label required select2']) }}
                                <select name="status_id" class="form-control" required>
                                    <option {{ @$data['repository']->status_id == 1 ? ' selected="selected"' : '' }}
                                        value="1">{{ _trans('Active') }}</option>
                                    <option {{ @$data['repository']->status_id == 4 ? ' selected="selected"' : '' }}
                                        value="4">{{ _trans('Inactive') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                {{ Form::label('Amount', 'Amount', ['class' => 'form-label required']) }}
                                <input type="number" class="form-control" min="1" name="amount" id="Amount"
                                    autocomplete="off" value="{{ @$data['repository']->amount }}"required />
                            </div>
                            <div class="form-group text-right">
                                <button type="submit"
                                    class="btn btn-primary pull-right"><b>{{ _trans('common.Submit') }}</b></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script  src="{{ asset('public/backend/js/fs_d_ecma/modal/__modal.min.js') }}"></script>
