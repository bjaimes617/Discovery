

$("#saverole").click(function () {
    $("#kt_form_roles").submit();
});

$("#updaterole").click(function () {
    $("#kt_form_update_role").submit();
});
// Class definition

var KTFormControls = function () {
    // Private functions

    var roles = function () {
        $("#kt_form_roles").validate({
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
                    "text": $("#kt_form_roles").data('error'),
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
        $("#kt_form_update_role").validate({
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
                    "text": $("#kt_form_update_role").data('error'),
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
    $('#editpermissions, #permissions').select2({
        placeholder: "Select a permission...",
    });

    $('.table').on('click', '.editrole', function (event) {
        event.preventDefault();
        $('#editname').val($(this).data("name"));
        $('#editid').val($(this).data("id"));
        $('#editslug').val($(this).data("slug"));
        $('#editdescription').val($(this).data("description"));
        $('#editlevel').val($(this).data("level"));
        var data = $(this).data("permissions");
        $('#editpermissions').select2().select2('val',[$(this).data("permissions")]);
        //$('#editpermissions').val();
        $('#editrole').modal('show');
    });




});

