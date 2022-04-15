function validateForm(dis) {
    let processing = true;
    let requiredFields = $(document)
        .find("input,textarea,select")
        .filter("[required]");

    $.each(requiredFields, function (idx, ele) {
        let parentDiv = $(ele).parent("div");
        let fieldObj = document.getElementById($(ele).attr("id"));
        let isValid = fieldObj.checkValidity(); // check fields for valid state by required attribute
        if (!isValid) {
            processing = false;
            $(ele).addClass("is-invalid");
            parentDiv
                .find(".invalid-feedback")
                .html(
                    "<strong>The " +
                        parentDiv.find("label").html() +
                        " field is required.</strong>"
                );
        } else {
            $(ele).removeClass("is-invalid");
            parentDiv.find(".invalid-feedback").html("");
        }
    });

    if (!processing) {
        $("#error-div").removeClass("d-none");
        $("#error-div")
            .find("#error-span")
            .html("Warning: Please check the form carefully for errors!");
    } else {
        $("#error-div").find("#error-span").html("");
    }

    return processing;
}
