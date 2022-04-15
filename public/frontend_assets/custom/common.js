$(document).ready(function () {
    $(document).find(".country").select2();
    $(document).find(".zone").select2();
    getMiniCart($("#mini-cart-url").val());

    $(window).on("keyup keypress", function (e) {
        let keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
});

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

function addToCart(url, dis) {
    let qty = 0;
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: CSRF_TOKEN,
            qty: qty,
        },
        dataType: "JSON",
        success: function (res) {
            makeMiniCart(res);
            if ($("#quick-view")) {
                $("#quick-view").modal("hide");
            }
            initSuccessSweetAlert("Successfully added item to cart.");
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: common.js ~ line 46 ~ addToCart ~ err",
                err
            );
        },
    });
}

function getMiniCart(url) {
    $.ajax({
        type: "GET",
        url: url,
        dataType: "JSON",
        success: function (res) {
            makeMiniCart(res);
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: common.js ~ line 46 ~ addToCart ~ err",
                err
            );
        },
    });
}

function showAddToCartSuccess(res) {
    if (res.status) {
        $("#productTitle").html(res.product.name);
        $("#productImage").attr(
            "src",
            BASE_URL + "/storage/product_images/thumbnail/" + res.product.image
        );
        $("#productImage").attr("alt", res.product.image);
        $("#addCartModal").modal("show");
    }
}

function makeMiniCart(res) {
    if (res.count > 0) {
        $(".cart-count").removeClass("d-none");
        $(".cart-count").html(res.count);
    } else {
        $(".cart-count").addClass("d-none");
    }
    $("#mini-cart").html(res.data);
}

function remove(dis, url, dynamicIdentifier) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: CSRF_TOKEN,
        },
        dataType: "JSON",
        success: function (res) {
            // REMOVE `tr`
            $("tr[data-id='" + dynamicIdentifier + "']").each(function (
                idx,
                ele
            ) {
                ele.remove();
            });
            // SET ORDER TOTAL
            $("#order-total").html(
                "$" + setDefaultPriceFormat(res.order_total)
            );
            // REGENERATE MINI CART
            makeMiniCart(res);
            // SWEET ALERT
            initSuccessSweetAlert("Successfully removed item from cart.");
            if (res.count === 0) {
                window.location = BASE_URL + "/shop";
            }
        },
        error: function (err) {
            console.log("🚀 ~ file: common.js ~ line 90 ~ remove ~ err", err);
        },
    });
}

function quickView(dis, url) {
    $.ajax({
        type: "GET",
        url: url,
        dataType: "JSON",
        success: function (res) {
            $(".ajax-quick-view").html(res.data);
            initCarousel();
            $("#quick-view").modal("show");
        },
        error: function (err) {
            console.log("🚀 ~ file: common.js ~ line 90 ~ remove ~ err", err);
        },
    });
}

function initCarousel() {
    var sliderDefaultOptions = {
        loop: true,
        margin: 0,
        responsiveClass: true,
        nav: false,
        navText: [
            '<i class="icon-left-open-big">',
            '<i class="icon-right-open-big">',
        ],
        dots: true,
        autoplay: true,
        autoplayTimeout: 15000,
        items: 1,
    };

    // Init all carousel
    $('[data-toggle="owl"]').each(function () {
        var pluginOptions = $(this).data("owl-options");

        if (typeof pluginOptions == "string") {
            pluginOptions = JSON.parse(
                pluginOptions.replace(/'/g, '"').replace(";", "")
            );
        }

        var newOwlSettings = $.extend(
            true,
            {},
            sliderDefaultOptions,
            pluginOptions
        );

        var owlIns = $(this).owlCarousel(newOwlSettings);
    });

    $(".product-single-default .product-single-carousel").owlCarousel(
        $.extend(true, {}, sliderDefaultOptions, {
            nav: true,
            navText: [
                '<i class="icon-angle-left">',
                '<i class="icon-angle-right">',
            ],
            dotsContainer: "#carousel-custom-dots",
            autoplay: false,
            onInitialized: function () {
                var $source = this.$element;

                if ($.fn.elevateZoom) {
                    $source.find("img").each(function () {
                        var $this = $(this),
                            zoomConfig = {
                                responsive: true,
                                zoomWindowFadeIn: 350,
                                zoomWindowFadeOut: 200,
                                borderSize: 0,
                                zoomContainer: $this.parent(),
                                zoomType: "inner",
                                cursor: "grab",
                            };
                        $this.elevateZoom(zoomConfig);
                    });
                }
            },
        })
    );

    $(".product-single-extended .product-single-carousel").owlCarousel(
        $.extend(true, {}, sliderDefaultOptions, {
            dots: false,
            autoplay: false,
            responsive: {
                0: {
                    items: 1,
                },
                480: {
                    items: 2,
                },
                1200: {
                    items: 3,
                },
            },
        })
    );

    $("#carousel-custom-dots .owl-dot").click(function () {
        $(".product-single-carousel").trigger("to.owl.carousel", [
            $(this).index(),
            300,
        ]);
    });

    // Gallery Lightbox
    var links = [];
    var $productSliderImages =
        $(".product-single-carousel .owl-item:not(.cloned) img").length === 0
            ? $(".product-single-gallery img")
            : $(".product-single-carousel .owl-item:not(.cloned) img");
    $productSliderImages.each(function () {
        links.push({ src: $(this).attr("data-zoom-image") });
    });

    $(".prod-full-screen").click(function (e) {
        var currentIndex;
        if (e.currentTarget.closest(".product-slider-container")) {
            currentIndex =
                ($(".product-single-carousel").data("owl.carousel").current() +
                    $productSliderImages.length -
                    Math.ceil($productSliderImages.length / 2)) %
                $productSliderImages.length;
        } else {
            currentIndex = $(e.currentTarget).closest(".product-item").index();
        }

        $.magnificPopup.open(
            {
                items: links,
                navigateByImgClick: true,
                type: "image",
                gallery: {
                    enabled: true,
                },
            },
            currentIndex
        );
    });
}

function updateCart(dis, url, dynamicIdentifier) {
    let processing = true;
    let qty = parseInt($("#product-quantity-" + dynamicIdentifier).val());
    if (qty === undefined || qty === "" || qty === NaN || qty < 1) {
        processing = false;
        alert("Please select a valid quantity before updating!");
    }
    if (processing) {
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: CSRF_TOKEN,
                qty: qty,
            },
            dataType: "JSON",
            success: function (res) {
                // RECALCULATE SUB TOTAL
                $("#product-sub-total-" + dynamicIdentifier).html(
                    setDefaultPriceFormat(
                        parseFloat(
                            $("#product-price-" + dynamicIdentifier).html()
                        ) * qty
                    )
                );
                // SET ORDER TOTAL
                $("#order-total").html(
                    "$" + setDefaultPriceFormat(res.order_total)
                );
                // REGENERATE MINI CART
                getMiniCart($("#mini-cart-url").val());
                // SWEET ALERT
                initSuccessSweetAlert(res.data);
            },
            error: function (err) {
                console.log(
                    "🚀 ~ file: common.js ~ line 282 ~ updateCart ~ err",
                    err
                );
            },
        });
    }
}

function initSuccessSweetAlert(msg) {
    Swal.fire({
        position: "top-end",
        icon: "success",
        title: msg,
        showConfirmButton: false,
        timer: 1500,
    });
}

function initErrorSweetAlert(msg) {
    Swal.fire({
        position: "top-end",
        icon: "error",
        title: msg,
        showConfirmButton: false,
        timer: 1500,
    });
}

function addToWishlist(dis, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: CSRF_TOKEN,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                initSuccessSweetAlert(res.data);
            } else {
                initErrorSweetAlert(
                    "Could not add item to wishlist, please try again later."
                );
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: common.js ~ line 337 ~ addToWishlist ~ err",
                err
            );
        },
    });
}

function removeFromWishlist(dis, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: CSRF_TOKEN,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                if (dis !== "-1") {
                    $(dis).closest("tr").remove();
                }
                initSuccessSweetAlert(res.data);
            } else {
                initErrorSweetAlert(
                    "Could not remove item from wishlist, please try again later."
                );
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: common.js ~ line 337 ~ addToWishlist ~ err",
                err
            );
        },
    });
}

function ajaxFormSubmit(dis, e) {
    let url = $(dis).attr("action");
    let dataString = $(dis).serialize();
    $.ajax({
        type: "POST",
        url: url,
        data: dataString,
        dataType: "JSON",
        success: function (res) {
            makeMiniCart(res);
            if ($("#quick-view")) {
                $("#quick-view").modal("hide");
            }
            initSuccessSweetAlert("Successfully added item to cart.");
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: common.js ~ line 46 ~ addToCart ~ err",
                err
            );
        },
    });
}

function initCustomDatePicker(ele, configOptions) {
    ele.daterangepicker(configOptions);
}

function setDefaultPriceFormat(val) {
    return Math.round(val).toFixed(2);
}

// mobile hyphens enforcement
$("input[type='tel']").keyup(function () {
    $(this).val(
        $(this)
            .val()
            .replace(/(\d{3})\-?(\d{3})\-?(\d{4})/, "$1-$2-$3")
    );
});

/*
 * Hacky fix for a bug in select2 with jQuery 3.6.0's new nested-focus "protection"
 * see: https://github.com/select2/select2/issues/5993
 * see: https://github.com/jquery/jquery/issues/4382
 *
 * TODO: Recheck with the select2 GH issue and remove once this is fixed on their side
 */

$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});

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
