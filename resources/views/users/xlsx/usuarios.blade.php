<html>
    <br style="background: #FFF;"/>
    <tr style="text-align: left">
        <td style="text-align: left;font-weight:bold; font-size: 12px;" colspan="4" align="left">REPORTE DE PERSONAL</td>   
    </tr>
    <br/><br/>
    <tr style="text-align: left">
        <td style="background: #444444;color:#FFFFFF;text-align: left;font-weight:bold; font-size: 12px;" colspan="6" align="center">Datos de Usuario</td> 
        <td style="background: #CCCCCC;color:#444444;text-align: left;font-weight:bold; font-size: 12px;" colspan="11" align="center">Ficha Personal</td>  
    </tr>
    <tr>
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">#</th>
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Nombre Empleado</th>
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Usuario</th>
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Email</th>
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Autenticacion 2FA</th>
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Estatus Login</th>        
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">IN Telefonico</th>
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Numero Empleado</th>          
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Login Telefonico</th> 
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Cargo</th> 
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Campaña</th> 
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Rol Asignado</th>
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Jefe Inmediato</th> 
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Jefe Inmediato Nivel 2</th> 
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Estatus</th> 
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Fecha de Ingreso</th> 
        <th class="desc" style="background: #366cf3;color:#FFFFFF;font-weight: bold;text-align: center;border: 2px solid #000000; wrap-text: true;">Fecha de Baja</th> 
    </tr>
    @if(isset($data) && count($data) > 0)
    <?php
    $i = 0;
    foreach ($data as $rs) {
        $i++;
        ?>
        <tr>   
            <td class="desc" style="text-align: left;border: 2px solid #000000; wrap-text: true;">{{$i}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['nombre_apellido']))}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['usuario']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['email']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">@if($rs['fa2']) Si @else No @endif</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['nombre_estatus']))}}</td>            
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['in_telefonico']}}</td>            
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['numero_empleado']}}</td>              
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['login_telefonico']}}</td> 
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['nombre_cargo']}}</td> 
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['nombre_campana']}}</td> 
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['name']}}</td> 
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['supervisor']))}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{ucwords(strtolower($rs['supervisor2']))}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['estatus']}}</td>
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">{{$rs['fecha_ingreso']}}</td>  
            <td class="desc" style="text-align: center;border: 2px solid #000000; wrap-text: true;">@if($rs['fecha_baja']) {{$rs['fecha_baja']}} @endif</td> 
        </tr>
    <?php } ?>
    @else
    <tr>
        <td class="desc" colspan="17" style="text-align: center;border: 2px solid #000000; wrap-text: true;">No existen resultados que coincidan con sus parametros de busqueda.</td>      
    </tr>
    @endif 
</html>