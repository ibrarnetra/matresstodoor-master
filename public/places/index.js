// [START maps_places_autocomplete_addressform]
// This sample uses the Places Autocomplete widget to:
// 1. Help the user select a place
// 2. Retrieve the address components associated with that place
// 3. Populate the form fields with those address components.
// This sample requires the Places library, Maps JavaScript API.
// Include the libraries=places parameter when you first load the API.
// For example: <script
// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
let autocomplete;
let address1Field;
let cityField;
let postCodeField;
let countryField;
let stateField;
let latField;
let lngField;

function initAutocompleteFields(
    address1,
    city,
    postCode,
    country,
    state,
    lat,
    lng
) {
    address1Field = address1;
    cityField = city;
    postCodeField = postCode;
    countryField = country;
    stateField = state;
    latField = lat;
    lngField = lng;
    initAutocomplete();
}

function initAutocomplete() {
    // Create the autocomplete object, restricting the search predictions to
    // addresses in the US and Canada.
    autocomplete = new google.maps.places.Autocomplete(address1Field, {
        componentRestrictions: { country: ["us", "ca"] },
        fields: ["address_components", "formatted_address", "geometry"],
        types: ["geocode"],
    });

    // Avoid paying for data that you don't need by restricting the set of
    // place fields that are returned to just the address components.
    autocomplete.setFields([
        "address_component",
        "formatted_address",
        "geometry",
    ]);

    // When the user selects an address from the drop-down, populate the
    // address fields in the form.
    autocomplete.addListener("place_changed", fillInAddress);
}

// [START maps_places_autocomplete_addressform_fillform]
function fillInAddress() {
    // Get the place details from the autocomplete object.
    const place = autocomplete.getPlace();

    if (place) {
        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        // place.address_components are google.maps.GeocoderAddressComponent objects
        // which are documented at http://goo.gle/3l5i5Mr
        let administrative_area_level_1 = "";
        let locality = "";
        let postal_code = "";
        let country = "";
        for (const component of place.address_components) {
            const componentType = component.types[0];

            switch (componentType) {
                case "administrative_area_level_1": {
                    administrative_area_level_1 = component.long_name;
                    break;
                }

                case "locality": {
                    locality = component.long_name;
                    break;
                }

                case "postal_code": {
                    postal_code = component.short_name;
                    break;
                }

                case "country":
                    country = component.long_name;
                    break;
            }
        }
        // set lat
        if ($("#" + latField.id)) {
            $("#" + latField.id).val(place.geometry.location.lat());
            $("#" + latField.id).trigger("change");
        }
        // set lng
        if ($("#" + lngField.id)) {
            $("#" + lngField.id).val(place.geometry.location.lng());
            $("#" + lngField.id).trigger("change");
        }
        // set address 1
        if ($("#" + address1Field.id)) {
            $("#" + address1Field.id).val(place.formatted_address);
            $("#" + address1Field.id).trigger("change");
        }
        // set city
        if ($("#" + cityField.id)) {
            $("#" + cityField.id).val(locality);
            $("#" + cityField.id).trigger("change");
        }
        // set postcode
        if ($("#" + postCodeField.id)) {
            $("#" + postCodeField.id).val(postal_code);
            $("#" + postCodeField.id).trigger("change");
        }
        // set country
        if ($("#" + countryField.id)) {
            $("#" + countryField.id).val(
                $("#" + countryField.id)
                    .find("option:contains('" + country + "')")
                    .val()
            );
            $("#" + countryField.id).attr(
                "data-zone",
                administrative_area_level_1
            );
            $("#" + countryField.id).trigger("change");
        }
        // set zone
        if ($("#" + stateField.id)) {
            $("#" + stateField.id).val(
                $("#" + stateField.id)
                    .find(
                        "option:contains('" + administrative_area_level_1 + "')"
                    )
                    .val()
            );
            $("#" + stateField.id).trigger("change");
        }
    }
}
// [END maps_places_autocomplete_addressform_fillform]
// [END maps_places_autocomplete_addressform]
