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
                  
                    {!! Form::open(['route' => 'saleSupplier.store', 'method' => 'post', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <input type="checkbox" name="both" value="1" />&nbsp;
                                <label>{{_trans('file.Both Customer and Supplier')}}</label>
                            </div>
                        </div>
                        <div class="col-md-4 customer-group-section mt-4">
                            <div class="form-group">
                                <label>{{_trans('file.Customer Group')}} *</strong> </label>
                                <select class="form-select select2-input ot-input mb-3 modal_select2"
                                    id="customer-group-id" name="customer_group_id">
                                    @foreach($ot_crm_customer_group_all as $customer_group)
                                    <option value="{{$customer_group->id}}">{{$customer_group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.name')}} *</strong> </label>
                                <input type="text" name="name" required class="form-control ot-form-control ot-input" placeholder="{{_trans('file.name')}}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
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
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Company Name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" required placeholder="{{_trans('file.Company Name')}}"
                                    class="form-control ot-form-control ot-input">
                                @if($errors->has('company_name'))
                                <span>
                                    <strong>{{ $errors->first('company_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.VAT Number')}}</label>
                                <input type="text" name="vat_number" class="form-control ot-form-control ot-input" placeholder="{{_trans('file.VAT Number')}}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Email')}} <span class="text-danger">*</span></label>
                                <input type="email" name="email"  required placeholder="{{_trans('file.Email')}}"
                                    class="form-control ot-form-control ot-input">
                                @if($errors->has('email'))
                                <span>
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Phone Number')}} <span class="text-danger">*</span></label>
                                <input type="text" name="phone_number" required placeholder="{{_trans('file.Phone Number')}}"
                                    class="form-control ot-form-control ot-input">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Address')}} <span class="text-danger">*</span></label>
                                <input type="text" name="address" required placeholder="{{_trans('file.Address')}}"
                                    class="form-control ot-form-control ot-input">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.City')}} <span class="text-danger">*</span></label>
                                <input type="text" name="city" required placeholder="{{_trans('file.City')}}" class="form-control ot-form-control ot-input">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.State')}}</label>
                                <input type="text" name="state" class="form-control ot-form-control ot-input" placeholder="{{_trans('file.State')}}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Postal Code')}}</label>
                                <input type="text" name="postal_code" class="form-control ot-form-control ot-input" placeholder="{{_trans('file.Postal Code')}}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>{{_trans('file.Country')}}</label>
                                <input type="text" name="country" class="form-control ot-form-control ot-input" placeholder="{{_trans('file.Country')}}">
                            </div>
                        </div>
                        @if (hasPermission('sales_product_supplier_store'))
                        <div class="col-md-12 mt-4">
                            <div class="form-group mt-4">
                                <input type="submit" value="{{_trans('file.Submit')}}" class="crm_theme_btn">
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
    $("ul#people #supplier-create-menu").addClass("active");
    $(".customer-group-section").hide();

    $('input[name="both"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('.customer-group-section').show(300);
            $('select[name="customer_group_id"]').prop('required',true);
        }
        else{
            $('.customer-group-section').hide(300);
            $('select[name="customer_group_id"]').prop('required',false);
        }
    });
</script>
@endsection