<div class="row row-{{ $data_id }}" data-id="row-{{ $data_id }}">
    <div class="col-md-2 mb-5">
        <div class="controls">
            <select class="form-select form-select-solid " required
                name="option[{{ $data_id }}][option_value][{{ $temp_key }}][option_value_id]">
                @foreach ($option_values as $option_value)
                    <option value="{{ $option_value->option_value_id }}">{{ $option_value->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2 mb-5">
        <div class="controls">

            <input class="form-control form-control-solid" type="number"  required min="0"
                name="option[{{ $data_id }}][option_value][{{ $temp_key }}][quantity]" value="0">
        </div>
    </div>
    <div class="col-md-2 mb-5">
        <div class="controls">
            <select class="form-select form-select-solid " required
                name="option[{{ $data_id }}][option_value][{{ $temp_key }}][subtract]">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>
    <div class="col-md-2 mb-5">
        <div class="controls">
            <select class="form-select form-select-solid " required
                name="option[{{ $data_id }}][option_value][{{ $temp_key }}][price_prefix]">
                <option value="+">+</option>
                <option value="-">-</option>
            </select>
            <input class="form-control form-control-solid" type="number" required min="0" step="0.1" value="0"
                name="option[{{ $data_id }}][option_value][{{ $temp_key }}][price]">
        </div>
    </div>
    <div class="col-md-2 mb-5">
        <div class="controls">
            <select class="form-select form-select-solid " required
                name="option[{{ $data_id }}][option_value][{{ $temp_key }}][weight_prefix]">
                <option value="+">+</option>
                <option value="-">-</option>
            </select>
            <input class="form-control form-control-solid" type="number" required min="0" step="0.1" value="0"
                name="option[{{ $data_id }}][option_value][{{ $temp_key }}][weight]">
        </div>
    </div>
    <div class="col-md-2 mb-5">
        <div class="controls">
            <button class="btn btn-danger btn-sm del_button" type="button"><i class="fas fa-minus-circle"></i></button>
        </div>
    </div>
</div>
