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
               rules: {
                ordenonix: {
                    required: true,
                    remote: {
                        url: $('input[name="ordenonix"]').data(
                            "check",
                        ),
                        type: "POST",
                        data: {
                            _token: function () {
                                return $('input[name="_token"]').val();
                            },
                            ordenonix: function () {
                                return $(
                                    'input[name="ordenonix"]',
                                ).val();
                            },
                            idventa: function () {
                                return $(
                                    'input[name="ordenonix"]',
                                ).data("idventa");
                            },
                        },
                        dataFilter: function (data) {
                            return data;
                        },
                    },
                },
            },
            messages: {
                ordenonix: {
                    remote: "El numero de Orden de ONIX ya se encuentra Registrado",
                },
            },
            // define validation rules           
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
                   var inputs = form.querySelectorAll("input, select");
                    for (var i = 0; i < inputs.length; i++) {
                        // Verificar si el input está deshabilitado
                        if (inputs[i].disabled) {
                            // Cambiar a readonly
                            inputs[i].disabled = false; // Primero habilitar el input
                            inputs[i].readOnly = true; // Luego establecerlo como readonly
                        }
                    }
                    button = form.querySelector("button[type='submit']");
                    button.disabled = true;
                    form.submit();
            },
        });
    };  
    return {
        // public functions
        init: function () {
            registroVenta();
        },     
    };
})();

var ActiveMap = () => {
     // Coordenadas iniciales por defecto (ej. Madrid, España)
   

    const centroInicial = $("#corddefault").data("cord");
    const zoominit      = $("#corddefault").data("zoom");
     console.log(centroInicial,zoominit);
    // Inicializar el mapa y la capa base de OpenStreetMap
    const map = L.map("map").setView(centroInicial,zoominit );
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 20,
        attribution: "© OpenStreetMap",
    }).addTo(map);

    // Crear el Pin arrastrable original
    let marker = L.marker(centroInicial, { draggable: true }).addTo(map);

    // Función para actualizar los inputs del formulario de Laravel
    function actualizarFormulario(direccion, lat, lng) {
        document.getElementById("direccion").value = direccion;
        document.getElementById("latitud").value = lat;
        document.getElementById("longitud").value = lng;
    }

    // --- INTEGRACIÓN DEL BUSCADOR ---
    // Añadimos el control de búsqueda al mapa (aparecerá una lupa arriba a la derecha)
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false, // Evita que el plugin cree su propio marcador feo
        placeholder: "Escribe una dirección...",
        errorMessage: "No se encontró esa dirección.",
    })
        .on("markgeocode", function (e) {
            // Este evento se dispara cuando el usuario selecciona una sugerencia del buscador
            const centro = e.geocode.center;
            const nombreDireccion = e.geocode.name;

            // 1. Movemos el mapa a la nueva ubicación encontrada
            map.setView(centro, 16);
            // 2. Movemos nuestro pin arrastrable allí
            marker.setLatLng(centro);
            // 3. Rellenamos el formulario de Laravel con los datos elegidos
            actualizarFormulario(nombreDireccion, centro.lat, centro.lng);
        })
        .addTo(map);
    // --- LOGICA DE ACTUALIZACIÓN CON CLIC O ARRASTRE ---
    // Función auxiliar para obtener texto de dirección mediante coordenadas (Geocodificación Inversa)
    function obtenerDireccionPorCoordenadas(lat, lng) {
        const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=es`;

        fetch(url)
            .then((response) => response.json())
            .then((data) => {
                if (data && data.display_name) {
                    actualizarFormulario(data.display_name, lat, lng);
                }
            })
            .catch((err) => console.error(err));
    }

    // Cargar dirección inicial por defecto
    obtenerDireccionPorCoordenadas(centroInicial[0], centroInicial[1]);

    // Evento: Al arrastrar el pin manual
    marker.on("dragend", function (e) {
        const posicion = marker.getLatLng();
        obtenerDireccionPorCoordenadas(posicion.lat, posicion.lng);
    });

    // Evento: Al hacer clic libre en cualquier parte del mapa
    map.on("click", function (e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        marker.setLatLng([lat, lng]);
        obtenerDireccionPorCoordenadas(lat, lng);
    });
}

jQuery(document).ready(function () {

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

    //activacion validate
    if($('form#masivaAdd').length > 0){
        ActiveMap();
        KTFormControls.init();
    }
});

$("#renovacionesSearch").validate({
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
                        { mData: "dn" },
                        { mData: "nombreapellido" },                     
                        { mData: "orden" },     
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
