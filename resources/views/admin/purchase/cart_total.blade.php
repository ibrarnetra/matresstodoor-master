<tr class="fs-6 text-gray-800">
    <td>
        {{$cart['product']['eng_description']['name']}}
        @if(count($option_arr) > 0)
        @foreach($option_arr as $option)
        @if(in_array($option->type, ['text', 'number', 'textarea']))
        <br>
        <small>- {{$option->eng_description->name}}: {{$options[$option->id]}}</small>
        @else
        @foreach($option->product_option_values as $option_value)
        <br>
        <small>- {{$option->eng_description->name}}: {{$option_value->eng_description->name}}</small>
        @endforeach
        @endif
        @endforeach
        @endif
    </td>
    <td class="text-end">
        {{$cart['quantity']}}
    </td>
    <td class="text-end">{{$currency_symbol . setDefaultPriceFormat($calculated_price)}}</td>
    <td class="text-end">{{$currency_symbol . setDefaultPriceFormat($cart['quantity'] * $calculated_price)}}</td>
</tr>