<script>
    "use strict";
    var KTDatatablesSearchOptionsAdvancedSearch = function () {

        $.fn.dataTable.Api.register('column().title()', function () {
            return $(this.header()).text().trim();
        });

        var initTable1 = function () {
            // begin first table
            var table = $('#kt_table_1').DataTable({
                responsive: true,
                "deferRender": true,
                // Pagination settings
                // read more: https://datatables.net/examples/basic_init/dom.html
                lengthMenu: [5, 10, 25, 50],

                pageLength: 10,

                language: {
                    'lengthMenu': 'Display _MENU_',
                },

                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('permisos.get') }}",
                    type: 'POST',
                    data: {
                        _token: $("#token").val(),
                        columnsDef: [
                            "Nombre",
                            "Accion",
                            "Descripci&oacute;n",
                            "Creado En",  
                            "Acciones"],
                    },
                },
                columns: [
                    {data: "Nombre"},
                    {data: "Accion"},
                    {data: "Descripci&oacute;n"},
                    {data: "Creado En"}, 
                    {data: "Acciones"}
                ],
            });
        };

        return {
            //main function to initiate the module
            init: function () {
                initTable1();
            },

        };

    }();

    jQuery(document).ready(function () {
        KTDatatablesSearchOptionsAdvancedSearch.init();
    });
</script>