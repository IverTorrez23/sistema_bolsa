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

      <th>Cod Compra</th>
      <th>Fecha</th>
      <th>Proveedor</th>
      <th>Compra credito</th>
      <th>Cancelado</th>
      <th>Usuario</th>
      <th>Costo Total</th>
      <th>Borrar</th>
    </tr>
  </thead>

  <tbody>
    <?php
    include_once("../../modelos/compra.modelo.php");


    $contador = 1;
    $ganacia = 0;
    $sub_costo = 0;
    $GananciasTotales = 0;
    $totalMontoVentas = 0;
    $alertaColorfila = '';
    $colorTexto = '';
    $sumatodascompras = 0;
    $montoaunenInversion = 0;
    $sumaMontoaunenInversion = 0;
    $obj = new Compra();
    if ($idalmacen == 0) {
      $resultado = $obj->ReporteComprasActivas($fechaIni, $fechaFin);
    } else {
      $resultado = $obj->ReporteComprasActivasDeAlmacen($fechaIni, $fechaFin, $idalmacen);
    }
    while ($fila = mysqli_fetch_object($resultado)) {
      /*$datoscomp=$fila->idcompra."||".
                             $fila->fecha_compra."||".
                             $fila->fecha_compra."||".
                             $fila->fecha_compra."||".
                             nameProducto

                                     $fila->idproducto."||".
                                     $fila->compra_facturada."||".
                                     $fila->id_proveedor."||".
                                     $fila->precio_venta_prod."||".
                                     $fila->precio_venta_prod_Fact."||".
                                     $fila->precio_tope."||".
                                     $fila->cantidad."||".
                                     $fila->monto_compra."||".
                                     $fila->precio_unit_compra."||".
                                     $fila->precio_unit_compraFacturado;
                                
  
                  /*obtenemos la ganacia de total vendido del producto*/
      /* $sub_costo=$fila->precio_compra_prod*$fila->cantidad_prod;   
                    $ganacia=$fila->subtotal_venta-$sub_costo;*/
      //$montoaunenInversion = ($fila->precio_unit_compra * $fila->stock_actual);
    ?>
      <tr>

        <td><a target="_blank" href="impresiones/tcpdf/pdf/nota_compra.php?codcompra=<?php echo $fila->idcompra; ?>"> <?php echo $fila->idcompra; ?></a></td>
        <td><?php echo $fila->fecha_compra; ?></td>

        <td><?php echo $fila->nameProveedor; ?></td>


        <td>
          <?php if ($fila->compra_credito == 1): ?>
            <i class="fa fa-check-circle" style="color: green;"></i>
          <?php else: ?>
            <i class="fa fa-circle" style="color: gray;"></i>
          <?php endif; ?>
        </td>
        <td>
          <?php if ($fila->cancelado == 1): ?>
            <i class="fa fa-check-circle" style="color: green;"></i>
          <?php else: ?>
            <i class="fa fa-circle" style="color: gray; cursor:pointer;" onclick="modalCuota(<?php echo $fila->idcompra; ?>,<?php echo $fila->monto_compra; ?>)" data-toggle="modal" data-target="#modalCuota"></i>
          <?php endif; ?>
        </td>
        <!-- <td><?php echo number_format((float)$montoaunenInversion, 2, '.', ''); ?></td> -->
        <td><?php echo $fila->Usuario; ?></td>
        <td><?php echo $fila->monto_compra; ?></td>


        <td>
          <div class="btn-group">
            <button class="btn btn-danger" data-toggle="modal" data-target="#modalElimComp" onclick="CargarinfoCOmpraElim('<?php echo $fila->idcompra; ?>')"><i class="fa fa-times"></i></button>
          </div>
        </td>


      </tr>
    <?php
      // $contador++; SUMA DE TOTALES
      //  $totalMontoVentas=$totalMontoVentas+$fila->subtotal_venta;
      // $GananciasTotales=$GananciasTotales+$ganacia;
      $sumatodascompras = $sumatodascompras + $fila->monto_compra;
      $sumaMontoaunenInversion = $sumaMontoaunenInversion + $montoaunenInversion;
    }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="6" style="text-align: center;"><b>Totales</b> </td>
      <td><b><?php echo number_format((float)$sumatodascompras, 2, '.', ''); ?></b></td>
    </tr>
  </tfoot>
</table>

<script type="text/javascript">
  /*========================CARGAMOS LOS DATOS PARA ELIMINAR=========================*/
  function CargarinfoCOmpraElim(idCompra) {
    $('#idcompraelim').val(idCompra);
    $('#labelcodigoelim').text(idCompra);

  }
</script>