function setMinPrice(dis) {
    let currentMinPrice = 0;
    let min = $(dis).val();
    if (min !== undefined || min !== NaN || min !== "") {
        currentMinPrice = min;
    }

    let currentMaxPrice = 2000;
    let max = $("#max").val();
    if (max !== undefined && max !== NaN && max !== "") {
        currentMaxPrice = max;
    }

    $("#price-filter").val(currentMinPrice + "-" + currentMaxPrice);
}

function setMaxPrice(dis) {
    let currentMinPrice = 0;
    let min = $("#min").val();
    if (min !== undefined && min !== NaN && min !== "") {
        currentMinPrice = min;
    }

    let currentMaxPrice = 2000;
    let max = $(dis).val();
    if (max !== undefined || max !== NaN || max !== "") {
        currentMaxPrice = max;
    }

    $("#price-filter").val(currentMinPrice + "-" + currentMaxPrice);
}

/**
 *
 * @param {*} baseUrl App base url
 * @param {*} q search q
 * @param {*} category category filter
 * @param {*} manufacturer brand filter
 * @param {*} variant option(variant) filer
 * @param {*} price_filter price filter
 * @param {*} order_by order by
 * @returns Complete Shop URL
 */
function shopUrl(
    baseUrl,
    q,
    category,
    manufacturer,
    variant,
    price_filter,
    order_by
) {
    /**
     * init BASE URL
     */
    let completeUrl = baseUrl + "?search=1";

    /**
     * check if search param exists
     */
    if (q !== null && q !== "" && q !== undefined) {
        completeUrl += "&q=" + q.trim();
    }

    /**
     * check if category filter exists
     */
    if (category !== null && category !== "" && category !== undefined) {
        completeUrl += "&category=" + category.trim();
    }

    /**
     * check if brand filter exists
     */
    if (
        manufacturer !== null &&
        manufacturer !== "" &&
        manufacturer !== undefined
    ) {
        completeUrl += "&manufacturer=" + manufacturer.trim();
    }

    /**
     * check if option(variant) filter exists
     */
    if (variant !== null && variant !== "" && variant !== undefined) {
        completeUrl += "&variant=" + variant.trim();
    }

    /**
     * check if price filter exists
     */
    if (
        price_filter !== null &&
        price_filter !== "" &&
        price_filter !== undefined
    ) {
        completeUrl += "&price-range=" + price_filter.trim();
    }

    /**
     * check if order by filter exists
     */
    if (order_by !== null && order_by !== "" && order_by !== undefined) {
        completeUrl += "&order-by=" + order_by.trim();
    }

    return completeUrl;
}
