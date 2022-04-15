@if (count($store_sale_summaries))
<div class="row mb-5">
   @foreach ($store_sale_summaries as $key => $store_sale_summary)
   <div class="col-md-6 mb-5">
      <div class="card shadow-sm">
         <div class="card-body">
            <h5 class="card-title">{{$key}}</h5>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Orders</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$store_sale_summary['total_orders']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Orders Done</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$store_sale_summary['total_orders_done']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Orders Postponed</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$store_sale_summary['total_orders_postponed']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Orders Canceled</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$store_sale_summary['total_orders_canceled']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Orders Pending</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$store_sale_summary['total_orders_pending']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Amount</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">${{setDefaultPriceFormat($store_sale_summary['total_amount'])}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Paid Amount</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">${{setDefaultPriceFormat($store_sale_summary['total_paid_amount'])}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Remaining Amount</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">${{setDefaultPriceFormat($store_sale_summary['total_remaining_amount'])}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text"><button onclick="orderDetail('{{$store_sale_summary['store_id']}}')" class="btn btn-primary btn-sm"><i class="far fa-eye me-2"></i>Detail</button></p>
               </div>
            </div>
            
         </div>
      </div>
   </div>
   @endforeach
</div>
@else
<div class="row mb-5">
   <div class="col-md-6 mb-5">
      <div class="card shadow-sm">
         <div class="card-body">
            <h5 class="card-title">No data found...</h5>
         </div>
      </div>
   </div>
</div>
@endif