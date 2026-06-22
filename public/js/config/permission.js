

$("#savepermission").click(function () {
    $("#kt_form_permission").submit();
});

$("#updatepermission").click(function () {
    $("#kt_form_update_permission").submit();
});
// Class definition

var KTFormControls = function () {
    // Private functions

    var roles = function () {
        $("#kt_form_permission").validate({
            // define validation rules
            rules: {
                name: {
                    required: true
                },
                slug: {
                    required: true
                },
                description: {
                    required: true
                },
                level: {
                    required: true
                }
            },
            invalidHandler: function (event, validator) {
                swal.fire({
                    "title": "",
                    "text": $("#kt_form_permission").data('error'),
                    "type": "error",
                    "confirmButtonClass": "btn btn-secondary",
                    "onClose": function (e) {
                        console.log('Closed');
                    }
                });

                event.preventDefault();
            },

            submitHandler: function (form) {
                form.submit(); // submit the form
                return false;
            }
        });


    }

    var editroles = function () {
        $("#kt_form_update_permission").validate({
            // define validation rules
            rules: {
                name: {
                    required: true
                },
                slug: {
                    required: true
                },
                description: {
                    required: true
                },
                level: {
                    required: true
                }
            },
            invalidHandler: function (event, validator) {
                swal.fire({
                    "title": "",
                    "text": $("#kt_form_update_permission").data('error'),
                    "type": "error",
                    "confirmButtonClass": "btn btn-secondary",
                    "onClose": function (e) {
                        console.log('Closed');
                    }
                });

                event.preventDefault();
            },

            submitHandler: function (form) {
                form.submit(); // submit the form
                return false;
            }
        });


    }

    return {
        // public functions
        init: function () {
            roles();
            editroles();
        }
    };
}();

jQuery(document).ready(function () {
    KTFormControls.init();
});

$(function () {
    $('.table').on('click', '.editpermission', function (event) {
        event.preventDefault();
        $('#editname').val($(this).data("name"));
        $('#editid').val($(this).data("id"));
        $('#editslug').val($(this).data("slug"));
        $('#editdescription').val($(this).data("description"));
        var data = $(this).data("permissions");
        //$('#editpermissions').val();
        $('#editpermission').modal('show');
    });




});

