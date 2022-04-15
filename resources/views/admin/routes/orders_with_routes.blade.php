<div class="row">
   <div class="col-md-12">
      <div class="table-responsive">
         <table class="table table-sm table-row-bordered table-striped gy-2 gs-2 align-middle" id="route-orders">
            <thead>
               <tr class="fw-bolder fs-6 text-gray-800">
                  <th style="width: 8%">Order #</th>
                  <th style="width: 15%">Customer</th>
                  <th style="width: 10%">Order Status</th>
                  <th style="width: 20%">Address</th>
                  <th style="width: 30%">Message</th>
                  <th style="width: 8%">Sort Order</th>
                  <th style="width: 5%">Action</th>
               </tr>
            </thead>
            <tbody>
               @if (count($orders) > 0)
               @php $a = 0 @endphp
               @foreach ($orders as $order)
               @php $a++ @endphp
               <tr>
                  <td>
                     {{$order->id}}
                  </td>
                  <td>
                     {{$order->first_name . " ". $order->last_name}}
                     <input class="order_status" type="hidden" name="order_status[]" value="{{$order->order_status->name}}" />
                     <input class="id" type="hidden" name="orders[{{$order->id}}][id]" value="{{$order->id}}" data-order-status="{{$order->order_status->name}}" @if ($order->route_location)
                     data-assigned="true"
                     @else
                     data-assigned="false"
                     @endif>
                  </td>
                  <td>
                     {{$order->order_status->name}}
                  </td>
                  <td>
                     <input class="shipping_address_1" type="hidden" name="orders[{{$order->id}}][shipping_address_1]" value="{{$order->shipping_address_1}}">
                     {{$order->shipping_address_1}}
                  </td>
                  <td>
                     <span class="text-danger custom-error">
                        @if ($order->order_status->name != "Pending" && $order->order_status->name != "Processing" && $order->order_status->name != "Ready" && $order->order_status->name != "Postpone")
                        This order can not be assigned to this route because it is already `{{$order->order_status->name}}`.
                        @endif
                        {{-- @if ($order->route_location)
                        This order can not be assigned to this route because it is already assigned to route `{{$order->route_location->route->name}}`.
                        @endif --}}
                     </span>
                  </td>
                  <td class="text-center sort-order">
                     <span class="sort-order-text">{{$a}}</span>
                     <input class="order_status_id" type="hidden" name="orders[{{$order->id}}][order_status_id]" value="{{$order->order_status->id}}" />
                     <input class="sort-order-value" type="hidden" name="orders[{{$order->id}}][sort_order]" value="{{$a}}">
                  </td>
                  <td class="text-center">
                     <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-active-light-primary" onclick="removeOrder(this)" title="Remove Order">
                        <i class="far fa-trash-alt" title="Remove Order"></i>
                     </a>
                  </td>
               </tr>
               @endforeach
               @else
               <tr>
                  <td colspan="6" class="text-center"><strong>No data found...</strong></td>
               </tr>
               @endif
            </tbody>
         </table>
      </div>
   </div>
</div>