$(document).ready(function () {
    $("#fecha")
        .daterangepicker({
            locale: {
                format: "DD/MM/YYYY",
            },
            buttonClasses: "btn",
            applyClass: "btn-primary",
            cancelClass: "btn-secondary",
        })
        .on("change", function (e) {
            UpdateDisplayAll();
        });

    $("#supervisores").on("change", function (e) {
        UpdateDisplayAll();
    });
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

    $("#table-asignados").DataTable({
        responsive: true,
        autoWidth: false,
        lengthMenu: [25, 50, 250, 500, 1000, -1],
        processing: "<i class='fas fa-spinner fa-spin'></i> Cargando...",
        bPaginate: true,
        bProcessing: true,
        order: [[0, "asc"]],
        dom: "Blfrtip",
        buttons: {
            buttons: ["copy", "csv", "excel"],
            dom: {
                button: {
                    className: "btn btn-primary",
                },
            },
        },
    });

    $("#table-notipificado").DataTable({
        responsive: true,
        autoWidth: false,
        processing: "<i class='fas fa-spinner fa-spin'></i> Cargando...",
        bPaginate: true,
        bProcessing: true,
        order: [[0, "asc"]],
        dom: "Blfrtip",
        buttons: {
            buttons: ["copy", "csv", "excel"],
            dom: {
                button: {
                    className: "btn btn-primary",
                },
            },
        },
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
        // UpdateDisplayAll();

        // 4. Creamos el nuevo intervalo
        refreshInterval = setInterval(function () {
            console.log("exe");
            UpdateDisplayAll();
        }, auto * 1000);
    });
});

function UpdateDisplayAll() {
    toastr.options.showDuration = "2000";
    toastr.info("Actualizando...");
    var data = {
        _token:
            $("[name=_token]").val() ||
            $('meta[name="csrf-token"]').attr("content"),
        fecha: $("#fecha").val(),
        supervisor: $("#supervisores").val(),
    };

    $.ajax({
        url: $("#url_data").val(),
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
            TableOperadores(response[0]);
            TableLeadAsignados(response[1]);
            TableNotipificado(response[2]);
        },
        error: function (xhr) {
            toastr.error("Error updating dashboard data");
        },
    });
}

function TableOperadores(response) {
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
    $("#stat-rechazadas").text(response.rechazadas);
    $("#stat-conversacionXventa").text(response.conversacionXventa + "%");

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
        if (parseInt(usuario.cargadas) < parseInt(usuario.venta_respondido)) {
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
        processing: "<i class='fas fa-spinner fa-spin'></i> Cargando...",
        bPaginate: true,
        bProcessing: true,
        order: [[0, "asc"]],
    });
}

function TableLeadAsignados(response) {
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
        lengthMenu: [25, 50, 250, 500, 1000, -1],
        processing: "<i class='fas fa-spinner fa-spin'></i> Cargando...",
        bPaginate: true,
        bProcessing: true,
        order: [[0, "asc"]],
        dom: "Blfrtip",
        buttons: {
            buttons: ["copy", "csv", "excel"],
            dom: {
                button: {
                    className: "btn btn-primary",
                },
            },
        },
    });
}

function TableNotipificado(response) {
    if ($.fn.DataTable.isDataTable("#table-notipificado")) {
        $("#table-notipificado").DataTable().destroy();
    }

    var tbody = $("#table-notipificado-body");
    tbody.empty();

    $.each(response, function (key, item) {
        var tr = $("<tr>");
        tr.append("<td>" + item.fecha + "</td>");
        tr.append("<td>" + item.hora + "</td>");
        tr.append("<td>" + item.idcontacto + "</td>");
        tr.append("<td>" + item.portabilidad + "</td>");
        tr.append("<td>" + item.ciclo_de_vida + "</td>");
        tr.append("<td>" + item.numero_contacto + "</td>");
        tr.append("<td>" + item.agente + "</td>");

        tbody.append(tr);
    });

    $("#table-notipificado").DataTable({
        responsive: true,
        autoWidth: false,
        processing: "<i class='fas fa-spinner fa-spin'></i> Cargando...",
        bPaginate: true,
        bProcessing: true,
        order: [[0, "asc"]],
        dom: "Blfrtip",
        buttons: {
            buttons: ["copy", "csv", "excel"],
            dom: {
                button: {
                    className: "btn btn-primary",
                },
            },
        },
    });
}
