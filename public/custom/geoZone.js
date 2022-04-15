let row = $(".row-sample");

$(document).on("click", ".del_button", function () {
    $(this).closest(".row").remove();
});

let row_num = 0;
let fieldsArray = ["country", "zone"];
function addNewRow() {
    let clone = row.clone();
    clone.removeClass("d-none");
    fieldsArray.forEach(setNameAttr);

    function setNameAttr(field, index, array) {
        clone
            .find("#" + field)
            .attr("name", "geozone" + "[" + row_num + "]" + "[" + field + "]")
            .removeAttr("id");
        clone.find(".select2-country").select2();
        clone.find(".select2-zone").select2();
    }
    $("#item_container").append(clone);
    row_num++;
}
