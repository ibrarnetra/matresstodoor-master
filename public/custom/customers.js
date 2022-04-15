let counter = 1;

function newTab(dis, url) {
    $(dis)
        .closest(".nav-tabs")
        .append(
            '<li class="nav-item">' +
                '<a class="nav-link" data-bs-toggle="tab" href="#address-' +
                counter +
                '">' +
                '<button type="button" class="btn btn-sm btn-danger" onclick="deleteTab(this)" data-id="address-' +
                counter +
                '" style="padding: 2px; border-radius: 2px;"><i class="fas fa-minus-circle"  style="padding: 2px;"></i></button> Address-' +
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

            initAutocompleteFields(
                document.getElementById("address_1_" + (counter - 1)),
                document.getElementById("city_" + (counter - 1)),
                document.getElementById("postcode_" + (counter - 1)),
                document.getElementById("country_id_" + (counter - 1)),
                document.getElementById("zone_id_" + (counter - 1)),
                document.getElementById("lat_" + (counter - 1)),
                document.getElementById("lng_" + (counter - 1))
            );

            $(document).find(".country").select2();
            $(document).find(".zone").select2();
            setDefaultMobileFormat($("#telephone_" + (counter - 1)));
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
