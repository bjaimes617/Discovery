$.validator.addMethod(
    "validateExtention",
    function (value, element) {
        $(element).closest(".form-group").addClass("is-invalid");
        var ext = value.split(".").pop();
        if (!["xls", "xlsx"].includes(ext)) {
            return false;
        } else {
            return true;
        }
    },
    "No se permite archivos con esta Extensión, Use Solo  XLSX ",
);

var KTFormControls = (function () {
    var registroVenta = function () {
        $("#masivaAdd").validate({
            // define validation rules
            rules: {
                numero_portabilidad: {
                    required: true,
                    remote: {
                        url: $('input[name="numero_portabilidad"]').data(
                            "check",
                        ),
                        type: "POST",
                        data: {
                            _token: function () {
                                return $('input[name="_token"]').val();
                            },
                            numero_portabilidad: function () {
                                return $(
                                    'input[name="numero_portabilidad"]',
                                ).val();
                            },
                            idventa: function () {
                                return $(
                                    'input[name="numero_portabilidad"]',
                                ).data("idventa");
                            },
                        },
                        dataFilter: function (data) {
                            return data;
                        },
                    },
                },
            },           
            errorElement: "span",
            errorClass: "text-danger",
            //display error alert on form submit
            invalidHandler: function (event, validator) {
                var alert = $("#kt_form_1_msg");
                alert.removeClass("kt--hide").show();
                KTUtil.scrollTop();
            },
            errorPlacement: function (error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    // Para Select2, insertar después del contenedor select2
                    error.insertAfter(element.next(".select2-container"));
                } else if (element.parent().hasClass("input-group")) {
                    // Para inputs dentro de input-group, insertar después del grupo completo
                    error.insertAfter(element.parent());
                } else {
                    // Para otros inputs, insertar después del input
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element)
                        .next(".select2-container")
                        .find(".select2-selection")
                        .addClass("is-invalid text-center");
                } else {
                    $(element).addClass("is-invalid text-center");
                }
            },

            unhighlight: function (element) {
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element)
                        .next(".select2-container")
                        .find(".select2-selection")
                        .removeClass("is-invalid text-center");
                } else {
                    $(element).removeClass("is-invalid text-center");
                }
            },
            submitHandler: function (form) {
                if (
                    form.autorizado.value == true &&
                    form.agente_venta.value != form.operador.value
                ) {
                    Swal.fire({
                        title: "¡ATENCION!",
                        html:
                            "Esta a punto de reasignar esta venta a otro Agente, Tenga en cuenta, El registro sera relacionado a: <b>" +
                            $("#operador option:selected").text() +
                            "</b> y a su Supervisor Asignado. ¿Desea continuar?",
                        showCancelButton: true,
                        confirmButtonText: "Procesar",
                        cancelButtonText: "Cancelar",
                    }).then((result) => {
                        $("button[type='submit']").prop("disabled", true);
                        if (result.value === true) {
                            var inputs = form.querySelectorAll("input, select");
                            for (var i = 0; i < inputs.length; i++) {
                                // Verificar si el input está deshabilitado
                                if (inputs[i].disabled) {
                                    // Cambiar a readonly
                                    inputs[i].disabled = false; // Primero habilitar el input
                                    inputs[i].readOnly = true; // Luego establecerlo como readonly
                                }
                            }
                                                        
                            form.submit();
                        }
                    });
                } else {
                    $("button[type='submit']").prop("disabled", true);
                    var inputs = form.querySelectorAll("input, select");
                    for (var i = 0; i < inputs.length; i++) {
                        // Verificar si el input está deshabilitado
                        if (inputs[i].disabled) {
                            // Cambiar a readonly
                            inputs[i].disabled = false; // Primero habilitar el input
                            inputs[i].readOnly = true; // Luego establecerlo como readonly
                        }
                    }

                    form.submit();
                }
            },
        });
    };

    var postventaupdate = function () {
        $("#postventaupdate").validate({
            // define validation rules
            rules: {},
            messages: {},
            errorElement: "span",
            errorClass: "text-danger",
            //display error alert on form submit
            invalidHandler: function (event, validator) {
                var alert = $("#kt_form_1_msg");
                alert.removeClass("kt--hide").show();
                KTUtil.scrollTop();
            },
            errorPlacement: function (error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    // Para Select2, insertar después del contenedor select2
                    error.insertAfter(element.next(".select2-container"));
                } else if (element.parent().hasClass("input-group")) {
                    // Para inputs dentro de input-group, insertar después del grupo completo
                    error.insertAfter(element.parent());
                } else {
                    // Para otros inputs, insertar después del input
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element)
                        .next(".select2-container")
                        .find(".select2-selection")
                        .addClass("is-invalid text-center");
                } else {
                    $(element).addClass("is-invalid text-center");
                }
            },

            unhighlight: function (element) {
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element)
                        .next(".select2-container")
                        .find(".select2-selection")
                        .removeClass("is-invalid text-center");
                } else {
                    $(element).removeClass("is-invalid text-center");
                }
            },
            submitHandler: function (form) {
                $("button[type='submit']").prop("disabled", true);
                var inputs = form.querySelectorAll("input, select");
                for (var i = 0; i < inputs.length; i++) {
                    // Verificar si el input está deshabilitado
                    if (inputs[i].disabled) {
                        // Cambiar a readonly
                        inputs[i].disabled = false; // Primero habilitar el input
                        inputs[i].readOnly = true; // Luego establecerlo como readonly
                    }
                }
                form.submit();
            },
        });
    };
    return {
        // public functions
        init: function () {
            registroVenta();
        },
        postventa: function () {
            postventaupdate();
        },
    };
})();

jQuery(document).ready(function () {
    if ($("#masivaAdd").length > 0) {
        KTFormControls.init();
    }

    $(document).on("input", ".sinCaracteres", function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z\s]/g, "");
    });

    $(document).on("input", ".input-number", function () {
        this.value = this.value.replace(/[^0-9]/g, "");
    });

    if ($(".select").length > 0) {
        $(".select").select2({
            theme: "bootstrap4",
            width: "100%",
            placeholder: {
                id: "-1", // the value of the option
                text: "Seleccione",
            },
        });
    }

    $(".datetimepickerHour").datetimepicker({
        format: "h:mm A",
        icons: {
            time: "fa fa-clock",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: "fa fa-chevron-left",
            next: "fa fa-chevron-right",
            today: "fa fa-calendar-check",
            clear: "fa fa-trash",
            close: "fa fa-times",
        },
    });

    $(".datepicker_single").datepicker({
        format: "dd/mm/yyyy",
    });

    $("#fecha").daterangepicker({
        locale: {
            format: "DD/MM/YYYY",
        },
        buttonClasses: "btn",
        applyClass: "btn-primary",
        cancelClass: "btn-secondary",
    });

    $("#HistoricoModalShow").on("shown.bs.modal", function () {
        if ($.fn.dataTable.isDataTable("#historico-venta-table")) {
            $("#historico-venta-table")
                .DataTable()
                .columns.adjust()
                .responsive.recalc();
        }
    });
});

$(document).on("change", "#estado", function () {
    var data = {
        _token: $('input[name="_token"]').val(),
        estado: $(this).val(),
    };
    var url = $(this).data("href");

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function (data) {
            $("#municipio").empty();
            $("#municipio").append(
                $("<option></option>").attr("value", "").text("[Seleccione]"),
            );
            for (i = 0; i < data.length; i++) {
                $("#municipio").append(
                    $("<option></option>")
                        .attr("value", data[i].id)
                        .text(data[i].municipio),
                );
            }
        },
        error: function (data) {
            toastr.error(data.responseJSON.message);
        },
    });
});

$(document).on("change", "#municipio", function () {
    var data = {
        _token: $('input[name="_token"]').val(),
        municipio: $(this).val(),
    };
    var url = $(this).data("href");

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function (data) {
            $("#tienda").empty();
            $("#tienda").append(
                $("<option></option>").attr("value", "").text("[Seleccione]"),
            );
            for (i = 0; i < data.length; i++) {
                var texto = data[i].id + " - " + data[i].unidad;
                $("#tienda").append(
                    $("<option></option>")
                        .attr("value", data[i].id)
                        .attr("data-direccion", data[i].direccion)
                        .text(texto),
                );
            }
        },
        error: function (data) {
            toastr.error(data.responseJSON.message);
        },
    });
});

$(document).on("change", "#tienda", function () {
    $("#direccion_tienda").empty();
    var direccion = $(this).find(":selected").data("direccion");
    console.log(direccion);
    $("#direccion_tienda").html(direccion);
});

//buscar registro de ventas claro / masivo /registradas
$("#baitSearch").validate({
    errorElement: "span",
    errorClass: "text-danger",
    errorPlacement: function (error, element) {
        error.addClass("is-invalid text-sm");
        element.closest(".form-group").append(error);
    },
    //display error alert on form submit
    invalidHandler: function (event, validator) {
        // var alert = $("#kt_form_1_msg");
        //  alert.removeClass("kt--hide").show();
        // KTUtil.scrollTop();
    },
    submitHandler: function (form) {
        event.preventDefault();

        $.ajax({
            type: form.method,
            url: form.action,
            data: $(form).serialize(),
            dataType: "json",
            success: function (data) {
                if ($.fn.dataTable.isDataTable("#datatable-ventas")) {
                    $("#ventas-result").fadeOut();
                    $("#datatable-ventas").DataTable().destroy();
                }

                $("#datatable-ventas").DataTable({
                    initComplete: function (settings, json) {
                        $("#ventas-result").fadeIn();
                    },
                    responsive: true,
                    ordering: false,
                    autoWidth: false,
                    deferRender: true,
                    data: data,
                    lengthMenu: [10, 20, 50, 75, 100],
                    bPaginate: true,
                    bProcessing: true,
                    columns: [
                        { mData: "creado" },
                        { mData: "hora" },
                        { mData: "fvc" },
                        { mData: "idcontacto" },
                        { mData: "identificador" },
                        { mData: "nombreapellido" },
                        { mData: "intelix" },
                        { mData: "ciclo_vida" },
                        { mData: "agente" },
                        { mData: "supervisor" },
                        { mData: "estatus" },
                        { mData: "acciones" },
                    ],
                    createdRow: function (row, data, dataIndex) {
                        $(row).attr("data-id", data.id); // suponiendo que 'id' es el campo único
                    },
                });
            },
            error: function (data) {
                $("#ventas-result").fadeOut();
                toastr.error(data.responseJSON.message);
            },
        });
    },
});
//destruir venta desde vista registradas
function DestroyVentas(id) {
    Swal.fire({
        title: "¡ATENCION!",
        html: "Esta a punto de eliminar el registro de Venta y todo lo relacionado con el, tenga en cuenta que esta acción no se puede deshacer, ¿Desea continuar?",
        showCancelButton: true,
        confirmButtonText: "Procesar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value === true) {
            $.ajax({
                type: "DELETE",
                url: $("#eliminar" + id).attr("action"),
                data: $("#eliminar" + id).serialize(),
                dataType: "json",
                success: function (data) {
                    toastr.success(data);
                    $('tr[data-id="' + id + '"]').hide();
                },
                error: function (data) {
                    toastr.error(data.responseJSON.message);
                },
            });
        }
    });
}

///search vista bakcoffice
$("#backofficeSearch").validate({
    errorElement: "span",
    errorClass: "text-danger",
    errorPlacement: function (error, element) {
        error.addClass("is-invalid text-sm");
        element.closest(".form-group").append(error);
    },
    //display error alert on form submit
    invalidHandler: function (event, validator) {
        // var alert = $("#kt_form_1_msg");
        //  alert.removeClass("kt--hide").show();
        // KTUtil.scrollTop();
    },
    submitHandler: function (form) {
        event.preventDefault();

        $.ajax({
            type: form.method,
            url: form.action,
            data: $(form).serialize(),
            dataType: "json",
            success: function (data) {
                if ($.fn.dataTable.isDataTable("#datatable-ventas")) {
                    $("#ventas-result").fadeOut();
                    $("#datatable-ventas").DataTable().destroy();
                }

                $("#datatable-ventas").DataTable({
                    initComplete: function (settings, json) {
                        $("#ventas-result").fadeIn();
                    },
                    responsive: true,
                    ordering: false,
                    autoWidth: false,
                    deferRender: true,
                    data: data,
                    lengthMenu: [10, 20, 50, 75, 100],
                    bPaginate: true,
                    bProcessing: true,
                    columns: [
                        { mData: "creado" },
                        { mData: "fvc" },
                        { mData: "identificador" },
                        { mData: "nombreapellido" },
                        { mData: "ciclo_vida" },
                        { mData: "agente" },
                        { mData: "supervisor" },
                        { mData: "estatus" },
                        { mData: "acciones" },
                    ],
                    createdRow: function (row, data, dataIndex) {
                        $(row).attr("data-id", data.id); // suponiendo que 'id' es el campo único
                    },
                });
            },
            error: function (data) {
                $("#ventas-result").fadeOut();
                toastr.error(data.responseJSON.message);
            },
        });
    },
});

///actualizar venta backoffice

$("button[type=submit]").on("click", function () {
    botonPresionado = $(this).val();
});

$("#auditoriaupdate").validate({
    errorElement: "span",
    errorClass: "text-danger",
    invalidHandler: function (event, validator) {
        var alert = $("#kt_form_1_msg");
        alert.removeClass("kt--hide").show();
        KTUtil.scrollTop();
    },
    errorPlacement: function (error, element) {
        if (element.hasClass("select2-hidden-accessible")) {
            // Para Select2, insertar después del contenedor select2
            error.insertAfter(element.next(".select2-container"));
        } else if (element.parent().hasClass("input-group")) {
            // Para inputs dentro de input-group, insertar después del grupo completo
            error.insertAfter(element.parent());
        } else {
            // Para otros inputs, insertar después del input
            error.insertAfter(element);
        }
    },
    highlight: function (element) {
        if ($(element).hasClass("select2-hidden-accessible")) {
            $(element)
                .next(".select2-container")
                .find(".select2-selection")
                .addClass("is-invalid");
        } else {
            $(element).addClass("is-invalid");
        }
    },
    unhighlight: function (element) {
        if ($(element).hasClass("select2-hidden-accessible")) {
            $(element)
                .next(".select2-container")
                .find(".select2-selection")
                .removeClass("is-invalid");
        } else {
            $(element).removeClass("is-invalid");
        }
    },
    submitHandler: function (form) {
        if (botonPresionado === "aprobada") {
            var camposaIntelix = [
                "id_venta",
                "numero_portabilidad",
                "nombre_apellido",
                "genero",
                "imei",
                "codigo_nip",
                "fecha_nacimiento",
                "tienda",
                "municipio",
                "direccion_tienda",
                "fecha_cita",
                "correo_electronico",
                "telefono_contacto",
                "fvc",
                "modalidad",
            ];
            var mensaje = "";
            mensaje =
                '<div class="row"><div class="col-md-12" style="padding: 0px;">';
            $(form).each(function () {
                for (let i = 0; i < camposaIntelix.length; i++) {
                    const element = camposaIntelix[i];
                    if (
                        $("#" + camposaIntelix[i]).val() == "" ||
                        camposaIntelix[i] == "municipio"
                    ) {
                        mensaje +=
                            camposaIntelix[i] +
                            " : <b>" +
                            $("#" + camposaIntelix[i]).text() +
                            "</b><br>";
                    } else {
                        mensaje +=
                            camposaIntelix[i] +
                            " : <b>" +
                            $("#" + camposaIntelix[i]).val() +
                            "</b><br>";
                    }
                }
            });
            mensaje +=
                "</div></div><br><p><b>¡ATENCIÓN!</b></p><p>Debe ingresar el numero de Folio de Intelix para continuar con el proceso de auditoria.</p>" +
                '<div class="form-group row"><label class="col-form-label col-lg-4 col-sm-12" for="folio_intelix">Folio Intelix <span style="color:red;">*</span></label><div class="col-lg-7 col-md-12 col-sm-12"><input type="text" name="folio_intelix" id="folio_intelix" class="form-control input-number" minlength="10" maxlength="20" required placeholder="Folio Intelix"></div></div>';
        } else {
            mensaje =
                "Está a punto de rechazar el registro, Indique las observaciones del rechazo:<br><br>" +
                `<textarea id="textoCopiar" style="width:100%; height:150px; margin-top:10px;"></textarea><div style="margin-top: 10px;"><label for="intelix">Intelix <span style="color:red;">*</span></label><select name="intelix" id="intelix" class="form-control" required>
                <option value="">Seleccione</option>
                ${intelix
                    .map(
                        (item) =>
                            `<option value="${item.descripcion}">${item.descripcion}</option>`,
                    )
                    .join("")}                
            </select>
            <br/>
            <input type="checkbox" id="checkboxContainer" />
            <label for="checkboxContainer">¿Rechazo Definitivo?</label>
        </div>`;
        }
        console.log(intelix[0].descripcion);
        Swal.fire({
            title: "¡ATENCIÓN!",
            html: mensaje,
            showCancelButton: true,
            confirmButtonText: "Procesar",
            cancelButtonText: "Cancelar",
            preConfirm: () => {
                if (botonPresionado === "aprobada") {
                    const folio =
                        document.getElementById("folio_intelix").value;
                    if (!folio) {
                        Swal.showValidationMessage(
                            "Folio Intelix es requerido",
                        );
                        return false;
                    }
                    if (folio.length < 10 || folio.length > 20) {
                        Swal.showValidationMessage(
                            "El Folio Intelix debe tener de 10 a 20 dígitos",
                        );
                        return false;
                    }
                    return folio;
                } else {
                    const observaciones =
                        document.getElementById("textoCopiar").value;
                    if (!observaciones) {
                        Swal.showValidationMessage(
                            "Las observaciones son requeridas",
                        );
                        return false;
                    }
                    const intelix = document.getElementById("intelix").value;
                    if (!intelix) {
                        Swal.showValidationMessage(
                            "El estatus de Intelix es requerido",
                        );
                        return false;
                    }
                    const checkbox =
                        document.getElementById("checkboxContainer");
                    return {
                        observaciones,
                        intelix,
                        check_rechazo: checkbox.checked ? 1 : 0,
                    };
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                if (botonPresionado === "aprobada") {
                    $("#folio").val(result.value);
                } else {
                    $("#observacionesAudit").val(result.value.observaciones);
                    $("#estatus_intelix").val(result.value.intelix);
                    const checkbox =
                        document.getElementById("checkboxContainer");
                    $("#check_rechazo").val(checkbox.checked ? 1 : 0);
                }
                $("#auditoria").val(botonPresionado); // valor del boton presionado
                form.submit();
            }
        });
    },
});

function CopyText(identificador) {
    //Seleccionamos el data del input con el texto generado
    var textoRaw = $("#button" + identificador).data("text");
    var estatus = $("#button" + identificador).data("estatus");

    if (estatus === 2) {
        var title = "Texto Generado De la Venta";
        var content = `
            <textarea id="textoCopiar" readonly style="width:100%; height:200px; white-space: pre-wrap; margin-bottom: 10px;"></textarea>
            <button id="btnCopiar" class="swal2-confirm swal2-styled" style="margin-right: 10px;">Copiar texto</button>
            <button id="btnCerrar" class="swal2-cancel swal2-styled">Cerrar</button>
        `;
    } else {
        var title = "Observaciones De la Venta";
        var content = `<textarea id="textoCopiar" disabled style="width:100%; height:200px; white-space: pre-wrap; margin-bottom: 10px;"></textarea>`;
    }

    Swal.fire({
        title: title,
        html: content,
        showConfirmButton: false,
        showCancelButton: estatus === 2 ? false : true,
        didOpen: () => {
            const textarea = Swal.getPopup().querySelector("#textoCopiar");
            const btnCopiar = Swal.getPopup().querySelector("#btnCopiar");
            const btnCerrar = Swal.getPopup().querySelector("#btnCerrar");

            // Asigna el texto al textarea (reemplaza secuencias \n por saltos reales)
            let textoRaw = $("#button" + identificador).data("text");
            textoRaw = textoRaw.replace(/\\n/g, "\n");
            textoRaw = textoRaw.replace(/\\u([\dA-F]{4})/gi, (match, grp) =>
                String.fromCharCode(parseInt(grp, 16)),
            );
            textarea.value = textoRaw;

            // Copiar al portapapeles
            if (btnCopiar) {
                btnCopiar.addEventListener("click", async () => {
                    const textarea =
                        Swal.getPopup().querySelector("#textoCopiar");
                    textarea.select();
                    try {
                        const successful = document.execCommand("copy");
                        if (successful) {
                            btnCopiar.textContent = "¡Copiado!";
                            setTimeout(() => {
                                btnCopiar.textContent = "Copiar texto";
                            }, 2000);
                        } else {
                            alert(
                                "No se pudo copiar el texto automáticamente. Por favor, copie manualmente.",
                            );
                        }
                    } catch (err) {
                        console.error("Error al copiar al portapapeles:", err);
                        alert(
                            "No se pudo copiar el texto automáticamente. Por favor, copie manualmente.",
                        );
                    }
                });

                // Cerrar el modal
                btnCerrar.addEventListener("click", () => {
                    Swal.close();
                });
            }
        },
    });
}

///buscador postventas bait
$(function () {
    if ($("#datatable-postventas").length != 0) {
        let columnas = [
            { data: "registrado", name: "registrado", searchable: true },
            { data: "fvc", name: "c.fvc", searchable: true }, // Es buena práctica usar el alias de tabla
            { data: "idcontacto", name: "c.idcontacto", searchable: true }, // Es buena práctica usar el alias de tabla
            {
                data: "numero_portar",
                name: "c.numero_portar",
                searchable: true,
            },
            {
                data: "nombre_apellido",
                name: "c.nombre_apellido",
                searchable: true,
            },
            { data: "ciclo_vida", name: "c.ciclo_vida" },
            { data: "estatus_intelix", name: "c.estatus_intelix" },
            {
                data: "agente",
                name: "agente", // Este nombre debe coincidir con el del filterColumn en el backend
                searchable: true,
            },
            {
                data: "supervisor",
                name: "supervisor",
                searchable: true,
            },
            { data: "estatus", name: "estatus", searchable: true },
            { data: "autorizar", name: "autorizar" },
            {
                data: "acciones",
                name: "acciones",
                orderable: false,
                searchable: false,
            },
        ];

        $("#datatable-postventas").DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: $("#DataVentas").val(),
                type: "POST",
                data: function (d) {
                    d._token = $("input[name=_token]").val();
                },
            },
            columns: columnas,
            processing: "Cargando...",
            // Otros textos si quieres
        });
    }
});

function EliminarVenta(id) {
    Swal.fire({
        title: "CONFIMRMACION DE ACCION",
        text: "¿Estas seguro de eliminar esta venta? tenga en cuenta, que se eliminara todo lo relacionado con esta venta",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cencelar",
    })
        .then((isConfirm) => {
            if (isConfirm.isConfirmed) {
                const data = {
                    _method: "DELETE",
                    _token: $("input[name=_token]").val(),
                };
                $.ajax({
                    url: $("#eliminarsales" + id).data("href"),
                    type: "POST",
                    dataType: "JSON",
                    data: data,
                    success: function (response) {
                        toastr.success(response.message);
                        $("#datatable-postventas").DataTable().ajax.reload();
                    },
                    error: function (response) {
                        toastr.error(response.message);
                    },
                });
            }
        })
        .catch((error) => {
            toastr.error(error.responseJSON.message);
        });
}

function VisualizarHistoricoVenta(id) {
    const data = {
        id: id,
        _token: $("input[name=_token]").val(),
    };
    $.ajax({
        url: $("#historicoshow" + id).data("href"),
        type: "POST",
        dataType: "JSON",
        data: data,
        success: function (response) {
            $("#appendVenta").empty();
            $("#appendVenta").append(response.venta);

            if ($(".select").length > 0) {
                $(".select").select2({
                    theme: "bootstrap4",
                    width: "100%",
                    placeholder: {
                        id: "-1", // the value of the option
                        text: "Seleccione",
                    },
                });
            }

            $(".datetimepickerHour").datetimepicker({
                format: "h:mm A",
                icons: {
                    time: "fa fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-chevron-up",
                    down: "fa fa-chevron-down",
                    previous: "fa fa-chevron-left",
                    next: "fa fa-chevron-right",
                    today: "fa fa-calendar-check",
                    clear: "fa fa-trash",
                    close: "fa fa-times",
                },
            });

            $(".datepicker_single").datepicker({
                format: "dd/mm/yyyy",
            });

            KTFormControls.postventa();
            let columnas = [
                { data: "fecha", name: "fecha" },
                { data: "sns", name: "sns" },
                { data: "usuario", name: "usuario" },
                { data: "estatus_concentra", name: "estatus_concentra" },
                { data: "estatus_intelix", name: "estatus_intelix" },
                { data: "estatus", name: "estatus" },
                { data: "observaciones", name: "observaciones" },
            ];

            if ($.fn.dataTable.isDataTable("#historico-venta-table")) {
                $("#historico-venta-table").DataTable().destroy();
            }
            $("#historico-venta-table").DataTable({
                initComplete: function (settings, json) {
                    console.log("se activa");
                    $("#HistoricoModalShow").modal("show");
                },
                processing: true,
                responsive: true,
                serverSide: false,
                ordering: false,
                order: [[0, "ASC"]],
                data: response.historico,
                columns: columnas,
                processing: "Cargando...",
            });
        },
        error: function (response) {
            toastr.error(response.responseJSON.error);
        },
    });
}

$("#unlockseguimientos").click(function () {
    Swal.fire({
        title: "CONFIRMACION DE ACCION",
        html: `El Estatus de las Ventas que estan <b>bloqueadas</b> seran liberados. <br><br><b style='color: red;'>¿Esta de acuerdo?</B><br><br>Tome en cuenta que Una Vez liberados ya no podran ser bloqueados si no se realiza un nuevo seguimiento.`,
        showCancelButton: true,
        confirmButtonColor: "#0066b9ff",
        confirmButtonText: "LIBERAR",
        cancelButtonText: "Cencelar",
    })
        .then((isConfirm) => {
            if (isConfirm.isConfirmed) {
                const data = {
                    _method: "PUT",
                    _token: $("input[name=_token]").val(),
                };
                $.ajax({
                    url: $(this).data("href"),
                    type: "PUT",
                    dataType: "JSON",
                    data: data,
                    success: function (response) {
                        toastr.success(response.message);
                        $("#datatable-postventas").DataTable().ajax.reload();
                    },
                    error: function (response) {
                        toastr.error(response.message);
                    },
                });
            }
        })
        .catch((error) => {
            toastr.error(error.responseJSON.message);
        });
});

///cargado de archivos CRM
$("#cargadorArchivo").validate({
    rules: {
        archivo: {
            required: true,
            validateExtention: true,
        },
    },
    errorPlacement: function (error, element) {
        error.addClass("is-invalid text-sm");
        element.closest(".form-group").append(error).after();
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    },
    submitHandler: function (form) {
        var Toastr = main();
        Toastr.fire({
            icon: "info",
            title: "Procesando Archivo, Espere por favor.",
        });
        form.submit();
    },
});

//validdor reportes
$("#reportesDescargar").validate({
    errorPlacement: function (error, element) {
        error.addClass("is-invalid text-sm");
        element.closest(".form-group").append(error).after();
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    },
    submitHandler: function (form) {
        form.submit();
    },
});
