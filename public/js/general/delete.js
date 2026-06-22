$(function () {
    $(".table").on("click", ".deleterow", function (event) {
        event.preventDefault();
        $("#deletetitlerecord").empty();
        $("#deletetitlerecord").append($(this).data("title"));
        $("#confirm").modal("show");
        var route = $(this).data("url");
        var token = $("#token").val();
        var rowToDelete = $(this).closest("tr");
        $("#delete")
            .off("click")
            .on("click", function (event) {
                event.preventDefault();
                $.ajax({
                    url: route,
                    headers: { "X-CSRF-TOKEN": token },
                    type: "DELETE",
                    dataType: "json",
                    success: function (msg) {
                        $("#confirm").modal("hide");
                        if (msg.type === "success") {
                            toastr.success(msg.message);
                        } else {
                            toastr.error(msg.message);
                        }
                        rowToDelete.hide();
                        /* var oTable = $(".table").dataTable({
                            bRetrieve: true,
                            sPaginationType: "full_numbers",
                        });*/
                    },
                });
            });
    });
});
