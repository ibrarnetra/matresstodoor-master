<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   </head>

   <body>
      <div style="width: 80%; margin: auto; font-family: sans-serif;">
         <table style="width: 100%" cellpadding="5">
            <tr>
               <td style="text-align: left;"><strong>Order #{{$order->invoice_no}}</strong></td>
            </tr>
         </table>
         <table style="width: 100%; border-spacing: 0px;" cellpadding="5" border="1">
            <tr>
               <td colspan="2" style="text-align: left;"><strong>Order Details</strong></td>
            </tr>
            <tr>
               <td style="width: 50%; text-algin: left;">
                  @if ($order->store_id != 0)
                  <strong>Store: </strong>{{$order->store_name}}<br>
                  <strong>Telephone: </strong>{{$order->store->telephone}}<br>
                  <strong>E-Mail: </strong>{{$order->store->email}}
                  @endif
               </td>
               <td style="width: 50%; text-algin: left;">
                  <strong>Date Added: </strong>{{date('Y-m-d', strtotime($order->created_at))}}<br>
                  <strong>Order ID: </strong>{{$order->id}}<br>
                  @if ($order->payment_method_id != 0)
                  <strong>Payment Method: </strong>{{$order->payment_method}} ({{$order->payment_method_code}})<br>
                  @endif
                  @if ($order->shipping_method_id != 0)
                  <strong>Shipping Method: </strong>{{$order->shipping_method}} ({{$order->shipping_method_code}})
                  @endif
               </td>
            </tr>
         </table>
         <table style="width: 100%;" cellpadding="5">
            <tr>
               <td></td>
            </tr>
         </table>
         <table style="width: 100%; border-spacing: 0px;" cellpadding="5" border="1">
            <tr>
               <td style=""><strong>Payment Address</strong></td>
               <td style=""><strong>Shipping Address</strong></td>
            </tr>
            <tr>
               <td style="width: 50%; text-algin: right;">
                  <span style="">{{$order->payment_first_name .' '. $order->payment_last_name}}</span><br>
                  <span style="">{{$order->payment_address_1}}</span><br>
                  @if (isset($order->payment_postcode) && !is_null($order->payment_postcode) && $order->payment_postcode == "")
                  <span style="">{{$order->payment_postcode}}</span><br>
                  @endif
                  <span style="">{{$order->payment_country}}</span><br>
                  <span style="">{{$order->payment_zone}}</span><br>
                  <span style="">{{$order->payment_city}}</span>
               </td>
               <td style="width: 50%; text-algin: right;">
                  <span style="">{{$order->shipping_first_name .' '. $order->shipping_last_name}}</span><br>
                  <span style="">{{$order->shipping_address_1}}</span><br>
                  @if (isset($order->shipping_postcode) && !is_null($order->shipping_postcode) && $order->shipping_postcode == "")
                  <span style="">{{$order->shipping_postcode}}</span><br>
                  @endif
                  <span style="">{{$order->shipping_country}}</span><br>
                  <span style="">{{$order->shipping_zone}}</span><br>
                  <span style="">{{$order->shipping_city}}</span>
               </td>
            </tr>
         </table>
         <table style="width: 100%;" cellpadding="5">
            <tr>
               <td></td>
            </tr>
         </table>
         <table style="width: 100%; border-spacing: 0px;" cellpadding="5" border="1">
            <tr>
               <td style="width: 30%;"><strong>Product</strong></td>
               <td style="width: 20%;"><strong>Model</strong></td>
               <td style="width: 13%; text-align: right;"><strong>Qty</strong></td>
               <td style="width: 18%; text-align: right;"><strong>Unit Price</strong></td>
               <td style="width: 19%; text-align: right;"><strong>Total</strong></td>
            </tr>
            @foreach ($order->order_products as $product)
            <tr>
               <td>
                  {{$product->name}}
                  @if(count($product->order_options) > 0)
                  @foreach($product->order_options as $option)
                  <br>
                  - {{$option->name}}: {{$option->value}}
                  @endforeach
                  @endif
               </td>
               <td>{{$product->product->model}}</td>
               <td style="text-align: right;">{{$product->quantity}}</td>
               <td style="text-align: right;">
                  @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left !=
                  ""){{$order->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($product->price)}}
               </td>
               <td style="text-align: right;">
                  @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left !=
                  ""){{$order->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($product->total)}}
               </td>
            </tr>
            @endforeach
            @foreach ($order->order_totals as $total)
            <tr>
               @if ($total->code == "shipping")
               <td colspan="4" style="text-align: right;"><strong>Shipping ({{$total->title}})</strong></td>
               <td style="text-align: right;">
                  <strong>@if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) &&
                     $order->currency->symbol_left
                     !=
                     ""){{$order->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($total->value)}}</strong>
               </td>
               @elseif ($total->code == "payment_method")
               <td colspan="4" style="text-align: right;"><strong>Payment Method</strong></td>
               <td style="text-align: right;"><strong>{{$total->title}}</strong></td>
               @elseif ($total->code == "payment_type")
               <td colspan="4" style="text-align: right;"><strong>Payment Type</strong></td>
               <td style="text-align: right;"><strong>{{$total->title}}</strong></td>
               @elseif ($total->code == "discount")
               <td colspan="4" style="text-align: right;"><strong>Discount ({{$total->title}})</strong></td>
               <td style="text-align: right;"><strong>@if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) &&
                     $order->currency->symbol_left
                     !=
                     ""){{$order->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($total->value)}}</strong></td>
               @else
               <td colspan="4" style="text-align: right;"><strong>{{$total->title}}</strong></td>
               <td style="text-align: right;"><strong>@if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) &&
                     $order->currency->symbol_left
                     !=
                     ""){{$order->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($total->value)}}</strong></td>
               @endif
            </tr>
            @endforeach
            <tr>
               <td colspan="4" style="text-align: right;"><strong>Delivery Date</strong></td>
               <td style="text-align: right;">
                  <strong>
                     @if ($order->delivery_date)
                     {{$order->delivery_date}}
                     @else
                     N/A
                     @endif
                  </strong>
               </td>
            </tr>
            <tr>
               <td colspan="5" style="text-align: left;"><strong>Customer Notes:</strong> @if ($order->customer_notes)
                  {{$order->customer_notes}}
                  @else
                  N/A
                  @endif</td>
            </tr>
         </table>
      </div>
   </body>

</html>