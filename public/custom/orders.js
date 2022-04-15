let obj1;
let address_haystack = [];
let tabs = [
    $('a[href="#products"]'),
    $('a[href="#payment-details"]'),
    $('a[href="#shipping-details"]'),
    $('a[href="#totals"]'),
];
let elementArray = [
    $(document).find("#addCustomer").find(".country"),
    $(document).find("#addCustomer").find(".zone"),
];

$(document).ready(function () {
    // initCustomDatePicker($("#card_exp"), {
    //     singleDatePicker: true,
    //     showDropdowns: true,
    //     minYear: 2000,
    //     autoApply: true,
    //     locale: {
    //         format: "YYYY-MM",
    //         separator: "-",
    //     },
    // });

    initSelect2Ajax($(".customer"), $("#customer_search").val());
    initSelect2Ajax($(".product"), $("#product_search").val());

    $("#store_name").val($("#store_id option:selected").text());

    $("#currency_code").val(
        $("#currency_id option:selected").attr("data-code")
    );

    $("#currency_value").val(
        $("#currency_id option:selected").attr("data-value")
    );

    $.each(elementArray, function (indexInArray, valueOfElement) {
        $(valueOfElement).select2("destroy");
        $(valueOfElement).select2({
            dropdownParent: $(document).find("#addCustomer .modal-body"),
        });
    });

    setShippingMethod($("input[name='shipping_method_id']:checked"));
    generateCreditCardExpiration();
});

$("#shipping_zone_id, zone_id").on("select2:select", function (e) {
    setCityVal(this);
});

$("#shipping_address").on("change", function () {
    let partial_selector = "payment";
    if ($(this).is("#shipping_address")) {
        partial_selector = "shipping";
    }
    fillCustomerDetail($(this), partial_selector);
});

$("#shipping_country_id, #shipping_zone_id").on("change", function () {
    let data = $("option:selected", this).text();
    if ($(this).is("#shipping_country_id")) {
        $("#shipping_country").val(data);
    } else if ($(this).is("#shipping_zone_id")) {
        $("#shipping_zone").val(data);
    }
});

function sanitizeVal(ele) {
    return ele.val() == undefined ||
        ele.val == "" ||
        ele.val() == null ||
        ele.val().length == 0
        ? 0
        : parseInt(ele.val());
}

$(document).on("select2:select", "#customer_id", function () {
    fillCustomerDetail($("#customer_id"));
    fillShippingAddress($("#customer_id"), $("#customers_addresses").val());
    $("#page-loader").addClass("show-loader");
    $.ajax({
        type: "POST",
        url: $("#clear_cart").val(),
        data: {
            _token: CSRF_TOKEN,
            customer_id: $("option:selected", this).val(),
        },
        dataType: "JSON",
        // success: function (res) {

        // },
        error: function (err) {
            console.log("🚀 ~ file: orders.js ~ line 91 ~ err", err);
        },
    });

    /**
     * clear cart from frontend
     */
    $("#cart-item, #unit-by-quantity, #sub-total, #grand-total").empty();
    $("#page-loader").removeClass("show-loader");
});

function fillCustomerDetail(dis, type = "customer") {
    if (type == "customer") {
        let first_name = "Not Found";
        let last_name = "Not Found";
        let email = "Not Found";
        let telephone = "Not Found";
        let selected = $("option:selected", dis).val();
        let customer_needle = customer_haystack.find(
            (item) => item.id == selected
        );
        if (customer_needle) {
            first_name = customer_needle.first_name;
            last_name = customer_needle.last_name;
            email = customer_needle.email;
            telephone = customer_needle.telephone;
        }
        $("#first_name").val(first_name);
        $("#last_name").val(last_name);
        $("#email").val(email);
        $("#telephone").val(telephone);
    } else {
        let first_name = "";
        let last_name = "";
        let telephone = "";
        let address_1 = "";
        let lat = "0.0000";
        let lng = "0.0000";
        let city = "";
        let postcode = "";
        let country_id = 0;
        let zone_id = 0;
        let selected = $("option:selected", dis).val();
        let customer_needle = customer_haystack.find(
            (item) => item.id == $("#customer_id option:selected").val()
        );
        if (customer_needle) {
            telephone = customer_needle.telephone;
        }
        let address_needle = address_haystack.find(
            (item) => item.id == selected
        );
        if (address_needle) {
            first_name = address_needle.first_name;
            last_name = address_needle.last_name;
            address_telephone = address_needle.telephone;
            address_1 = address_needle.address_1;
            lat = address_needle.lat;
            lng = address_needle.lng;
            city = address_needle.city;
            postcode = address_needle.postcode;
            country_id = address_needle.country_id;
            zone_id = address_needle.zone_id;
        }
        $("#" + type + "_first_name").val(first_name);
        $("#" + type + "_last_name").val(last_name);
        $("#" + type + "_address_1").val(address_1);
        $("#" + type + "_lat").val(lat);
        $("#" + type + "_lng").val(lng);
        $("#" + type + "_city").val(city);
        $("#" + type + "_postcode").val(postcode);
        $("#" + type + "_country_id").val(country_id);
        $("#" + type + "_country_id").attr("data-zone", zone_id);
        $("#" + type + "_country_id").trigger("change");

        if (telephone !== "" && telephone !== null) {
            $("#" + type + "_telephone").val(telephone);
        } else {
            $("#" + type + "_telephone").val(address_telephone);
        }
    }
}

function fillShippingAddress(dis, url) {
    let selected = $("option:selected", dis).val();
    $("#imageLoading").removeClass("d-none");
    $.ajax({
        type: "GET",
        url: url,
        data: {
            id: selected,
        },
        dataType: "JSON",
        delay: 250,
        success: function (res) {
            address_haystack = res.data;
            let html = '<option value="0">-- None --</option>';
            address_haystack.forEach(function (item, index) {
                html +=
                    '<option value="' +
                    item.id +
                    '">' +
                    item.address_1 +
                    "</option>";
            });
            if (address_haystack.length > 1) {
                $("#shipping-address-div").removeClass("d-none");
            }
            $("#shipping_address").html(html);
            $("#shipping_address")
                .val($("#shipping_address option:eq(1)").val())
                .trigger("change");
            $("#imageLoading").addClass("d-none");
        },
        error: function (err) {
            console.log(err);
        },
    });
}

function setCityVal(dis) {
    if ($(dis).is("#payment_zone_id")) {
        $("#payment_city").val($("option:selected", dis).text());
    }
    if ($(dis).is("#shipping_zone_id")) {
        $("#shipping_city").val($("option:selected", dis).text());
    }
    if ($(dis).is("#zone_id")) {
        $("#city").val($("option:selected", dis).text());
    }
}

$(document).on("select2:select", ".product", function () {
    getProductOptions($(this));
});

function getProductOptions(dis) {
    let selected = $("option:selected", dis).val();
    let currency_symbol = $("#currency_id option:selected").attr("data-symbol");
    $("#imageLoading").removeClass("d-none");
    $.ajax({
        url:
            BASE_URL +
            "products/get-options/?id=" +
            selected +
            "&currency_symbol=" +
            currency_symbol,
        dataType: "JSON",
        type: "GET",
        delay: 250,
        success: function (res) {
            $("#product-options").html(res.data);
            $("#option_type_order").val(JSON.stringify(res.option_type_order));
            $("#imageLoading").addClass("d-none");
        },
        error: function (err) {
            console.log(err);
        },
    });
}

function generateUUID(product_id) {
    let uuid = product_id;
    let option_type_order = JSON.parse($("#option_type_order").val());

    option_type_order.forEach(function (ele, idx) {
        if (ele === "select") {
            if (
                $("select[data-id=select] option:selected").val() !==
                    undefined &&
                $("select[data-id=select] option:selected").val() !== "0" &&
                $("select[data-id=select] option:selected").val() !== ""
            ) {
                uuid += $("select[data-id=select] option:selected").val();
            }
        }
        if (ele === "radio") {
            if ($("input[data-id=radio]:checked").val() !== undefined) {
                uuid += $("input[data-id=radio]:checked").val();
            }
        }
        if (ele === "checkbox") {
            $.each($("input[data-id=checkbox]:checked"), function () {
                uuid += $(this).val();
            });
        }
    });

    return uuid;
}

function submitFrom() {
    let product_id = $(".product option:selected").val();
    let quantity = $("#product_qty").val();
    if (product_id == undefined || product_id == null || product_id == NaN) {
        toastr.warning("Please select a product!");
    } else if (
        quantity == undefined ||
        quantity == null ||
        quantity == NaN ||
        quantity < 1
    ) {
        toastr.warning("Enter atleast 1 quantity!");
    } else {
        let processing = true;
        let requiredFields = $("#product-options")
            .find("input,textarea,select")
            .filter("[required]:visible"); // fetch all fields in the current collapse
        $.each(requiredFields, function (indexInArray, valueOfElement) {
            const fieldObj = document.getElementById(
                $(valueOfElement).attr("id")
            );

            processing = fieldObj.checkValidity(); // check fields for valid state by required attribute
            if (!processing) {
                fieldObj.reportValidity(); // triggers html5 required messages
            }
        });

        if (
            $("div.checkbox-group :checkbox:checked").length &&
            $("div.checkbox-group :checkbox:checked").length === 0
        ) {
            processing = false;
            toastr.warning("Please select a valid checkbox value!");
        }

        let dataString = $("#add-to-cart").serialize();
        let customer_id =
            $(".customer option:selected").val() == undefined
                ? 0
                : $(".customer option:selected").val();
        let currency_symbol = $("#currency_id option:selected").attr(
            "data-symbol"
        );

        // TO AVOID DUPLICATION
        let uuid = generateUUID(product_id);
        $(".p-row").each(function () {
            let el = $(this);
            if (el.attr("data-id") == uuid) {
                let old_qty = el.find(".qty").val();
                let new_qty = $("#product_qty").val();
                let updated_qty = parseInt(old_qty) + parseInt(new_qty);
                el.find(".qty").val(updated_qty);
                updateProductQty(
                    el.find(".updateProductQty"),
                    el.attr("data-cart")
                );
                processing = false;
            }
        });

        if (processing) {
            $("#page-loader").addClass("show-loader");
            $.ajax({
                type: "POST",
                url: $("#add_to_cart").val(),
                data:
                    dataString +
                    "&customer_id=" +
                    customer_id +
                    "&currency_symbol=" +
                    currency_symbol,
                dataType: "JSON",
                success: function (res) {
                    $("#cart-item").html(res.data);
                    $("#unit-by-quantity").html(
                        res.total_units + "/" + res.total_quantity
                    );
                    $("#sub-total").html(
                        currency_symbol + setDefaultPriceFormat(res.sub_total)
                    );
                    $("#grand-total").html(
                        currency_symbol + setDefaultPriceFormat(res.sub_total)
                    );

                    $("#index").val(res.index);
                },
                error: function (err) {
                    toastr.error("Error, please contact admin!");
                },
            });
            $("#page-loader").removeClass("show-loader");
        }
    }
}

function updateProductQty(dis, cart_id) {
    let parent = $(dis).closest(".p-row");
    let customer_id =
        $(".customer option:selected").val() == undefined
            ? 0
            : $(".customer option:selected").val();
    let currency_symbol = $("#currency_id option:selected").attr("data-symbol");

    $("#page-loader").addClass("show-loader");
    $.ajax({
        type: "POST",
        url: $("#validate_purchase_qty").val(),
        data: {
            cart_id: cart_id,
            updated_qty: parent.find(".qty").val(),
            price: parent.find(".price").val(),
            customer_id: customer_id,
            _token: CSRF_TOKEN,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status == true) {
                let row_total = res.data.quantity * res.price;
                parent.find(".qty").html(res.data.quantity);
                parent
                    .find(".row_total")
                    .html(currency_symbol + setDefaultPriceFormat(row_total));

                $("#unit-by-quantity").html(
                    res.total_units + "/" + res.total_quantity
                );
                $("#sub-total").html(
                    currency_symbol + setDefaultPriceFormat(res.sub_total)
                );
                $("#grand-total").html(
                    currency_symbol + setDefaultPriceFormat(res.sub_total)
                );
            }
        },
        error: function (err) {
            toastr.error("Error, please contact admin!");
        },
    });
    $("#page-loader").removeClass("show-loader");
}

function delRow(dis, url, cart_id) {
    let currency_symbol = $("#currency_id option:selected").attr("data-symbol");

    $("#imageLoading").removeClass("d-none");
    $.ajax({
        type: "GET",
        url: url,
        data: {
            id: cart_id,
            customer_id: sanitizeVal($("#customer_id")),
        },
        dataType: "JSON",
        success: function (res) {
            $(dis).closest(".p-row").remove();
            $("#unit-by-quantity").html(
                res.total_units + "/" + res.total_quantity
            );
            $("#sub-total").html(
                currency_symbol + setDefaultPriceFormat(res.sub_total)
            );
            $("#grand-total").html(
                currency_symbol + setDefaultPriceFormat(res.sub_total)
            );

            $("#imageLoading").addClass("d-none");
        },
        error: function (err) {
            toastr.error("Error, please contact admin!");
        },
    });
}

function getCartTotal(url) {
    let currency_symbol = $("#currency_id option:selected").attr("data-symbol");
    let country_id = $("#shipping_country_id option:selected").val();
    let zone_id = $("#shipping_zone_id option:selected").val();

    $("#order-tax").remove();
    $("#page-loader").addClass("show-loader");
    $.ajax({
        type: "GET",
        url: url,
        data: {
            customer_id: sanitizeVal($("#customer_id")),
            currency_symbol: currency_symbol,
            country_id: country_id,
            zone_id: zone_id,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status == true) {
                // DYNAMIC HTML
                $("#cart-total").html(res.data);
                $("#order-sub-total")
                    .closest(".text-gray-800")
                    .after(res.tax_html);
                // DYNAMIC VALUES
                let sub_total = setDefaultPriceFormat(res.sub_total);
                let sub_total_html = currency_symbol + sub_total;
                let unit_by_qty = res.total_units + "/" + res.total_quantity;

                $("#temp_grand_total").val(sub_total);
                $("#input-sub-total").val(sub_total);
                $("#input-grand-total").val(sub_total);
                $("#order-unit-by-quantity").html(unit_by_qty);
                $("#order-sub-total").html(sub_total_html);
                $("#order-grand-total").html(sub_total_html);
                // AMOUNT CALCULATIONS
                calGrandTotal();
                // setPaymentTypeAndAmount();
            }
        },
        error: function (err) {
            toastr.error("Error, please contact admin!");
        },
    });
    $("#page-loader").removeClass("show-loader");
}

function calGrandTotal() {
    let currency_symbol = $("#currency_id option:selected").attr("data-symbol");
    let temp_grand_total = (sub_total = sanitizeVal($("#temp_grand_total")));
    let shipping_cost = sanitizeVal($("#shipping_method_cost"));

    let applyExtraCharges =
        $("input[name='extra-charges']:checked").val() === "Y" ? true : false;
    let extra_charges =
        $("#input-extra-charge-amount").val() === undefined ||
        $("#input-extra-charge-amount").val() === "" ||
        $("#input-extra-charge-amount").val() === null ||
        $("#input-extra-charge-amount").val() === NaN ||
        $("#input-extra-charge-amount").val().length === 0
            ? 0.0
            : parseFloat($("#input-extra-charge-amount").val());

    let applyTax =
        $("input[name='apply_tax']:checked").val() === "Y" ? true : false;
    let tax_rate = parseFloat($("#input-tax-rate").val());
    let tax_type = $("#input-tax-type").val();

    let applyDiscount =
        $("input[name='apply-discount']:checked").val() === "Y" ? true : false;
    let discount_amount = sanitizeVal($("#input-discount-amount"));

    // RECALCULATE GRAND TOTAL
    if (applyExtraCharges) {
        temp_grand_total += extra_charges;
    }
    // APPLY DISCOUNT IF ANY
    if (applyDiscount) {
        temp_grand_total -= discount_amount;
    }
    sub_total = temp_grand_total += shipping_cost;
    // APPLY TAX IF ANY
    let tax_amount = 0.0;
    if (applyTax) {
        if (tax_type === "fixed") {
            tax_amount = tax_rate;
        } else {
            tax_amount = setDefaultPriceFormat(
                (temp_grand_total * tax_rate) / 100
            );
        }
    }
    let formatted_tax_amount = setDefaultPriceFormat(tax_amount);
    $("#order-tax-amount").html(currency_symbol + formatted_tax_amount);
    $("#input-tax-amount").val(parseFloat(formatted_tax_amount));
    temp_grand_total += parseFloat(tax_amount);
    // FORMATTED VALUES
    let formatted_sub_total = setDefaultPriceFormat(sub_total);
    let formatted_grand_total = setDefaultPriceFormat(temp_grand_total);
    // DYNAMIC HTML VAL
    $("#order-sub-total").html(currency_symbol + formatted_sub_total);
    $("#order-grand-total").html(currency_symbol + formatted_grand_total);
    // DYNAMIC INPUT VAL
    $("#input-sub-total").val(formatted_sub_total);
    $("#input-grand-total").val(formatted_grand_total);
    setPaymentTypeAndAmount();
}

function generateCartForEdit(order_id) {
    let currency_symbol = $("#currency_id option:selected").attr("data-symbol");

    $.ajax({
        type: "GET",
        url: BASE_URL + "orders/generate-cart-for-edit",
        data: {
            order_id: order_id,
            currency_symbol: currency_symbol,
        },
        dataType: "JSON",
        success: function (res) {
            $("#cart-item").html(res.data);
            $("#unit-by-quantity").html(
                res.total_units + "/" + res.total_quantity
            );
            $("#sub-total").html(
                currency_symbol + setDefaultPriceFormat(res.sub_total)
            );
            $("#grand-total").html(
                currency_symbol + setDefaultPriceFormat(res.sub_total)
            );

            $("#index").val(res.index);
            $("#imageLoading").addClass("d-none");
        },
        error: function (err) {
            toastr.error("Error, please contact admin!");
        },
    });
}

function fillCustomerData(dis) {
    if ($(dis).is("#first_name")) {
        $("#address_first_name").val($(dis).val());
    }
    if ($(dis).is("#last_name")) {
        $("#address_last_name").val($(dis).val());
    }
    if ($(dis).is("#telephone")) {
        $("#address_telephone").val($(dis).val());
    }
}

function ajaxCustomerCreate(dis, event) {
    let dataString = $(dis).serialize();
    let url = $(dis).attr("action");
    $(".custom-loader").removeClass("d-none");
    $.ajax({
        type: "POST",
        url: url,
        data: dataString,
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                $(".custom-loader").addClass("d-none");
                $("#addCustomer").modal("toggle");
                let select2Data = {
                    id: res.customer_id,
                    text:
                        $(dis).find("#address_first_name").val() +
                        " " +
                        $(dis).find("#address_last_name").val() +
                        ", " +
                        $(dis).find("#address_1").val() +
                        " " +
                        $(dis).find("#city").val() +
                        ", " +
                        $(dis).find("#zone_id option:selected").text() +
                        ", " +
                        $(dis).find("#country_id option:selected").text(),
                };
                let newOption = new Option(
                    select2Data.text,
                    select2Data.id,
                    true,
                    true
                );
                $("#customer_id").append(newOption).trigger("change");
                // SET SHIPPING ADDRESS OF CUSTOMER
                setShippingAddress(dis);
            } else {
                let errors = "";
                $.each(res.error, function (indexInArray, valueOfElement) {
                    errors += valueOfElement + "\n";
                });
                toastr.error(errors);
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: orders.js ~ line 492 ~ ajaxCustomerCreate ~ err",
                err
            );
        },
    });
}

function showHideAuthorize(dis) {
    setPaymentMethod(dis);
    if ($(dis).is(":checked")) {
        let dataCode = $(dis).attr("data-code");
        if (dataCode === "authorize") {
            $("#authorize-div").removeClass("d-none");
            makeFieldsRequiredUnRequired(dataCode);
            $(".coc-div").addClass("d-none");
        } else if (dataCode === "COC") {
            makeFieldsRequiredUnRequired(dataCode);
            $("#authorize-div").addClass("d-none");
            $(".coc-div").removeClass("d-none");
        } else {
            makeFieldsRequiredUnRequired(dataCode);
            $("#authorize-div").addClass("d-none");
            $(".coc-div").addClass("d-none");
        }
    }
    hideShowBillSection();
}

function makeFieldsRequiredUnRequired(dataCode) {
    if (dataCode === "COC") {
        $("#payment_type").prop("required", true);
        $("paid_amount").prop("required", true);
        $("#card_number").prop("required", false);
        $("#card_exp").prop("required", false);
        $("#card_cvv").prop("required", false);
        $("#payment_mode").prop("required", true);      
    } else if (dataCode === "authorize") {
        $("#payment_type").prop("required", false);
        $("paid_amount").prop("required", false);
        $("#card_number").prop("required", true);
        $("#card_exp").prop("required", true);
        $("#card_cvv").prop("required", true);
        $("#payment_mode").prop("required", false);      
    } else {
        $("#payment_type").prop("required", false);
        $("paid_amount").prop("required", false);
        $("#card_number").prop("required", false);
        $("#card_exp").prop("required", false);
        $("#card_cvv").prop("required", false);
        $("#payment_mode").prop("required", false);      
       
    }
}

function setStore(dis) {
    $("#store_name").val($("option:selected", dis).text());
}

function setCurrency(dis) {
    $("#currency_code").val($("option:selected", dis).attr("data-code"));
    $("#currency_value").val($("option:selected", dis).attr("data-value"));
}

function setShippingMethod(dis) {
    let currency_symbol = $("#currency_id option:selected").attr("data-symbol");
    let code = $(dis).attr("data-code");
    let shippingMethod = $(dis).attr("id");
    $("#shipping_method").val(shippingMethod);
    $("#shipping_method_code").val(code);
    $("#shipping_method_cost").val($(dis).attr("data-cost"));
    $("#order-shipping-method-text").html(
        "(" + shippingMethod + " - " + code + ")"
    );
    $("#order-shipping-method").html(
        currency_symbol + setDefaultPriceFormat($(dis).attr("data-cost"))
    );
}

function setPaymentMethod(dis) {
    let code = $(dis).attr("data-code");
    let paymentMethod = $(dis).attr("id");
    $("#payment_method").val(paymentMethod);
    $("#payment_method_code").val(code);
    $("#order-payment-method").html(paymentMethod + " (" + code + ")");
}

function setShippingAddress(dis) {
    $("#shipping_first_name").val($(dis).find("#address_first_name").val());
    $("#shipping_last_name").val($(dis).find("#address_last_name").val());
    $("#shipping_telephone").val($(dis).find("#address_telephone").val());
    $("#shipping_company").val($(dis).find("#company").val());
    $("#shipping_address_1").val($(dis).find("#address_1").val());
    $("#shipping_lat").val($(dis).find("#lat").val());
    $("#shipping_lng").val($(dis).find("#lng").val());
    $("#shipping_address_2").val($(dis).find("#address_2").val());
    $("#shipping_city").val($(dis).find("#city").val());
    $("#shipping_postcode").val($(dis).find("#postcode").val());

    $("#shipping_country_id").val(
        $(dis).find("#country_id option:selected").val()
    );
    $("#shipping_country_id").attr(
        "data-zone",
        $(dis).find("#zone_id option:selected").val()
    );
    $("#shipping_country_id").trigger("change");
}

function setPaymentTypeAndAmount() {
    let currency_symbol = $("#currency_id option:selected").attr("data-symbol");
    $("#order-payment-type").remove();
    $("#order-paid-amount").remove();
    $("#order-remaining-amount").remove();
    if ($("input[name='payment_method_id']:checked").val()) {
        let dataCode = $("input[name='payment_method_id']:checked").attr(
            "data-code"
        );
        if (dataCode === "COC") {
            let payment_type =
                $("#payment_type option:selected").val() === "full"
                    ? "Full Payment"
                    : "Partial Payment";
            let paid_amount =
                $("#paid_amount").val() !== undefined ||
                $("#paid_amount").val() !== "" ||
                $("#paid_amount").val() !== NaN
                    ? parseFloat($("#paid_amount").val())
                    : 0;
            let grand_total =
                $("#input-grand-total").val() !== undefined ||
                $("#input-grand-total").val() !== "" ||
                $("#input-grand-total").val() !== NaN
                    ? parseFloat($("#input-grand-total").val())
                    : 0;
            let remaining_amount = grand_total - paid_amount;
            // let html =
            //     '<tr class="fs-6 text-gray-800" id="order-payment-type">' +
            //     '<td colspan="2" class="fw-bolder text-end">' +
            //     '<input type="hidden" id="payment-type-val" value="' +
            //     payment_type +
            //     '" />' +
            //     "Payment Type:" +
            //     "</td>" +
            //     '<td colspan="2" class="fw-bolder text-end" id="payment-type-field">' +
            //     payment_type +
            //     "</td>" +
            //     "</tr>" +
            //     '<tr class="fs-6 text-gray-800" id="order-paid-amount">' +
            //     '<td colspan="2" class="fw-bolder text-end">' +
            //     "        Paid Amount:" +
            //     '<input type="hidden" id="paid-amount-val" value="' +
            //     paid_amount +
            //     '" />' +
            //     "    </td>" +
            //     '    <td colspan="2" class="fw-bolder text-end" id="paid-amount-field">' +
            //     currency_symbol +
            //     setDefaultPriceFormat(paid_amount) +
            //     "    </td>" +
            //     "</tr>" +
            //     '<tr class="fs-6 text-gray-800" id="order-remaining-amount">' +
            //     '    <td colspan="2" class="fw-bolder text-end">' +
            //     '<input type="hidden" id="remaining-amount-val" value="' +
            //     remaining_amount +
            //     '" />' +
            //     "        Remaining Amount:" +
            //     "    </td>" +
            //     '    <td colspan="2" class="fw-bolder text-end" id="remaining-amount-field">' +
            //     currency_symbol +
            //     setDefaultPriceFormat(remaining_amount) +
            //     "    </td>" +
            //     "</tr>";

            // $("#order-payment-method").closest(".text-gray-800").after(html);
            $("#remaining_amount").val(setDefaultPriceFormat(remaining_amount));
        }
    }
}

function handleTax(dis) {
    calGrandTotal();
}

function handleExtraCharges(dis) {
    if ($(dis).val() === "Y") {
        $("#input-extra-charge-amount").prop("disabled", false);
    } else {
        $("#input-extra-charge-amount").val(0);
        $("#input-extra-charge-amount").prop("disabled", true);
    }
    calGrandTotal();
    recalculateRemainingAmount();
}

$("#input-extra-charge-amount").on("change focusout", function () {
    calGrandTotal();
    recalculateRemainingAmount();
});

$("#payment_type").on("change focusout", function () {
    setPaymentTypeAndAmount();
});

$("#paid_amount").on("change focusout", function () {
    setPaymentTypeAndAmount();
});

function recalculateRemainingAmount() {
    let currency_symbol = $("#currency_id option:selected").attr("data-symbol");
    let paid_amount =
        $("#paid-amount-val").val() !== undefined ||
        $("#paid-amount-val").val() !== "" ||
        $("#paid-amount-val").val() !== NaN
            ? parseFloat($("#paid-amount-val").val())
            : 0;
    let grand_total =
        $("#input-grand-total").val() !== undefined ||
        $("#input-grand-total").val() !== "" ||
        $("#input-grand-total").val() !== NaN
            ? parseFloat($("#input-grand-total").val())
            : 0;
    if ($("#paid-amount-val").val()) {
        $("#remaining-amount-field").html(
            currency_symbol + setDefaultPriceFormat(grand_total - paid_amount)
        );
    }
}

function handleDiscount(dis) {
    if ($(dis).val() === "Y") {
        $("#input-discount-amount").prop("disabled", false);
    } else {
        $("#input-discount-amount").val(0);
        $("#input-discount-amount").prop("disabled", true);
    }
    calGrandTotal();
    recalculateRemainingAmount();
}

$("#input-discount-amount").on("change focusout", function (e) {
    value = parseFloat(e.target.value);
    max_allowed_discount = parseFloat(e.target.max);
    if (value > max_allowed_discount) {
        $("#input-discount-amount").val("0");
        toastr.warning("Your allowed discount is less than " + value);
    } else {
        calGrandTotal();
        recalculateRemainingAmount();
    }
});

function getApplicableTaxClass(url, country_id, zone_id) {
    let isTaxApplicable = true;
    $.ajax({
        type: "POST",
        url: url,
        data: {
            country_id: country_id,
            zone_id: zone_id,
            _token: CSRF_TOKEN,
        },
        async: false,
        dataType: "JSON",
        success: function (res) {
            if (!res.status) {
                isTaxApplicable = res.status;
                toastr.error(res.data);
            }
        },
        error: function (err) {
            console.log("🚀 ~ file: form.blade.php ~ line 1243 ~ err", err);
        },
    });
    return isTaxApplicable;
}

function validateCart(url, customer_id) {
    let isCartValid = true;
    $.ajax({
        type: "POST",
        url: url,
        data: {
            customer_id: customer_id,
            _token: CSRF_TOKEN,
        },
        async: false,
        dataType: "JSON",
        success: function (res) {
            if (!res.status) {
                isCartValid = res.status;
                toastr.error(res.data);
            }
        },
        error: function (err) {
            console.log("🚀 ~ file: form.blade.php ~ line 1243 ~ err", err);
        },
    });
    return isCartValid;
}

function getUncalOrderTotal(url, customer_id) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            customer_id: customer_id,
            _token: CSRF_TOKEN,
        },
        async: false,
        dataType: "JSON",
        success: function (res) {
            $("#uncal-order-total").val(
                $("#currency_id option:selected").attr("data-symbol") +
                    setDefaultPriceFormat(res.sub_total)
            );
        },
        error: function (err) {
            console.log("🚀 ~ file: form.blade.php ~ line 1243 ~ err", err);
        },
    });
}

function hideShowBillSection() {
    let paymentMethodCode = $("input[name='payment_method_id']:checked").attr(
        "data-code"
    );
    let value = $("#payment_mode option:selected").val();

    if (value === "cash" && paymentMethodCode === "COC") {
        $(".bills-div").removeClass("d-none");
    } else {
        $(".bills-div").addClass("d-none");
        //$("#payment_mode").val($("#payment_mode option:first").val());
    }
}

function hideShowAmountSection(dis) {
    let value = $("option:selected", dis).val();
    if (value === "partial") {
        $(".partial-div").removeClass("d-none");
    } else {
        $(".partial-div").addClass("d-none");
    }
}

function handleOrderSubmit(dis) {
    const notes = {
        hundred: 100,
        fifty: 50,
        twenty: 20,
        ten: 10,
        five: 5,
        two: 2,
        one: 1,
    };
    let valid = true;
    let paymentMode = $("#payment_mode option:selected").val();
    let grandTotal = parseInt($("#input-grand-total").val());
    let paymentType = $("#payment_type option:selected").val();
    let paymentMethod = $('#payment_method_code').val();
    if(paymentMethod == 'COC')
    {
        if(paymentMode === undefined || paymentMode === null || paymentMode === NaN || paymentMode == "")
        {
            valid = false;
            toastr.warning("Please Select Payment Mode");
        }
    }
    let paidAmount =
        $("#paid_amount").val() !== undefined &&
        $("#paid_amount").val() !== NaN &&
        $("#paid_amount").val() !== null &&
        $("#paid_amount").val() !== ""
            ? parseInt($("#paid_amount").val())
            : 0;
    let noteGrandTotal = 0;
  
    if (paymentMode === "cash") {
        for (const key in notes) {
            let noteCount =
                $("#" + key).val() !== undefined &&
                $("#" + key).val() !== NaN &&
                $("#" + key).val() !== null &&
                $("#" + key).val() !== ""
                    ? parseInt($("#" + key).val())
                    : 0;
            noteGrandTotal += parseInt(notes[key]) * noteCount;
        }
        if (paymentType === "partial") {
            if (noteGrandTotal !== paidAmount) {
                valid = false;
            }
        } else {
            if (noteGrandTotal !== grandTotal) {
                valid = false;
            }
        }
    }
    if (!valid) {
        if (paymentType === "partial") {
            toastr.warning(
                "The calculated bills grand total is not equal to order paid amount!"
            );
        } else {
            toastr.warning(
                "The calculated bills grand total is not equal to order grand total!"
            );
        }
    }
    if (valid) {
        document.getElementById("multi_step_form").submit();
    }
    return valid;
}

function createProductForAdminPanel(dis, event) {
    let dataString = $(dis).serialize();
    let url = $(dis).attr("action");
    $(".custom-loader").removeClass("d-none");
    $.ajax({
        type: "POST",
        url: url,
        data: dataString,
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                $(".custom-loader").addClass("d-none");
                $("#add-admin-product").modal("toggle");
                toastr.success("Product created successfully.");

                /**
                 * resetting form fields
                 */
                $("#name").val("");
                $("#price").val("");
                $("#quantity").val("");
                $("#quantity").val("");
                $("#category_id").val($("#category_id option:first").val());
                $("#manufacturer_id").val(
                    $("#manufacturer_id option:first").val()
                );
            } else {
                let errors = "";
                $.each(res.error, function (indexInArray, valueOfElement) {
                    errors += valueOfElement + "\n";
                });
                toastr.error(errors);
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: orders.js ~ line 1120 ~ createProductForAdminPanel ~ err",
                err
            );
        },
    });
}
