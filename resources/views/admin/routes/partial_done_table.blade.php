<h5 class="text-center">Return Product</h5>
<table class="table table-striped table-sm gy-2 gs-2 align-middle">
    <thead>
       <tr class="fw-bolder fs-6 text-gray-800">
           <th>Checkbox</th>
           <th>Product</th>
           <th>Return Quantity</th>
       </tr> 
    </thead>
    <tbody>
        <input type="hidden" name="return_total_amount" id="return-total-amount" value="0">
        <input type="hidden"  id="tax_country_id" value="{{$country_id}}">
        <input type="hidden"  id="apply_tax" value="{{$apply_tax}}">
        <input type="hidden"  id="order_discount_amount" value="{{$discount_amount}}">
        <input type="hidden" id="tax_url" value="{{route('tax-classes.getApplicableTaxClass')}}">
        <input type="hidden" id="tax_zone_id" value="{{$zone_id}}">
        @foreach ($order_products as $product)
        <tr class="fw-bolder fs-6 text-gray-800">
            <input type="hidden" class="return_single_amount" name="return_single_amount[]" value="{{$product->price}}" />
            <td><input class="form-check-input order_product_id" name="order_product_id[]" type="checkbox" onchange="returnQuantityPrice(this,'product_id')" value="{{$product->id}}" @if($product->return_ind == 'Yes') checked @endif/></td>
            <td>   {{ $product->name }}
                @if (count($product->order_options) > 0)
                @foreach ($product->order_options as $option)
                    <br>
                    - {{ $option->name }}: {{ $option->value }}
                @endforeach
            @endif </td>
            <td><input type="number" disabled name="quantity[]" max="{{$product->quantity}}" @if($product->return_ind == 'Yes')value="{{$product->return_quantity}}" @else value="0" @endif class="form-control form-control-solid return_product_quantity" onblur="returnQuantityPrice(this,'quantity')" /></td>
        </tr>
        @endforeach
    </tbody>

</table>