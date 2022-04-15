<div style="width: 100%; font-family: sans-serif;">
   
    <table style="width: 100%" cellpadding="5">
        <tr>
            <td style="font-size: 20px; text-align: left;"><strong>{{$purchase->invoice_no}}</strong></td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 10px; border-spacing: 0px;" cellpadding="5" border="1">
        <tr>
            <td colspan="2" style="text-align: left;"><strong>Purchase Details</strong></td>
        </tr>
      
        <tr>
            <td style="width: 50%; text-algin: left;">
                @if ($purchase->warehouse)
                <strong>Warehouse: </strong>{{$purchase->warehouse->name}}<br>
                <strong>Telephone: </strong>{{$purchase->warehouse->contact_no}}<br>
                <strong>E-Mail: </strong>{{$purchase->warehouse->email}}
                @endif
            </td>
            <td style="width: 50%; text-algin: left;">
                <strong>Date Added: </strong>{{date(getConstant('DATE_FORMAT'), strtotime($purchase->created_at))}}<br>
                <strong>Purchase ID: </strong>{{$purchase->id}}<br>
                <strong>Serial No: </strong>{{$purchase->serial_no}}<br>
                @if($purchase->vehicle_no)
                <strong>Vehicle No: </strong>{{$purchase->serial_no}}<br>
                @endif
                 
            </td>
        </tr>
    </table>
  
 
    <table style="width: 100%; font-size: 10px;" cellpadding="5">
        <tr>
            <td></td>
        </tr>
    </table>
   
   
    <table style="width: 100%; font-size: 10px; border-spacing: 0px;" cellpadding="5" border="1">
        <tr>
            <td style="width: 50%;"><strong>Product</strong></td>
            <td style="width: 25%;"><strong>Model</strong></td>
            <td style="width: 25%; text-align: right;"><strong>Qty</strong></td>
            {{-- <td style="width: 18%; text-align: right;"><strong>Unit Price</strong></td>
            <td style="width: 19%; text-align: right;"><strong>Total</strong></td> --}}
        </tr>
      
        @foreach ($purchase->purchase_products as $detail)
        <tr>
      
           
            <td>
                {{$detail->name}}
                @if(isset($detail->purchase_options) && $detail->purchase_options)
                @foreach($detail->purchase_options as $purchase_option)
                <br>
                - {{$purchase_option->name}}: {{$purchase_option->value}}
                @endforeach
                @endif
               
            </td>
            <td>{{$detail->product->model}}</td>
           
            <td style="text-align: right;">{{$detail->quantity}}</td>
            {{-- <td style="text-align: right;">
                @if (isset($purchase->currency->symbol_left) && !is_null($purchase->currency->symbol_left) && $purchase->currency->symbol_left !=
                ""){{$purchase->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($detail->purchase_unit_price)}}
            </td>
           
            <td style="text-align: right;">
                @if (isset($purchase->currency->symbol_left) && !is_null($purchase->currency->symbol_left) && $purchase->currency->symbol_left !=
                ""){{$purchase->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($detail->total_amount)}}
            </td> --}}
          
        </tr>
   
        @endforeach
      
        {{-- <tr>
                                        
            <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">Total Purchase Amount</td>
            <td class="fw-bolder fs-6 text-gray-800 text-end">@if (isset($purchase->currency->symbol_left) && !is_null($purchase->currency->symbol_left) &&
               $purchase->currency->symbol_left
               !=
               ""){{$purchase->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($purchase->purchase_total_amount)}}</td>
         </tr>
         <tr>
                <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">Total Discount Amount</td>
                <td class="fw-bolder fs-6 text-gray-800 text-end">@if (isset($purchase->currency->symbol_left) && !is_null($purchase->currency->symbol_left) &&
                   $purchase->currency->symbol_left
                   !=
                   ""){{$purchase->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($purchase->purchase_discount_amount)}}</td>
         </tr>
         <tr>
                    <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">Total Payable Amount</td>
                    <td class="fw-bolder fs-6 text-gray-800 text-end">@if (isset($purchase->currency->symbol_left) && !is_null($purchase->currency->symbol_left) &&
                       $purchase->currency->symbol_left
                       !=
                       ""){{$purchase->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($purchase->purchase_total_payable_amount)}}</td>
          
         </tr> --}}
        
        <tr>
            <td colspan="2" style="text-align: right;"><strong>Purchase Date</strong></td>
            <td style="text-align: right;">
                <strong>
                    @if ($purchase->purchase_date)
                    {{date(getConstant('DATE_FORMAT'),strtotime($purchase->purchase_date))}}
                    @else
                    N/A
                    @endif
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: left;"><strong>Remarks:</strong> @if ($purchase->remarks)
                {{$purchase->remarks}}
                @else
                N/A
                @endif</td>
        </tr>
    </table>
</div>