<?php
session_start();
$fechaIni = $_GET['fini'] . ' 00:00:00';
$fechaFin = $_GET['ffin'] . ' 23:59:00';
$idalmacen = $_GET['idalmacen'];

// echo $fechaFin;
$_SESSION["frchaini"] = $fechaIni;
$_SESSION["fechafin"] = $fechaFin;
$_SESSION["idalmacen"] = $idalmacen;
include_once("../../modelos/empleado.modelo.php");
include_once("../../modelos/almacen.modelo.php");
include_once("../../modelos/venta.modelo.php");

if ($idalmacen == 0) {
  $nombreVendedor = 'Todos';
} else {

  $objemep = new Almacen();
  $resultEmp = $objemep->mostarUnAlmacen($idalmacen);
  $filaemp = mysqli_fetch_object($resultEmp);
  $nombreEmp = $filaemp->nombre_almacen;
  $nombreVendedor = $nombreEmp;
}
?>
<!-- <label>Almacen seleccionado: <?php echo $nombreVendedor; ?> </label> -->
<table class="table table-bordered table-striped ">
  <thead>
    <tr>

      <th>Cod Venta</th>
      <th>Fecha</th>
      <th>Cliente</th>
      <th>Venta credito</th>
      <th>Cancelado</th>
      <th>Usuario</th>
      <th>Venta Total</th>
      <th>Borrar</th>
    </tr>
  </thead>

  <tbody>
    <?php

    $contador = 1;
    $ganacia = 0;
    $sub_costo = 0;
    $GananciasTotales = 0;
    $totalMontoVentas = 0;
    $alertaColorfila = '';
    $colorTexto = '';
    $sumatodasVentas = 0;
    $montoaunenInversion = 0;
    $sumaMontoaunenInversion = 0;
    $obj = new Venta();
    $resultado = $obj->ReporteVentasActivas($fechaIni, $fechaFin);
    
    while ($fila = mysqli_fetch_object($resultado)) {
      
    ?>
      <tr>

        <td><a target="_blank" href="impresiones/tcpdf/pdf/nota_venta.php?codventa=<?php echo $fila->idventa; ?>"> <?php echo $fila->idventa; ?></a></td>
        <td><?php echo $fila->fecha_venta; ?></td>

        <td><?php echo $fila->nameCliente; ?></td>


        <td>
          <?php if ($fila->venta_credito == 1): ?>
            <i class="fa fa-check-circle" style="color: green;"></i>
          <?php else: ?>
            <i class="fa fa-circle" style="color: gray;"></i>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($fila->cancelado == 1): ?>
            <i class="fa fa-check-circle" style="color: green;"></i>
          <?php else: ?>
            <i class="fa fa-circle" style="color: gray; cursor:pointer;" onclick="modalCuota(<?php echo $fila->idventa; ?>,<?php echo $fila->monto_venta; ?>)" data-toggle="modal" data-target="#modalCuota"></i>
          <?php endif; ?>
        </td>
        <!-- <td><?php echo number_format((float)$montoaunenInversion, 2, '.', ''); ?></td> -->
        <td><?php echo $fila->Usuario; ?></td>
        <td><?php echo $fila->monto_venta; ?></td>


        <td>
          <div class="btn-group">
            <button class="btn btn-danger" data-toggle="modal" data-target="#modalElimVenta" onclick="CargarinfoVentaElim('<?php echo $fila->idventa; ?>')"><i class="fa fa-times"></i></button>
          </div>
        </td>


      </tr>
    <?php
      // $contador++; SUMA DE TOTALES
      //  $totalMontoVentas=$totalMontoVentas+$fila->subtotal_venta;
      // $GananciasTotales=$GananciasTotales+$ganacia;
      $sumatodasVentas = $sumatodasVentas + $fila->monto_venta;
      
    }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="6" style="text-align: center;"><b>Totales</b> </td>
      <td><b><?php echo number_format((float)$sumatodasVentas, 2, '.', ''); ?></b></td>
    </tr>
  </tfoot>
</table>

<script type="text/javascript">
  /*========================CARGAMOS LOS DATOS PARA ELIMINAR=========================*/
  function CargarinfoVentaElim(idVenta) {
    $('#idventa').val(idVenta);
    $('#labelcodigoelim').text(idVenta);

  }
</script>