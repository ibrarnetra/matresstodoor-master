<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   </head>

   <body>
      <div style="width: 100%; margin: auto; font-family: sans-serif;">
         <p>You have received an order.</p>
         <p>Order ID: <strong>{{$order->id}}</strong></p>
         <p>Date Added: <strong>{{formatGivenDateTime($order->created_at,'Y-m-d')}}</strong></p>
         <p>Order Status: <strong>{{$order->order_status->name}}</strong></p>

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
         </table>
      </div>
   </body>

</html>