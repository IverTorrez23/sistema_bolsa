<?php
include_once("modelos/compraProducto.modelo.php");
include_once("modelos/compra.modelo.php");
include_once("modelos/venta.modelo.php");
$datosad = $_SESSION["usuarioAdmin"];
if ($_SESSION["tipo_user"] != "admin") {
  echo '<script>
        window.location="inicio";
      </script>';
}

$idVenta = $_GET["codventa"];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Detalle
      <small>Venta <?php echo $idVenta; ?> </small>
    </h1>
    <input type="hidden" value="<?php echo $idVenta; ?>" placeholder="id venta">
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <!-- <li><a href="#">Examples</a></li> -->
      <li class="active">Reportes</li>
    </ol>
  </section>


  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <!-- <h3 class="box-title">Reportes De Inventario</h3> -->
        <!-- <button class="btn btn-danger btn-xs" onclick="window.open('impresiones/tcpdf/pdf/reporte_inventario.php')">PDF</button> -->
        <!--  <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div> -->
      </div>
      <div class="box-body">
        <table class="table table-bordered table-striped ">
          <thead>
            <tr>
              <th>Nº Lote</th>
              <th>Producto</th>
              <th>Medidas</th>
              <!-- <th>Marca</th>-->
              <th>Cantidad venta</th>
              <th>Precio Vendido Bs.</th>
              <th>Sub total Bs.</th>
              <!-- <th>Precio Venta Fact.</th> -->
              <th>Eliminar</th>
              
            </tr>
          </thead>

          <tbody>
            <?php

            function ajustarDecimales($num)
            {
              // Si tiene 2 decimales o menos, forzar formato 0.00
              if (round($num, 2) == $num) {
                return number_format($num, 2, '.', '');
              }
              // Si tiene más, devolver el número tal cual
              return $num;
            }

            $contador = 1;
            $totalVenta = 0;
            $montoaunenInversion = 0;
            $sumaMontoaunenInversion = 0;
            $obj = new Venta();
            $resultado = $obj->nota_Venta($idVenta);
            while ($fila = mysqli_fetch_object($resultado)) {
              $datosLote = $fila->id_compra_producto . "||" .
                $fila->id_venta_producto . "||" .
                $fila->cantidad_prod . "||" .
                $fila->nombre_producto . "||" .
                $fila->subtotal_venta . "||" .
                $fila->monto_venta;


          $montoaunenInversion = ($fila->precio_unit_compra * $fila->stock_actual);
            ?>
              <tr>
                <td><?php echo $fila->id_compra_producto; ?></td>
                <td><?php echo $fila->nombre_producto; ?></td>

                <td><?php echo $fila->descripcion; ?></td>
                <td><?php echo $fila->cantidad_prod; ?></td>
                <!-- <td><?php echo $fila->stock_actual; ?></td> -->

                <td><?php echo $fila->precio_unitario_venta; ?></td>


                <!-- <td><?php echo ajustarDecimales($fila->precio_unit_compra); ?></td> -->

                <?php
                /*$costo_float = floatval($fila->precio_unit_compra);
                $montoInvent = $costo_float * $fila->stock_actual;
                $montoDecimal = $montoInvent;*/

                ?>
                <td><?php echo ajustarDecimales($fila->subtotal_venta); ?></td>
                
                <td>
                  <div class="btn-group">
                      <button class="btn btn-danger" data-toggle="modal" data-target="#modaleditarCompra" onclick="CargarinfoCompraEnModal('<?php echo $datosLote ?>')"><i class="fa fa-times"></i></button>
                  </div>
                </td>

              </tr>
            <?php
              $contador++;
              $totalVenta = $totalVenta + $fila->subtotal_venta;
            }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" style="text-align: center;"><b>Total</b> </td>
              <td><b><?php echo  ajustarDecimales($totalVenta); ?></b></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        Footer
      </div>
      <!-- /.box-footer-->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->



</div>
<!-- /.content-wrapper -->


<!-- Modal para eliminar-->
<div id="modaleditarCompra" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">


      <div class="modal-header" style="background: #f70808; color:white;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <label class="modal-title">Eliminar Item de esta venta ?
        </label>
      </div>
           <label class="modal-title">Se eliminara el producto <label id="nro_lote"></label>  de esta venta, esta seguro ?</label>
      <input type="hidden"  name="idventaproducto" id="idventaproducto" readonly>
      <input type="hidden" name="cantidadvent" id="cantidadvent" readonly>
      <input type="hidden"  name="idcompraproducto" id="idcompraproducto" readonly>
      <input type="hidden"  name="subtotalventa" id="subtotalventa" readonly>
      <input type="hidden"  name="totalventa" id="totalventa" readonly>
      <input type="hidden"  name="idventa" id="idventa" value="<?php echo $idVenta; ?>" readonly>
   
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" class="btn btn-primary" name="btnElimitemventa" id="btnElimitemventa">Eliminar</button>
      </div>

    </div>

  </div>
</div>


<script>
  function CargarinfoCompraEnModal(datoscomp) {
    f = datoscomp.split('||');
    $('#nro_lote').text(f[3]);
    $('#idventaproducto').val(f[1]);
    $('#cantidadvent').val(f[2]);
    $('#idcompraproducto').val(f[0]);
    $('#subtotalventa').val(f[4]);
    $('#totalventa').val(f[5]);
  }

  /********ACTUALIZAR UNA COMPRA***********/
  $(document).ready(function() {
    $("#btnElimitemventa").on('click', function() {

      var formDataCompedit = new FormData();

      var btnElimitemventa = $('#btnElimitemventa').val();
      var idventaproducto = $('#idventaproducto').val();
      var cantidadvent = $('#cantidadvent').val();
      var idcompraproducto = $('#idcompraproducto').val();
      var subtotalventa = $('#subtotalventa').val();
      var totalventa = $('#totalventa').val();
      var idventa = $('#idventa').val();
      
      if ((idventaproducto == '') ||
        (cantidadvent == '') ||
        (idcompraproducto == '') ||
        (subtotalventa == '') ||
        (idventa == '') ||
        (totalventa == '') 
      ) {
        setTimeout(function() {}, 2000);
        swal('ATENCION', 'Se perdieron algunos datos, recargue la pagina y vuelva a intentar', 'warning');


      } else {
        formDataCompedit.append('idventaproducto', idventaproducto);
        formDataCompedit.append('cantidadvent', cantidadvent);
        formDataCompedit.append('idcompraproducto', idcompraproducto);
        formDataCompedit.append('subtotalventa', subtotalventa);
        formDataCompedit.append('totalventa', totalventa);
        formDataCompedit.append('idventa', idventa);
        formDataCompedit.append('btnElimitemventa', btnElimitemventa);

        $.ajax({
          url: 'controladores/venta.controlador.php',
          type: 'post',
          data: formDataCompedit,
          contentType: false,
          processData: false,
          success: function(response) {
            console.info(response);
            //    var posOk=response[1];
            //    var posIdpregunta=response[4];
            //    console.info(posIdpregunta);
            if (response == 1) {

              setTimeout(function() {
                location.reload();
              }, 2000);
              swal('EXELENTE', '', 'success');

            } else {
              if (response == 2) {
                setTimeout(function() {}, 2000);
                swal('ERROR', 'El item fue eliminado de la venta, pero no se actualizo correctamente el stock', 'error');
              } else {
                if(response == 3) {
                  setTimeout(function() {}, 2000);
                swal('ERROR', 'El item fue eliminado de la venta, pero no se actualizo correctamente el monto de la venta', 'error');
                } else{
                  if(response == 0){
                    setTimeout(function() {}, 2000);
                    swal('ERROR', 'No se pudo eliminar el item de la venta', 'error');
                  }
                }
                
              }
            }
          }
        });

      } /*FIN DEL ELSE QUE SE EJECTUTA AL CONFIRMAR QUE TODOS LOS CAMPOS FUERON LLENADOS*/
      return false;


    });
  });
</script>