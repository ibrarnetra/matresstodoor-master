let row = $(".row-sample");

$(document).on("click", ".del_button", function () {
    if ($("#type").val() === "edit") {
        $.ajax({
            type: "GET",
            url: $(this).attr("data-url"),
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    toastr.success(res.data, "");
                } else {
                    toastr.error(res.data, "");
                }
            },
            error: function (err) {
                console.log("🚀 ~ file: options.js ~ line 16 ~ err", err);
            },
        });
    }
    $(this).closest(".row").remove();
});

function addNewRow(langArray, fieldsArray, description) {
    langArray.forEach(initLang);

    function initLang(lang, index, array) {
        let clone = row.clone();
        fieldsArray.forEach(setNameAttr);
        function setNameAttr(field, index, array) {
            clone
                .find('input[name="' + field + '"]')
                .attr(
                    "name",
                    description +
                        "[" +
                        lang +
                        "]" +
                        "[data]" +
                        "[" +
                        temp_index +
                        "]" +
                        "[" +
                        field +
                        "]"
                );
        }

        clone.removeClass("d-none");
        clone.removeClass("row-sample");
        $("#item_container_" + lang + "").append(clone);
    }
    temp_index++;
}

$("#option_type").on("change", function () {
    let selected_val = $("option:selected", this).val();
    console.log("🚀 ~ file: options.js ~ line 38 ~ selected_val", selected_val);
    let singletons = ["text", "textarea", "file", "date", "time", "datetime"];
    if (singletons.includes(selected_val)) {
        $(".multiple").addClass("d-none");
    } else {
        $(".multiple").removeClass("d-none");
    }
});
