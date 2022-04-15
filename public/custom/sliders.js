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

function addNewRow(fieldsArray) {
    let clone = row.clone();
    fieldsArray.forEach((field, index, array) => {
        clone
            .find('input[name="' + field + '"]')
            .attr("name", "slides[" + temp_index + "]" + "[" + field + "]");
    });
    clone.removeClass("d-none");
    clone.removeClass("row-sample");
    $("#item_container").append(clone);

    temp_index++;
}
