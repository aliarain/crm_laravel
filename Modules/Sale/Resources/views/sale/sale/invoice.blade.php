<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', @$general_setting->site_logo)}}" />
    <title>{{@$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
            text-_transform: capitalize;
        }
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor:pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }
        tr {border-bottom: 1px dotted #ddd;}
        td,th {padding: 7px 0;width: 50%;}

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media print {
            * {
                font-size:12px;
                line-height: 20px;
            }
            td,th {padding: 5px 0;}
            .hidden-print {
                display: none !important;
            }
            @page { margin: 1.5cm 0.5cm 0.5cm; }
            @page:first { margin-top: 0.5cm; }
            tbody::after {
                content: ''; display: block;
                page-break-after: always;
                page-break-inside: avoid;
                page-break-before: avoid;        
            }
        }
    </style>
  </head>
<body>

<div style="max-width:400px;margin:0 auto">
    @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = '../../pos'; @endphp
    @else
        @php $url = url()->previous(); @endphp
    @endif
    <div class="hidden-print">
        <table>
            <tr>
                <td><a href="{{$url}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> {{_trans('file.Back')}}</a> </td>
                <td><button onclick="window.print();" class="crm_theme_btn"><i class="dripicons-print"></i> {{_trans('file.Print')}}</button></td>
            </tr>
        </table>
        <br>
    </div>
        
    <div id="receipt-data">
        <div class="centered">
            @if(@$general_setting->site_logo)
                <img src="{{url('public/logo', @$general_setting->site_logo)}}" height="42" width="50" style="margin:10px 0;filter: brightness(0);">
            @endif
            
            <h2>{{$ot_crm_biller_data->company_name}}</h2>
            
            <p>{{_trans('file.Address')}}: {{$ot_crm_warehouse_data->address}}
                <br>{{_trans('file.Phone Number')}}: {{$ot_crm_warehouse_data->phone}}
            </p>
        </div>
        <p>{{_trans('file.Date')}}: {{date(@$general_setting->date_format, strtotime($ot_crm_sale_data->created_at->toDateString()))}}<br>
            {{_trans('file.reference')}}: {{$ot_crm_sale_data->reference_no}}<br>
            {{_trans('file.customer')}}: {{$ot_crm_customer_data->name}}
        </p>
        <table class="table-data">
             <tbody class="tbody">
                <?php $total_product_tax = 0;?>
                @foreach($ot_crm_product_sale_data as $key => $product_sale_data)
                <?php 
                    $ot_crm_product_data = Modules\Sale\Entities\SaleProduct::find($product_sale_data->product_id);
                    if($product_sale_data->variant_id) {
                        $variant_data = Modules\Sale\Entities\SaleVariant::find($product_sale_data->variant_id);
                        $product_name = $ot_crm_product_data->name.' ['.$variant_data->name.']';
                    }
                    elseif($product_sale_data->product_batch_id) {
                        $product_batch_data = Modules\Sale\Entities\SaleProductBatch::select('batch_no')->find($product_sale_data->product_batch_id);
                        $product_name = $ot_crm_product_data->name.' ['._trans("file.Batch No").':'.$product_batch_data->batch_no.']';
                    }
                    else
                        $product_name = $ot_crm_product_data->name;

                    if($product_sale_data->imei_number) {
                        $product_name .= '<br>'._trans('IMEI or Serial Numbers').': '.$product_sale_data->imei_number;
                    }
                ?>
                <tr>
                    <td colspan="2">
                        {!!$product_name!!}
                        <br>{{$product_sale_data->qty}} x {{number_format((float)($product_sale_data->total / $product_sale_data->qty), 2, '.', '')}}

                        @if($product_sale_data->tax_rate)
                            <?php $total_product_tax += $product_sale_data->tax ?>
                            [{{_trans('file.Tax')}} ({{$product_sale_data->tax_rate}}%): {{$product_sale_data->tax}}]
                        @endif
                    </td>
                    <td style="text-align:right;vertical-align:bottom">{{number_format((float)$product_sale_data->total, 2, '.', '')}}</td>
                </tr>
                @endforeach
            
            <!-- <tfoot> -->
                <tr>
                    <th colspan="2" style="text-align:left">{{_trans('file.Total')}}</th>
                    <th style="text-align:right">{{number_format((float)$ot_crm_sale_data->total_price, 2, '.', '')}}</th>
                </tr>
                @if(@$general_setting->invoice_format == 'gst' && @$general_setting->state == 1)
                <tr>
                    <td colspan="2">IGST</td>
                    <td style="text-align:right">{{number_format((float)$total_product_tax, 2, '.', '')}}</td>
                </tr>
                @elseif(@$general_setting->invoice_format == 'gst' && @$general_setting->state == 2)
                <tr>
                    <td colspan="2">SGST</td>
                    <td style="text-align:right">{{number_format((float)($total_product_tax / 2), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td colspan="2">CGST</td>
                    <td style="text-align:right">{{number_format((float)($total_product_tax / 2), 2, '.', '')}}</td>
                </tr>
                @endif
                @if($ot_crm_sale_data->order_tax)
                <tr>
                    <th colspan="2" style="text-align:left">{{_trans('file.Order Tax')}}</th>
                    <th style="text-align:right">{{number_format((float)$ot_crm_sale_data->order_tax, 2, '.', '')}}</th>
                </tr>
                @endif
                @if($ot_crm_sale_data->order_discount)
                <tr>
                    <th colspan="2" style="text-align:left">{{_trans('file.Order Discount')}}</th>
                    <th style="text-align:right">{{number_format((float)$ot_crm_sale_data->order_discount, 2, '.', '')}}</th>
                </tr>
                @endif
                @if($ot_crm_sale_data->coupon_discount)
                <tr>
                    <th colspan="2" style="text-align:left">{{_trans('file.Coupon Discount')}}</th>
                    <th style="text-align:right">{{number_format((float)$ot_crm_sale_data->coupon_discount, 2, '.', '')}}</th>
                </tr>
                @endif
                @if($ot_crm_sale_data->shipping_cost)
                <tr>
                    <th colspan="2" style="text-align:left">{{_trans('file.Shipping Cost')}}</th>
                    <th style="text-align:right">{{number_format((float)$ot_crm_sale_data->shipping_cost, 2, '.', '')}}</th>
                </tr>
                @endif
                <tr>
                    <th colspan="2" style="text-align:left">{{_trans('file.grand total')}}</th>
                    <th style="text-align:right">{{number_format((float)$ot_crm_sale_data->grand_total, 2, '.', '')}}</th>
                </tr>
                <tr>
                    @if(@$general_setting->currency_position == 'prefix')
                    <th class="centered" colspan="3">{{_trans('file.In Words')}}: <span>{{@$currency->code}}</span> <span>{{numberTowords($numberInWords)}}</span></th>
                    @else
                    <th class="centered" colspan="3">{{_trans('file.In Words')}}: <span>{{numberTowords($numberInWords)}}</span> <span>{{@$currency->code}}</span></th>
                    @endif
                </tr>
            </tbody>
            <!-- </tfoot> -->
        </table>
        <table>
             <tbody class="tbody">
                @foreach($ot_crm_payment_data as $payment_data)
                <tr style="background-color:#ddd;">
                    <td style="padding: 5px;width:30%">{{_trans('file.Paid By')}}: {{$payment_data->paying_method}}</td>
                    <td style="padding: 5px;width:40%">{{_trans('file.Amount')}}: {{number_format((float)$payment_data->amount, 2, '.', '')}}</td>
                    <td style="padding: 5px;width:30%">{{_trans('file.Change')}}: {{number_format((float)$payment_data->change, 2, '.', '')}}</td>
                </tr>                
                @endforeach
                <tr><td class="centered" colspan="3">{{_trans('file.Thank you for shopping with us. Please come again')}}</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    localStorage.clear();
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>
