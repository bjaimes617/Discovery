$("#fecha").daterangepicker({
    locale: {
        format: "DD/MM/YYYY",
    },
    buttonClasses: "btn",
    applyClass: "btn-primary",
    cancelClass: "btn-secondary",
});

$("#fechaasignados").daterangepicker({
    locale: {
        format: "DD/MM/YYYY",
    },
    buttonClasses: "btn",
    applyClass: "btn-primary",
    cancelClass: "btn-secondary",
});

$(document).ready(function () {
    // destroy datatable if exists
    if ($.fn.DataTable.isDataTable("#metricasusers")) {
        $("#metricasusers").DataTable().destroy();
    }

    $("#metricasusers").DataTable({
        responsive: true,
        autoWidth: false,
        processing: "<i class='fas fa-spinner fa-spin'></i> Cargando...",
        bPaginate: true,
        bProcessing: true,
        order: [[0, "asc"]],
    });

    var refreshInterval = null;
    $("#auto").change(function () {
        console.log("actualizando el tiempo de refresco automático");
        var auto = $(this).val();

        // 1. Limpiamos el intervalo anterior si existe
        if (refreshInterval !== null) {
            clearInterval(refreshInterval);
            refreshInterval = null;
        }

        // 2. Si es 0 (No Refresh), no hacemos nada más
        if (auto == 0) {
            return;
        }

        // 3. Llamamos a la función inmediatamente (opcional pero recomendado)
        UpdateDisplayAll();

        // 4. Creamos el nuevo intervalo
        refreshInterval = setInterval(function () {
            console.log("exe");
            UpdateDisplayAll();
        }, auto * 1000);
    });
});

function UpdateDisplayAll() {
    var data = {
        _token:
            $("[name=_token]").val() ||
            $('meta[name="csrf-token"]').attr("content"),
        fecha: $("#fecha").val(),
    };

    $.ajax({
        url: $("#url_data").val(),
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            if ($.fn.DataTable.isDataTable("#metricasusers")) {
                $("#metricasusers").DataTable().destroy();
            }

            // Update stats
            $("#stat-lead_asignados").text(response.lead_asignados);
            $("#stat-meta_venta").text(response.meta_venta);
            $("#stat-ventas_discovery").text(response.ventas_discovery);
            $("#stat-ventas_respondio").text(response.ventas_respondio);
            $("#stat-conversion_global").text(response.conversion_global + "%");
            $("#stat-ingresadas_intelix").text(response.ingresadas_intelix);
            $("#stat-fvc24").text(response.fvc24);
            $("#stat-fvc48").text(response.fvc48);
            $("#stat-perdida_contacto").text(response.perdida_contacto + "%");
            $("#stat-contactoAlead").text(response.contacto_a_lead + "%");
            $("#stat-no_cargado").text(response.no_cargado);

            $("#stat-conversacionXventa").text(
                response.conversacionXventa + "%",
            );

            // Update table
            var tbody = $("#table-usuarios-body");
            tbody.empty();
            var sumaleads = 0;
            var sumaventas = 0;

            $.each(response.usuarios, function (key, usuario) {
                sumaleads += parseInt(usuario.leads) || 0;
                sumaventas += parseInt(usuario.cargadas) || 0;

                var tr = $("<tr>");
                tr.append("<td>" + (usuario.supervisor || "-") + "</td>");
                tr.append("<td>" + usuario.nombre + "</td>");
                tr.append("<td>" + usuario.leads + "</td>");
                tr.append("<td>" + usuario.meta + "</td>");

                var cargadasHtml = "";
                if (
                    parseInt(usuario.cargadas) <
                    parseInt(usuario.venta_respondido)
                ) {
                    cargadasHtml =
                        '<span style="color: red; font-weight: bold;"><i class="fas fa-times-circle"></i><span> ';
                }
                cargadasHtml += usuario.cargadas;

                tr.append("<td>" + cargadasHtml + "</td>");
                tr.append("<td>" + usuario.venta_respondido + "</td>");
                tr.append("<td>" + usuario.conversion + "</td>");
                tbody.append(tr);
            });

            var tfoot = $("#table-usuarios-foot");
            tfoot.empty();
            var trFoot = $("<tr>");
            trFoot.append("<th></th>");
            trFoot.append("<th><b>Totales:</b></th>");
            trFoot.append("<th>" + sumaleads + "</th>");
            trFoot.append("<th>-</th>");
            trFoot.append("<th>" + sumaventas + "</th>");
            trFoot.append("<th>-</th>");
            trFoot.append("<th>-</th>");
            tfoot.append(trFoot);

            $("#metricasusers").DataTable({
                responsive: true,
                autoWidth: false,
                processing:
                    "<i class='fas fa-spinner fa-spin'></i> Cargando...",
                bPaginate: true,
                bProcessing: true,
                order: [[0, "asc"]],
            });
        },
        error: function (xhr) {
            console.error("Error updating dashboard data", xhr);
        },
    });
}

function asignadossinventas() {
    var data = {
        _token:
            $("[name=_token]").val() ||
            $('meta[name="csrf-token"]').attr("content"),
        fecha: $("#fechaasignados").val(),
    };

    $.ajax({
        url: $("#url_sinventas").val(),
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            $("#asignadossinventas").modal("show");
            if ($.fn.DataTable.isDataTable("#table-asignados")) {
                $("#table-asignados").DataTable().destroy();
            }

            var tbody = $("#table-asignados-body");
            tbody.empty();

            $.each(response, function (key, item) {
                var tr = $("<tr>");
                tr.append("<td>" + item.fecha + "</td>");
                tr.append("<td>" + item.hora + "</td>");
                tr.append("<td>" + item.idcontacto + "</td>");
                tr.append("<td>" + item.ciclo_de_vida + "</td>");
                tr.append("<td>" + item.numero_contacto + "</td>");
                tr.append("<td>" + item.agente + "</td>");
                tr.append("<td>" + item.supervisor + "</td>");
                tbody.append(tr);
            });

            $("#table-asignados").DataTable({
                responsive: true,
                autoWidth: false,
                processing:
                    "<i class='fas fa-spinner fa-spin'></i> Cargando...",
                bPaginate: true,
                bProcessing: true,
                order: [[0, "asc"]],
            });
        },
        error: function (response) {
            $("#asignadossinventas").modal("hide");
            toastr.error(response.responseJSON);
        },
    });
}
