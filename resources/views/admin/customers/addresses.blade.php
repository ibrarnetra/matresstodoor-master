<div class="tab-pane" id="address-{{$counter}}" role="tabpanel">
    <div class="row">
        <div class="mb-5 col-md-6">
            <label class="form-label required" for="first_name_{{$counter}}">First Name</label>
            <input type="text" class="form-control form-control-solid" id="first_name_{{$counter}}" name="address[{{$counter}}][first_name]" required>
        </div>
        <div class="mb-5 col-md-6">
            <label class="form-label required" for="last_name_{{$counter}}">Last Name</label>
            <input type="text" class="form-control form-control-solid" id="last_name_{{$counter}}" name="address[{{$counter}}][last_name]" required>
        </div>
    </div>

    <div class="row">
        <div class="mb-5 col-md-6">
            <div class="fv-row">
                <label class="form-label" for="telephone">Mobile (xxx-xxx-xxxx) </label>
                <input type="tel" placeholder="xxx-xxx-xxxx" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" class="form-control form-control-solid" id="telephone_{{$counter}}"
                    name="address[{{$counter}}][telephone]">
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="mb-5 col-md-6">
            <label class="form-label" for="company_{{$counter}}">Company</label>
    <input type="text" class="form-control form-control-solid" id="company_{{$counter}}" name="address[{{$counter}}][company]">
</div>
</div> --}}
<div class="row">
    <input type="hidden" name="address[{{$counter}}][lat]" id="lat_{{$counter}}">
    <input type="hidden" name="address[{{$counter}}][lng]" id="lng_{{$counter}}">
    <div class="mb-5 col-md-12">
        <label for="address_1_{{$counter}}" class="form-label required">Address 1 </label>
        <input class="form-control form-control-solid" name="address[{{$counter}}][address_1]" id="address_1_{{$counter}}" required>
    </div>
</div>
<div class="row">
    <div class="mb-5 col-md-12">
        <label for="address_2_{{$counter}}" class="form-label">Address 2 </label>
        <input class="form-control form-control-solid" name="address[{{$counter}}][address_2]" id="address_2_{{$counter}}">
    </div>
</div>
<div class="row">
    <div class="mb-5 col-md-6">
        <label class="form-label required" for="city_{{$counter}}">City</label>
        <input type="text" class="form-control form-control-solid" id="city_{{$counter}}" name="address[{{$counter}}][city]" required>
    </div>
</div>
<div class="row">
    <div class="mb-5 col-md-6">
        <label class="form-label required">Country</label>
        <select class="form-select form-select-solid country" name="address[{{$counter}}][country_id]" id="country_id_{{$counter}}" onchange="getZones(this, '{{route('zones.getZones')}}')" required
            style="width: 100%;">
            <option value="" disabled selected>-- Select Country --</option>
            @if (count($countries) > 0)
            @foreach ($countries as $country)
            <option value="{{$country->id}}" @if ($country->name == "Canada")
                selected
                @endif>{!! $country->name !!}</option>
            @endforeach
            @endif
        </select>
    </div>
    <div class="mb-5 col-md-6">
        <label class="form-label required">Region / State</label>
        <select class="form-select form-select-solid zone" name="address[{{$counter}}][zone_id]" id="zone_id_{{$counter}}" required style="width: 100%;">
            <option value="" disabled selected>-- Select State --</option>
            @if (count($zones) > 0)
            @foreach ($zones as $zone)
            <option value="{{$zone->id}}" @if ($zone->name == "Ontario")
                selected
                @endif>{!! $zone->name !!}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="row">
    <div class="mb-5 col-md-6">
        <label class="form-label required" for="postcode_{{$counter}}">Postcode</label>
        <input type="text" class="form-control form-control-solid" id="postcode_{{$counter}}" name="address[{{$counter}}][postcode]" required>
    </div>
    <div class="mb-5 col-md-6">
        <label class="form-label">Default Address </label>
        <div class="form-check form-check-solid">
            <input class="form-check-input" type="radio" name="address[{{$counter}}][is_default]" value="1" />
        </div>
    </div>
</div>
</div>