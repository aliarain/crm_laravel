@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('style')
    <style type="text/css">
        .panel-title {
            display: inline;
            font-weight: bold;
        }

        .display-table {
            display: table;
        }

        .display-tr {
            display: table-row;
        }

        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 61%;
        }
    </style>
@endsection
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default credit-card-box">
                    <div class="panel-heading display-table">
                        <div class="row display-tr">
                            <h3 class="panel-title display-td">{{ _trans('payment.Payment Details') }}</h3>
                        </div>
                    </div>
                    <div class="panel-body">

                        @if (Session::has('success'))
                            <div class="alert alert-success text-center">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                <p>{{ Session::get('success') }}</p>
                            </div>
                        @endif

                        <form role="form" action="{{ route('payments.store') }}" method="post"
                            class="require-validation" data-cc-on-file="false"
                            data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
                            @csrf

                            <div class='form-row row'>
                                <div class='form-group required'>
                                    <label class='control-label'>{{ _trans('payment.Name on Card') }}</label>
                                    <input class='form-control' type='text' name="name_on_card">
                                </div>
                            </div>

                            <div class='form-row row'>
                                <div class='form-group  required'>
                                    <label class='control-label'>{{ _trans('payment.Card Number') }}</label>
                                    <input autocomplete='off' class='form-control card-number'type='text'
                                        name="card_number">
                                </div>
                            </div>

                            <div class='form-row row'>
                                <div class='col-xs-12 col-md-4 form-group cvc required'>
                                    <label class='control-label'>{{ _trans('payment.CVC') }}</label>
                                    <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311'
                                        type='number'>
                                </div>
                                <div class='col-xs-12 col-md-4 form-group expiration required'>
                                    <label class='control-label'>{{ _trans('payment.Expiration Month') }}</label> <input
                                        class='form-control card-expiry-month' placeholder='MM' type='text'>
                                </div>
                                <div class='col-xs-12 col-md-4 form-group expiration required'>
                                    <label class='control-label'>{{ _trans('payment.Expiration Year') }}</label> <input
                                        class='form-control card-expiry-year' placeholder='YYYY' type='text'>
                                </div>
                            </div>

                            <div class='form-row row'>
                                <div class='col-md-12 error form-group d-none'>
                                    <div class='alert-danger alert'>
                                        {{ _trans('common.Please correct the errors and try again.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <button class="btn btn-primary btn-lg btn-block"
                                        type="submit">{{ _trans('payment.Pay Now ($100)') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="https://js.stripe.com/v2/"></script>
    <script src="{{ asset('public/backend/js/payment.js') }}"></script>
@endsection
