// Class definition
$(function () {
    $("#fecha").daterangepicker({
        locale: {
            format: "DD/MM/YYYY",
        },
        buttonClasses: "btn",
        applyClass: "btn-primary",
        cancelClass: "btn-secondary",
    });

    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>',
        };
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        };
    }

    $(".select").select2({
        placeholder: {
            id: "-1", // the value of the option
            text: "Todos",
        },
    });

    $(".date").datepicker({
        format: "dd/mm/yyyy",
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        templates: arrows,
    });

    //buscar registro de ventas claro / masivo /registradas
    $("#masivaSearch").validate({
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
                        lengthMenu: [10, 20, 50, 75],
                        bPaginate: true,
                        bProcessing: true,
                        columns: [
                            { mData: "creado" },
                            { mData: "producto" },
                            { mData: "identificador" },
                            { mData: "tipo_cliente" },
                            { mData: "nombreapellido" },
                            { mData: "plan" },
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

    //buscar registro de ventas claro / masivo /registradas
    $("#AuditSearch").validate({
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
                        lengthMenu: [10, 20, 50, 75],
                        bPaginate: true,
                        bProcessing: true,
                        columns: [
                            { mData: "creado" },
                            { mData: "producto" },
                            { mData: "identificador" },
                            { mData: "nombreapellido" },
                            { mData: "tipo_cliente" },
                            { mData: "plan" },
                            { mData: "agente" },
                            { mData: "supervisor" },
                            { mData: "auditado" },
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
});

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

///funcion para copiar texto de la auditoria ya almacenado
function CopyText(identificador) {
    //Seleccionamos el data del input con el texto generado
    var textoRaw = $("#button" + identificador).data("text");
    var estatus = $("#button" + identificador).data("estatus");

    if (estatus == 2) {
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
        showCancelButton: estatus == 2 ? false : true,
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
            btnCopiar.addEventListener("click", async () => {
                const textarea = Swal.getPopup().querySelector("#textoCopiar");
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
        },
    });
}

$("#formReportes").validate({
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

    submitHandler: function (form, event) {
        form.submit();
    },
});
