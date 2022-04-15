<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   </head>

   <body>
      <div style="width: 100%; margin: auto; font-family: sans-serif;">
         <p>Order ID: <strong>{{$order_id}}</strong></p>
         <p>Date Added: <strong>{{date('Y-m-d', strtotime($order_created_at))}}</strong></p>
         <p>Your order has been updated to the following status:</p>
         <p>{{$order_status}}</p>
         <p>To view your order click on the link below:</p>
         <p><a href="{{route('frontend.orderDetail', ['id'=>$order_id])}}">{{getConstant('APP_NAME')}} | Order Detail</a></p>
      </div>
   </body>

</html>