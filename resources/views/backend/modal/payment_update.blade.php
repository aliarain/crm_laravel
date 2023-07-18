<div class="modal fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content data">
            <div class="modal-header text-center modal_headerBg_color">
                <h5 class="modal-title text-white">{{@$data['title']}} </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
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

                        <p>
                            <strong>{{ _trans('common.Deal Amount')}}</strong> : {{  $data['payment']->user->agreement->amount }} Tk
                        </p>
                        
                        <form action="{{ $data['url'] }}" method="POST">
                            @csrf
                            <input type="text" hidden name="balance" value="{{  $data['payment']->user->agreement->amount }}" />
                            <div class="form-group">
                                {{Form::label('payment_method','Payment Method', ['class' => 'form-label required'])}}
                                <select name="payment_method" class="form-control" required>
                                     @foreach (\DB::table('payment_types')->where('status_id',1)->get() as $item)
                                        <option {{ @$data['payment']->payment_type_id == $item->id ? "selected" : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                     @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                {{Form::label('date','Payment Month', ['class' => 'form-label required'])}}
                                <input type="month" name="date" class="form-control" required value="{{ $data['payment']->payment_date}}" />
                            </div>
                            <div class="form-group">
                                {{Form::label('amount','Pay', ['class' => 'form-label required'])}}
                                <input type="number" name="amount" class="form-control" required value="{{ $data['payment']->amount }}">
                            </div>
                            <div class="form-group">
                                {{Form::label('payment_note','Note', ['class' => 'form-label required'])}}
                                <input type="text" name="payment_note" class="form-control" required >
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary pull-right"><b>{{ _trans('common.Update')}}</b></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
