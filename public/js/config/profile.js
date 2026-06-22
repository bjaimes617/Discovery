"use strict";

// Class definition
var KTUserProfile = (function () {
    // Base elements
    var offcanvas;

    // Private functions
    var initAside = function () {
        // Mobile offcanvas for mobile mode
        offcanvas = new KTOffcanvas("kt_user_profile_aside", {
            overlay: true,
            baseClass: "kt-app__aside",
            closeBy: "kt_user_profile_aside_close",
            toggleBy: "kt_subheader_mobile_toggle",
        });
    };

    var password = function () {
        $.validator.addMethod("pwcheck", function (value) {
            return (
                /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && // consists of only these
                /[A-Z]/.test(value) && // has a lowercase letter
                /\d/.test(value)
            ); // has a digit
        });
        $("#kt_form_update_password").validate({
            // define validation rules
            rules: {
                currentpassword: {
                    required: true,
                    remote: {
                        url: $("#currentpassword").data("check"),
                        type: "post",
                        data: {
                            _token: $("input[name='_token']").val(),
                        },
                    },
                },
                newpassword: {
                    required: true,
                    pwcheck: true,
                    minlength: 8,
                    remote: {
                        url: $("#newpassword").data("check"),
                        type: "post",
                        data: {
                            _token: $("input[name='_token']").val(),
                        },
                    },
                },
                confirmpassword: {
                    required: true,
                    equalTo: "#newpassword",
                },
            },
            messages: {
                currentpassword: {
                    remote: "La contraseña actual es incorrecta por favor ingresela nuevamente.",
                    required: "Este campo es obligatorio.",
                },
                newpassword: {
                    pwcheck:
                        "La contraseña ingresada no cumple con las condiciones de seguridad.",
                    required: "Este campo es obligatorio.",
                    remote: "La contraseña nueva no puede ser igual a las ultimas tres (03) que utilizaste.",
                    minlength: "Debe ingresar como minimo 12 caracteres.",
                },
                confirmpassword: {
                    equalTo: "La contraseña debe coincidir con la que ingreso.",
                    required: "Este campo es obligatorio.",
                },
            },
            invalidHandler: function (event, validator) {
                /*swal.fire({
                    "title": "",
                    "text": $("#kt_form_update_password").data('error'),
                    "type": "error",
                    "confirmButtonClass": "btn btn-secondary",
                    "onClose": function (e) {
                        console.log('Closed');
                    }
                });*/

                event.preventDefault();
            },

            submitHandler: function (form) {
                form.submit(); // submit the form
                return false;
            },
        });
    };

    return {
        // public functions
        init: function () {
            initAside();
            password();
        },
    };
})();

$("#updatepassword").click(function () {
    $("#kt_form_update_password").submit();
});

KTUtil.ready(function () {
    KTUserProfile.init();
});
