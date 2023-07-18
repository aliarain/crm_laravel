@extends('backend.layouts.app')

@section('content')
{!! breadcrumb([
'title' => @$data['title'],
route('admin.dashboard') => _trans('common.Dashboard'),
'#' => @$data['title'],
]) !!}
<div class="row">
    <div class="col-md-12">
        <div class="card ot-card">
            <div class="card-body">
               
                {!! Form::open(['route' => ['saleSupplier.update'], 'method' => 'post', 'files' => true]) !!}
                <div class="row">
                    <input type="hidden" name="id" value="{{ $supplier->id }}">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.name')}} *</strong> </label>
                            <input type="text" name="name" value="{{$supplier->name}}" required
                                class="form-control ot-form-control ot-input">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        @if ($supplier->image)
                        <img src="{{url('public/images/supplier',$supplier->image)}}" height="80" width="80">
                            
                        @else
                        <img src="{{url('public/static/blank_small.png')}}" height="80" width="80">
                            
                        @endif
                        <div class="form-group">
                            <label>{{_trans('file.Image')}}</label>
                            <input type="file" name="image" class="form-control ot-form-control ot-input">
                            @if($errors->has('image'))
                            <span>
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.Company Name')}} <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" value="{{$supplier->company_name}}" required
                                class="form-control ot-form-control ot-input">
                            @if($errors->has('company_name'))
                            <span>
                                <strong>{{ $errors->first('company_name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.VAT Number')}}</label>
                            <input type="text" name="vat_number" value="{{$supplier->vat_number}}"
                                class="form-control ot-form-control ot-input">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.Email')}} <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{$supplier->email}}" required
                                class="form-control ot-form-control ot-input">
                            @if($errors->has('email'))
                            <span>
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.Phone Number')}} <span class="text-danger">*</span></label>
                            <input type="text" name="phone_number" value="{{$supplier->phone_number}}" required
                                class="form-control ot-form-control ot-input">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.Address')}} <span class="text-danger">*</span></label>
                            <input type="text" name="address" value="{{$supplier->address}}" required
                                class="form-control ot-form-control ot-input">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.City')}} <span class="text-danger">*</span></label>
                            <input type="text" name="city" value="{{$supplier->city}}" required
                                class="form-control ot-form-control ot-input">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.State')}}</label>
                            <input type="text" name="state" value="{{$supplier->state}}"
                                class="form-control ot-form-control ot-input">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.Postal Code')}}</label>
                            <input type="text" name="postal_code" value="{{$supplier->postal_code}}"
                                class="form-control ot-form-control ot-input">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>{{_trans('file.Country')}}</label>
                            <input type="text" name="country" value="{{$supplier->country}}"
                                class="form-control ot-form-control ot-input">
                        </div>
                    </div>
                    @if (hasPermission('sales_product_supplier_update'))
                    <div class="col-md-12">
                        <div class="form-group mt-3">
                            <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn">
                        </div>
                    </div>
                    @endif

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $("ul#people").siblings('a').attr('aria-expanded','true');
    $("ul#people").addClass("show");
</script>
@endsection