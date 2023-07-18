<div class="modal  fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content data">
            <div class="modal-header modal-header-image text-center">
                <h5 class="modal-title text-white">{{@$data['title']}} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-0">
                    <div class="col-md-12">
                        <form action="{{ $data['url'] }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                {{Form::label('Amount','Amount', ['class' => 'form-label required'])}}
                                <input type="number" class="form-control ot-form-control ot_input" min="1" name="amount" id="Amount" autocomplete="off" value="{{ $data['expense']->amount  }}" readonly/>
                            </div>
                            <div class="form-group">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('common.Account') }}</label>
                                <select name="account" class="form-control modal_select2">
                                    @foreach ($data['accounts'] as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ _trans('account.Payment Method') }} <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-control modal_select2" required>
                                    @foreach ($data['payment_method'] as $payment_method)
                                        <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group d-flex justify-content-end">
                                <button type="submit" class="crm_theme_btn d-flex justify-content-end">{{ $data['button'] }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

$('.modal_select2').select2({
    placeholder: 'Choose one',
})
</script>

