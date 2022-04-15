let row = $(".row-sample");
let temp_index = 1;

$(document).ready(function () {
    initDatePicker($(".special-datepicker"));
    if ($("#method").val() == "create") {
        addNewRow(
            ["eng", "fr"],
            ["attribute_id", "attribute_text"],
            "attribute"
        );
    }
    initSelect2Ajax($("#categories"), $("#category_url").val());
    initSelect2Ajax($("#related"), $("#related_url").val());
    initCkEditor("product_description[en][description]");
    initCkEditor("product_description[en][short_description]");
});

$(document).on("click", ".del_button", function () {
    $(this).closest(".row").remove();
});

function getRandomInt(max) {
    return Math.floor(Math.random() * Math.floor(max));
}

function addNewRow(langArray, fieldsArray, description) {
    langArray.forEach(initLang);

    function initLang(lang, index, array) {
        let clone = row.clone();
        fieldsArray.forEach(setNameAttr);
        function setNameAttr(field, index, array) {
            if (field == "attribute_id") {
                clone
                    .find('select[name ="' + field + '"]')
                    .attr(
                        "name",
                        description +
                            "[" +
                            lang +
                            "]" +
                            "[" +
                            temp_index +
                            "]" +
                            "[" +
                            field +
                            "]"
                    );
            }
            if (field == "attribute_text") {
                clone
                    .find('textarea[name ="' + field + '"]')
                    .attr(
                        "name",
                        description +
                            "[" +
                            lang +
                            "]" +
                            "[" +
                            temp_index +
                            "]" +
                            "[" +
                            field +
                            "]"
                    );
            }
        }

        clone.removeClass("d-none");
        $("#item_container_" + lang + "").append(clone);
    }
    temp_index++;
}

function addNewSpecial(fieldsArray, description) {
    let clone = $(".row-special").clone();
    let rand = getRandomInt(1000);
    initDatePicker(clone.find(".special-datepicker"));

    fieldsArray.forEach(setNameAttr);

    function setNameAttr(field, index, array) {
        if (field == "customer_group_id") {
            clone
                .find('select[name ="' + field + '"]')
                .attr(
                    "name",
                    description + "[" + rand + "]" + "[" + field + "]"
                );
        } else {
            clone
                .find('input[name ="' + field + '"]')
                .attr(
                    "name",
                    description + "[" + rand + "]" + "[" + field + "]"
                );
        }
        clone.removeClass("d-none");
        clone.removeClass("row-special");
        $("#special-container").append(clone);
    }
}

function deleteImage(dis, url) {
    $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
            toastr.success("Deleted Successfully", "");
            $("a[data-id='" + $(dis).attr("data-id") + "']")
                .parent()
                .addClass("d-none");
        },
        error: function (jqXHR, exception) {
            console.log(jqXHR);
        },
    });
}

function delTab(dis, option_id = 0) {
    let parent = $(dis).closest(".nav-item");
    let tab_pane_id = "option-" + parent.attr("data-id");
    $('div[id="' + tab_pane_id + '"]').remove();
    parent.remove();
    if (option_id !== 0) {
        $("#imageLoading").removeClass("d-none");
        $.ajax({
            url:
                BASE_URL +
                "products/delete-product-option?option-id=" +
                option_id,
            type: "GET",
            dataType: "JSON",
            success: function (res) {
                if (res.status == true) {
                    toastr.success("Deleted Tab Successfully", "");
                    $("#imageLoading").addClass("d-none");
                } else {
                    toastr.error("Error", "");
                }
            },
            error: function (jqXHR, exception) {
                toastr.error("Error", "");
                console.log(jqXHR);
            },
        });
    }
}

$("#option_id").on("change", function () {
    let rand = getRandomInt(1000);
    $(".option-nav-link").removeClass("active");
    $(".option-tab-pane").removeClass("active");
    let selected_option_id = $("option:selected", this).val();
    let selected_val = $("option:selected", this).attr("data-type");
    let selected_text = $("option:selected", this).text();
    let nav_item =
        '<li class="nav-item" data-id="' +
        rand +
        "-" +
        selected_option_id +
        '">' +
        '<a class="nav-link option-nav-link active" ' +
        'data-bs-toggle="tab" ' +
        'href="#option-' +
        rand +
        "-" +
        selected_option_id +
        '" ' +
        'role="tab">' +
        "" +
        selected_text +
        "" +
        '<button class="ms-4 btn btn-sm btn-danger" style="padding: 2px; border-radius: 2px;" type="button" onclick="delTab(this)">' +
        '<i class="fas fa-minus-circle text-white" style="padding: 2px;"></i>' +
        "</button>" +
        "</a>" +
        "</li>";
    let tab_pane = "";
    let singletons = ["text", "number", "textarea"];
    if (singletons.includes(selected_val)) {
        tab_pane =
            '<div class="tab-pane option-tab-pane active" id="option-' +
            rand +
            "-" +
            selected_option_id +
            '" role="tabpanel">' +
            '<div class="row mt-8">' +
            '<div class="mb-5 col-md-6">' +
            '<label class="form-label required" for="option[' +
            rand +
            "-" +
            selected_option_id +
            '][required]">Required</label>' +
            '<input type="hidden" name="option[' +
            rand +
            "-" +
            selected_option_id +
            '][option_id]" value="' +
            selected_option_id +
            '">' +
            '<select class="form-select form-select-solid " name="option[' +
            rand +
            "-" +
            selected_option_id +
            '][required]">' +
            '<option value="1" selected>Yes</option>' +
            '<option value="0">No</option>' +
            "</select>" +
            "</div>" +
            '<div class="mb-5 col-md-6">' +
            '<label class="form-label required" for="option[' +
            rand +
            "-" +
            selected_option_id +
            '][value]">Option Value</label>' +
            '<input type="text" class="form-control form-control-solid" name="option[' +
            rand +
            "-" +
            selected_option_id +
            '][value]">' +
            "</div>" +
            "</div>" +
            "</div>";
    } else {
        tab_pane =
            '<div class="tab-pane option-tab-pane active" id="option-' +
            rand +
            "-" +
            selected_option_id +
            '" role="tabpanel">' +
            '<div class="row mt-8">' +
            '<div class="mb-5 col-md-3">' +
            '<label class="form-label required" for="option[' +
            rand +
            "-" +
            selected_option_id +
            '][required]">Required</label>' +
            '<input type="hidden" name="option[' +
            rand +
            "-" +
            selected_option_id +
            '][option_id]" value="' +
            selected_option_id +
            '">' +
            '<select class="form-select form-select-solid " name="option[' +
            rand +
            "-" +
            selected_option_id +
            '][required]" >' +
            '<option value="1" selected>Yes</option>' +
            '<option value="0">No</option>' +
            "</select>" +
            "</div>" +
            "</div>" +
            '<div id="container-' +
            rand +
            "-" +
            selected_option_id +
            '">' +
            '<div class="row">' +
            '<div class="col-md-2">' +
            '<label class="form-label required" for="option_value_id">Option Value </label>' +
            "</div>" +
            '<div class="col-md-2">' +
            '<label class="form-label required" for="quantity">Quantity</label>' +
            "</div>" +
            '<div class="col-md-2">' +
            '<label class="form-label required" for="subtract">Subtract Stock </label>' +
            "</div>" +
            '<div class="col-md-2">' +
            '<label class="form-label required">Price </label>' +
            "</div>" +
            '<div class="col-md-2">' +
            '<label class="form-label required">Weight ' +
            "</label>" +
            "</div>" +
            "</div>" +
            "</div>" +
            '<div class="row">' +
            '<div class="offset-md-11 col-md-1 text-left">' +
            '<div class="controls">' +
            '<button class="btn btn-primary btn-sm" type="button" data-id="' +
            rand +
            "-" +
            selected_option_id +
            '" onclick="getOptionValues(this)">' +
            '<i class="fas fa-plus-circle"></i></button>' +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";
    }
    $("#option-nav-tabs").append(nav_item);
    $("#option-tab-content").append(tab_pane);
});

function getOptionValues(dis) {
    let option_id = $(dis).attr("data-id");
    $("#imageLoading").removeClass("d-none");
    $.ajax({
        url: BASE_URL + "products/load-option-values?option-id=" + option_id,
        type: "GET",
        dataType: "JSON",
        success: function (res) {
            $("#container-" + option_id).append(res.data);
            $("#imageLoading").addClass("d-none");
        },
        error: function (jqXHR, exception) {
            console.log(jqXHR);
        },
    });
    // alert(option_id);
}
