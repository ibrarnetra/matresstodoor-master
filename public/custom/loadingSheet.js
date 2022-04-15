let currency_symbol = "$";

$(document).ready(function () {
    initSelect2Ajax(
        $(".product"),
        $("#product_search").val(),
        $(document).find("#add-item .modal-body")
    );
});

$(document).on("select2:select", ".product", function () {
    getProductOptions($(this));
});

function getProductOptions(dis) {
    let selected = $("option:selected", dis).val();

    $(".custom-loader").removeClass("d-none");
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
            $("#product-options")
                .find("[form='add-to-cart']")
                .removeAttr("form");
            $(".custom-loader").addClass("d-none");
        },
        error: function (err) {
            console.log(err);
        },
    });
}

function addItem(dis, event) {
    if (validateCheckbox()) {
        let dataString = $(dis).serialize();
        let url = $(dis).attr("action");
        $(".custom-loader").removeClass("d-none");
        $.ajax({
            type: "POST",
            url: url,
            data: dataString,
            dataType: "JSON",
            success: function (res) {
                console.log(
                    "🚀 ~ file: loadingSheet.js ~ line 52 ~ addItem ~ res",
                    res
                );
                if (res.status) {
                    $(".custom-loader").addClass("d-none");
                    $("#add-item").modal("toggle");
                    toastr.success(res.data, "");
                    location.reload();
                } else {
                    let errors = "";
                    $.each(res.error, function (indexInArray, valueOfElement) {
                        errors += valueOfElement + "\n";
                    });
                    alert(errors);
                }
            },
            error: function (err) {
                location.reload();
                console.log(
                    "🚀 ~ file: loadingSheet.js ~ line 68 ~ addItem ~ err",
                    err
                );
            },
        });
    }
}

function validateCheckbox() {
    let processing = true;
    $(document)
        .find("#product-options")
        .find("div.checkbox-group")
        .each(function (idx, ele) {
            let isRequired = $(ele)
                .parent()
                .find(".form-label")
                .hasClass("required")
                ? true
                : false;

            if (isRequired) {
                if ($(":checkbox:checked", ele).length === 0) {
                    processing = false;
                    alert(
                        "The " +
                            $(ele).parent().find(".form-label").html() +
                            " field is required!"
                    );
                    return false; // breaks
                }
            }
        });
    return processing;
}
