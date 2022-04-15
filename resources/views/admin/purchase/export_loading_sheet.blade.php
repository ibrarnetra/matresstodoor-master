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
   @if ($date != date('Y-m-d', strtotime($order->delivery_date)))
   <thead>
      <tr>
         <th colspan="{{2 + $total_options}}" rowspan="2" style="text-align: center;"><strong>{{date('F jS, Y', strtotime($order->delivery_date))}}</strong></th>
      </tr>
      <tr>
         <th colspan="{{2 + $total_options}}"></th>
      </tr>
      <tr>
         <th><strong>Product</strong></th>
         @foreach ($options as $val)
         <th><strong>{{$val->eng_description->name}}</strong></th>
         @endforeach
         <th><strong>Mattress</strong></th>
      </tr>
   </thead>
   @endif

   <tbody>
      @foreach ($order->order_products as $key => $product)
      <tr>
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
      </tr>
      @endforeach
   </tbody>

   @php
   $date = date('Y-m-d', strtotime($order->delivery_date));
   @endphp
   @endforeach
   @endif
</table>