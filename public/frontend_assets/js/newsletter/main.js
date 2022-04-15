function initNewsletter(dis, url) {
    let email = $(dis).closest(".input-group").find(".form-control").val();

    if (/(.+)@(.+){2,}\.(.+){2,}/.test(email)) {
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: CSRF_TOKEN,
                email: email,
            },
            dataType: "JSON",
            success: function (res) {
                if (res.status) {
                    initSuccessSweetAlert(
                        "Successfully subscribed to newsletter."
                    );
                } else {
                    initErrorSweetAlert(
                        "You have already subscribed to newsletter."
                    );
                }
            },
            error: function (err) {
                console.log(
                    "🚀 ~ file: main.js ~ line 22 ~ initNewsletter ~ err",
                    err
                );
            },
        });
    }

    $(dis).closest(".input-group").find(".form-control").val("");
}
