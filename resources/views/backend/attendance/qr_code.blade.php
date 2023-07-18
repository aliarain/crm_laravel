@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
    <div class="content-wrapper dashboard-wrapper mt-30">
        <!-- Main content -->
        <section class="content p-0">
            <div class="container-fluid table-filter-container border-radius-5 p-imp-30">
                <div class="row align-items-center mb-15">
                    <div class="col-sm-6 col-12">
                        <h5 class="fm-poppins m-0 text-dark">{{ @$data['title'] }}</h5>
                    </div>
                    <div class="col-sm-6 col-12">
                        <a href="#" onclick='printDiv(`qr_div`);'
                            class="btn btn-sm btn-primary float-left-sm-device float-right">{{ _trans('common.Print') }}</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="visible-print text-center" id="qr_div">
                            @php
                                $qr_value = encrypt(auth()->user()->company_id);
                            @endphp
                            {!! QrCode::size(700)->style('square')->generate($qr_value) !!}
                            <p class="text-center" >{{ _trans('common.Scan me to checkin.')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    @include('backend.partials.datatable')
@endsection
