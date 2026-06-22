"use strict";

// Class Definition
var KTLoginGeneral = function () {

    var login = $('#kt_login');

    var showErrorMsg = function (form, type, msg) {
        var alert = $('<div class="alert alert-' + type + ' alert-dismissible" role="alert">\
			<div class="alert-text">' + msg + '</div>\
			<div class="alert-close">\
                <i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>\
            </div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        //alert.animateClass('fadeIn animated');
        KTUtil.animateClass(alert[0], 'fadeIn animated');
        alert.find('span').html(msg);
    }

    var handleResetFormSubmit = function () {

        $.validator.addMethod("pwcheck", function (value) {
            return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
                    && /[A-Z]/.test(value) // has a lowercase letter
                    && /\d/.test(value) // has a digit
        });

        $('#kt_password_submit').click(function (e) {
            e.preventDefault();

            var btn = $(this);
            var form = $('#kt_password_form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        pwcheck: true,
                        minlength: 12
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    email: {
                        required: 'Este campo es obligatorio.',
                        email: 'ingrese un cuenta de email valida.'
                    },
                    password: {
                        pwcheck: 'La contraseña ingresada no cumple con las condiciones de seguridad.',
                        required: 'Este campo es obligatorio.',
                        minlength: 'Debe ingresar como minimo 12 caracteres.'
                    },
                    password_confirmation: {
                        equalTo: 'La contraseña debe coincidir con la que ingreso.',
                        required: 'Este campo es obligatorio.'
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            KTApp.progress(btn[0]);

            setTimeout(function () {
                KTApp.unprogress(btn[0]);
            }, 2000);

            // ajax form submit:  http://jquery.malsup.com/form/
            form.ajaxSubmit({
                url: $("#url").val(),
                success: function (response, status, xhr, $form) {
                    // similate 2s delay
                    setTimeout(function () {
                        KTApp.unprogress(btn[0]);
                        showErrorMsg(form, response.status, response.msg);
                    }, 1000);
                    if (response.status == 'success')
                        setTimeout(function () {
                            window.location = response.url;
                        }, 2000);
                }, error: function (error) {
                    var msg = '';
                    if (error.status == 429)
                        msg = 'Ha excedido el número de intentos, intente nuevamente en 2 minutos';
                    else
                        msg = 'Ocurrio un error, por favor comuniquese con el administrador';

                    setTimeout(function () {
                        KTApp.unprogress(btn[0]);
                        showErrorMsg(form, 'danger', msg);
                    }, 3000);
                }
            });
        });
    }
    // Public Functions
    return {
        // public functions
        init: function () {
            handleResetFormSubmit();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function () {
    KTLoginGeneral.init();
});
