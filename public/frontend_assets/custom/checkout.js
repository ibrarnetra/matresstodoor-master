let address_haystack = [];

$(document).ready(function () {
    generateCreditCardExpiration();
});

function checkout() {
    let shipping_method = $("input[name='shipping_address_id']").val();
    let dataCode = $("input[name='payment_method_id']").attr("data-code");
    let card_number = $("#card_number").val();
    let card_exp = $("#card_exp").val();
    let card_cvv = $("#card_cvv").val();

    let processing = true;
    if (
        shipping_method === undefined ||
        shipping_method === NaN ||
        shipping_method === ""
    ) {
        processing = false;
        alert(
            "Please select a valid shipping address or add an address to address book by heading over to your dashboard."
        );
    }

    if (dataCode === undefined || dataCode === NaN || dataCode === "") {
        processing = false;
        alert("Please select a valid payment method.");
    }

    if (dataCode === "authorize") {
        if (
            card_number === undefined ||
            card_number === NaN ||
            card_number === ""
        ) {
            processing = false;
            alert("Please select a valid card number.");
        }
        if (card_exp === undefined || card_exp === NaN || card_exp === "") {
            processing = false;
            alert("Please select a valid card expiration.");
        }
        if (card_cvv === undefined || card_cvv === NaN || card_cvv === "") {
            processing = false;
            alert("Please select a valid card cvv.");
        }
    }

    if (processing) {
        $("#checkoutForm").submit();
    }
}

function showHideAuthorize(dis) {
    if ($(dis).is(":checked")) {
        let dataCode = $(dis).attr("data-code");
        if (dataCode === "authorize") {
            $("#authorize-div").removeClass("d-none");
            makeFieldsRequiredUnRequired(dataCode);
        } else {
            makeFieldsRequiredUnRequired(dataCode);
            $("#authorize-div").addClass("d-none");
        }
        $("input[name='payment_method']").val($(dis).attr("id"));
        $("#payment_method_id").val($(dis).val());
        $("input[name='payment_method_code']").val($(dis).attr("data-code"));
    }
}

function makeFieldsRequiredUnRequired(dataCode) {
    if (dataCode === "authorize") {
        $("#cardNumber").prop("required", true);
        $("#cardExpMonth").prop("required", true);
        $("#card_exp_year").prop("required", true);
        $("#cvv").prop("required", true);
        $("#cardNumber").prop("form", "checkout-form");
        $("#cardExpMonth").prop("form", "checkout-form");
        $("#card_exp_year").prop("form", "checkout-form");
        $("#cvv").prop("form", "checkout-form");
    } else {
        $("#cardNumber").prop("required", false);
        $("#cardExpMonth").prop("required", false);
        $("#card_exp_year").prop("required", false);
        $("#cvv").prop("required", false);
        $("#cardNumber").removeAttr("form");
        $("#cardExpMonth").removeAttr("form");
        $("#card_exp_year").removeAttr("form");
        $("#cvv").removeAttr("form");
    }
}

function fillShippingAddress(customer_id, url) {
    $.ajax({
        type: "GET",
        url: url,
        data: {
            id: customer_id,
        },
        dataType: "JSON",
        delay: 250,
        success: function (res) {
            address_haystack = res.data;
            console.log(
                "🚀 ~ file: checkout.js ~ line 104 ~ fillShippingAddress ~ address_haystack",
                address_haystack
            );

            let html = '<option value="0">-- None --</option>';
            address_haystack.forEach(function (item, index) {
                html +=
                    '<option value="' +
                    item.id +
                    '" data-country="' +
                    item.country.id +
                    '" data-zone="' +
                    item.zone.id +
                    '">' +
                    item.address_1 +
                    "</option>";
            });
            $("#auth_billing_shipping_address").html(html);
            $("#auth_billing_shipping_address")
                .val($("#auth_billing_shipping_address option:eq(1)").val())
                .trigger("change");

            $("#auth_delivery_shipping_address").html(html);
            $("#auth_delivery_shipping_address")
                .val($("#auth_delivery_shipping_address option:eq(1)").val())
                .trigger("change");
        },
        error: function (err) {
            console.log(err);
        },
    });
}

function fillCustomerDetail(dis, type = "shipping") {
    let first_name = "";
    let last_name = "";
    let address_1 = "";
    let city = "";
    let postcode = "";
    let country_id = 0;
    let zone_id = 0;

    let selected = $("option:selected", dis).val();
    let address_needle = address_haystack.find((item) => item.id == selected);
    if (address_needle) {
        first_name = address_needle.first_name;
        last_name = address_needle.last_name;
        address_1 = address_needle.address_1;
        city = address_needle.city;
        postcode = address_needle.postcode;
        country_id = address_needle.country_id;
        zone_id = address_needle.zone_id;

        // DISABLE REQUIRED ATTRIBUTE
        $("#" + type + "_first_name").prop("required", false);
        $("#" + type + "_last_name").prop("required", false);
        $("#" + type + "_address_1").prop("required", false);
        $("#" + type + "_city").prop("required", false);
        $("#" + type + "_postcode").prop("required", false);
        $("#" + type + "_country_id").prop("required", false);
        $("#" + type + "_zone_id").prop("required", false);
    }
    $("#" + type + "_first_name").val(first_name);
    $("#" + type + "_last_name").val(last_name);
    $("#" + type + "_address_1").val(address_1);
    $("#" + type + "_city").val(city);
    $("#" + type + "_postcode").val(postcode);
    $("#" + type + "_country_id").val(country_id);
    $("#" + type + "_country_id").attr("data-zone", zone_id);
    $("#" + type + "_country_id").trigger("change");
}

function toggleRegisterGuest() {
    let accountType = $("input[name='account_type']:checked").val();
    if (accountType === "register") {
        $("button[aria-controls='billing-detail']").html(
            "Step 2: Account & Billing Details"
        );
        $("#register-account-div").removeClass("d-none");
        $("#register_password").prop("required", true);
        $("#register_password").attr("form", "checkout-form");
        $("#register_password_confirmation").prop("required", true);
        $("#register_password_confirmation").attr("form", "checkout-form");
        $("#privacy-div").removeClass("d-none");
        $("#privacy-policy").prop("required", true);
    } else if (accountType === "guest") {
        $("button[aria-controls='billing-detail']").html(
            "Step 2: Billing Details"
        );
        $("#register-account-div").addClass("d-none");
        $("#register_password").prop("required", false);
        $("#register_password").removeAttr("form");
        $("#register_password_confirmation").prop("required", false);
        $("#register_password_confirmation").removeAttr("form");
        $("#privacy-div").addClass("d-none");
        $("#privacy-policy").prop("required", false);
    }
}

function fillDeliveryAddress(dis) {
    let isSameAsBilling =
        $("input[id='same-as-billing']:checked").val() !== undefined ||
        $("input[id='same-as-billing']:checked").val() !== "" ||
        $("input[id='same-as-billing']:checked").val() !== NaN
            ? Boolean($("input[id='same-as-billing']:checked").val())
            : false;

    if (isSameAsBilling) {
        if ($(dis).attr("name") === "zone_id") {
            if (
                $("#zone_id").val() !== undefined ||
                $("#zone_id").val() !== NaN ||
                $("#zone_id").val() !== ""
            ) {
                $("#delivery_country_id").val($("#country_id").val());
                $("#delivery_country_id").attr(
                    "data-zone",
                    $("#zone_id").val()
                );
                $("#delivery_country_id").trigger("change");
            }
        } else {
            $("#delivery_" + $(dis).attr("name")).val($(dis).val());
        }
    }
}

function toggleExistingNewAddress(type) {
    let addressSelectionValue = $(
        "input[name='" + type + "_address_selection']:checked"
    ).val();

    if (addressSelectionValue === "existing") {
        $("#" + type + "-existing-address-div").removeClass("d-none");
        $("#" + type + "-new-address-div").addClass("d-none");
        // MAKE FIELDS REQUIRED AND ATTACH THEM WITH FORM
        // loop for select tags
        $.each(
            $("#" + type + "-existing-address-div").find("select"),
            function (indexInArray, valueOfElement) {
                $(valueOfElement).attr("form", "checkout-form");
                $(valueOfElement).prop("required", true);
            }
        );
        // loop for input tags
        $.each(
            $("#" + type + "-existing-address-div").find("input"),
            function (indexInArray, valueOfElement) {
                if (
                    $(valueOfElement).attr("id") ===
                        "auth_" + type + "_company" ||
                    $(valueOfElement).attr("id") ===
                        "auth_" + type + "_address_2"
                ) {
                    return;
                }
                $(valueOfElement).attr("form", "checkout-form");
                $(valueOfElement).prop("required", true);
            }
        );
        // MAKE FIELDS NOT REQUIRED AND DETACH ATTACH THEM WITH FORM
        // loop for select tags
        $.each(
            $("#" + type + "-new-address-div").find("select"),
            function (indexInArray, valueOfElement) {
                $(valueOfElement).removeAttr("form");
                $(valueOfElement).prop("required", false);
            }
        );
        // loop for input tags
        $.each(
            $("#" + type + "-new-address-div").find("input"),
            function (indexInArray, valueOfElement) {
                $(valueOfElement).removeAttr("form");
                $(valueOfElement).prop("required", false);
            }
        );
    } else {
        toggleAutoComplete(
            $("#" + type + "-new-address-div")
                .closest(".collapse")
                .attr("data-step")
        );
        $("#" + type + "-existing-address-div").addClass("d-none");
        $("#" + type + "-new-address-div").removeClass("d-none");
        // MAKE FIELDS NOT REQUIRED AND DETACH ATTACH THEM WITH FORM
        // loop for select tags
        $.each(
            $("#" + type + "-existing-address-div").find("select"),
            function (indexInArray, valueOfElement) {
                $(valueOfElement).removeAttr("form", "checkout-form");
                $(valueOfElement).prop("required", false);
            }
        );
        // loop for input tags
        $.each(
            $("#" + type + "-existing-address-div").find("input"),
            function (indexInArray, valueOfElement) {
                $(valueOfElement).removeAttr("form", "checkout-form");
                $(valueOfElement).prop("required", false);
            }
        );
        // MAKE FIELDS REQUIRED AND ATTACH THEM WITH FORM
        // loop for select tags
        $.each(
            $("#" + type + "-new-address-div").find("select"),
            function (indexInArray, valueOfElement) {
                $(valueOfElement).attr("form", "checkout-form");
                $(valueOfElement).prop("required", true);
            }
        );
        // loop for input tags
        $.each(
            $("#" + type + "-new-address-div").find("input"),
            function (indexInArray, valueOfElement) {
                if (
                    $(valueOfElement).attr("id") ===
                        "auth_" + type + "_company" ||
                    $(valueOfElement).attr("id") ===
                        "auth_" + type + "_address_2"
                ) {
                    return;
                }
                $(valueOfElement).attr("form", "checkout-form");
                $(valueOfElement).prop("required", true);
            }
        );
    }
}

// checkout validation
function validateCheckoutStep(ele) {
    let requiredFields = $(ele)
        .find("input,textarea,select")
        .filter("[required]:visible"); // fetch all fields in the current collapse
    let isValid = true;
    $.each(requiredFields, function (indexInArray, valueOfElement) {
        const fieldObj = document.getElementById($(valueOfElement).attr("id"));
        isValid = fieldObj.checkValidity(); // check fields for valid state by required attribute
        if (!isValid) {
            fieldObj.reportValidity(); // triggers html5 required messages
            return false;
        }
    });

    return isValid;
}

// Multi step next
function moveToNextStep(dis, step) {
    if(step == '5')
    {
        let value = $(".payment_method_id").attr('data-code')
        if(value == 'authorize')
        {
            let cardMNumber = $('#cardNumber');
            let CVV = $("#cvv");
            let owner = $('#owner');

            var isCardValid = $.payform.validateCardNumber(cardMNumber.val());
            var isCvvValid = $.payform.validateCardCVC(CVV.val());
    
            if(owner.val().length < 5){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Owner must contain 5 character!'
                  })
                
                   return false;
            }else if (!isCvvValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Wrong Cvv!'
                  })
                return false;
            }  else if (!isCardValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Invalid Card Number!'
                  })
                return false;
              } 
        }
       
    }
    let valid = validateCheckoutStep($("div[data-step='" + step + "']"));
    if (valid) {
        $(
            "button[aria-controls='" +
                $("div[data-step='" + (step + 1) + "']").attr("id") +
                "']"
        ).prop("disabled", false);
        toggleAutoComplete(step + 1);
        $("div[data-step='" + (step + 1) + "']").collapse("show");
    }
}
// Multi step previous
function moveToPreviousStep(dis, step) {
    let valid = validateCheckoutStep($("div[data-step='" + (step + 1) + "']"));
    if (valid) {
        toggleAutoComplete(step);
        $("div[data-step='" + step + "']").collapse("show");
    }
}

function toggleAutoComplete(step) {
    // check if authenticated
    if ($("#auth_type").val() === "auth") {
        // init geo-autocomplete for billing address
        if (parseInt(step) === 2) {
            initAutocompleteFields(
                document.getElementById("auth_billing_address_1"),
                document.getElementById("auth_billing_city"),
                document.getElementById("auth_billing_postcode"),
                document.getElementById("auth_billing_country_id"),
                document.getElementById("auth_billing_zone_id"),
                document.getElementById("auth_billing_lat"),
                document.getElementById("auth_billing_lng")
            );
        }

        // init geo-autocomplete for delivery address
        if (parseInt(step) === 3) {
            initAutocompleteFields(
                document.getElementById("auth_delivery_address_1"),
                document.getElementById("auth_delivery_city"),
                document.getElementById("auth_delivery_postcode"),
                document.getElementById("auth_delivery_country_id"),
                document.getElementById("auth_delivery_zone_id"),
                document.getElementById("auth_delivery_lat"),
                document.getElementById("auth_delivery_lng")
            );
        }
    }

    // check if guest
    if ($("#auth_type").val() === "guest") {
        // init geo-autocomplete for billing address
        if (parseInt(step) === 2) {
            initAutocompleteFields(
                document.getElementById("address_1"),
                document.getElementById("city"),
                document.getElementById("postcode"),
                document.getElementById("country_id"),
                document.getElementById("zone_id"),
                document.getElementById("lat"),
                document.getElementById("lng")
            );
        }

        // init geo-autocomplete for delivery address
        if (parseInt(step) === 3) {
            initAutocompleteFields(
                document.getElementById("delivery_address_1"),
                document.getElementById("delivery_city"),
                document.getElementById("delivery_postcode"),
                document.getElementById("delivery_country_id"),
                document.getElementById("delivery_zone_id"),
                document.getElementById("delivery_lat"),
                document.getElementById("delivery_lng")
            );
        }
    }
}

function autofillAddressMobile(dis) {
    $("#address_telephone").val($(dis).val());
    $("#delivery_telephone").val($(dis).val());
}

function getApplicableTaxClass(url, country_id, zone_id) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            country_id: country_id,
            zone_id: zone_id,
            _token: CSRF_TOKEN,
        },
        dataType: "JSON",
        success: function (res) {
            let tax_rate = 0.0;
            let tax_type = "fixed";
            let tax_title = "N/A";
            if (res.status) {
                if (res.tax_class) {
                    tax_rate = res.tax_class.tax_rate;
                    tax_type = res.tax_class.tax_type;
                    tax_title = res.tax_class.tax_class;
                }
            }
            // Apply Tax
            let orderTax = tax_rate; // fixed tax rate
            if (tax_type === "percentage") {
                orderTax =
                    (parseFloat($("#order-sub-total").html()) * tax_rate) / 100; // percentage tax rate
            }
            $("#order-tax").html(setDefaultPriceFormat(orderTax)); // set tax value

            $("#order-grand-total").html(
                setDefaultPriceFormat(
                    parseFloat($("#order-sub-total").html()) + orderTax
                )
            ); // grand_total + tax
        },
        error: function (err) {
            console.log("🚀 ~ file: form.blade.php ~ line 1243 ~ err", err);
        },
    });
}
