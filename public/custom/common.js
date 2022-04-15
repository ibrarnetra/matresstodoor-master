let customer_haystack = [];

const dateToday = new Date();
const dateYesterday = new Date(dateToday);
dateYesterday.setDate(dateYesterday.getDate() - 1);

let customDatePickerConfig = {
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: false,
    autoApply: true,
    startDate: dateYesterday,
    locale: {
        format: "YYYY-MM-DD",
        separator: "-",
    },
};

$(document).ready(function () {
    $(".select2").select2();
    if ($('a[href="#general"]').length) {
        $('a[href="#general"]').tab("show");
        $('a[href="#en"]').tab("show");
        $("ul.nav-tabs a").click(function (e) {
            e.preventDefault();
            $(this).tab("show");
        });
    }

    if ($('a[href="#en"]').length) {
        $('a[href="#en"]').tab("show");
        $("ul.nav-tabs a").click(function (e) {
            e.preventDefault();
            $(this).tab("show");
        });
    }

    if ($('a[href="#attribute"]').length) {
        $('a[href="#attribute-en"]').tab("show");
        $("ul.nav-tabs a").click(function (e) {
            e.preventDefault();
            $(this).tab("show");
        });
    }

    if ($("#method")[0]) {
        let method = $("#method").val();
        if (method == "create") {
            var d = new Date();
            var datestring =
                d.getFullYear() +
                "-" +
                ("0" + (d.getMonth() + 1)).slice(-2) +
                "-" +
                ("0" + d.getDate()).slice(-2); // 2015-05-16
            $(".datepicker").val(datestring);
        }
    }

    $(document).find(".country").select2();
    $(document).find(".zone").select2();
    $(document).find(".geozone").select2();
    initTimePicker($(".time-picker"));
    initDatePicker($(".datepicker"));
    initCustomDatePicker($(".custom-date-picker"), customDatePickerConfig);

    $(window).on("keyup keypress", function (e) {
        let keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
});

function initDatePicker(ele) {
    ele.daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 2000,
        autoApply: true,
        minDate: dateYesterday,
        startDate: dateYesterday,
        locale: {
            format: "YYYY-MM-DD",
            separator: "-",
        },
    });
}

function loadModal(url) {
    $.ajax({
        url: url,
        type: "GET",
        dataType: "JSON",
        delay: 200,
        success: function (res) {
            $(".generic-modal-div").html(res.data);
            $("#generic-modal").modal("show");
        },
        error: function (err) {
            console.log(err);
        },
    });
}

function updateStatus(
    updateStatusUrl,
    current_status,
    initDataTable = true,
    dis = "-1"
) {
    let url = updateStatusUrl;
    let data = {
        current_status: current_status,
        _token: CSRF_TOKEN,
    };
    if (dis !== "-1") {
        url = BASE_URL + "orders/update-status/" + $(dis).attr("data-id");
        data = {
            current_status: current_status,
            order_status_id: $("option:selected", dis).val(),
            _token: CSRF_TOKEN,
        };
    }
    $.ajax({
        url: url,
        type: "POST",
        dataType: "JSON",
        delay: 200,
        data: data,
        success: function (res) {
            toastr.success("Success", "");
            if (initDataTable) {
                $("#generic-datatable").DataTable().ajax.reload();
            } else {
                location.reload();
            }
        },
        error: function (err) {
            toastr.error("Error", "");
        },
    });
}

function deleteData(delUrl, initDataTable = true) {
   
      
        swal
        .fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        })
        .then(result => {
            if (result.value) {
                $.ajax({
                    url: delUrl,
                    type: "GET",
                    dataType: "JSON",
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.data, "");
                            if (initDataTable) {
                                $("#generic-datatable").DataTable().ajax.reload();
                            } else {
                                location.reload();
                            }
                        } else {
                            toastr.error(res.data, "");
                        }
                    },
                    error: function (err) {
                        toastr.error("Error", "");
                    },
                });
            } else if (
                // Read more about handling dismissals
                result.dismiss === swal.DismissReason.cancel
            ) {
                toastr.success("Your record is safe!", "Not Deleted");
            }
        });
}

function initDataTable(ele, config) {
    ele.DataTable(config);
}

/**
 *
 * @param {*} ele element that is going to initialize that ajax base select2
 * @param {*} url url for ajax
 * @param {*} modal dataParent element whether the select2 is nested in a focus changing element i.e. Bootstrap Modal
 */
function initSelect2Ajax(ele, url, modal = "") {
    let config = {
        ajax: {
            url: url,
            dataType: "JSON",
            delay: 250,
            data: function (params) {
                let country_id = "";
                let customer_id = "";
                if (ele.hasClass("zone")) {
                    country_id = ele.closest(".row").find(".country").val();
                }
                if (ele.is(".coupon") || ele.is(".voucher")) {
                    customer_id =
                        $(".customer option:selected").val() == undefined
                            ? "-1"
                            : $(".customer option:selected").val();
                }
                return {
                    q: params.term, // search term
                    country_id: country_id,
                    customer_id: customer_id,
                };
            },
            processResults: function (res) {
                if (ele.is("#customer_id") && res.data.length > 0) {
                    customer_haystack = res.data;
                }
                return {
                    results: res.search,
                };
            },
            cache: true,
        },
        placeholder: "-- Search Data --",
        minimumInputLength: 1,
    };

    /**
     * ref:https://stackoverflow.com/questions/62894122/conditionally-change-object-property
     */
    config = {
        ...config,
        ...(ele.hasClass("country") && { placeholder: "Search Country" }),
    };

    config = {
        ...config,
        ...(ele.hasClass("zone") && { placeholder: "Search Zone" }),
    };

    config = {
        ...config,
        ...(ele.hasClass("category") && { placeholder: "Search Category" }),
    };

    config = {
        ...config,
        ...(ele.hasClass("related") && {
            placeholder: "Search Related Product",
        }),
    };

    config = {
        ...config,
        ...(ele.hasClass("product") && { placeholder: "Search Product",
        minimumInputLength: 1,
        language: {
            inputTooShort: function() {
                return 'Please type Product Name';
            },
            noResults: function(){
                return "No Product Found";
            }
        }  }),
    };

    config = {
        ...config,
        ...(ele.hasClass("parent-category") && {
            placeholder: "Search Parent Category",
        }),
    };

    config = {
        ...config,
        ...(ele.hasClass("customer") && { placeholder: "Search Customer",  minimumInputLength: 1,
        language: {
            inputTooShort: function() {
                return 'Please type Customer Name';
            },
            noResults: function(){
                return "No Customer Found";
            }
        }  }),
    };
    config = {
        ...config,
        ...(ele.hasClass("order") && { placeholder: "Search Order Number",
        minimumInputLength: 1,
        language: {
            inputTooShort: function() {
                return 'Please type Order Number';
            },
            noResults: function(){
                return "No Order Found";
            }
        } }),
    };

    config = {
        ...config,
        ...(ele.hasClass("order-id") && { placeholder: "Search Order Number" }),
    };

    config = {
        ...config,
        ...(modal !== "" && { dropdownParent: modal }),
    };

    ele.select2(config);
}

$(".delete-checkbox").on("click", function () {
    let toggle = false;
    setTimeout(() => {
        $("input[name=id]").each(function (i, ele) {
            if ($(ele).is(":checked")) {
                toggle = true;
            }
        });
        if (toggle) {
            $(".create").addClass("d-none");
            $(".delete").removeClass("d-none");
        } else {
            $(".create").removeClass("d-none");
            $(".delete").addClass("d-none");
        }
    }, 100);
});

function bulkDelete(url) {
    let ids = [];
    $("input[name=id]").each(function (i, ele) {
        if ($(ele).is(":checked")) {
            ids.push($(ele).val());
        }
    });
    $.ajax({
        url: url,
        type: "POST",
        data: {
            ids: ids,
            _token: CSRF_TOKEN,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                location.reload();
            } else {
                alert("Could not deleted data!");
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: common.js ~ line 159 ~ bulkDelete ~ err",
                err
            );
        },
    });
}

function getZones(dis, url) {
    let country_id = $("option:selected", dis).val();
    let data_zone = $(dis).attr("data-zone");
    $.ajax({
        type: "GET",
        url: url,
        data: {
            country_id: country_id,
        },
        dataType: "JSON",
        success: function (res) {
            let ele = $(dis).closest(".row").find(".zone, .select2-zone");
            if (res.status) {
                ele.html("").select2({ data: res.data });
                if (typeof data_zone !== "undefined" && data_zone !== false) {
                    if (
                        $("#" + ele.attr("id") + " option").filter(function () {
                            // check if zone has a value === data_zone make is selected
                            return $(this).val() == data_zone;
                        }).length
                    ) {
                        ele.val(data_zone);
                    } else {
                        // check if zone has text === data_zone make is selected
                        ele.val(
                            ele
                                .find("option:contains('" + data_zone + "')")
                                .val()
                        );
                    }
                    ele.trigger("change");
                }
                if (ele.attr("data-id") === "modal-zone") {
                    ele.select2({
                        dropdownParent: $(document).find(
                            "#addCustomer .modal-body"
                        ),
                    });
                }
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: common.js ~ line 233 ~ getZones ~ err",
                err
            );
        },
    });
}

function initCkEditor(ele) {
    ClassicEditor.create(document.getElementById(ele)).catch((error) => {
        console.error(error);
    });
}

function initTimePicker(ele) {
    ele.flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
}

function initDateRangePicker(ele) {
    let date = new Date();
    let unFormattedStartDate = new Date(date.getFullYear(), date.getMonth(), 1);
    let formattedStateDate =
        unFormattedStartDate.getFullYear() +
        "-" +
        ("0" + (unFormattedStartDate.getMonth() + 1)).slice(-2) +
        "-" +
        ("0" + unFormattedStartDate.getDate()).slice(-2);

    ele.daterangepicker({
        singleDatePicker: false,
        showDropdowns: true,
        startDate: formattedStateDate,
        locale: {
            format: "YYYY-MM-DD",
            separator: " to ",
            cancelLabel: "Clear",
        },
        autoUpdateInput: false,
    });
}

function setDefaultPriceFormat(val) {
    return Math.round(val).toFixed(2);
}

function initCustomDatePicker(ele, configOptions) {
    ele.daterangepicker(configOptions);
}

function assignUnassignOrder(dis, url) {
    let assigned_to = $("option:selected", dis).val();
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: CSRF_TOKEN,
            assigned_to: assigned_to,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                toastr.success(res.data, "");
            } else {
                toastr.error(res.data, "");
            }
            $("#generic-datatable").DataTable().ajax.reload();
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: orders.js ~ line 823 ~ assignUnassignOrder ~ err",
                err
            );
        },
    });
}

function initCustomDatePicker(ele, options) {
    ele.daterangepicker(options);
}

$(".custom-date-picker").on("apply.daterangepicker", function (ev, picker) {
    $(this).val(picker.startDate.format("YYYY-MM-DD"));
});

$(".custom-date-picker").on("cancel.daterangepicker", function (ev, picker) {
    $(this).val("");
});

// mobile hyphens enforcement
$("input[type='tel']").keyup(function () {
    $(this).val(
        $(this)
            .val()
            .replace(/(\d{3})\-?(\d{3})\-?(\d{4})/, "$1-$2-$3")
    );
});

// mobile hyphens enforcement
function setDefaultMobileFormat(ele) {
    ele.keyup(function () {
        $(this).val(
            $(this)
                .val()
                .replace(/(\d{3})\-?(\d{3})\-?(\d{4})/, "$1-$2-$3")
        );
    });
}

/*
 * Hacky fix for a bug in select2 with jQuery 3.6.0's new nested-focus "protection"
 * see: https://github.com/select2/select2/issues/5993
 * see: https://github.com/jquery/jquery/issues/4382
 *
 * TODO: Recheck with the select2 GH issue and remove once this is fixed on their side
 */

$(document).on("select2:open", () => {
    document
        .querySelector(".select2-container--open .select2-search__field")
        .focus();
});

// $(document).on("select2:open", () => {
//     document.querySelector(".select2-search__field").focus();
// });

/**
 *
 * @param {trigger element id} triggerEleId should be a unique id
 * @param {true || false} isContainer should be true if the focus is changes i.e Bootstrap Modal
 * @param {focusable element using document.getElementById} containerEle
 * @param {element from which text be extracted from} copyableTextEle
 */
function initCopyToClipBoard(
    triggerEleId,
    isContainer,
    containerEle,
    copyableTextEle
) {
    let clipboard =
        isContainer === true
            ? new ClipboardJS(triggerEleId, {
                  container: containerEle, // For use in Bootstrap Modals or with any other library that changes the focus you'll want to set the focused element as the container value.
                  text: function () {
                      return copyableTextEle;
                  },
              })
            : new ClipboardJS(triggerEleId, {
                  text: function () {
                      return copyableTextEle;
                  },
              });

    // clipboard.on("success", function (e) {
    //     console.info("🚀 ~ file: common.js ~ line 485 ~ e.action", e.action);
    //     console.info("🚀 ~ file: common.js ~ line 485 ~  e.text", e.text);
    //     console.info("🚀 ~ file: common.js ~ line 485 ~ e.trigger", e.trigger);

    //     e.clearSelection();
    // });

    // clipboard.on("error", function (e) {
    //     console.error("🚀 ~ file: common.js ~ line 485 ~ e.action", e.action);
    //     console.error("🚀 ~ file: common.js ~ line 485 ~ e.trigger", e.trigger);
    // });
}

function generateCreditCardExpiration() {
    let currentDate = new Date();
    let formattedSplitString = currentDate
        .toLocaleDateString("en-GB", {
            // you can use undefined as first argument
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
        })
        .split("/");

    let months = "";
    let years = "";
    let singletons = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    for (let month = 1; month <= 12; month++) {
        let selected = "";
        if (singletons.includes(month)) {
            month = "0" + month;
        }
        if (month === formattedSplitString["1"]) {
            selected = "selected";
        }
        months +=
            "<option value='" +
            month +
            "' " +
            selected +
            ">" +
            month +
            "</option>";
    }

    let currentYear = parseInt(formattedSplitString["2"]);
    for (let year = 1; year <= 31; year++) {
        let selected = "";
        if (year === 1) {
            selected = "selected";
        }
        years +=
            "<option value='" +
            currentYear +
            "' " +
            selected +
            ">" +
            currentYear +
            "</option>";

        currentYear++;
    }

    $("#card_exp_month").html(months);
    $("#card_exp_year").html(years);
}

function toggleSlugModal(url) {
    $(".custom-loader").removeClass("d-none");
    $.ajax({
        type: "GET",
        url: url,
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                $("#slug-modal-container").html(res.data);
                $("#slug-modal").modal("show");
                $(".custom-loader").addClass("d-none");
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: common.js ~ line 598 ~ toggleSlugModal ~ err",
                err
            );
        },
    });
}

function handleSlugUpdate(dis, event) {
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
                $("#slug-modal").modal("hide");
                toastr.success(res.data, "");
            } else {
                let errors = "";
                $.each(res.error, function (indexInArray, valueOfElement) {
                    errors += valueOfElement + "\n";
                });
                toastr.error(errors, "");
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
