let counter = 1;

function newTab(dis, url) {
    $(dis)
        .closest(".nav-tabs")
        .append(
            '<li class="nav-item custom-nav-item">' +
                '<a class="nav-link custom-nav-link" data-toggle="tab" href="#address-' +
                counter +
                '">' +
                '<button type="button" class="btn btn-sm btn-danger custom-nav-btn" onclick="deleteTab(this)" data-id="address-' +
                counter +
                '"><i class="fas fa-minus-circle"  style="padding: 2px;"></i></button> Address-' +
                counter +
                "" +
                "</a>" +
                "</li>"
        );
    $.ajax({
        type: "GET",
        url: url,
        data: {
            counter: counter,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                $(".address-content").append(res.data);
            }
            $(document).find(".country").select2();
            $(document).find(".zone").select2();
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: customers.js ~ line 28 ~ newTab ~ err",
                err
            );
        },
    });

    counter++;
}

function deleteTab(dis) {
    let id = $(dis).attr("data-id");
    $("#" + id).remove();
    $(dis).closest(".nav-item").remove();
}

function subUnsubToNewsletter(dis, url) {
    let isSubbed = $(dis).is(":checked") ? "1" : "0";
    $.ajax({
        type: "POST",
        url: url,
        data: {
            _token: CSRF_TOKEN,
            is_subbed: isSubbed,
        },
        dataType: "JSON",
        success: function (res) {
            if (res.status) {
                initSuccessSweetAlert(res.data);
            } else {
                initErrorSweetAlert(res.data);
            }
        },
        error: function (err) {
            console.log(
                "🚀 ~ file: auth.js ~ line 65 ~ subUnsubToNewsletter ~ err",
                err
            );
        },
    });
}
