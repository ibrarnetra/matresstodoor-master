<table id="generic-datatable" class="table table-striped table-row-bordered table-sm gy-2 gs-2 align-middle">
    <thead>
       @php 
         $paid_amount = 0;
      @endphp
     
       <tr class="fw-bolder fs-6 text-gray-800">
          <th>Payment Method</th>
          <th>Payment type</th>
          <th >Payment Moess</th>
          <th>Paid Amount</th>
          <th>Remaining Amount</th>
          <th>Date</th>
        
       </tr>
    </thead>
    <tbody>
      @if(count($payments)>0)
     
     @foreach($payments as $payment)
    @php
       $paid_amount += $payment->paid_amount;
    @endphp
     <tr>
        <td>{{$payment->method}}</td>
        <td>{{$payment->type}}</td>
        <td>{{$payment->mode}}</td>
        <td>{{$payment->paid_amount}}</td>
        <td>{{$payment->remaining_amount}}</td>
        <td>{{date("d-m-Y", strtotime($payment->updated_at));  }}</td>
     </tr>
     @if(count($payment->orderBills)>0)
     <tr class="fw-bolder fs-6 text-gray-800">
    
      @foreach ($payment->orderBills as $bill)
      <th style="width: {{setDefaultPriceFormat(100 / count($payment->orderBills))}}%">{{ucfirst($bill->bill_type)}}</th>
      @endforeach
     
   </tr>
      @foreach ($payment->orderBills as $bill)
      <td style="width: {{setDefaultPriceFormat(100 / count($payment->orderBills))}}%">{{$bill->notes}}</td>
      @endforeach
      @endif
 
       @endforeach

       @else
       <tr>
          <td colspan="7" class="text-center"><strong>No Data Found...</strong></td>
       </tr>
       @endif
    </tbody>
    <tfoot>
       <tr>
          <td colspan="6" class="text-end fw-bolder fs-6 text-gray-800">Total Paid Amount</td>
          <td class="text-center fw-bolder fs-6 text-gray-800">${{setDefaultPriceFormat($paid_amount)}}</td>
       </tr>
    </tfoot>
 </table>