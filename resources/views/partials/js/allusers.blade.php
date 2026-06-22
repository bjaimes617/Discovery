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
                    url: "{{ route('user.get') }}",
                    type: 'POST',
                    data: {
                        _token: $("#token").val(),
                        columnsDef: [
                            "Nombre y Apellido",
                            "Usuario",
                            "Email",                              
                            "Ficha de Personal",
                            "Autenticacion",
                            "campania",
                            "Rol",
                            "Creado El", 
                            "Estatus",
                            "Acciones"],
                    },
                },
                columns: [
                    {data: "Nombre y Apellido"},
                    {data: "Usuario"},
                    {data: "Email"},                     
                    {data: "Ficha de Personal"}, 
                    {data: "Autenticacion"}, 
                    {data: "campania"}, 
                    {data: "Rol"}, 
                    {data: "Creado El"}, 
                    {data: "Estatus"}, 
                    {data: "Acciones"}
                ],
                createdRow: function (row, data, dataIndex) {
                    $(row).attr("data-id", data.id); // suponiendo que 'id' es el campo único
                },
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