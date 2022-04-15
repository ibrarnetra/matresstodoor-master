let row = $(".row-sample");

$(document).on("click", ".del_button", function () {
    $(this).closest(".row-sample").remove();
});

let row_num = 0;
let fieldsArray = ["tax_rate_id", "based", "priority"];
function addNewRow() {
    let clone = row.clone();
    clone.removeClass("d-none");
    fieldsArray.forEach(setNameAttr);
    function setNameAttr(field, index, array) {
        clone
            .find("#" + field)
            .attr("name", "tax_rule" + "[" + row_num + "]" + "[" + field + "]")
            .removeAttr("id");
    }
    $("#item_container").append(clone);
    row_num++;
}
