<?php
include_once("modelos/venta.modelo.php");
include_once("modelos/empleado.modelo.php");
include_once("modelos/almacen.modelo.php");
ini_set('date.timezone', 'America/La_Paz');
$fecha = date("Y-m-d");
$hora = date("H:i");
/*$fechaHora=$fecha.' '.$hora;*/
if ($_SESSION["tipo_user"] != "admin") {
  echo '<script>
        window.location="inicio";
      </script>';
}
?>


<!-- <script type="text/javascript" src = " https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"> </script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
 -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Reportes
      <small>Compras</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <!-- <li><a href="#">Examples</a></li> -->
      <li class="active">Reportes</li>
    </ol>
  </section>


  <script type="text/javascript">
    function generatePDF() {
      var doc = new jsPDF();
      var elementHTML = document.querySelector("#titulo").innerHTML;
      var specialElementHandlers = {
        '#elemnetH': function(element, renderer) {
          return true;
        }
      };
      // doc.text(20,20,'hello word');
      // doc.save('documento.pdf');
      doc.fromHTML(
        elementHTML,
        15,
        15, {
          'width': 170,
          'elementHandlers': specialElementHandlers
        }
      );

    }
  </script>


  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <!-- <h3  class="box-title">Reportes De Ventas</h3> -->

        <label>Fecha Inico:</label>
        <input type="date" name="dateInico" id="dateInico" value="<?php echo $fecha ?>">
        <label>Fecha Fin:</label>
        <input type="date" name="dateFinal" id="dateFinal" value="<?php echo $fecha ?>">
        <!-- <label>Almacen:</label>
        <select id="selectalmacen" name="selectalmacen">
          <option value="0">Todos</option>
          <?php
          $objemp = new Almacen();
          $result = $objemp->listarAlmacenActivos();
          while ($fila = mysqli_fetch_object($result)) {
          ?>
            <option value="<?php echo $fila->id_almacen; ?>"><?php echo $fila->nombre_almacen; ?></option>

          <?php
          }
          ?>

        </select> -->
        <button class="btn btn-success btn-xs" onclick="generacionReporte();">Generar</button>
        <button style="float: right;" class="btn btn-danger btn-xs" onclick="window.open('impresiones/tcpdf/pdf/reporte_compras.php')">PDF</button>
        <!--  <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div> -->
      </div>
      <div class="box-body" id="divtablareportes">

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


  <!-- Modal reg cuota-->
  <div id="modalCuota" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">


        <div class="modal-header" style="background: #397bdd; color:white;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Registrar Cuota</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">

            <div class="form-group">
              <div class="input-group">

                <input type="hidden" name="idcompra" id="idcompra" placeholder="id compra">
                <input type="hidden" name="txtmontoCompra" id="txtmontoCompra" placeholder="monto compra">
                <input type="hidden" name="texttipouser" id="texttipouser" value="<?php echo $_SESSION['tipouser'] ?>">
                <input type="hidden" name="textiduser" id="textiduser" value="<?php echo $iduseractual; ?>">

                <div class="form-group">
                  <div class="input-group">
                    <!-- <label>Todos los productos  de venta se eliminaran </label>-->
                    <label>Se agregara un pago a la compra: &nbsp; </label> <label id="labelcodigo"></label> <label>&nbsp;</label>
                  </div>
                </div>

                <div class="form-group">
                  <label>Monto</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    <input type="number" class="form-control input-lx" name="montoCuota" id="montoCuota" placeholder="Ingresar monto" required="">
                  </div>
                </div>


              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary" name="btnsaveCuota" id="btnsaveCuota">Guardar</button>
        </div>

      </div>

    </div>
  </div>
  <!-- /.modal -->


 <!-- Modal Eliminar empleado-->
<div id="modalElimComp" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      
      <div class="modal-header" style="background: #dd4b39; color:white;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Eliminar compra</h4>
      </div>
      <div class="modal-body">
         <div class="box-body">

            <div class="form-group">
              <div class="input-group">
                  <input type="hidden" name="tipo_user_elim" id="tipo_user_elim" value="<?php echo $_SESSION["tipo_user"]; ?>" placeholder="tipo de usuario">
                   <input type="hidden" name="idadmin_elim" id="idadmin_elim" value="<?php echo $iduseractual; ?>">

                  <input type="hidden" name="idcompraelim" id="idcompraelim" placeholder="id compra">
                 

                 
              </div>  
            </div>

           
            <div class="form-group">
              <div class="input-group">
                  <label >Desea eliminar la compra con codigo&nbsp;  </label> <label id="labelcodigoelim"></label> <label>&nbsp;?</label>

              </div>  
            </div>


         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" class="btn btn-primary" name="btnelimcomp" id="btnelimcomp">Eliminar</button>
      </div>
     
    </div>

  </div>
</div>




</div>
<!-- /.content-wrapper -->
<script type="text/javascript">

</script>
<script type='text/javascript'>
  generacionReporte();

  function generacionReporte() {
    var fecha_ini = $('#dateInico').val();
    var fecha_fin = $('#dateFinal').val();
    var idalmacen = $('#selectalmacen').val();

    $('#divtablareportes').load('vistas/modulos/tabla_reporte_compras.php?fini=' + fecha_ini + '&ffin=' + fecha_fin + '&idalmacen=' + idalmacen);
    // alert(response);                                      
  }



  /*===================FUNCION  ===========================================*/
  $(document).ready(function() {
    $("#btnsaveCuota").on('click', function() {
      var idcompraget = 0;
      var formDataelim = new FormData();

      var btnsaveCuota = $('#btnsaveCuota').val();
      var idcompra = $('#idcompra').val();
      var texttipouser = $('#texttipouser').val();
      var textiduser = $('#textiduser').val();
      var montoCuota = $('#montoCuota').val();
      var txtmontoCompra = $('#txtmontoCompra').val();


      if ((idcompra == '')) {
        setTimeout(function() {}, 2000);
        swal('ATENCION', 'No se selecciono la compra', 'warning');


      } else {
        /*cargamos las nueva variables a a los parametros que se enviara al archivo php que registra*/

        formDataelim.append('idcompra', idcompra);
        formDataelim.append('texttipouser', texttipouser);
        formDataelim.append('btnsaveCuota', btnsaveCuota);
        formDataelim.append('textiduser', textiduser);
        formDataelim.append('montoCuota', montoCuota);
        formDataelim.append('txtmontoCompra', txtmontoCompra);
        idcompraget = parseInt(idcompra);
        $.ajax({
          url: 'controladores/cuotaCompra.controlador.php',
          type: 'post',
          data: formDataelim,
          contentType: false,
          processData: false,
          success: function(response) {
            console.info(response);
            //    var posOk=response[1];
            //    var posIdpregunta=response[4];
            //    console.info(posIdpregunta);
            if (response == 1) {
              $('#montoCuota').val('');
              setTimeout(function() {
                location.href = 'impresiones/tcpdf/pdf/nota_compra.php?codcompra=' + idcompraget;
              }, 1000);
              swal('EXELENTE', 'registro correctamente', 'success');

            } else {
              swal('ERROR', 'Intente nuevamente, Sucedio un error ' + response, 'error');

            }
          }
        });

      } /*FIN DEL ELSE QUE SE EJECTUTA AL CONFIRMAR QUE TODOS LOS CAMPOS FUERON LLENADOS*/
      return false;


    });
  });




  /*===================FUNCION QUE LLAMA AL ELIMINAR ===========================================*/
$(document).ready(function() { 
   $("#btnelimcomp").on('click', function() {
  
   var formDataelim = new FormData(); 
   
   var btnelimcomp=$('#btnelimcomp').val();
   var idadmin_elim=$('#idadmin_elim').val();
   var idcompraelim=$('#idcompraelim').val();

   if ( (idcompraelim=='') ) 
   {
     setTimeout(function(){  }, 2000); swal('ATENCION','No se selecciono compra','warning'); 
     
     
   }
   else
   {
     /*cargamos las nueva variables a a los parametros que se enviara al archivo php que registra*/
     formDataelim.append('idcompraelim',idcompraelim);
     formDataelim.append('idadmin_elim',idadmin_elim);
     formDataelim.append('btnelimcomp',btnelimcomp);

      $.ajax({ url: 'controladores/compraNueva.controlador.php', 
               type: 'post', 
               data: formDataelim, 
               contentType: false, 
               processData: false, 
               success: function(response) { 
                console.info(response);
                //    var posOk=response[1];
                //    var posIdpregunta=response[4];
                //    console.info(posIdpregunta);
                  if (response==1) 
                  {
                    
                    setTimeout(function(){ location.href='reporte-compras'; }, 2000); swal('EXELENTE','','success');        
                  }
                  else
                  {
                      if (response==2) 
                      {
                        setTimeout(function(){  }, 2000); swal('ERROR','No se puede eliminar, Hay ventas registradas de esta compra (Nº Lote), primero debe eliminar la venta','error');
                      }
                      else
                      {
                      setTimeout(function(){  }, 2000); swal('ERROR','Intente nuevamente','error');
                      }                 
                  } 
                }
            }); 

      }/*FIN DEL ELSE QUE SE EJECTUTA AL CONFIRMAR QUE TODOS LOS CAMPOS FUERON LLENADOS*/
        return false;


    }); 
  });

  function modalCuota(idcompra, montoCompra) {
    console.log(idcompra, montoCompra);
    $('#idcompra').val(idcompra);
    $('#txtmontoCompra').val(montoCompra);
    $('#labelcodigo').text(idcompra);
  }
</script>