@if(count($options) > 0)
<h4>Choose Option(s)</h4>
<hr>
<div class="row">
    @foreach($options as $option)
    @if($option->option->type == "select")
    <div class="mb-5 col-md-12">
        <label class="form-label @if($option->required == '1') required @endif">{{$option->eng_description->name}}</label>
        <select class="form-select form-select-solid" name="option[{{$option->id}}]" form="add-to-cart" data-id="select" id="option[{{$option->id}}]" @if($option->required == '1')
            required @endif>
            @if($option->required != '1') <option value="0">-- None --</option> @endif
            @foreach($option->product_option_values as $product_option_value)
            <option value="{{$product_option_value->id}}">
                {{$product_option_value->eng_description->name}}
                {{$product_option_value->price_prefix . "" . $currency_symbol . setDefaultPriceFormat($product_option_value->price)}} (Total Price =
                {{$currency_symbol . setDefaultPriceFormat($option->product->price + $product_option_value->price)}})
            </option>
            @endforeach
        </select>
    </div>
    @elseif($option->option->type == "radio")
    <div class="mb-5 col-md-12">
        <label class="form-label @if($option->required == '1') required @endif">{{$option->eng_description->name}}</label>
        <div class="row">
            @foreach($option->product_option_values as $key => $product_option_value)
            <div class="col-md-4 mb-5">
                <div class="form-check form-check-solid form-check-inline">
                    <input class="form-check-input" type="radio" data-id="radio" form="add-to-cart" id="{{$option->eng_description->name}}-{{$product_option_value->id}}" name="option[{{$option->id}}]"
                        value="{{$product_option_value->id}}" @if($option->required == '1' && $key == 0) required @endif />
                    <label class="form-check-label" for="{{$option->eng_description->name}}-{{$product_option_value->id}}">
                        {{$product_option_value->eng_description->name}}
                        {{$product_option_value->price_prefix . "" . $currency_symbol . setDefaultPriceFormat($product_option_value->price)}}
                    </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @elseif($option->option->type == "checkbox")
    <div class="mb-4 col-md-12">
        <label class="form-label @if($option->required == '1') required @endif">{{$option->eng_description->name}}</label>
        <div class="row @if($option->required == '1') checkbox-group @endif">
            @foreach($option->product_option_values as $key => $product_option_value)
            <div class="col-md-3 mb-5">
                <div class="form-check form-check-solid form-check-inline">
                    <input class="form-check-input" type="checkbox" form="add-to-cart" data-id="checkbox" id="{{$option->eng_description->name}}-{{$product_option_value->id}}"
                        name="option[{{$option->id}}][]" value="{{$product_option_value->id}}" />
                    <label class="form-check-label" for="{{$option->eng_description->name}}-{{$product_option_value->id}}">
                        {{$product_option_value->eng_description->name}}
                        {{$product_option_value->price_prefix . "" . $currency_symbol . setDefaultPriceFormat($product_option_value->price)}} 
                    </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @elseif($option->option->type == "text")
    <div class="mb-5 col-md-12">
        <label class="form-label @if($option->required == '1') required @endif">{{$option->eng_description->name}}</label>
        <input class="form-control form-control-solid" data-id="text" type="text" name="option[{{$option->id}}]" form="add-to-cart" id="option[{{$option->id}}]" value="{{$option->value}}" readonly>
    </div>
    @else
    <div class="mb-5 col-md-12">
        <label class="form-label @if($option->required =='1') required @endif">{{$option->eng_description->name}}</label>
        <textarea class="form-control form-control-solid" data-id="textarea" name="option[{{$option->id}}]" form="add-to-cart" id="option[{{$option->id}}]" rows="4"
            readonly>{{$option->value}}</textarea>
    </div>
    @endif
    @endforeach
</div>
@endif