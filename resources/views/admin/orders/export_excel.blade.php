<table border="" cellpadding="8" style="border-spacing: 0px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">
   @php
   $date = '';
   $total_orders = count($orders);
   $total_options = count($options);
   $a = 0;
   @endphp
   @if ($total_orders > 0)
   @foreach($orders as $order)
   @php
   $a++;
   @endphp
   @if ($date != date(getConstant('DATE_FORMAT'), strtotime($order->created_at)))
   <thead>
      <tr>
         <th colspan="{{16 + $total_options}}" rowspan="2" style="text-align: center;"><strong>{{date('F jS, Y', strtotime($order->created_at))}}</strong></th>
      </tr>
      <tr>
         <th colspan="{{16 + $total_options}}"></th>
      </tr>
     
      <tr>
         <th><strong>Date</strong></th>
         <th><strong>No.</strong></th>
         <th><strong>Order No.</strong></th>
         <th><strong>Customer Name</strong></th>
         <th><strong>PH#</strong></th>
         <th><strong>City</strong></th>
         <th><strong>Product</strong></th>
         @foreach ($options as $val)
         <th><strong>{{$val->eng_description->name}}</strong></th>
         @endforeach
         <th><strong>Mattress</strong></th>
         <th><strong>Amt on</strong></th>
         <th><strong>Address</strong></th>
         <th><strong>Total</strong></th>
         <th><strong>Customer Notes</strong></th>
         <th><strong>Status</strong></th>
         <th><strong>Extra info</strong></th>
         <th><strong>Delivery Date</strong></th>
         <th><strong>Bifercated Prices</strong></th>
         <th><strong>Email ID</strong></th>
      </tr>
   </thead>
   @endif

   <tbody>
      @foreach ($order->order_products as $key => $product)
      <tr>
       
         @if ($key == 0)
         <td rowspan="{{count($order->order_products)}}">{{ date('jS', strtotime($order->created_at)) }}</td>
         <td rowspan="{{count($order->order_products)}}">{{ $a }}</td>
         <td rowspan="{{count($order->order_products)}}">{{ $order->id }}</td>
         <td rowspan="{{count($order->order_products)}}">{{ $order->first_name .' '. $order->last_name }}</td>
         <td rowspan="{{count($order->order_products)}}">{{ $order->telephone }}</td>
         <td rowspan="{{count($order->order_products)}}">{{ $order->shipping_city }}</td>
         @endif

         <td>
            {{$product->name}}
         </td>
        
         @foreach ($options as $val)
         <td>
            @if(count($product->order_options) > 0)
            @foreach($product->order_options as $option)
            @if ($val->eng_description->name == $option->name)
            {{$option->value}},&nbsp;
            @endif
            @endforeach
            @endif
         </td>
         @endforeach
         <td style="text-align: right;">{{$product->quantity}}</td>
         @php 
         $remaining_amount = "0.00";
          list($payment_exists, $remaining_amount) = getRemainingAmountFromPayments($order->id);
          @endphp
           
         @if ($key == 0)
         <td rowspan="{{count($order->order_products)}}">{{setDefaultPriceFormat($remaining_amount)}}</td>
         <td rowspan="{{count($order->order_products)}}">{{ $order->shipping_address_1 }}</td>
         <td rowspan="{{count($order->order_products)}}">{{setDefaultPriceFormat($order->total)}}</td>
         <td rowspan="{{count($order->order_products)}}">{{$order->customer_notes}}</td>
         <td rowspan="{{count($order->order_products)}}">{{ $order->order_status->name }}</td>
         <td rowspan="{{count($order->order_products)}}"></td>
         <td rowspan="{{count($order->order_products)}}">{{date(getConstant('DATE_FORMAT'), strtotime($order->delivery_date))}}</td>
         @endif
        
         <td>{{setDefaultPriceFormat($product->price)}}</td>
       
         @if ($key == 0)
         <td rowspan="{{count($order->order_products)}}">{{ $order->email }}</td>
     
         @endif
      </tr>
      @endforeach
    
   </tbody>
  

   @php
   
   $date = date(getConstant('DATE_FORMAT'), strtotime($order->created_at));
  
   @endphp
   @endforeach
   @endif
</table>