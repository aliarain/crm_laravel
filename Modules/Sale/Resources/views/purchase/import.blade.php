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
                   
                    {!! Form::open(['route' => 'salePurchase.import', 'method' => 'post', 'files' => true, 'id' =>
                    'purchase-form']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{_trans('file.Warehouse')}} <span class="text-danger">*</span></label>
                                        <select required name="warehouse_id"
                                            class="form-select select2-input ot-input mb-3 modal_select2"
                                            data-live-search="true" data-live-search-style="begins"
                                            title="Select warehouse...">
                                            <option value="" disabled>Select One</option>
                                            @foreach($ot_crm_warehouse_list as $warehouse)
                                            <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{_trans('file.Supplier')}}</label>
                                        <select name="supplier_id"
                                            class="form-select select2-input ot-input mb-3 modal_select2"
                                            data-live-search="true" data-live-search-style="begins">
                                            <option value="" disabled>Select One</option>
                                            @foreach($ot_crm_supplier_list as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name .' ('.
                                                $supplier->company_name .')'}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{_trans('file.Purchase Status')}}</label>
                                        <select name="status"
                                            class="form-select select2-input ot-input mb-3 modal_select2">
                                            <option value="1">{{_trans('file.Recieved')}}</option>
                                            <option value="3">{{_trans('file.Pending')}}</option>
                                            <option value="4">{{_trans('file.Ordered')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{_trans('file.Attach Document')}}</label>
                                        <i class="dripicons-question" data-toggle="tooltip"
                                            title="Only jpg, jpeg, png, gif, pdf, csv, docx, xlsx and txt file is supported"></i>
                                        <input type="file" name="document"
                                            class="form-control ot-form-control ot-input" />
                                        @if($errors->has('extension'))
                                        <span>
                                            <strong>{{ $errors->first('extension') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{_trans('file.Upload CSV File')}} <span class="text-danger">*</span></label>
                                        <input type="file" name="file" class="form-control ot-form-control ot-input"
                                            required />
                                        <p>{{_trans('file.The correct column order is')}} (product_code, quantity,
                                            purchase_unit, product_cost, discount, tax_name) {{_trans('file.and you must
                                            follow this')}}. {{_trans('file.All columns are required')}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label></label><br>
                                        <a href="{{ url('Modules/Sale/public/sample_file/sample_purchase_products.csv')}}"
                                            class="crm_line_btn"><i class="dripicons-download"></i>
                                            {{_trans('file.Download Sample File')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group ">
                                        <input type="hidden" name="total_qty" value="0" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="total_discount" value="0" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="total_tax" value="0" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="total_cost" value="0" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="item" value="0" />
                                        <input type="hidden" name="order_tax" value="0" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="grand_total" value="0" />
                                        <input type="hidden" name="paid_amount" value="0.00" />
                                        <input type="hidden" name="payment_status" value="1" />
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{_trans('file.Order Tax')}}</label>
                                        <select class="form-select select2-input ot-input mb-3 modal_select2"
                                            name="order_tax_rate">
                                            <option value="0">{{_trans('file.No Tax')}}</option>
                                            @foreach($ot_crm_tax_list as $tax)
                                            <option value="{{$tax->rate}}">{{$tax->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            <strong>{{_trans('file.Discount')}}</strong>
                                        </label>
                                        <input type="number" name="order_discount"
                                            class="form-control ot-form-control ot-input" step="any" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <strong>{{_trans('file.Shipping Cost')}}</strong>
                                        </label>
                                        <input type="number" name="shipping_cost"
                                            class="form-control ot-form-control ot-input" step="any" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">{{_trans('file.Note')}}</label>
                                        <textarea rows="5" class="form-control ot-form-control ot-input"
                                            name="note"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-end mt-20">
                                <input type="submit" value="{{_trans('file.submit')}}" class="crm_theme_btn"
                                    id="submit-button">
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    @endsection
    @section('script')
    <script type="text/javascript">
        $("ul#purchase").siblings('a').attr('aria-expanded','true');
        $("ul#purchase").addClass("show");
        $("ul#purchase #purchase-import-menu").addClass("active");
        $('[data-toggle="tooltip"]').tooltip();
    </script>
    @endsection