@if (count($res['team_members']) > 0)
<div class="row">
   <div class="col-md-4">
      <div class="card shadow-sm">
         <div class="card-body">
            <h5 class="card-title">Me</h5>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Customers</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$res['total_customers']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Orders</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$res['total_orders']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Sales</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">${{$res['total_sales']}}</p>
               </div>
            </div>
         </div>
      </div>
   </div>
   @foreach ($res['team_members'] as $team_member)
 
   <div class="col-md-4 mb-5">
      <div class="card shadow-sm">
         <div class="card-body">
            <h5 class="card-title">{{$team_member['full_name']}}</h5>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Customers</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$team_member['total_customers']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Orders</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$team_member['total_orders']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Sales</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">${{setDefaultPriceFormat($team_member['total_sales'])}}</p>
               </div>
            </div>
         </div>
      </div>
   </div>
   @endforeach
</div>
@else
@if (Auth::guard('web')->user()->hasRole("Super Admin") ||
Auth::guard('web')->user()->hasRole("Office Admin"))
<div class="row">
   @if (count($sale_rep_stats) > 0)
   @foreach ($sale_rep_stats as $sale_rep_stat)
   @if($sale_rep_stat['total_orders']>0 || $sale_rep_stat['total_customers']> 0 || $sale_rep_stat['total_sales']>0 )
   <div class="col-md-4 mb-5">
      <div class="card shadow-sm">
         <div class="card-body">
            <h5 class="card-title">{{$sale_rep_stat['full_name']}}</h5>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Customers</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$sale_rep_stat['total_customers']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Orders</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">{{$sale_rep_stat['total_orders']}}</p>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 text-start">
                  <p class="card-text">Total Sales</p>
               </div>
               <div class="col-md-6 text-end">
                  <p class="card-text">${{setDefaultPriceFormat($sale_rep_stat['total_sales'])}}</p>
               </div>
            </div>
         </div>
      </div>
   </div>
   @endif
   @endforeach
   @else
   <div class="col-md-4">
      <!--begin: Statistics Widget 6-->
      <div class="card bg-light-success card-xl-stretch mb-xl-8 shadow-sm">
         <!--begin::Body-->
         <div class="card-body my-3">
            <a href="javascript:void(0);" class="card-title fw-bolder text-success fs-5 mb-3 d-block">Total Orders</a>
            <div class="py-1">
               <span class="text-dark fs-1 fw-bolder me-2" id="total-orders">{{$res['total_orders']}}</span>
            </div>
            @php
            $per_order = 0;
            if($res['total_orders'] > 0){
            $per_order = 100;
            }
            @endphp
            <div class="progress h-7px bg-success bg-opacity-50 mt-7">
               <div class="progress-bar bg-success" role="progressbar" id="total-orders-progress" style="width: {{$per_order}}%" aria-valuenow="{{$per_order}}" aria-valuemin="0" aria-valuemax="100">
               </div>
            </div>
         </div>
         <!--end:: Body-->
      </div>
      <!--end: Statistics Widget 6-->
   </div>
   <div class="col-md-4">
      <!--begin: Statistics Widget 6-->
      <div class="card bg-light-warning card-xl-stretch mb-xl-8 shadow-sm">
         <!--begin::Body-->
         <div class="card-body my-3">
            <a href="javascript:void(0);" class="card-title fw-bolder text-warning fs-5 mb-3 d-block">Total Sales</a>
            <div class="py-1">
               <span class="text-dark fs-1 fw-bolder me-2" id="total-sales">{{"$".setDefaultPriceFormat($res['total_sales'])}}</span>
            </div>
            @php
            $per_sale = 0;
            if($res['total_sales'] > 0){
            $per_sale = 100;
            }
            @endphp
            <div class="progress h-7px bg-warning bg-opacity-50 mt-7">
               <div class="progress-bar bg-warning" role="progressbar" id="total-sales-progress" style="width: {{$per_sale}}%" aria-valuenow="{{$per_sale}}" aria-valuemin="0" aria-valuemax="100">
               </div>
            </div>
         </div>
         <!--end:: Body-->
      </div>
      <!--end: Statistics Widget 6-->
   </div>
   <div class="col-md-4">
      <!--begin: Statistics Widget 6-->
      <div class="card bg-light-primary card-xl-stretch mb-5 mb-xl-8 shadow-sm">
         <!--begin::Body-->
         <div class="card-body my-3">
            <a href="javascript:void(0);" class="card-title fw-bolder text-primary fs-5 mb-3 d-block">Total Customers</a>
            <div class="py-1">
               <span class="text-dark fs-1 fw-bolder me-2" id="total-customers">{{$res['total_customers']}}</span>
            </div>
            @php
            $per_customer = 0;
            if($res['total_customers'] > 0){
            $per_customer = 100;
            }
            @endphp
            <div class="progress h-7px bg-primary bg-opacity-50 mt-7">
               <div class="progress-bar bg-primary" role="progressbar" id="total-customers-progress" style="width: {{$per_customer}}%" aria-valuenow="{{$per_customer}}" aria-valuemin="0"
                  aria-valuemax="100"></div>
            </div>
         </div>
         <!--end:: Body-->
      </div>
      <!--end: Statistics Widget 6-->
   </div>
   @endif
</div>
@else
<div class="row">
   <div class="col-md-4">
      <!--begin: Statistics Widget 6-->
      <div class="card bg-light-success card-xl-stretch mb-xl-8 shadow-sm">
         <!--begin::Body-->
         <div class="card-body my-3">
            <a href="javascript:void(0);" class="card-title fw-bolder text-success fs-5 mb-3 d-block">Total Orders</a>
            <div class="py-1">
               <span class="text-dark fs-1 fw-bolder me-2" id="total-orders">{{$res['total_orders']}}</span>
            </div>
            @php
            $per_order = 0;
            if($res['total_orders'] > 0){
            $per_order = 100;
            }
            @endphp
            <div class="progress h-7px bg-success bg-opacity-50 mt-7">
               <div class="progress-bar bg-success" role="progressbar" id="total-orders-progress" style="width: {{$per_order}}%" aria-valuenow="{{$per_order}}" aria-valuemin="0" aria-valuemax="100">
               </div>
            </div>
         </div>
         <!--end:: Body-->
      </div>
      <!--end: Statistics Widget 6-->
   </div>
   <div class="col-md-4">
      <!--begin: Statistics Widget 6-->
      <div class="card bg-light-warning card-xl-stretch mb-xl-8 shadow-sm">
         <!--begin::Body-->
         <div class="card-body my-3">
            <a href="javascript:void(0);" class="card-title fw-bolder text-warning fs-5 mb-3 d-block">Total Sales</a>
            <div class="py-1">
               <span class="text-dark fs-1 fw-bolder me-2" id="total-sales">{{"$".setDefaultPriceFormat($res['total_sales'])}}</span>
            </div>
            @php
            $per_sale = 0;
            if($res['total_sales'] > 0){
            $per_sale = 100;
            }
            @endphp
            <div class="progress h-7px bg-warning bg-opacity-50 mt-7">
               <div class="progress-bar bg-warning" role="progressbar" id="total-sales-progress" style="width: {{$per_sale}}%" aria-valuenow="{{$per_sale}}" aria-valuemin="0" aria-valuemax="100">
               </div>
            </div>
         </div>
         <!--end:: Body-->
      </div>
      <!--end: Statistics Widget 6-->
   </div>
   <div class="col-md-4">
      <!--begin: Statistics Widget 6-->
      <div class="card bg-light-primary card-xl-stretch mb-5 mb-xl-8 shadow-sm">
         <!--begin::Body-->
         <div class="card-body my-3">
            <a href="javascript:void(0);" class="card-title fw-bolder text-primary fs-5 mb-3 d-block">Total Customers</a>
            <div class="py-1">
               <span class="text-dark fs-1 fw-bolder me-2" id="total-customers">{{$res['total_customers']}}</span>
            </div>
            @php
            $per_customer = 0;
            if($res['total_customers'] > 0){
            $per_customer = 100;
            }
            @endphp
            <div class="progress h-7px bg-primary bg-opacity-50 mt-7">
               <div class="progress-bar bg-primary" role="progressbar" id="total-customers-progress" style="width: {{$per_customer}}%" aria-valuenow="{{$per_customer}}" aria-valuemin="0"
                  aria-valuemax="100"></div>
            </div>
         </div>
         <!--end:: Body-->
      </div>
      <!--end: Statistics Widget 6-->
   </div>
</div>
@endif
@endif