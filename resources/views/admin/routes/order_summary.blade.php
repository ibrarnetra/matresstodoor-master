@php
$currency_sign = '$';
if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != '') {
    $currency_sign = $order->currency->symbol_left;
}

@endphp

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-1">

                <h3 class="card-title">

                    <span class="card-label fw-bolder fs-3"><i class="fas fa-info-circle"></i>
                        &nbsp;Order # {{ $order->id }} Summary</span>


                </h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                fill="#000000">
                                <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1"></rect>
                                <rect fill="#000000" opacity="0.5"
                                    transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                    x="0" y="7" width="16" height="2" rx="1">
                                </rect>
                            </g>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="card-body">


                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table
                                class="table table-sm table-row-bordered table-column-bordered table-row-gray-300 border gs-3 gy-3">
                                <thead>
                                    <tr class="fw-bolder fs-6 text-gray-800">
                                        <th style="width: 40%;">Product</th>
                                        <th style="width: 20%;">Model</th>
                                        <th style="width: 10%; text-align: right;">Qty</th>
                                        <th style="width: 10%; text-align: right;">Return Qty</th>
                                        <th style="width: 15%; text-align: right;">Unit Price</th>
                                        <th style="width: 15%; text-align: right;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_products as $product)
                                        <tr>
                                            <td>
                                                {{ $product->name }}
                                                @if (count($product->order_options) > 0)
                                                    @foreach ($product->order_options as $option)
                                                        <br>
                                                        - {{ $option->name }}: {{ $option->value }}
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $product->product->model }}</td>
                                            <td class="text-end">{{ $product->quantity }}</td>
                                            <td class="text-center">{{ $product->return_quantity }}</td>
                                            <td class="text-end">
                                                @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($product->price) }}
                                            </td>
                                            <td class="text-end">
                                                @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($product->total) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach ($order->order_totals as $total)

                                        <tr>

                                            @if ($total->code == 'grand_total')
                                                @php $grand_total = $total->value; @endphp
                                            @endif
                                            @if ($total->code == 'grand_total')
                                                <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                                    {{ $total->title }}</td>
                                                <td colspan="2"  class="fw-bolder fs-6 text-gray-800 text-end">
                                                    @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($total->value) }}
                                                </td>
                                            @endif

                                        </tr>
                                    @endforeach
                                    @php
                                        $total_paid_amount = '0.00';
                                        $total_remaining_amount = '0.00';
                                        $total_return_amount = '0.00';
                                        foreach ($order->payments as $payment) {
                                            $total_paid_amount += $payment->paid_amount;
                                            $total_return_amount += $payment->return_amount;
                                        }
                                        if (isset($grand_total)) {
                                            $total_remaining_amount = $grand_total - $total_paid_amount - $total_return_amount;
                                        }
                                    @endphp

                                    <tr>
                                        <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                            Paid Amount</td>
                                        <td colspan="2" class="fw-bolder fs-6 text-gray-800 text-end">

                                            @if (isset($total_paid_amount))

                                                {{ $currency_sign }}{{ setDefaultPriceFormat($total_paid_amount) }}
                                            @else
                                                {{ $currency_sign }}0.00
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                            Remaining Amount</td>
                                        <td colspan="2" class="fw-bolder fs-6 text-gray-800 text-end">

                                            @if (isset($total_remaining_amount))
                                                {{ $currency_sign }}{{ setDefaultPriceFormat($total_remaining_amount) }}
                                            @else
                                                {{ $currency_sign }}0.00
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                            Return Amount</td>
                                        <td colspan="2" class="fw-bolder fs-6 text-gray-800 text-end">

                                            @if (isset($total_return_amount))
                                                {{ $currency_sign }}{{ setDefaultPriceFormat($total_return_amount) }}
                                            @else
                                                {{ $currency_sign }}0.00
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                            Delivery Date</td>
                                        <td colspan="2" class="fw-bolder fs-6 text-gray-800 text-end">
                                            @if ($order->delivery_date)
                                                {{ date(getConstant('DATE_FORMAT'),strtotime($order->delivery_date)) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="fs-6 text-gray-800 text-start"><span
                                                class="fw-bolder">Customer Notes:</span>
                                            @if ($order->customer_notes)
                                                {{ $order->customer_notes }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">


            <div class="card-body">
                {{-- @if (count($order->payments))
                    <div class="table-responsive">
                        <table
                            class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
                            <thead>
                                <tr class="fw-bolder fs-6 text-gray-800">
                                    <th>Payment Method</th>
                                    <th>Payment type</th>
                                    <th>Payment Mode</th>
                                    <th>Paid Amount</th>
                                    <th>Remaining Amount</th>
                                    <th>Date</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($order->payments as $payment)

                                    <tr>
                                        <td>{{ $payment->method }}</td>
                                        <td>{{ $payment->type }}</td>
                                        <td>{{ $payment->mode }}</td>
                                        <td> @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ $payment->paid_amount }}</td>
                                        <td> @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ $payment->remaining_amount }}</td>
                                        <td>{{ date('d-m-Y', strtotime($payment->updated_at)) }}
                                        </td>
                                    </tr>
                                    @if (count($payment->orderBills) > 0)
                                        <tr class="fw-bolder fs-6 text-gray-800">

                                            @foreach ($payment->orderBills as $bill)
                                                <th
                                                    style="width: {{ setDefaultPriceFormat(100 / count($order->order_bills)) }}%">
                                                    {{ ucfirst($bill->bill_type) }}</th>
                                            @endforeach

                                        </tr>
                                        @foreach ($payment->orderBills as $bill)
                                            <td
                                                style="width: {{ setDefaultPriceFormat(100 / count($order->order_bills)) }}%">
                                                {{ $bill->notes }}</td>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif --}}


                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <b>
                            <a class="nav-link active" id="tab-order-summary" href="javascript:void(0)"
                                onclick="changeTab(this, 'tab-order-summary')">Cash Summary</a>
                        </b>
                    </li>
                    <b>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-order-comment" href="javascript:void(0)"
                                onclick="changeTab(this,'tab-order-comment')">Order Comments</a>
                        </li>
                    </b>

                </ul>
                <div class="mt-5 route-order-cash">
                    @if (count($order->payments))
                        <div class="table-responsive">
                            <table
                                class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
                                <thead>
                                    <tr class="fw-bolder fs-6 text-gray-800">
                                        <th>Payment Method</th>
                                        <th>Payment type</th>
                                        <th>Payment Mode</th>
                                        <th>Paid Amount</th>
                                        <th>Remaining Amount</th>
                                        <th>Date</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($order->payments as $payment)

                                        <tr>
                                            <td>{{ $payment->method }}</td>
                                            <td>{{ $payment->type }}</td>
                                            <td>{{ $payment->mode }}</td>
                                            <td> @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ $payment->paid_amount }}</td>
                                            <td> @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ $payment->remaining_amount }}</td>
                                            <td>{{ date(getConstant('DATE_FORMAT'), strtotime($payment->updated_at)) }}
                                            </td>
                                        </tr>
                                        @if (count($payment->orderBills) > 0)
                                            <tr class="fw-bolder fs-6 text-gray-800">

                                                @foreach ($payment->orderBills as $bill)
                                                    <th
                                                        style="width: {{ setDefaultPriceFormat(100 / count($order->order_bills)) }}%">
                                                        {{ ucfirst($bill->bill_type) }}</th>
                                                @endforeach

                                            </tr>
                                            @foreach ($payment->orderBills as $bill)
                                                <td
                                                    style="width: {{ setDefaultPriceFormat(100 / count($order->order_bills)) }}%">
                                                    {{ $bill->notes }}</td>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="mt-5 d-none route-order-comment">
                    <div class="table-responsive">
                        <table
                            class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
                            <thead>
                                <tr class="fw-bolder fs-6 text-gray-800">
                                    <th style="width: 15%">Date Added</th>
                                    <th style="width: 35%">Comment</th>
                                    <th style="width: 10%; text-align: center;">
                                        Notified</th>
                                    <th style="width: 10%; text-align: center;">
                                        Status</th>
                                    <th style="width: 10%; text-align: center;">
                                        Delivery Date</th>
                                    <th style="width: 20%; text-align: center;">
                                        Updated By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->order_histories as $order_history)
                                    <tr>
                                        <td>{{ date(getConstant('DATE_FORMAT'), strtotime($order_history->created_at)) }}
                                        </td>
                                        <td>
                                            @if ($order_history->comment == '')
                                                N/A
                                            @else
                                                {{ $order_history->comment }}
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if ($order_history->notify == 0)
                                                No
                                            @else
                                                Yes
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            {{ $order_history->order_status->name }}
                                        </td>
                                        <td style="text-align: center;">
                                            @if ($order_history->delivery_date)
                                                {{ date(getConstant('DATE_FORMAT'), strtotime($order_history->delivery_date)) }}
                                            @else
                                                @if ($order->delivery_date)
                                                    {{ date(getConstant('DATE_FORMAT'), strtotime($order->delivery_date)) }}
                                                @else
                                                    N/A
                                                @endif
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if ($order_history->generated_by)
                                                {{ $order_history->generated_by->first_name . ' ' . $order_history->generated_by->last_name }}
                                                ({{ $order_history->generated_by->roles[0]->name }})
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



{{-- <div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-1">
                <h3 class="card-title">
                    <span class="card-label fw-bolder fs-3"><i class="far fa-comment-dots"></i>
                        &nbsp;Order History</span>
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#history">History</a>
                            </li>
                        </ul>

                        <div class="tab-content tabcontent-border mt-3">
                            <div class="tab-pane active" id="history" role="history">
                                <div class="row">
                                    <div class="col-md-12">
                                       
                                    </div>
                                </div> --}}
