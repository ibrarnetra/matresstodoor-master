function addOrderHistory(dis, type = "order") {
    let valid = true;

    let paymentMode = $(dis).find("#payment_mode option:selected").val(); // get payment mode
    let paymentMethod = $(dis).find("#order-payment-method").val(); // get payment_method
    let paymentType = $(dis).find("#order-payment-type").val(); // get payment_type
    let remainingAmount = amountSanitizer(
        $(dis).find("#order-remaining-amount").val()
    ); // get remaining_amount

    let totalAmount = amountSanitizer($(dis).find("#order-total-amount").val()); // get total_amount

    if (paymentMode === "cash" || paymentMode == "cash-card" || paymentMode == "cash-online") {
        if (paymentMethod === "COD") {
            let notesGrandTotal = calculateNotesTotal(); // get notes total
            if (paymentMode == "cash") {
                if (notesGrandTotal !== totalAmount) {
                    valid = false;
                    toastr.warning(
                        "The calculated bills grand total is not equal to order grand total!"
                    );
                }
            } else if (paymentMode == "cash-card" || paymentMode == "cash-online") {
                let cashAmount = $(dis).find("#both-cash-amount").val();
                if (notesGrandTotal != cashAmount) {
                    valid = false;
                    toastr.warning(
                        "The calculated bills grand total is not equal to cash amount!"
                    );
                }
            }
        }
        if (paymentType === "partial") {
            let notesGrandTotal = calculateNotesTotal(); // get notes total
            if (paymentMode == "cash") {
                if (notesGrandTotal !== remainingAmount) {
                    valid = false;
                    toastr.warning(
                        "The calculated bills grand total is not equal to order remaining amount!"
                    );
                }
            } else if (paymentMode == "cash-card" || paymentMode == "cash-online") {
                let cashAmount = $(dis).find("#both-cash-amount").val();

                if (notesGrandTotal != cashAmount) {
                    valid = false;
                    toastr.warning(
                        "The calculated bills grand total is not equal to cash amount!"
                    );
                }
            }
        }
    }
    if ($(dis).find("#payment_mode").prop("required")) {
        if (
            paymentMode == "" ||
            paymentMode == NaN ||
            paymentMethod == undefined
        ) {
            valid = false;
        }
    }

    if (paymentMode == "cash-card" || paymentMode == "cash-online")
    {
        let CashAmount = $(dis).find("#both-cash-amount").val();
        if (
            CashAmount == "" ||
            CashAmount == NaN ||
            CashAmount == undefined
            || CashAmount == "0"
        ) {
            valid = false;
            toastr.warning("Cash amount is required");
        }


    }

    return valid;
}

function calculateNotesTotal() {
    const notes = {
        hundred: 100,
        fifty: 50,
        twenty: 20,
        ten: 10,
        five: 5,
        two: 2,
        one: 1,
    };
    let notesGrandTotal = 0;
    for (const key in notes) {
        let noteCount =
            $("#" + key).val() !== undefined &&
            $("#" + key).val() !== NaN &&
            $("#" + key).val() !== null &&
            $("#" + key).val() !== ""
                ? parseInt($("#" + key).val())
                : 0;
        notesGrandTotal += parseInt(notes[key]) * noteCount;
    }
    return notesGrandTotal;
}

function amountSanitizer(eleWithAttr) {
    return eleWithAttr !== undefined &&
        eleWithAttr !== NaN &&
        eleWithAttr !== null &&
        eleWithAttr !== ""
        ? parseInt(eleWithAttr)
        : 0;
}

function loadUpdateOrderModal(dis, id, url, type = "order") {
    let parent = type === "order" ? $("#add-route") : $("#update-payment");
    parent.find("#partial-done-table").html("");

    $.ajax({
        type: "GET",
        url: url,
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                parent.find("#order-detail").html(res.data);
                parent.find("#partial-done-table").html(res.partial_done);
                parent.modal("show");
                parent.find("#order-id").val(id); // set order_id
                let paymentMethod = $(dis)
                    .closest("tr")
                    .attr("data-payment-method"); // get payment_method
                let paymentType = $(dis)
                    .closest("tr")
                    .attr("data-payment-type"); // get payment_type
                let remainingAmount = amountSanitizer(
                    $(dis).closest("tr").attr("data-remaining-amount")
                ); // get remaining_amount
                let totalAmount = amountSanitizer(
                    $(dis).closest("tr").attr("data-total-amount")
                ); // get total_amount
                let orderStatus = $(dis)
                    .closest("tr")
                    .attr("data-order-status"); // get order_status
                let orderStatusId = $(dis)
                    .closest("tr")
                    .attr("data-order-status-id"); // get order_status_id

                parent.find("#order-payment-method").val(paymentMethod);
                parent.find("#order-payment-type").val(paymentType);

                parent.find("#order-remaining-amount").val(remainingAmount);
                parent.find("#order-total-amount").val(totalAmount);
                $("#payment-recieved-row").addClass("d-none");
                if (parseInt(remainingAmount) === 0) {
                    $("#payment-received").val(true);
                }
                parent.find("#order-status-id").val(orderStatusId);
                parent.find("#order-status").val(orderStatus);
                if (type === "payment" && parseInt(remainingAmount) === 0) {
                    parent
                        .find("#update-payment-button")
                        .attr("disabled", "disabled");
                }

                /**
                 * preselect order status if matches in case of `Update Order`
                 */
                if (type === "order") {
                    let exists =
                        0 !=
                        $(
                            "#order_status_id option[value=" +
                                orderStatusId +
                                "]"
                        ).length;

                    if (exists) {
                        $(
                            "#order_status_id option[value=" +
                                orderStatusId +
                                "]"
                        ).prop("selected", true);
                    }

                    handleRemoveOrderVisibility($("#order_status_id"));
                }

                if (
                    paymentMethod === "COD" ||
                    paymentMethod === "p-link" ||
                    paymentType === "partial" ||
                    paymentMethod === "authorize"
                ) {
                    parent.find("#payment-mode-section").removeClass("d-none");
                }
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: route.js ~ line 74 ~ loadUpdateOrderModal ~ err",
                err
            );
        },
    });
}

function routeOrderCashSummary(dis, id, url) {
    let parent = $("#route-order-cash");
    parent.modal("hide");
    parent.find("#route-order-cash-summary").html("");
    $.ajax({
        type: "GET",
        url: url,
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                parent.modal("show");
                parent.find("#route-order-cash-summary").html(res.data);
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: route.js ~ line 74 ~ loadUpdateOrderModal ~ err",
                err
            );
        },
    });
    parent.modal("show");
}
function loadOptimizationModal(optimization_url) {
    $(".custom-loader").removeClass("d-none");
    let start_location_id = $("#start_location_id").val();
    let end_location = $("#end_location").val();
    $.ajax({
        type: "POST",
        url: optimization_url,
        data: {
            _token: CSRF_TOKEN,
            start_location_id: start_location_id,
            end_location: end_location,
        },
        dataType: "JSON",
        success: function (res) {
            $(".custom-loader").addClass("d-none");
            if (res.status) {
                $("#optimization-res-div").html(res.data);
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: index.blade.php ~ line 243 ~ routeOptimization ~ err",
                err
            );
        },
    });
}

function updateRoutes(url) {
    let start_location_id = $("#start_location_id").val();
    let end_location = $("#end_location").val();
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: CSRF_TOKEN,
            start_location_id: start_location_id,
            end_location: end_location,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                $("#optimize-route-modal").modal("hide");
                toastr.success(
                    "Successfully optimized route delivery route.",
                    ""
                );
                location.reload();
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: index.blade.php ~ line 243 ~ routeOptimization ~ err",
                err
            );
        },
    });
}

function getLatLng(dis, url) {
    $(".custom-loader").removeClass("d-none");
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: CSRF_TOKEN,
        },
        dataType: "JSON",
        success: function (res) {
            $(".custom-loader").addClass("d-none");
            if (res.status) {
                toastr.success(res.data, "");
                $(dis).remove();
            } else {
                toastr.error(res.data, "");
            }
            if ($(".get-lat-lng").length === 0) {
                location.reload();
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: route.js ~ line 145 ~ getLatLng ~ err",
                err
            );
        },
    });
}
function getOrderSummary(dis, url) {
    $("#route-order-summary").html("");
    $.ajax({
        type: "GET",
        url: url,
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                $("#order-summary").modal("show");
                $("#route-order-summary").html(res.data);
            } else {
                toastr.error("Something went wrong");
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: route.js ~ line 145 ~ getLatLng ~ err",
                err
            );
        },
    });
}

function copyToClipboard(dis) {
    initCopyToClipBoard(
        "#copy",
        true,
        document.getElementById("unoptimized-routes"),
        $("#addresses").val()
    );
}

function removeAddress(dis, url) {
    $(".custom-loader").removeClass("d-none");
    $.ajax({
        type: "GET",
        url: url,
        dataType: "JSON",
        success: function (res) {
            $(".custom-loader").addClass("d-none");
            if (res.status) {
                toastr.success(res.data, "");
                $(dis).closest("tr").remove();
            } else {
                toastr.error(res.data, "");
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: route.js ~ line 145 ~ getLatLng ~ err",
                err
            );
        },
    });
}

function hideShowBillSection() {
    let value = $("#payment_mode option:selected").val();

    if (value === "cash" || value == "cash-card" || value == "cash-online") {
        $(".bills-section").removeClass("d-none");
        if (value == "cash-card" || value == "cash-online") {
            $("#both-cash-card-amount").removeClass("d-none");
        } else {
            $("#both-cash-card-amount").addClass("d-none");
        }
    } else {
        $(".bills-section").addClass("d-none");
    }
}

function copySingleAddress(dis) {
    initCopyToClipBoard(
        "#copy-address",
        false,
        "",
        $(dis).closest("tr").find(".address").val()
    );
}

function generateLoadingSheet(dis, route_id, url) {
    $(".custom-loader").removeClass("d-none");
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: CSRF_TOKEN,
            route_id: route_id,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                toastr.success("Successfully generated loading sheet");
                $(".custom-loader").addClass("d-none");
                window.location =
                    BASE_URL + "loading-sheets/detail/" + res.loading_sheet_id;
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: route.js ~ line 235 ~ generateLoadingSheet ~ err",
                err
            );
        },
    });
}

function loadOrdersModal(dis, url) {
    $(".custom-loader").removeClass("d-none");
    $.ajax({
        type: "GET",
        url: url,
        dataType: "JSON",
        success: function (res) {
            $("#order-modal-section").html(res.data);
            $(".custom-loader").addClass("d-none");
            initDataTable(
                $(document).find("#generic-datatable"),
                setConfigOptions()
            );
            $("#orders-modal").modal("show");
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: route.js ~ line 272 ~ loadOrdersModal ~ err",
                err
            );
        },
    });
}

function loadRouteSummary(dis, url) {
    $(".custom-loader").removeClass("d-none");
    $.ajax({
        type: "GET",
        url: url,
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                $(".custom-loader").addClass("d-none");
                $("#summary").html(res.data);
                $("#route-summary-modal").modal("show");
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: route.js ~ line 321 ~ loadRouteSummary ~ err",
                err
            );
        },
    });
}

function handleRemoveOrderVisibility(dis) {
    $("#partial-done-table").addClass("d-none");
    if ($(dis).val() == "17") {
        $("#remove-order-div").removeClass("d-none");
    } else if ($(dis).val() == "20") {
        $("#partial-done-table").removeClass("d-none");
    } else {
        $("#remove-order-div").addClass("d-none");
    }
    if ($(dis).val() == "16" || $(dis).val() == "20") {
        $("#payment-recieved-row").removeClass("d-none");
        $("#r-amount").attr("readonly", true);
        let amount = $("#r-amount").val();
        if (parseFloat(amount) > 0) {
            $("#r-amount").prop("disabled", false);
            $("#payment_mode").prop("required", true);
            $("#payment_method").prop("disabled", false);
        } else {
            $("#payment-recieved-row").addClass("d-none");
            $("#payment_mode").prop("required", false);
        }
    } else {
        $("#payment-recieved-row").addClass("d-none");
        $("#r-amount").prop("disabled", true);
        $("#payment_method").prop("disabled", true);
        $("#payment_mode").prop("required", false);
    }
}

function returnQuantityPrice(ele, type) {
    if (type == "quantity") {
        let max = $(ele).attr("max");
        let quan = $(ele).val();
        if (parseInt(quan) > parseInt(max)) {
            $(ele).val("0");
            toastr.warning("Quantity must be less than " + max);
        }
    }
    else{
        let index = $('.order_product_id').index(ele);
        if($('.order_product_id').eq(index)[0].checked)
        {
            $(".return_product_quantity").eq(index).prop('disabled', false);
        }
        else{
            $(".return_product_quantity").eq(index)[0].value = 0;
            $(".return_product_quantity").eq(index).prop('disabled', true);
        }
    }
    let status = $(".order_product_id");
    let total_return_amount = 0;
    for (let i = 0; i < status.length; i++) {
        if (status[i].checked) {
            let quantity = $(".return_product_quantity").eq(i)[0].value;
            let price = $(".return_single_amount").eq(i)[0].value;
            total_return_amount += quantity * price;
        }
    }
    let discount_amount = $("#order_discount_amount").val();
    let total_remaining_amount = $("#total_remaining_amount").val();
    total_remaining_amount += discount_amount;
    total_return_amount -= discount_amount;
    let country_id = $("#tax_country_id").val();
    let zone_id = $("#tax_zone_id").val();
    let url = $("#tax_url").val();
    let apply_tax = $("#apply_tax").val();
    if (apply_tax == "Y") {
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
                if (res.status == true) {
                    let tax_rate = res.tax_class.tax_rate;
                    if (total_return_amount > 0 && tax_rate) {
                        let tax_amount = total_return_amount * (tax_rate / 100);
                        tax_amount = Math.round(tax_amount);
                        total_return_amount += tax_amount;
                        $("#return-total-amount").val(total_return_amount);
                    }
                }

                let TotalPaidAmount = amountSanitizer(
                    parseFloat(total_remaining_amount).toFixed(2) -
                        parseFloat(total_return_amount).toFixed(2)
                );
                $("#r-amount").val(TotalPaidAmount);
                $("#order-remaining-amount").val(TotalPaidAmount);
                $("#order-total-amount").val(TotalPaidAmount);
            },
            error: function (err) {
                toastr.error("Error, please contact admin!");
            },
        });
    } else {
        $("#return-total-amount").val(total_return_amount);
        let TotalPaidAmount = amountSanitizer(
            parseFloat(total_remaining_amount).toFixed(2) -
                parseFloat(total_return_amount).toFixed(2)
        );
        $("#r-amount").val(TotalPaidAmount);
        $("#order-remaining-amount").val(TotalPaidAmount);
        $("#order-total-amount").val(TotalPaidAmount);
    }
}
