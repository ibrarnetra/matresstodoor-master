<div class="row">
    <div class="col-md-6">
        <div class="table-responsive">
            <table class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
                <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <td>
                            <strong><i class="fas fa-user"></i> &nbsp;Customer Details</strong>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span title="Customer">
                                {{ $order->first_name . ' ' . $order->last_name }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span title="Email">
                                {{ $order->email }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span title="Telephone">
                                {{ $order->telephone }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-6">
        <div class="table-responsive">
            <table class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
                <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <td>
                            <strong><i class="fas fa-truck"></i> &nbsp;Shipping Details</strong>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $order->shipping_first_name . ' ' . $order->shipping_last_name }}</td>
                    </tr>
                    <tr>
                        <td>
                            @if (isset($order->shipping_postcode) && !is_null($order->shipping_postcode) && $order->shipping_postcode != '')
                                {{ $order->shipping_city . ', ' . $order->shipping_postcode }}
                            @else
                                {{ $order->shipping_city }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $order->shipping_zone . ', ' . $order->shipping_country }}</td>
                    </tr>
                    <tr>
                        <td>{{ $order->shipping_address_1 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@php
$currency_sign = '$';
if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != '') {
    $currency_sign = $order->currency->symbol_left;
}

@endphp

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
                <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <th style="width: 40%;">Product</th>
                        <th style="width: 20%;">Model</th>
                        <th class="text-end" style="width: 10%;">Qty</th>
                        <th class="text-end" style="width: 15%;">Unit Price</th>
                        <th class="text-end" style="width: 15%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->order_products as $product)
                        <tr>
                            <td style="width: 40%;">
                                {{ $product->name }}
                                @if (count($product->order_options) > 0)
                                    @foreach ($product->order_options as $option)
                                        <br>
                                        - {{ $option->name }}: {{ $option->value }}
                                    @endforeach
                                @endif
                            </td>
                            <td style="width: 20%;">{{ $product->product->model }}</td>
                            <td class="text-end" style="width: 10%;">{{ $product->quantity }}</td>
                            <td class="text-end" style="width: 15%;">
                                @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($product->price) }}
                            </td>
                            <td class="text-end" style="width: 15%;">
                                @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($product->total) }}
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($order->order_totals as $total)
                        <tr>
                            @if ($total->code == 'shipping')
                                <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">Shipping
                                    ({{ $total->title }})</td>
                                <td class="fw-bolder fs-6 text-gray-800 text-end">
                                    @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($total->value) }}</td>
                            @elseif ($total->code == "payment_method")
                                <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">Payment Method</td>
                                <td class="fw-bolder fs-6 text-gray-800 text-end">{{ $total->title }}</td>
                            @elseif ($total->code == "payment_type")
                                <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">Payment Type</td>
                                <td class="fw-bolder fs-6 text-gray-800 text-end">{{ $total->title }}</td>
                            @elseif ($total->code == "payment_mode")
                                <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">Payment Mode</td>
                                <td class="fw-bolder fs-6 text-gray-800 text-end">{{ $total->title }}</td>
                            @elseif ($total->code == "discount")
                                <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">Discount
                                    ({{ $total->title }})</td>
                                <td class="fw-bolder fs-6 text-gray-800 text-end">
                                    @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($total->value) }}</td>
                            @else
                                @if ($total->code == 'grand_total')
                                    @php $grand_total = $total->value; @endphp
                                @endif
                                @if ($total->code != 'paid_amount' && $total->code != 'remaining_amount')
                                    <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">{{ $total->title }}
                                    </td>
                                    <td class="fw-bolder fs-6 text-gray-800 text-end">
                                        @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($total->value) }}</td>
                                @endif
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
                        <td class="fw-bolder fs-6 text-gray-800 text-end">

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
                        <td class="fw-bolder fs-6 text-gray-800 text-end">

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
                        <td class="fw-bolder fs-6 text-gray-800 text-end">

                            @if (isset($total_return_amount))
                                {{ $currency_sign }}{{ setDefaultPriceFormat($total_return_amount) }}
                            @else
                                {{ $currency_sign }}0.00
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row" id="payment-recieved-row">
    <div class="mb-5 col-md-6" id="remaing_amount">
        <label class="form-label" for="r-amount">Remaining Amount </label>
        @php
            $total_amount = setDefaultPriceFormat($order->total);
            /**
             * check to see whether there was a payment entry on `payments` table
             * if there was a `payment` then the `remaining amount` is the amount `payable`
             * if there was no `payment` then the `order total` is the amount `payable`
             */
            [$payment_exists, $remaining_amount] = getRemainingAmountFromPayments($order->id);
            $remaining_amount = $payment_exists ? setDefaultPriceFormat($remaining_amount) : $total_amount;
        @endphp
        <input class="form-control form-control-solid" type="hidden" id="total_remaining_amount"
            value="{{ $remaining_amount }}">
        <input class="form-control form-control-solid" type="text" id="r-amount" value="{{ $remaining_amount }}"
            {{ $remaining_amount > 0 ? '' : 'disabled' }}>
    </div>

    <div class="mb-5 col-md-6 d-none" id="payment-mode-section">
        <label for="payment_mode" class="form-label required">Payment Mode</label>
        <select class="form-select form-select-solid" aria-label="payment_mode" id="payment_mode" name="payment_mode"
            {{ $remaining_amount > 0 ? '' : 'disabled' }} onchange="hideShowBillSection()"
            oninvalid="this.setCustomValidity('Please select the payment mode')" oninput="this.setCustomValidity('')">
            <option value="" selected>--Please select payment mode--</option>
            <option value="online transfer">Online Transfer</option>
            <option value="cash">Cash</option>
            <option value="card">Card (Credit/Debit)</option>
            <option value="cash-card">Both Cash & Card</option>
            <option value="cash-online">Both Cash & Online Transfer</option>
        </select>
    </div>
    <div class="row d-none bills-section">
        <div class="mb-5 offset-md-6 col-md-6">
            <div class="form-group d-none" id="both-cash-card-amount">
                <label class="form-label">Cash Amount</label>
            <input class="form-control form-control-solid" required type="number" placeholder="cash amount" name="both_cash_amount" id="both-cash-amount" value="0">
              <br />
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-5">
                    <label for="hundred" class="form-label">100's</label>
                    <input class="form-control form-control-solid form-control-sm" type="number" placeholder="100"
                        id="hundred" name="bills[hundred]" value="{{ old('hundred') }}">
                </div>
                <div class="col-md-6 mb-5">
                    <label for="fifty" class="form-label">50's</label>
                    <input class="form-control form-control-solid form-control-sm" type="number" placeholder="50"
                        id="fifty" name="bills[fifty]" value="{{ old('fifty') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-5">
                    <label for="twenty" class="form-label">20's</label>
                    <input class="form-control form-control-solid form-control-sm" type="number" placeholder="20"
                        id="twenty" name="bills[twenty]" value="{{ old('twenty') }}">
                </div>
                <div class="col-md-6 mb-5">
                    <label for="ten" class="form-label">10's</label>
                    <input class="form-control form-control-solid form-control-sm" type="number" placeholder="10"
                        id="ten" name="bills[ten]" value="{{ old('ten') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-5">
                    <label for="five" class="form-label">5's</label>
                    <input class="form-control form-control-solid form-control-sm" type="number" placeholder="5"
                        id="five" name="bills[five]" value="{{ old('five') }}">
                </div>
                <div class="col-md-6 mb-5">
                    <label for="two" class="form-label">2's</label>
                    <input class="form-control form-control-solid form-control-sm" type="number" placeholder="2"
                        id="two" name="bills[two]" value="{{ old('two') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-5">
                    <label for="one" class="form-label">1's</label>
                    <input class="form-control form-control-solid form-control-sm" type="number" placeholder="1"
                        id="one" name="bills[one]" value="{{ old('one') }}">
                </div>
            </div>
        </div>
    </div>
</div>
