<tr class="p-row fs-6 text-gray-800" data-id="{{$uuid}}" data-cart="{{$cart['id']}}">
    <td>
        {{$cart['product']['eng_description']['name']}}
        <input type="hidden" name="product[{{$idx}}][product_id]" value="{{$cart['product']['id']}}">
        <input type="hidden" name="product[{{$idx}}][name]" value="{{$cart['product']['eng_description']['name']}}">
        @if(count($option_arr) > 0)
        @foreach($option_arr as $option)
        @foreach($option->product_option_values as $option_value)
        <br>
        <small>- {{$option->eng_description->name}}: {{$option_value->eng_description->name}}</small>
        @if($option->type == "checkbox")
        <input type="hidden" name="product[{{$idx}}][option][{{$option_value->product_option_id}}-{{$option->eng_description->name}}-{{$option->type}}][]"
            value="{{$option_value->id}}-{{$option_value->eng_description->name}}">
        @else
        <input type="hidden" name="product[{{$idx}}][option][{{$option_value->product_option_id}}-{{$option->eng_description->name}}-{{$option->type}}]"
            value="{{$option_value->id}}-{{$option_value->eng_description->name}}">
        @endif
        @endforeach
        @endforeach
        @endif
    </td>
    <td class="text-end">
        <div class="input-group input-group-sm">
            <input type="text" class="form-control qty" aria-label="qty" value="{{$cart['quantity']}}" name="product[{{$idx}}][quantity]" id="product[{{$idx}}][quantity]" aria-describedby="qty">
            <div class="input-group-append">
                <button class="btn btn-primary updateProductQty" type="button" onclick="updateProductQty(this,{{$cart['id']}})" title="Refresh">
                    <i class="fas fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </td>

    <input class="price" type="hidden" name="product[{{$idx}}][price]" id="product[{{$idx}}][price]" value="{{$calculated_price}}">
    {{-- <td class="text-end">
        {{$currency_symbol . setDefaultPriceFormat($calculated_price)}}
    </td>
    <td class="text-end row_total">
        {{$currency_symbol . setDefaultPriceFormat($cart['quantity'] * $calculated_price)}}
    </td> --}}
    <td class="text-end">
        <button type="button" title="Remove" onclick="delRow(this,'{{route('purchases.removeCartItem')}}','{{$cart['id']}}')" class="btn btn-sm btn-danger">
            <i class="fas fa-minus-circle text-white"></i>
        </button>
    </td>
</tr>