<?php
include_once("modelos/compraProducto.modelo.php");
include_once("modelos/compra.modelo.php");
$datosad = $_SESSION["usuarioAdmin"];
if ($_SESSION["tipo_user"] != "admin") {
  echo '<script>
        window.location="inicio";
      </script>';
}

$idCompra = $_GET["codcompra"];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Detalle
      <small>Compra <?php echo $idCompra; ?> </small>
    </h1>
    <input type="hidden" value="<?php echo $idCompra; ?>" placeholder="id compra">
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
              <th>Cantidad compra</th>
              <th>Stok</th>
              <th>Precio Venta Bs.</th>
              <!-- <th>Precio Venta Fact.</th> -->
              <th>Costo Unitario Bs.</th>
              <th>Valor</th>
              <!-- <th>En Inv.</th> -->
              <th>Editar</th>
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
            $totalCompra = 0;
            $montoaunenInversion = 0;
            $sumaMontoaunenInversion = 0;
            $obj = new Compra();
            $resultado = $obj->nota_Compra($idCompra);
            while ($fila = mysqli_fetch_object($resultado)) {
              $datosLote = $fila->id_compra_producto . "||" .
                $fila->id_producto . "||" .
                $fila->cantidad_compra . "||" .
                $fila->subtotal_compra . "||" .
                $fila->precio_venta_prod . "||" .
                $fila->precio_unit_compra . "||" .
                $fila->id_compra . "||" .
                $fila->monto_compra;


          $montoaunenInversion = ($fila->precio_unit_compra * $fila->stock_actual);
            ?>
              <tr>
                <td><?php echo $fila->id_compra_producto; ?></td>
                <td><?php echo $fila->nombre_producto; ?></td>

                <td><?php echo $fila->descripcion; ?></td>
                <td><?php echo $fila->cantidad_compra; ?></td>
                <td><?php echo $fila->stock_actual; ?></td>

                <td><?php echo $fila->precio_venta_prod; ?></td>


                <td><?php echo ajustarDecimales($fila->precio_unit_compra); ?></td>

                <?php
                /*$costo_float = floatval($fila->precio_unit_compra);
                $montoInvent = $costo_float * $fila->stock_actual;
                $montoDecimal = $montoInvent;*/

                ?>
                <td><?php echo ajustarDecimales($fila->subtotal_compra); ?></td>
                <!-- <td><?php echo number_format((float)$montoaunenInversion, 2, '.', ''); ?> -->
                <td>
                  <div class="btn-group">
                    <?php
                    if ($fila->cantidad_compra == $fila->stock_actual) {
                    ?>
                      <button class="btn btn-warning" data-toggle="modal" data-target="#modaleditarCompra" onclick="CargarinfoCompraEnModal('<?php echo $datosLote ?>')"><i class="fa fa-pencil"></i></button>
                    <?php
                    } else {
                    ?>
                      <button class="btn btn-default"  ><i class="fa fa-pencil"></i></button>
                    <?php
                    }
                    ?>
                  </div>
                </td>

              </tr>
            <?php
              $contador++;
              $totalCompra = $totalCompra + $fila->subtotal_compra;
            }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="7" style="text-align: center;"><b>Total</b> </td>
              <td><b><?php echo  ajustarDecimales($totalCompra); ?></b></td>
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


<!-- Modal para editar compra-->
<div id="modaleditarCompra" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">


      <div class="modal-header" style="background: #f39c12; color:white;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <label class="modal-title">Modificar Lote: <label id="nro_lote"></label>
        </label>
      </div>
      <div class="modal-body">
        <div class="box-body">

          <div class="form-group ">
            <label>Elija producto</label>
            <div class="input-group ">
              <input type="hidden" name="tipo_user_edit" id="tipo_user_edit" value="<?php echo $_SESSION["tipo_user"]; ?>" placeholder="tipo de usuario">
              <input type="hidden" name="idadmin_edit" id="idadmin_edit" value="<?php echo $iduseractual; ?>">
              <input type="hidden" name="idcompraproducto" id="idcompraproducto">
              <input type="hidden" name="idcompra" id="idcompra">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <select class="form-control input-lx" style="width: 100%;" name="selectprodedit" id="selectprodedit">
                <?php
                $obj = new Producto();
                $resultado = $obj->listarProductosActivos();
                while ($filp = mysqli_fetch_object($resultado)) {
                ?>
                  <option value="<?php echo $filp->id_producto; ?>"><?php echo $filp->nombre_producto . " [" . $filp->descripcion . "]"; ?></option>
                <?php
                }

                ?>


              </select>
            </div>
          </div>


          <div class="form-group">
            <label>Cantidad (Cantidad de productos comprados)</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-check"></i></span>
              <input type="number" class="form-control input-lx" min="1" name="nuevoCantidadedit" id="nuevoCantidadedit" placeholder="Ingresar cantidad" required="" onkeyup="calcularCostoPorUnidadEdit();" autocomplete="off">
            </div>
          </div>


          <div class="form-group">
            <label>Costo Total de Compra</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-check"></i></span>
              <input type="number" class="form-control input-lx" min="1" name="costoSubTotalcompraedit" id="costoSubTotalcompraedit" placeholder="Ingresar costo total" required="" onkeyup="calcularCostoPorUnidadEdit();" autocomplete="off">
              <input type="hidden" class="form-control input-lx" min="1" name="totalMontocompra" id="totalMontocompra" placeholder="Monto total" readonly>
              <input type="hidden" class="form-control input-lx" min="1" name="subTotalViejo" id="subTotalViejo" placeholder="sub total viejo" readonly>
            </div>
          </div>


          <div class="form-group">
            <label>Costo de producto por unidad (costo de cada producto)</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa  fa-money"></i></span>
              <input type="text" class="form-control input-lx" name="nuevoCostoCompedit" id="nuevoCostoCompedit" placeholder="Costo de producto por unidad" required="" readonly="" autocomplete="off">
            </div>
          </div>


          <div class="form-group">
            <label>Precio de venta del producto (precio para la venta)</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa  fa-money"></i></span>
              <input type="text" class="form-control input-lx" name="nuevoCostoVentaedit" id="nuevoCostoVentaedit" placeholder="Ingresar costo de venta" required="" autocomplete="off">
            </div>
          </div>


          <script type="text/javascript">
            function calcularCostoPorUnidadEdit() {
              var costoPorUnidad = 0;
              var cantidadItem = document.getElementById('nuevoCantidadedit').value;
              var costoTotalCompras = document.getElementById('costoSubTotalcompraedit').value;
              if (cantidadItem != '' & costoTotalCompras != '') {
                costoPorUnidad = (costoTotalCompras) / (cantidadItem);
                document.getElementById('nuevoCostoCompedit').value = costoPorUnidad
                // alert(costoPorUnidad);
              }

            }
          </script>

          <!--<div class="form-group">
              <label>Costo de producto por unidad (Facturado)</label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="fa  fa-money"></i></span>
                 <input type="text" class="form-control input-lx" name="nuevoCostoCompFactedit" id="nuevoCostoCompFactedit" placeholder="Ingresar costo de producto facturada" required="" >
              </div>  
            </div>-->



        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" class="btn btn-primary" name="btnguardarCompraEdit" id="btnguardarCompraEdit">Guardar</button>
      </div>

    </div>

  </div>
</div>


<script>
  function CargarinfoCompraEnModal(datoscomp) {

    f = datoscomp.split('||');
    $('#nro_lote').text(f[0]);
    $('#idcompraproducto').val(f[0]);
    $('#selectprodedit').val(f[1]);
    $('#nuevoCantidadedit').val(f[2]);
    $('#costoSubTotalcompraedit').val(f[3]);
    $('#nuevoCostoVentaedit').val(f[4]);
    $('#nuevoCostoCompedit').val(f[5]);
    $('#idcompra').val(f[6]);
    $('#totalMontocompra').val(f[7]);
    $('#subTotalViejo').val(f[3]);

  }

  /********ACTUALIZAR UNA COMPRA***********/
  $(document).ready(function() {
    $("#btnguardarCompraEdit").on('click', function() {

      var formDataCompedit = new FormData();

      var btnguardarCompraEdit = $('#btnguardarCompraEdit').val();
      var idadmin_edit = $('#idadmin_edit').val();
      var tipo_user_edit = $('#tipo_user_edit').val();
      var idcompraproducto = $('#idcompraproducto').val();
      var selectprodedit = $('#selectprodedit').val();
      var nuevoCantidadedit = $('#nuevoCantidadedit').val();
      var costoSubTotalcompraedit = $('#costoSubTotalcompraedit').val();
      var nuevoCostoVentaedit = $('#nuevoCostoVentaedit').val();
      var nuevoCostoCompedit = $('#nuevoCostoCompedit').val();
      var idcompra = $('#idcompra').val();
      var totalMontocompra = $('#totalMontocompra').val();
      var subTotalViejo = $('#subTotalViejo').val();



      if ((selectprodedit == '') ||
        (idcompraproducto == '') ||
        (nuevoCantidadedit == '') ||
        (costoSubTotalcompraedit == '') ||
        (nuevoCostoVentaedit == '') ||
        (nuevoCostoCompedit == '') ||
        (idcompra == '')
      ) {
        setTimeout(function() {}, 2000);
        swal('ATENCION', 'Deve de completar todos los campos', 'warning');


      } else {
        formDataCompedit.append('idadmin_edit', idadmin_edit);
        formDataCompedit.append('tipo_user_edit', tipo_user_edit);

        formDataCompedit.append('idcompraproducto', idcompraproducto);
        formDataCompedit.append('selectprodedit', selectprodedit);
        formDataCompedit.append('nuevoCantidadedit', nuevoCantidadedit);
        formDataCompedit.append('costoSubTotalcompraedit', costoSubTotalcompraedit);
        formDataCompedit.append('nuevoCostoVentaedit', nuevoCostoVentaedit);
        formDataCompedit.append('nuevoCostoCompedit', nuevoCostoCompedit);
        formDataCompedit.append('idcompra', idcompra);
        formDataCompedit.append('totalMontocompra', totalMontocompra);
        formDataCompedit.append('subTotalViejo', subTotalViejo);
        formDataCompedit.append('btnguardarCompraEdit', btnguardarCompraEdit);

        $.ajax({
          url: 'controladores/compra.controlador.php',
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
                swal('ERROR', 'No se puede actualizar, Hay ventas registradas de esta compra (Nº Lote), primero debe eliminar la venta', 'error');
              } else {
                setTimeout(function() {}, 2000);
                swal('ERROR', 'Intente nuevamente', 'error');
              }
            }
          }
        });

      } /*FIN DEL ELSE QUE SE EJECTUTA AL CONFIRMAR QUE TODOS LOS CAMPOS FUERON LLENADOS*/
      return false;


    });
  });
</script>