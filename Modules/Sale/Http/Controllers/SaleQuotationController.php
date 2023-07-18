<?php

namespace Modules\Sale\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleBiller;
use Modules\Sale\Entities\SaleCustomer;
use Modules\Sale\Entities\SaleCustomerGroup;
use Modules\Sale\Entities\SalePosSetting;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SaleProductQuotation;
use Modules\Sale\Entities\SaleProductVariant;
use Modules\Sale\Entities\SaleQuotation;
use Modules\Sale\Entities\SaleSupplier;
use Modules\Sale\Entities\SaleTax;
use Modules\Sale\Entities\SaleUnit;
use Modules\Sale\Entities\SaleVariant;
use Modules\Sale\Entities\SaleWarehouse;

class SaleQuotationController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        $data['title'] = 'Quotation List';
        $data['search'] = '';
        $data['quotations'] = SaleQuotation::with('biller', 'customer', 'supplier', 'user', 'warehouse');
        if ($search != "") {
            $data['quotations'] = $data['quotations']->where('reference_no', 'like', '%' . $search . '%');
        }
        $data['quotations'] = $data['quotations']->latest()->paginate($entries);
        return view('sale::quotation.index', compact('data'));
    }

    public function create()
    {
        $ot_crm_biller_list = SaleBiller::where('is_active', true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_customer_list = SaleCustomer::where('is_active', true)->get();
        $ot_crm_supplier_list = SaleSupplier::where('is_active', true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
        $data['title'] = 'Quotation Create';

        return view('sale::quotation.create', compact('data', 'ot_crm_biller_list', 'ot_crm_warehouse_list', 'ot_crm_customer_list', 'ot_crm_supplier_list', 'ot_crm_tax_list'));
    }

    public function store(Request $request)
    {
        $data = $request->except('document');
        $data['user_id'] = Auth::id();
        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ],
                [
                    'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                ]
            );
            if ($v->fails()) {
                return redirect()->back()->withErrors($v->errors());
            }

            $documentName = $document->getClientOriginalName();
            $document->move('public/quotation/documents', $documentName);
            $data['document'] = $documentName;
        }
        $data['reference_no'] = 'qr-' . date("Ymd") . '-' . date("his");
        $ot_crm_quotation_data = SaleQuotation::create($data);
        if ($ot_crm_quotation_data->quotation_status == 2) {
            //collecting mail data
            $ot_crm_customer_data = SaleCustomer::find($data['customer_id']);
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['reference_no'] = $ot_crm_quotation_data->reference_no;
            $mail_data['total_qty'] = $ot_crm_quotation_data->total_qty;
            $mail_data['total_price'] = $ot_crm_quotation_data->total_price;
            $mail_data['order_tax'] = $ot_crm_quotation_data->order_tax;
            $mail_data['order_tax_rate'] = $ot_crm_quotation_data->order_tax_rate;
            $mail_data['order_discount'] = $ot_crm_quotation_data->order_discount;
            $mail_data['shipping_cost'] = $ot_crm_quotation_data->shipping_cost;
            $mail_data['grand_total'] = $ot_crm_quotation_data->grand_total;
        }
        $product_id = $data['product_id'];
        $product_batch_id = $data['product_batch_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $sale_unit = $data['sale_unit'];
        $net_unit_price = $data['net_unit_price'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $product_quotation = [];

        foreach ($product_id as $i => $id) {
            if ($sale_unit[$i] != 'n/a') {
                $ot_crm_sale_unit_data = SaleUnit::where('unit_name', $sale_unit[$i])->first();
                $sale_unit_id = $ot_crm_sale_unit_data->id;
            } else {
                $sale_unit_id = 0;
            }

            if ($sale_unit_id) {
                $mail_data['unit'][$i] = $ot_crm_sale_unit_data->unit_code;
            } else {
                $mail_data['unit'][$i] = '';
            }

            $ot_crm_product_data = SaleProduct::find($id);
            if ($ot_crm_product_data->is_variant) {
                $ot_crm_product_variant_data = SaleProductVariant::select('variant_id')->FindExactProductWithCode($id, $product_code[$i])->first();
                $product_quotation['variant_id'] = $ot_crm_product_variant_data->variant_id;
            } else {
                $product_quotation['variant_id'] = null;
            }

            if ($product_quotation['variant_id']) {
                $variant_data = SaleVariant::find($product_quotation['variant_id']);
                $mail_data['products'][$i] = $ot_crm_product_data->name . ' [' . $variant_data->name . ']';
            } else {
                $mail_data['products'][$i] = $ot_crm_product_data->name;
            }

            $product_quotation['quotation_id'] = $ot_crm_quotation_data->id;
            $product_quotation['product_id'] = $id;
            $product_quotation['product_batch_id'] = $product_batch_id[$i];
            $product_quotation['qty'] = $mail_data['qty'][$i] = $qty[$i];
            $product_quotation['sale_unit_id'] = $sale_unit_id;
            $product_quotation['net_unit_price'] = $net_unit_price[$i];
            $product_quotation['discount'] = $discount[$i];
            $product_quotation['tax_rate'] = $tax_rate[$i];
            $product_quotation['tax'] = $tax[$i];
            $product_quotation['total'] = $mail_data['total'][$i] = $total[$i];
            SaleProductQuotation::create($product_quotation);
        }
        $message = 'Quotation created successfully';

        Toastr::success($message, 'Success');
        return redirect()->route('saleQuotation.index');

    }

    public function sendMail(Request $request)
    {
        $data = $request->all();
        $ot_crm_quotation_data = SaleQuotation::find($data['quotation_id']);
        $ot_crm_product_quotation_data = SaleProductQuotation::where('quotation_id', $data['quotation_id'])->get();
        $ot_crm_customer_data = SaleCustomer::find($ot_crm_quotation_data->customer_id);
        if ($ot_crm_customer_data->email) {
            //collecting male data
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['reference_no'] = $ot_crm_quotation_data->reference_no;
            $mail_data['total_qty'] = $ot_crm_quotation_data->total_qty;
            $mail_data['total_price'] = $ot_crm_quotation_data->total_price;
            $mail_data['order_tax'] = $ot_crm_quotation_data->order_tax;
            $mail_data['order_tax_rate'] = $ot_crm_quotation_data->order_tax_rate;
            $mail_data['order_discount'] = $ot_crm_quotation_data->order_discount;
            $mail_data['shipping_cost'] = $ot_crm_quotation_data->shipping_cost;
            $mail_data['grand_total'] = $ot_crm_quotation_data->grand_total;

            foreach ($ot_crm_product_quotation_data as $key => $product_quotation_data) {
                $ot_crm_product_data = SaleProduct::find($product_quotation_data->product_id);
                if ($product_quotation_data->variant_id) {
                    $variant_data = SaleVariant::find($product_quotation_data->variant_id);
                    $mail_data['products'][$key] = $ot_crm_product_data->name . ' [' . $variant_data->name . ']';
                } else {
                    $mail_data['products'][$key] = $ot_crm_product_data->name;
                }

                if ($product_quotation_data->sale_unit_id) {
                    $ot_crm_unit_data = SaleUnit::find($product_quotation_data->sale_unit_id);
                    $mail_data['unit'][$key] = $ot_crm_unit_data->unit_code;
                } else {
                    $mail_data['unit'][$key] = '';
                }

                $mail_data['qty'][$key] = $product_quotation_data->qty;
                $mail_data['total'][$key] = $product_quotation_data->total;
            }

            try {
                Mail::send('mail.quotation_details', $mail_data, function ($message) use ($mail_data) {
                    $message->to($mail_data['email'])->subject('Quotation Details');
                });
                $message = 'Mail sent successfully';
            } catch (\Exception$e) {
                $message = 'Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        } else {
            $message = 'Customer doesnt have email!';
        }

        return redirect()->back()->with('message', $message);
    }

    public function getCustomerGroup($id)
    {
        $ot_crm_customer_data = SaleCustomer::find($id);
        $ot_crm_customer_group_data = SaleCustomerGroup::find($ot_crm_customer_data->customer_group_id);
        return $ot_crm_customer_group_data->percentage;
    }

    public function getProduct($id)
    {
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_price = [];
        $product_data = [];

        $product_type = [];
        $product_id = [];
        $product_list = [];
        $qty_list = [];
        $batch_no = [];
        $product_batch_id = [];

        //retrieve data of product without variant
        $ot_crm_product_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
            ->where([
                ['sale_products.is_active', true],
                ['sale_product_warehouses.warehouse_id', $id],
            ])
            ->whereNull('sale_product_warehouses.variant_id')
            ->whereNull('sale_product_warehouses.product_batch_id')
            ->select('sale_product_warehouses.*')
            ->get();

        foreach ($ot_crm_product_warehouse_data as $product_warehouse) {
            $product_qty[] = $product_warehouse->qty;
            $product_price[] = $product_warehouse->price;
            $ot_crm_product_data = SaleProduct::find($product_warehouse->product_id);
            $product_code[] = $ot_crm_product_data->code;
            $product_name[] = $ot_crm_product_data->name;
            $product_type[] = $ot_crm_product_data->type;
            $product_id[] = $ot_crm_product_data->id;
            $product_list[] = null;
            $qty_list[] = null;
            $batch_no[] = null;
            $product_batch_id[] = null;
        }

        config()->set('database.connections.mysql.strict', false);
        \DB::reconnect(); //important as the existing connection if any would be in strict mode

        $ot_crm_product_with_batch_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
            ->where([
                ['sale_products.is_active', true],
                ['sale_product_warehouses.warehouse_id', $id],
            ])
            ->whereNull('sale_product_warehouses.variant_id')
            ->whereNotNull('sale_product_warehouses.product_batch_id')
            ->select('sale_product_warehouses.*')
            ->groupBy('sale_product_warehouses.product_id')
            ->get();

        //now changing back the strict ON
        config()->set('database.connections.mysql.strict', true);
        \DB::reconnect();

        foreach ($ot_crm_product_with_batch_warehouse_data as $product_warehouse) {
            $product_qty[] = $product_warehouse->qty;
            $product_price[] = $product_warehouse->price;
            $ot_crm_product_data = SaleProduct::find($product_warehouse->product_id);
            $product_code[] = $ot_crm_product_data->code;
            $product_name[] = $ot_crm_product_data->name;
            $product_type[] = $ot_crm_product_data->type;
            $product_id[] = $ot_crm_product_data->id;
            $product_list[] = null;
            $qty_list[] = null;
            $product_batch_data = SaleProductBatch::select('id', 'batch_no')->find($product_warehouse->product_batch_id);
            $batch_no[] = $product_batch_data->batch_no;
            $product_batch_id[] = $product_batch_data->id;
        }
        //retrieve data of product with variant
        $ot_crm_product_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
            ->where([
                ['sale_products.is_active', true],
                ['sale_product_warehouses.warehouse_id', $id],
            ])->whereNotNull('sale_product_warehouses.variant_id')->select('sale_product_warehouses.*')->get();
        foreach ($ot_crm_product_warehouse_data as $product_warehouse) {
            $product_qty[] = $product_warehouse->qty;
            $ot_crm_product_data = SaleProduct::find($product_warehouse->product_id);
            $ot_crm_product_variant_data = SaleProductVariant::select('item_code')->FindExactProduct($product_warehouse->product_id, $product_warehouse->variant_id)->first();
            $product_code[] = $ot_crm_product_variant_data->item_code;
            $product_name[] = $ot_crm_product_data->name;
            $product_type[] = $ot_crm_product_data->type;
            $product_id[] = $ot_crm_product_data->id;
            $product_list[] = null;
            $qty_list[] = null;
            $batch_no[] = null;
            $product_batch_id[] = null;
        }
        //retrieve product data of digital and combo
        $ot_crm_product_data = SaleProduct::whereNotIn('type', ['standard'])->where('is_active', true)->get();
        foreach ($ot_crm_product_data as $product) {
            $product_qty[] = $product->qty;
            $ot_crm_product_data = $product->id;
            $product_code[] = $product->code;
            $product_name[] = $product->name;
            $product_type[] = $product->type;
            $product_id[] = $product->id;
            $product_list[] = $product->product_list;
            $qty_list[] = $product->qty_list;
        }
        $product_data = [$product_code, $product_name, $product_qty, $product_type, $product_id, $product_list, $qty_list, $product_price, $batch_no, $product_batch_id];
        return $product_data;
    }

    public function ot_crmProductSearch(Request $request)
    {
        $todayDate = date('Y-m-d');
        $product_code = explode("(", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $product_variant_id = null;
        $ot_crm_product_data = SaleProduct::where('code', $product_code[0])->first();
        if (!$ot_crm_product_data) {
            $ot_crm_product_data = SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
                ->select('sale_products.*', 'sale_product_variants.id as product_variant_id', 'sale_product_variants.item_code', 'sale_product_variants.additional_price')
                ->where('sale_product_variants.item_code', $product_code[0])
                ->first();
            $product_variant_id = $ot_crm_product_data->product_variant_id;
            $ot_crm_product_data->code = $ot_crm_product_data->item_code;
            $ot_crm_product_data->price += $ot_crm_product_data->additional_price;
        }
        $product[] = $ot_crm_product_data->name;
        $product[] = $ot_crm_product_data->code;
        if ($ot_crm_product_data->promotion && $todayDate <= $ot_crm_product_data->last_date) {
            $product[] = $ot_crm_product_data->promotion_price;
        } else {
            $product[] = $ot_crm_product_data->price;
        }

        if ($ot_crm_product_data->tax_id) {
            $ot_crm_tax_data = SaleTax::find($ot_crm_product_data->tax_id);
            $product[] = $ot_crm_tax_data->rate;
            $product[] = $ot_crm_tax_data->name;
        } else {
            $product[] = 0;
            $product[] = 'No Tax';
        }
        $product[] = $ot_crm_product_data->tax_method;
        if ($ot_crm_product_data->type == 'standard') {
            $units = SaleUnit::where("base_unit", $ot_crm_product_data->unit_id)
                ->orWhere('id', $ot_crm_product_data->unit_id)
                ->get();
            $unit_name = array();
            $unit_operator = array();
            $unit_operation_value = array();
            foreach ($units as $unit) {
                if ($ot_crm_product_data->sale_unit_id == $unit->id) {
                    array_unshift($unit_name, $unit->unit_name);
                    array_unshift($unit_operator, $unit->operator);
                    array_unshift($unit_operation_value, $unit->operation_value);
                } else {
                    $unit_name[] = $unit->unit_name;
                    $unit_operator[] = $unit->operator;
                    $unit_operation_value[] = $unit->operation_value;
                }
            }

            $product[] = implode(",", $unit_name) . ',';
            $product[] = implode(",", $unit_operator) . ',';
            $product[] = implode(",", $unit_operation_value) . ',';
        } else {
            $product[] = 'n/a' . ',';
            $product[] = 'n/a' . ',';
            $product[] = 'n/a' . ',';
        }
        $product[] = $ot_crm_product_data->id;
        $product[] = $product_variant_id;
        $product[] = $ot_crm_product_data->promotion;
        $product[] = $ot_crm_product_data->is_batch;
        $product[] = $ot_crm_product_data->is_imei;
        return $product;
    }

    public function productQuotationData($id)
    {
        $ot_crm_product_quotation_data = SaleProductQuotation::where('quotation_id', $id)->get();
        foreach ($ot_crm_product_quotation_data as $key => $product_quotation_data) {
            $product = SaleProduct::find($product_quotation_data->product_id);
            if ($product_quotation_data->variant_id) {
                $ot_crm_product_variant_data = SaleProductVariant::select('item_code')->FindExactProduct($product_quotation_data->product_id, $product_quotation_data->variant_id)->first();
                $product->code = $ot_crm_product_variant_data->item_code;
            }
            if ($product_quotation_data->sale_unit_id) {
                $unit_data = SaleUnit::find($product_quotation_data->sale_unit_id);
                $unit = $unit_data->unit_code;
            } else {
                $unit = '';
            }

            $product_quotation[0][$key] = $product->name . ' [' . $product->code . ']';
            $product_quotation[1][$key] = $product_quotation_data->qty;
            $product_quotation[2][$key] = $unit;
            $product_quotation[3][$key] = $product_quotation_data->tax;
            $product_quotation[4][$key] = $product_quotation_data->tax_rate;
            $product_quotation[5][$key] = $product_quotation_data->discount;
            $product_quotation[6][$key] = $product_quotation_data->total;
            if ($product_quotation_data->product_batch_id) {
                $product_batch_data = ProductBatch::select('batch_no')->find($product_quotation_data->product_batch_id);
                $product_quotation[7][$key] = $product_batch_data->batch_no;
            } else {
                $product_quotation[7][$key] = 'N/A';
            }

        }
        return $product_quotation;
    }

    public function edit($id)
    {
        $ot_crm_customer_list = SaleCustomer::where('is_active', true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_biller_list = SaleBiller::where('is_active', true)->get();
        $ot_crm_supplier_list = SaleSupplier::where('is_active', true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
        $ot_crm_quotation_data = SaleQuotation::find($id);
        $ot_crm_product_quotation_data = SaleProductQuotation::where('quotation_id', $id)->get();
        $data['title'] = 'Quotation Edit';
        return view('sale::quotation.edit', compact('data', 'ot_crm_customer_list', 'ot_crm_warehouse_list', 'ot_crm_biller_list', 'ot_crm_tax_list', 'ot_crm_quotation_data', 'ot_crm_product_quotation_data', 'ot_crm_supplier_list'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ],
                [
                    'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                ]
            );
            if ($v->fails()) {
                return redirect()->back()->withErrors($v->errors());
            }

            $documentName = $document->getClientOriginalName();
            $document->move('public/quotation/documents', $documentName);
            $data['document'] = $documentName;
        }
        $ot_crm_quotation_data = SaleQuotation::find($id);
        $ot_crm_product_quotation_data = SaleProductQuotation::where('quotation_id', $id)->get();
        //update quotation table
        $ot_crm_quotation_data->update($data);
        if ($ot_crm_quotation_data->quotation_status == 2) {
            //collecting mail data
            $ot_crm_customer_data = SaleCustomer::find($data['customer_id']);
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['reference_no'] = $ot_crm_quotation_data->reference_no;
            $mail_data['total_qty'] = $data['total_qty'];
            $mail_data['total_price'] = $data['total_price'];
            $mail_data['order_tax'] = $data['order_tax'];
            $mail_data['order_tax_rate'] = $data['order_tax_rate'];
            $mail_data['order_discount'] = $data['order_discount'];
            $mail_data['shipping_cost'] = $data['shipping_cost'];
            $mail_data['grand_total'] = $data['grand_total'];
        }
        $product_id = $data['product_id'];
        $product_batch_id = $data['product_batch_id'];
        $product_variant_id = $data['product_variant_id'];
        $qty = $data['qty'];
        $sale_unit = $data['sale_unit'];
        $net_unit_price = $data['net_unit_price'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];

        foreach ($ot_crm_product_quotation_data as $key => $product_quotation_data) {
            $old_product_id[] = $product_quotation_data->product_id;
            $ot_crm_product_data = SaleProduct::select('id')->find($product_quotation_data->product_id);
            if ($product_quotation_data->variant_id) {
                $ot_crm_product_variant_data = SaleProductVariant::select('id')->FindExactProduct($product_quotation_data->product_id, $product_quotation_data->variant_id)->first();
                $old_product_variant_id[] = $ot_crm_product_variant_data->id;
                if (!in_array($ot_crm_product_variant_data->id, $product_variant_id)) {
                    $product_quotation_data->delete();
                }

            } else {
                $old_product_variant_id[] = null;
                if (!in_array($product_quotation_data->product_id, $product_id)) {
                    $product_quotation_data->delete();
                }

            }
        }

        foreach ($product_id as $i => $pro_id) {
            if ($sale_unit[$i] != 'n/a') {
                $ot_crm_sale_unit_data = SaleUnit::where('unit_name', $sale_unit[$i])->first();
                $sale_unit_id = $ot_crm_sale_unit_data->id;
            } else {
                $sale_unit_id = 0;
            }

            $ot_crm_product_data = SaleProduct::select('id', 'name', 'is_variant')->find($pro_id);
            if ($sale_unit_id) {
                $mail_data['unit'][$i] = $ot_crm_sale_unit_data->unit_code;
            } else {
                $mail_data['unit'][$i] = '';
            }

            $input['quotation_id'] = $id;
            $input['product_id'] = $pro_id;
            $input['product_batch_id'] = $product_batch_id[$i];
            $input['qty'] = $mail_data['qty'][$i] = $qty[$i];
            $input['sale_unit_id'] = $sale_unit_id;
            $input['net_unit_price'] = $net_unit_price[$i];
            $input['discount'] = $discount[$i];
            $input['tax_rate'] = $tax_rate[$i];
            $input['tax'] = $tax[$i];
            $input['total'] = $mail_data['total'][$i] = $total[$i];
            $flag = 1;
            if ($ot_crm_product_data->is_variant) {
                $ot_crm_product_variant_data = SaleProductVariant::select('variant_id')->where('id', $product_variant_id[$i])->first();
                $input['variant_id'] = $ot_crm_product_variant_data->variant_id;
                if (in_array($product_variant_id[$i], $old_product_variant_id)) {
                    SaleProductQuotation::where([
                        ['product_id', $pro_id],
                        ['variant_id', $input['variant_id']],
                        ['quotation_id', $id],
                    ])->update($input);
                } else {
                    SaleProductQuotation::create($input);
                }
                $variant_data = SaleVariant::find($input['variant_id']);
                $mail_data['products'][$i] = $ot_crm_product_data->name . ' [' . $variant_data->name . ']';
            } else {
                $input['variant_id'] = null;
                if (in_array($pro_id, $old_product_id)) {
                    SaleProductQuotation::where([
                        ['product_id', $pro_id],
                        ['quotation_id', $id],
                    ])->update($input);
                } else {
                    SaleProductQuotation::create($input);
                }
                $mail_data['products'][$i] = $ot_crm_product_data->name;
            }
        }

        $message = 'Quotation updated successfully';

        Toastr::success($message, 'Success');
        return redirect()->route('saleQuotation.index');
    }

    public function createSale($id)
    {
        $ot_crm_customer_list = SaleCustomer::where('is_active', true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_biller_list = SaleBiller::where('is_active', true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
        $ot_crm_quotation_data = SaleQuotation::find($id);
        $ot_crm_product_quotation_data = SaleProductQuotation::where('quotation_id', $id)->get();
        $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
        $data['title'] = "Create Sale";
        return view('sale::quotation.create_sale', compact('data', 'ot_crm_customer_list', 'ot_crm_warehouse_list', 'ot_crm_biller_list', 'ot_crm_tax_list', 'ot_crm_quotation_data', 'ot_crm_product_quotation_data', 'ot_crm_pos_setting_data'));
    }

    public function createPurchase($id)
    {
        $ot_crm_supplier_list = SaleSupplier::where('is_active', true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
        $ot_crm_quotation_data = SaleQuotation::find($id);
        $ot_crm_product_quotation_data = SaleProductQuotation::where('quotation_id', $id)->get();
        $ot_crm_product_list_without_variant = $this->productWithoutVariant();
        $ot_crm_product_list_with_variant = $this->productWithVariant();
        $data['title'] = "Create Purchase";

        return view('sale::quotation.create_purchase', compact('data', 'ot_crm_product_list_without_variant', 'ot_crm_product_list_with_variant', 'ot_crm_supplier_list', 'ot_crm_warehouse_list', 'ot_crm_tax_list', 'ot_crm_quotation_data', 'ot_crm_product_quotation_data'));
    }

    public function productWithoutVariant()
    {
        return SaleProduct::ActiveStandard()->select('id', 'name', 'code')
            ->whereNull('is_variant')->get();
    }

    public function productWithVariant()
    {
        return SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
            ->ActiveStandard()
            ->whereNotNull('is_variant')
            ->select('sale_products.id', 'sale_products.name', 'sale_product_variants.item_code')
            ->orderBy('position')->get();
    }

    public function deleteBySelection(Request $request)
    {
        $quotation_id = $request['quotationIdArray'];
        foreach ($quotation_id as $id) {
            $ot_crm_quotation_data = SaleQuotation::find($id);
            $ot_crm_product_quotation_data = SaleProductQuotation::where('quotation_id', $id)->get();
            foreach ($ot_crm_product_quotation_data as $product_quotation_data) {
                $product_quotation_data->delete();
            }
            $ot_crm_quotation_data->delete();
        }
        return 'Quotation deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_quotation_data = SaleQuotation::find($id);
        $ot_crm_product_quotation_data = SaleProductQuotation::where('quotation_id', $id)->get();
        foreach ($ot_crm_product_quotation_data as $product_quotation_data) {
            $product_quotation_data->delete();
        }
        $ot_crm_quotation_data->delete();
        Toastr::success(_trans('response.Quotation deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
