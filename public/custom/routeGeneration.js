let acceptable = ["Pending", "Processing", "Ready", "Postpone"];

function loadRouteOrdersModal(url) {
    let order_ids = [];
    $("input[name=id]").each(function (i, ele) {
        if ($(ele).is(":checked")) {
            order_ids.push($(ele).val());
        }
    });

    if (order_ids.length === 0) {
        toastr.error("Select Order(s) to generate Route.", "");
    } else {
        $.ajax({
            type: "POST",
            url: url,
            data: {
                order_ids: order_ids,
                _token: CSRF_TOKEN,
            },
            dataType: "JSON",
            success: function (res) {
            
                if (res.status) {
                    $("#order-routes").html(res.data);
                    if ($(document).find("#orders-modal")) {
                        $(document).find("#orders-modal").modal("hide");
                    }
                    $("#add-route").modal("show");
                   
                    $.each(res.order_statuses, function (idx, val) {
                        if (!acceptable.includes(val)) {
                            $("#add-route")
                                .find("button[type='submit']")
                                .prop("disabled", true);
                            return false; // breaks loop
                        }
                        else{
                            $("#add-route")
                            .find("button[type='submit']")
                            .prop("disabled", false);
                        }
                    });
                    // if (res.already_assigned_orders_count > 0) {
                    //     $("#add-route")
                    //         .find("button[type='submit']")
                    //         .prop("disabled", true);
                    // }
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
}

function removeOrder(dis) {
    $(dis).closest("tr").remove();
    recalculateSortOrder();
    let status = $("#add-route").find(".order_status");
    if (status.length == 0) {
        $("#add-route").find("button[type='submit']").prop("disabled", true);
    }
    for (let i = 0; i < status.length; i++) {
        if (!acceptable.includes(status[i].value)) {
            $("#add-route")
                .find("button[type='submit']")
                .prop("disabled", true);
            return false; // breaks loop
        } else {
            $("#add-route")
                .find("button[type='submit']")
                .prop("disabled", false);
        }
    }
    // $.each($("#add-route").find(".id"), function (idx, ele) {
    //     console.log("1");
    //     if (!acceptable.includes($(ele).attr("data-order-status"))) {
    //         $("#add-route")
    //             .find("button[type='submit']")
    //             .prop("disabled", true);
    //         return false; // breaks loop
    //     } else if ($(ele).attr("data-assigned") === "true") {
    //         $("#add-route")
    //             .find("button[type='submit']")
    //             .prop("disabled", true);
    //         return false; // breaks loop
    //     } else {
    //         $("#add-route")
    //             .find("button[type='submit']")
    //             .prop("disabled", false);
    //     }
    // });
}

function recalculateSortOrder() {
    $("#route-orders")
        .find(".sort-order")
        .each(function (idx, ele) {
            $(ele)
                .find(".sort-order-text")
                .html(idx + 1);
            $(ele)
                .find(".sort-order-value")
                .val(idx + 1);
        });
}

function routeCreation(dis) {
    let order_ids = [];
    let valid = true;
    // create order_ids for route creation
    $(document)
        .find("#route-orders")
        .find(".id")
        .each(function (i, ele) {
            order_ids.push($(ele).val());
        });
    // check if route creation has any orders before creation
    if (order_ids.length === 0) {
        alert("No orders have been selected.");
        valid = false; // prevents form submission
    }
    return valid;
}
