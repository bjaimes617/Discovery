<html>
    <br style="background: #FFF;"/>
    <tr style="text-align: left">
        <td style="text-align: left;font-weight:bold; font-size: 12px;" colspan="4" align="left">REPORTE DE VENTAS ({{$date}})</td>   
    </tr>
    <tr style="text-align: left">
        <td style="text-align: left;font-weight:bold; font-size: 11px;" colspan="4" align="left">Portabilidad Concentra</td>   
    </tr>
    <br/><br/>
    <tr>
        <td class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">#</td>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">DN Cliente</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Nombre del Cliente</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">C&oacute;digo del Vendedor</th>
        <!--<th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Titularidad</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Tipo de Linea</th>-->
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Recarga</th>
        <!--<th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Liberado</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Sexo</th>-->
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Fecha de Nacimiento</th>
        <!--<th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Entidad de Nacimiento</th>-->
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Correo Electronico</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Folio de la Venta</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Nombre del Ejecutivo</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Usuario del Ejecutivo</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Login Telefonico</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Nombre del Supervisor</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Nombre del Coordinador</th>
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">CURP</th>  
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">NIP</th>
        <!--<th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Centro de Atenci&oacute;n</th>--> 
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">DN Contacto Alterno</th> 
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">DN Contacto Alterno2</th> 
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">BackOffice</th> 
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Tipificacion1</th> 
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Tipificacion2</th> 
        <th class="" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Fecha de la Venta</th> 
    </tr>
    @if(isset($data) && count($data) > 0)
    <?php
    $i = 0;
    foreach ($data as $rs) {
        $i++;
        ?>
        <tr>
            <td class="desc" style="text-align: left;border: 2px solid #000000; wrap-text: true;">{{$i}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['dn']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['nombre_cliente']))}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['in_telefonico']}}</td>
            <!--<td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['titularidad']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['tipo_linea']}}</td>-->
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['recarga']}}</td>
            <!--<td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['liberado']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['sexo']}}</td>-->
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['fecha_nacimiento']}}</td>
           <!-- <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['entidad_nacimiento']}}</td>-->
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['email']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['folio_venta']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['agente']))}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['usuario']))}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['login_telefonico']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['supervisor']))}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['coord']))}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['curp']}}</td>   
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['nip']}}</td>
            <!--<td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['centro_atencion']}}</td>-->
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['dn_alterno']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['dn_alterno2']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['backoffice']))}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['nombre_tipificacion1']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['nombre_tipificacion2']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['fecha_venta']}}</td>   
        </tr>
    <?php } ?>
    @else
    <tr>
        <td class="desc" colspan="20" style="text-align: center;border: 2px solid #000000; wrap-text: true;">No existen resultados que coincidan con sus parametros de busqueda.</td>      
    </tr>
    @endif 
</html>