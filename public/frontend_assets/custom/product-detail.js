$(document).ready(function () {
    handlePriceVariants();
});

$("select[data-id='select']").on("change", function () {
    handlePriceVariants();
});

$("input[data-id='radio']").on("click", function () {
    handlePriceVariants();
});

$("input[data-id='checkbox']").on("click", function () {
    handlePriceVariants();
});

function handlePriceVariants() {
    let basePrice = parseFloat($("#base-price").val());

    $("input[data-id='radio']:checked").each(function () {
        let val = parseFloat($(this).attr("data-price"));
        let prefix = $(this).attr("data-prefix");

        if (prefix === "-") {
            basePrice -= val;
        } else {
            basePrice += val;
        }
    });

    $("input[data-id='checkbox']:checked").each(function () {
        let val = parseFloat($(this).attr("data-price"));
        let prefix = $(this).attr("data-prefix");

        if (prefix === "-") {
            basePrice -= val;
        } else {
            basePrice += val;
        }
    });

    $(document)
        .find("select[data-id='select']")
        .each(function (index, element) {
            let val = parseFloat(
                $("option:selected", element).attr("data-price")
            );
            let prefix = $("option:selected", element).attr("data-prefix");

            if (prefix === "-") {
                basePrice -= val;
            } else {
                basePrice += val;
            }
        });

    $(document)
        .find(".product-price")
        .html("$" + setDefaultPriceFormat(basePrice));
}
