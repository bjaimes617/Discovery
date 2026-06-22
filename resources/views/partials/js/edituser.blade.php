<script>
"use strict";

// Class definition
var KTContactsAdd = function () {
    // Base elements
    var wizardEl;
    var formEl;
    var validator;
    var wizard;

    // Private functions
    var initWizard = function () {
        // Initialize form wizard
        wizard = new KTWizard('kt_contacts_add', {
            startStep: 1, // initial active step number
            clickableSteps: true  // allow step clicking
        });

        // Validation before going to next page
        wizard.on('beforeNext', function (wizardObj) {
            if (validator.form() !== true) {
                wizardObj.stop();  // don't go to the next step
            }
        })

        // Change event
        wizard.on('change', function (wizard) {
            KTUtil.scrollTop();
        });
    }

    var initValidation = function () {
        validator = formEl.validate({
            // Validate only visible fields
            ignore: ":hidden",

            // Validation rules
            rules: {
                nombre_apellido: {
                    required: true
                },
                usuario: {
                    required: true,
                    remote: {
                        url: $("#usuario").data('check'),
                        type: "post",
                        data:
                                {
                                      _token: $('input[name="_token"]').val(),
                                    id: $("#id").val()
                                }
                    }
                },
                numero_empleado: {
                    remote: {
                        url: $("#numero_empleado").data('check'),
                        type: "post",
                        data:
                                {
                                      _token: $('input[name="_token"]').val(),
                                    id: $("#id").val()
                                }
                    }
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: $("#email").data('check'),
                        type: "post",
                        data:
                                {
                                      _token: $('input[name="_token"]').val(),
                                    id: $("#id").val()
                                }
                    }
                }
            },
            messages: {
                usuario: {
                    remote: 'Este nombre de usuario ya se encuentra registrado.'
                },
                email: {
                    remote: 'Este Email ya se encuentra registrado.'
                },
                numero_empleado: {
                    remote: 'Este Numero de empleado ya se encuentra registrado.'
                }
            },

            // Display error
            invalidHandler: function (event, validator) {
                KTUtil.scrollTop();
                swal.fire({
                    "title": "",
                    "text": "Por favor verifique los datos que ha ingresado, existen alguno errores.",
                    "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                });
            },

            // Submit valid form
            submitHandler: function (form) {

            }
        });
    }

    var initSubmit = function () {
        var btn = formEl.find('[data-ktwizard-type="action-submit"]');

        btn.on('click', function (e) {
            e.preventDefault();

            if (validator.form()) {
                // See: src\js\framework\base\app.js
                KTApp.progress(btn);
                //KTApp.block(formEl);

                // See: http://malsup.com/jquery/form/#ajaxSubmit
                formEl.ajaxSubmit({
                    success: function (data) {
                        KTApp.unprogress(btn);
                        //KTApp.unblock(formEl);
                        swal.fire({
                            "title": "",
                            "text": "El usuario fue registrado exitosamente.",
                            "type": "success",
                            "confirmButtonClass": "btn btn-secondary"
                        }).then(function () {
                            window.location.href = data.url;
                        });
                    }
                });
            }
        });
    }


    return {
        // public functions
        init: function () {
            formEl = $('#kt_contacts_edit_form');

            initWizard();
            initValidation();
            initSubmit();
        }
    };
}();

jQuery(document).ready(function () {
    KTContactsAdd.init();
});


$(function () {
    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }

    $('#roles').select2({
        placeholder: "Seleccione el rol...",
    });

    $('.dater').datepicker({
        format: 'dd/mm/yyyy',
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        templates: arrows
    });

    $('#valida_ficha_personal').change(function () {
        if (this.checked) {
            $('#ficha_personal').show();
        } else {
            $('#ficha_personal').hide();
        }
    });
    
    @if(isset($user->ficha_personal) && $user->ficha_personal == 'Si')
        $("#valida_ficha_personal").prop('checked', 'checked');
        $('#ficha_personal').show();
    @endif   
    
    var my_repeater = $('#supervisores').repeater({
        initEmpty: false,
        show: function () {
            $(this).slideDown();
        },

        hide: function (deleteElement) {
            if (confirm('Esta Seguro de eliminar este supervisor?')) {
                $(this).slideUp(deleteElement);
                $(this).remove();
            }
        },
        isFirstItemUndeletable: true

    });
    
    @if(isset($superAsignados) && count($superAsignados) > 0)
        $("#supervisor").prop('disabled', false);
        $("#supervisor").prop('required', true);
        $('#supervisores').show();
        my_repeater.setList([
            @foreach($superAsignados as $supervisor)
                {'supervisor': '{{$supervisor}}'},
            @endforeach

        ]);
    @endif

    $('#cargo').on('change', function () {
        if ($(this).val() == 5) {
            $("#supervisor").prop('disabled', false);
            $("#supervisor").prop('required', true);
            $('#supervisores').show();
        } else {
            $("#supervisor").prop('disabled', 'disabled');
            $("#supervisor").prop('required', false);
            $('#supervisores').hide();
        }
    });
    
    if ($('#estatus_personal').val() == 2) {
        $("#fecha_baja").prop('disabled', false);
        $("#fecha_baja").prop('required', true);
        $('#fechab').show();
    } else {
        $("#fecha_baja").val('');
        $("#fecha_baja").prop('disabled', 'disabled');
        $("#fecha_baja").prop('required', false);
        $('#fechab').hide();
    }
    
    $('#estatus_personal').on('change', function () {
        if ($(this).val() == 2) {
            $("#fecha_baja").prop('disabled', false);
            $("#fecha_baja").prop('required', true);
            $('#fechab').show();
        } else {
            $("#fecha_baja").prop('disabled', 'disabled');
            $("#fecha_baja").prop('required', false);
            $('#fechab').hide();
        }
    });
});

$('#roles').change(function () {
console.log("ejecuta");
    var data = {
        id: $(this).val(),
        _token: $('input[name="_token"]').val()
    };
    $.ajax({
        type: 'POST',
        url: $(this).data('href'),
        data: data,
        dataType: 'json',
        success: function (data)
        {
            $('#permisos').removeAttr('disabled');
            
            $('#permisos option').prop('disabled', false);
            
            $('#permisos').val([]).trigger('change');
            
            const permisosOcultos = Array.isArray(data) ? data : [data];
            
            $('#permisos option').each(function () {             
                const optionValue = parseInt($(this).val());   
                if (data.includes(parseInt(optionValue))) {                     
                    $(this).prop('disabled', true); // Ocultar la opción
                }               
            });           
           
            $('#permisos').select2({
                placeholder: "Selecciona Permisos Adicionales",   
                allowClear: true           
            });             
        },
        error: function (data) {
            console.error("Error al obtener permisos:", data);
        }
    });

});



</script>