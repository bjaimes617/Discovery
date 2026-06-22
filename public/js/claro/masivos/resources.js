var KTFormControls = (function () {
    // Private functions

    var form1 = function () {
        $("#masivaAdd").validate({
            // define validation rules
            rules: {},
            messages: {},
            errorElement: "span",
            errorClass: "text-danger",
            //display error alert on form submit
            invalidHandler: function (event, validator) {
                console.log(validator);
                var alert = $("#kt_form_1_msg");
                alert.removeClass("kt--hide").show();
                KTUtil.scrollTop();
            },
            errorPlacement: function (error, element) {
                console.log(element);
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
    var form2 = function () {
        var botonPresionado = ""; // variable global para saber qué botón se presionó

        // Detectar qué botón se presiona antes del submit
        $("button[type=submit]").on("click", function () {
            botonPresionado = $(this).val();
        });

        $("#AuditoriaUpdate").validate({
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
                // Generar texto con los datos del formulario
                var texto = "";
                $(form)
                    .find(
                        "input[type=text]:visible, input[type=number]:visible, select:visible, textarea:visible",
                    )
                    .each(function () {
                        var formGroup = $(this).closest(".form-group.row");
                        var etiqueta = formGroup
                            .find("label")
                            .first()
                            .text()
                            .replace("*", "")
                            .trim();

                        var valor;
                        if ($(this).is("select") && $(this).val() !== "") {
                            valor = $(this).find("option:selected").text();
                        } else {
                            valor = $(this).val();
                        }
                        if (
                            valor !== null &&
                            valor !== undefined &&
                            valor.toString().trim() !== ""
                        ) {
                            texto += etiqueta + ": " + valor + "\n";
                        }
                    });

                var mensaje = "";
                if (botonPresionado === "aprobada") {
                    mensaje =
                        "Está a punto de aprobar el registro y sus datos serán enviados al GoogleSheet, ¿Desea continuar? <br/><br/>" +
                        "<strong>Copie el siguiente texto para Enviar por Telegram:</strong><br/>" +
                        `<textarea id="textoCopiar" style="width:100%; height:150px; margin-top:10px;" readonly>${texto}</textarea>` +
                        `<button id="btnCopiar" style="margin-top:10px;" class="swal2-confirm swal2-styled">Copiar texto</button>`;
                } else if (botonPresionado === "rechazada") {
                    mensaje =
                        "Está a punto de rechazar el registro, Indique las observaciones del rechazo:<br><br>" +
                        `<textarea id="textoCopiar" style="width:100%; height:150px; margin-top:10px;"></textarea>` +
                        `<div style="margin-top: 10px;">
            <input type="checkbox" id="checkboxContainer" />
            <label for="checkboxContainer">¿Es recuperable?</label>
        </div>`;
                } else {
                    mensaje = "¿Desea continuar?";
                }

                Swal.fire({
                    title: "¡ATENCIÓN!",
                    html: mensaje,
                    showCancelButton: true,
                    confirmButtonText: "Procesar",
                    cancelButtonText: "Cancelar",
                    didOpen: () => {
                        if (botonPresionado === "aprobada") {
                            const btnCopiar =
                                Swal.getPopup().querySelector("#btnCopiar");

                            const textarea =
                                Swal.getPopup().querySelector("#textoCopiar");
                            // Remover cualquier listener previo para evitar duplicados
                            const nuevoBtnCopiar = btnCopiar.cloneNode(true);
                            btnCopiar.replaceWith(nuevoBtnCopiar);

                            nuevoBtnCopiar.addEventListener(
                                "click",
                                async () => {
                                    const textarea =
                                        Swal.getPopup().querySelector(
                                            "#textoCopiar",
                                        );
                                    textarea.select();
                                    try {
                                        const successful =
                                            document.execCommand("copy");
                                        if (successful) {
                                            nuevoBtnCopiar.textContent =
                                                "¡Copiado!";
                                            setTimeout(() => {
                                                nuevoBtnCopiar.textContent =
                                                    "Copiar texto";
                                            }, 2000);
                                        } else {
                                            alert(
                                                "No se pudo copiar el texto automáticamente. Por favor, copie manualmente.",
                                            );
                                        }
                                    } catch (err) {
                                        alert(
                                            "No se pudo copiar el texto automáticamente. Por favor, copie manualmente.",
                                        );
                                    }
                                },
                            );
                        } else {
                            const textarea =
                                Swal.getPopup().querySelector("#textoCopiar");
                            textarea.select();
                        }
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#auditoria").val(botonPresionado);
                        $("#auditoriatext").val(
                            botonPresionado === "rechazada"
                                ? $("#textoCopiar").val()
                                : JSON.stringify(texto),
                        );
                        $("#recuperable").val(
                            $("#checkboxContainer").is(":checked") ? 1 : 0,
                        );

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
            },
        });
    };
    var form3 = function () {
        $("#formsegumientos").validate({
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
                event.preventDefault();
                // Generar texto con los datos del formulario
                mensaje =
                    "Se Registrara la actualizacion de Estatus para la venta, <br/><br/>" +
                    "<strong> ¿Desea continuar?</strong><br/>";

                Swal.fire({
                    title: "¡ATENCIÓN!",
                    html: mensaje,
                    showCancelButton: true,
                    confirmButtonText: "Procesar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        const btnConfirm = Swal.getConfirmButton();
                        // Desactivar el botón para evitar múltiples clicks
                        btnConfirm.disabled = true;
                        $.ajax({
                            type: "POST",
                            url: form.action,
                            data: $(form).serialize(),
                            dataType: "json",
                            success: function (data) {
                                toastr.success(data.message);
                                $("#registerSegumiento").modal("hide");
                                btnConfirm.disabled = false;
                                $("#PasteContent").empty();
                            },
                            error: function (data) {
                                toastr.error(data.responseJSON.message);
                                btnConfirm.disabled = false;
                            },
                        });
                    }
                });
            },
        });
    };
    return {
        // public functions
        init: function () {
            form1();
        },
        audit: function () {
            form2();
        },
        seguimiento: function () {
            form3();
        },
    };
})();

jQuery(document).ready(function () {
    if ($("#AuditoriaUpdate").length > 0) {
        ///solo aplica para el formulario de auditorias se recoje el valor del boton presionado
        $("button[type=submit]").click(function () {
            const botonPresionado = $(this).val();
        });
        KTFormControls.audit();
    } else {
        KTFormControls.init();
    }
});

$(function () {
    $(".input-number").on("input", function () {
        this.value = this.value.replace(/[^0-9]/g, "");
    });

    if ($(".select").length > 0) {
        $(".select").select2({
            placeholder: {
                id: "-1", // the value of the option
                text: "Seleccione",
            },
        });
    }
});

$("#producto").on("change", function () {
    var data = {
        _token: $('input[name="_token"]').val(),
        producto: $(this).val(),
    };
    var url = $(this).data("href");
    $("#displayTipoVenta").show();

    if ($(this).val() === "3") {
        $("#tipoventa").prop("required", true).prop("disabled", false);
        $("#tipoventa option").show();
        $("#tipoventa option[value='Hogar']").remove();
        $("#tipoventa").val("").select2().trigger("change");
        console.log("aca");
    } else {
        $("#tipoventa").prop("required", false).prop("disabled", true);
        $("#tipoventa option").hide();
        if ($("#tipoventa option[value='Hogar']").length === 0) {
            $("#tipoventa").append('<option value="Hogar">Hogar</option>');
        }
        $("#tipoventa").val("Hogar").select2().trigger("change");
    }

    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function (data) {
            $("#tipo_plan").empty();
            $("#tipo_plan").append(
                $("<option></option>").attr("value", "").text("[Seleccione]"),
            );
            for (i = 0; i < data[0].length; i++) {
                valuegroup = data[0][i].group;
                $("#tipo_plan").append(
                    $("<optgroup></optgroup>").attr("label", valuegroup),
                );
                for (j = 0; j < data[1].length; j++) {
                    if (
                        valuegroup == data[1][j].group &&
                        data[1][j].group !== null
                    ) {
                        $("#tipo_plan").append(
                            $("<option></option>")
                                .attr("value", data[1][j].id)
                                .text(data[1][j].descripcion),
                        );
                    }
                }
            }
            $("#tipo_plan").append(
                $("<optgroup></optgroup>").attr("label", "Sin Categoria"),
            );
            for (e = 0; e < data[1].length; e++) {
                if (data[1][e].group === null) {
                    $("#tipo_plan").append(
                        $("<option></option>")
                            .attr("value", data[1][e].id)
                            .text(data[1][e].descripcion),
                    );
                }
            }
        },
        error: function (data) {
            toastr.error(data.responseJSON.message);
        },
    });
});

function ActiveRegisterSegumiento(id) {
    var register = id;
    buttonPresionado = $("#buttonSegumiento" + id);

    var url = $("#urlSeguimiento").val();
    var data = {
        _token: $('input[name="_token"]').val(),
        producto: buttonPresionado.data("producto"),
        venta: register,
    };

    var Pasteconten = $("#PasteContent");
    Pasteconten.empty();
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "json",
        success: function (data) {
            $("#registerSegumiento").modal("show");
            $("#venta").val(register);

            if (!$.inArray(buttonPresionado.data("estatus"), [1, 2, 3])) {
                $("#newestatus").val(buttonPresionado.data("estatus"));
            }

            for (let i = 0; i < data[0].length; i++) {
                var contentToMove = $("#copyCheckDocuments").clone();

                // Elimina el id del contenedor para evitar duplicados
                contentToMove.removeAttr("id");
                // Encuentra el label principal (el que tiene class="col-form-label")
                var mainLabel = contentToMove.find(
                    'label.col-form-label[for="checkdocumentos"]',
                );
                // Cambia el texto del label
                mainLabel.text(data[0][i].documento);
                // Cambia el atributo 'for' del label principal
                mainLabel.attr("for", "check" + data[0][i].id);

                // Cambia el id y name del input checkbox
                contentToMove
                    .find("input#checkdocumentos")
                    .attr("id", "check" + data[0][i].id)
                    .attr("name", "check" + data[0][i].id);

                if (data[1] !== null && data[1][data[0][i].id]) {
                    contentToMove
                        .find("input#check" + data[0][i].id)
                        .prop("checked", true);
                }
                // Finalmente, agrega el clon al contenedor destino
                Pasteconten.append(contentToMove);
                contentToMove.show();
                KTFormControls.seguimiento();
            }
        },
        error: function (data) {
            toastr.error(data.responseJSON.message);
        },
    });
}
