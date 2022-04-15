@if (count($product_option_quantity_summaries['products']) > 0)
    <div class="accordion row mb-5" id="kt_accordion_1">
        <div class="accordion-item">
            <div class="accordion-header" id="kt_accordion_1_header_1">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"> <button class="accordion-button fs-4 fw-bold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1"
                                    aria-expanded="true" aria-controls="kt_accordion_1_body_1">Product
                                    Inventory</button></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 accordion-collapse collapse" id="kt_accordion_1_body_1"
                aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                <div class="col-md-12 mb-5 accordion-body">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <table class="table table-striped table-sm gy-4 gs-4 align-middle">
                                <tr>
                                    <th></th>
                                    @foreach ($product_option_quantity_summaries['optionValueDescriptions'] as $optionValue)
                                        <th><b>{{ $optionValue->name }}</b></th>
                                    @endforeach
                                    <th><b>Default</b></th>
                                </tr>
                                @foreach($product_option_quantity_summaries['manufacturers'] as $manufacturer)
                                <tr>
                                    <th class="text-center"> <b>Manufacturer:&nbsp;&nbsp;{{$manufacturer->name}}</b></th>
                                </tr>
                                @foreach ($product_option_quantity_summaries['products'] as $product)
                                    @if($product->manufacturer_id == $manufacturer->id)
                                    <tr>
                                        <th><b>{{ $product->eng_description->name }}</b></th>
                                        @foreach ($product_option_quantity_summaries['optionValueDescriptions'] as $optionValue)
                                            <th>{{ isset($product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$optionValue->option_value_id]['quantity']) ? $product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$optionValue->option_value_id]['quantity']  . ' - ' : '' }}{{ isset($product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$optionValue->option_value_id]['pending_quantity']) ? $product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$optionValue->option_value_id]['pending_quantity'] . ' = ' : '' }}
                                                {{ isset($product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$optionValue->option_value_id]['quantity']) ? $product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$optionValue->option_value_id]['quantity']-$product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$optionValue->option_value_id]['pending_quantity']  : '' }}
                                            </th>

                                        @endforeach
                                        <th>{{ isset($product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$product->eng_description->name]['quantity']) ? $product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$product->eng_description->name]['quantity'] . ' - ' : '' }}{{ isset($product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$product->eng_description->name]['pending_quantity']) ? $product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$product->eng_description->name]['pending_quantity'] . ' = ' : '' }}
                                            {{ isset($product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$product->eng_description->name]['quantity']) ? $product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$product->eng_description->name]['quantity']-$product_option_quantity_summaries['productSummary'][$manufacturer->id][$product->id][$product->eng_description->name]['pending_quantity']  : '' }}
                                        </th>
                                    </tr>
                                    @endif
                                @endforeach
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    </div>
    </div>
@endif
