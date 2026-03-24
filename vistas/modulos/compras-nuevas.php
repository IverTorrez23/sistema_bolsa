<?php
$_SESSION['arrayIdprodCompra'] = array();
include_once("modelos/productos.modelo.php");
include_once("modelos/marca.modelo.php");
include_once("modelos/categorias.modelo.php");
include_once("modelos/clientes.modelo.php");
include_once("modelos/compraProducto.modelo.php");
include_once("modelos/proveedor.modelo.php");
date_default_timezone_set('America/La_Paz');
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Lista de Productos
      <input type="hidden" name="texttipouser" id="texttipouser" value="<?php echo $_SESSION['tipouser'] ?>">
      <input type="hidden" name="textiduser" id="textiduser" value="<?php echo $iduseractual; ?>">

    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <!-- <li><a href="#">Examples</a></li> -->
      <li class="active">Lista de Productos</li>
    </ol>
  </section>


  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div style="height: 270px;border: 1px solid #85929E; overflow: scroll; ">
      <div class="box">
        <!--  <div class="box-header with-border">
          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
            Agregar producto
          </button>
        </div> -->

        <div class="box-body">
          <table class="table table-bordered table-striped tablas tablaProductos">
            <thead>
              <tr>

                <th>Nombre</th>
                <th>Medidas</th>
                <!-- <th style="width: 2%;">Cantidad</th> -->

                <th>Accion</th>
                <!-- <th>Acciones</th> -->

              </tr>
            </thead>

            <tbody>
              <?php
              $contador = 1;
              $obj = new Producto();
              $resultado = $obj->listarProductosActivos();
              while ($fila = mysqli_fetch_object($resultado)) {
                $datosProd = $fila->idcompraprod . "||" .
                  $fila->mombreProducto . "||" .
                  $fila->codigoProducto . "||" .
                  $fila->prodDescripsion . "||" .
                  $fila->marcaProd . "||" .
                  $fila->catProd . "||" .
                  $fila->stokactual . "||" .
                  $fila->precioVentaprod . "||" .
                  $fila->precioVentaprodFact;



              ?>
                <tr>

                  <td><?php echo $fila->nombre_producto; ?></td>
                  <td><?php echo $fila->descripcion; ?></td>

                  <!-- <td><input type="number" id="<?php echo $fila->id_producto; ?>" name="<?php echo $fila->id_producto; ?>" placeholder="Cantidad"></td> -->
                  <td><button class="btn btn-success btn-xs agregarProducto recuperarBoton" data-id="<?php echo $fila->id_producto ?>">Agregar <i class="fa  fa-plus"></i> </button> </td>

                </tr>
              <?php
                $contador++;
              }
              ?>
            </tbody>
          </table>
        </div>

        <!-- /.box-body -->
        <div class="box-footer">
          Footer
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.div scrool -->
    <div class="form-group" style="width: 50%;">
      <label>Seleccione proveedor</label>
      <div class="input-group">
        <span class="input-group-addon"><i class="fa  fa-user"></i></span>
        <select class="form-control select2 input-lx" name="selectprov" id="selectprov">
          <option value="0">Sin nombre</option>
          <?php
          $objprov = new Proveedor();
          $resultadoprov = $objprov->listarProveedoresActivos();
          while ($filcli = mysqli_fetch_object($resultadoprov)) {
          ?>
            <option value="<?php echo $filcli->id_proveedor; ?>"><?php echo $filcli->nombre_proveedor . ' ' . $filcli->apellido_proveedor; ?></option>
          <?php
          }
          ?>

        </select>

      </div>

    </div>

    <div class="checkbox">
      <label>
        <input type="checkbox" name="checkCredit" id="checkCredit"> Compra a credito
      </label>
    </div>

    <div class="">
      <label>
        Fecha Compra: 
        <input type="date" class="form-control" name="fechaCompra" id="fechaCompra" value="<?php echo date('Y-m-d'); ?>">
      </label>
    </div>
    <!--<small>CODIGO:</small>  <input type="text" name="" id="miInput" value="" autofocus placeholder="CODIGO PRODUCTO">
    <div class="form-group row " id="" > -->


    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Productos </h3>

            <!-- <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div> -->
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover formularioVenta" border="1">
              <tr style="background: #d2d6de">
                <th style="width:40%;">Producto</th>
                <!-- <th>Codigo</th> -->

                <!-- <th>Precio Facturado</th> -->
                <!-- <th style="width:7%;">Identificador</th> -->
                <th style="width:7%;">Cantidad</th>
                <th style="width:7%;">Costo de Compra</th>
                <th style="width:7%;">(Costo unitario)</th>
                <th style="width:7%;">Precio de venta</th>

                <th>Opcion</th>
              </tr>
              <tbody id="nuevoProducto" class="nuevoProducto">
                <!-- <tr>
                  <td>Adaptador wifi</td>
                  <td>KP-AW11</td>
                  <td>50</td>
                  <td>45</td>
                  <td><input type="number" name="" min="1" value="1" value=""> </td>
                  <td><input type="text" name="" value=""> </td>
                  <td><button class="btn btn-danger btn-xs">Eliminar</button></td>
                </tr>
                <tr>
                  <td>Adaptador wifi</td>
                  <td>KP-AW11</td>
                  <td>50</td>
                  <td>45</td>
                  <td><input type="number" name="" min="1" value="1"> </td>
                  <td><input type="text" name="" value=""> </td>
                  <td><button class="btn btn-danger btn-xs">Eliminar</button></td>
                </tr> -->




              </tbody>
              <tfoot>
                <tr>
                  <td colspan="2" style="text-align: center; "><b> Total</b></td>
                  <td><input type="text" id="totalMontoCompra" name="totalMontoCompra" readonly=""></td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col-xs-12 -->
    </div>
    <!-- /.row -->
     <div class="contenedor-cuota" style="float: right; display: none;">
     Cuota: <input type="text" id="cuotaCompra" name="cuotaCompra" style="float: right;" autocomplete="off">
     </div><br><br>
     
    <button class="btn btn-primary" id="btnguardarCompra" name="btnguardarCompra" style="float: right;display: none;">Guardar compra</button>

    <br>
</div>
<!-- /div nuevoProducto -->


</section>
<!-- /.content -->

</div>
<!-- /.content-wrapper -->


<!--MODAL PARA AGREGAR USUSARIOS -->


<!-- CODIGO QUE SE EJECUTA AL AGREGAR PRODUCTO A LA VENTA -->

<script type="text/javascript">

  window.addEventListener('pageshow', function(event) {
    // Si event.persisted es true, significa que la página viene de la caché
    if (event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2)) {
        window.location.reload();
    }
});


  var arrayIdproductosVenta = [];
  var arrayPrecioOficialProducto = [];
  var arrayPrecioOficialFacturadoProducto = [];
  $(".tablaProductos").on("click", "button.agregarProducto", function() {

    var id = $(this).attr("data-id"); /*obtenemos el dato que cargamos en el campo 'data-id'  */
    let cantidad_ingresada_venta = $("#" + id + "").val(); //obtenemos la cantidad ingresada en el listado, antes de oprimir el boton Agregar
    $("#" + id + "").val(''); //dejamos vacio el campo de cantidad ingresada por el clientes antes de presionar Agregar
    //console.log(id);  
    //$(this).removeClass("btn-success agregarProducto");// se elimina la clase para que ya no pueda agregar el mismo producto
    //$(this).addClass("btn-defaul");//se le da el color por defecto con una clase nueva
    generarTablaDetalleVenta(id, cantidad_ingresada_venta)

  });

  async function generarTablaDetalleVenta(id, cantidaIngresada) {
    try {

      $.ajax({
        url: "ajax/cargartablaProductoCompra.php",
        data: {
          "idcompraprod": id
        },
        type: "POST",
        cache: false,
        dataType: "json",
        success: function(respuesta) {
          console.log(respuesta)
          let id_producto = respuesta["id_producto"];
          let nombre_producto = respuesta["nombre_producto"];
          let descripcion = respuesta["descripcion"];
          let codigo_producto = respuesta["codProduct"];
          let precio_venta = respuesta["precioVentaprod"];
          let precio_facturado = respuesta["precioVentaProdFact"];
          let stockTotal = respuesta["stockActual"];
          let cantidad_ingresada = cantidaIngresada; //obtenemos la cantidad ingresada en el listado, antes de oprimir el boton Agregar
          //PREGUNTAMOS SI EL PRODUCTO SELECCIONADO NO EXISTE EN EL ARRAY
          if (!arrayIdproductosVenta.includes(id)) {
            $("#btnguardarCompra").css("display", "block"); /*mostramos el boton guardar venta*/

            // CARGAMOS EL ID DEL PRODUCTO AL ARRAYDE PRODUCTOS SELECCIONADOS

            //PREGUNTAMOS SI EL NUEVO ID YA ESTA EN EL ARRAYIDPRODUCTOSVENTA
            if (arrayIdproductosVenta.includes(id)) {
              //SI EL PRODUCTO SELECCIONADO YA EXISTE EN LA LISTA DE PRODUCTOS PARA LA VENTA ACTUAL, SOLO AUMENTAMOS LA CANTIDAD
              var cantidad_anterior = $("#txtcantidad" + id + "").val(); //obtenemos la cantidad que anteriormente selecciono para sumarle la nueva cantidad
              var cantidad_sumada = parseInt(cantidad_anterior) + parseInt(cantidad_ingresada); //sumamos la cantidad anterior mas la cantidad ingresada
              $("#txtcantidad" + id + "").val(cantidad_sumada);
              calculosubtotal(cantidad_sumada, id, precio_venta, stockTotal, ""); //hacemos el calculo con la cantidad sumada
            } else //por falso, crea una nueva fila para el producto
            {
              //creamos una constante para obtener de manera aleatoria del rango de 1 al 10
              const randomNumberInRange = (min, max) =>
                Math.floor(Math.random() * (max - min)) + min;
              var numberrandon = randomNumberInRange(1, 10);
              var colorfila = '';
              switch (numberrandon) {
                case 1:
                  colorfila = '#F68B86';
                  break;
                case 2:
                  colorfila = '#FFA130';
                  break;
                case 3:
                  colorfila = '#FFF230';
                  break;
                case 4:
                  colorfila = '#CDFF5A';
                  break;
                case 5:
                  colorfila = '#5AFFA3';
                  break;
                case 6:
                  colorfila = '#5AFFEB';
                  break;
                case 7:
                  colorfila = '#5F99FF';
                  break;
                case 8:
                  colorfila = '#FF84F6';
                  break;
                case 9:
                  colorfila = '#FFBDBD';
                  break;
                case 10:
                  colorfila = '#D2D0D1';
                  break;
                default:
                  colorfila = '';
              }

              arrayIdproductosVenta.push(id);
              arrayPrecioOficialProducto.push(precio_venta); /*cargamos en un array los precios oficiales de los productos*/
              arrayPrecioOficialFacturadoProducto.push(precio_facturado); /*cargamos en un array los precios oficiales facturados de los productos*/
              $(".nuevoProducto").append(

                '<tr style="background:' + colorfila + ';">' +
                '<!--producto-->' +

                '<td>' + nombre_producto + ' - ' + descripcion + ' </td>' +


                //Cantidad comprada
                '<td> <input type="number" id="txtcantidad' + id + '" oninput="calculosubtotal(this.value,' + id + ',' + precio_venta + ',' + stockTotal + ',\'cantidad\')" name="nuevaCantidadProducto" min="1" value="' + cantidad_ingresada + '"  class="nuevaCantidadProducto" idcompraprod="nuevaCantidadProducto"> </td> ' +
                ///Costo total de la compra del producto
                '<td> <input type="number" id="txtSubTotal' + id + '" oninput="calculosubtotal(this.value,' + id + ',' + precio_venta + ',' + stockTotal + ',\'subTotal\')" name="nuevaCantidadProducto" min="1" value="' + cantidad_ingresada + '"  class="nuevaCantidadProducto nuevoPrecio" idcompraprod="nuevaCantidadProducto"> </td> ' +
                //Costo de producto por unidad
                '<td> <input type="text" readonly name="preciovendido" id="txtcostoUnidad' + id + '" oninput="sumarTotalPrecio()" precioReal="' + precio_venta + '" value="' + precio_venta + '" > </td>' +

                ///Precio para la venta
                '<td> <input type="number" id="txtprecioVenta' + id + '"  name="nuevaCantidadProducto" min="1" value="' + cantidad_ingresada + '"  class="nuevaCantidadProducto" idcompraprod="nuevaCantidadProducto"> </td> ' +

                '<td><button class="btn btn-danger btn-xs quitarProducto" idcompraprod="' + id + '" precioOficialProd="' + precio_venta + '" precioOficialFactProd="' + precio_facturado + '">Eliminar</button></td>' +

                '</tr>')
              calculosubtotal(cantidad_ingresada, id, precio_venta, stockTotal, ""); //hacemos el calculo con la cantidad ingresada
              sumarTotalPrecio();
              console.log('arrary', arrayIdproductosVenta)
            } /*FIN DEL ELSE CUANDO NO EXISTE EL ID EN EL ARRAYIDPRODUCTOSVENTA*/
          } //fin cuando se la cabtidad ingresada es mayor a cero
          else {
            swal({
              title: "El producto ya fue seleccionado",
              text: "",
              type: "error",
              confirmButtonText: "¡Cerrar!"
            });
          }

        } //fin del success

      })

    } catch (error) {
      console.error("Error:", error);
    }
  }





  /*============================================================
  =            quitar producto y recuperar el boton            =
  ============================================================*/
  var idQuitarProducto = [];
  localStorage.removeItem("quitarProducto"); /*ELIMINA DEL LOCALSTORAGE LOS ID AL CARGAR LA PAGINA*/
  $(".formularioVenta").on("click", "button.quitarProducto", function() {
    //console.log("boton");
    $(this).parent().parent().remove(); // se elimnina el registro del la tabla producto
    let idcompraprod = $(this).attr("idcompraprod"); //obtnemos el idProducto de la clase quitarproducto boton eliinar

    let precOfiProd = $(this).attr("precioOficialProd") /*obtenemos el precio del producto*/
    let precOfiFactProd = $(this).attr("precioOficialFactProd") /*obtenemos el precio facturado del producto*/
    //console.log(idcompraprod);
    // ELIMINA EL ID DEL ARRAY DE PRODUCTOS PARA LA VENTA
    var i = arrayIdproductosVenta.indexOf(idcompraprod);
    arrayIdproductosVenta.splice(i, 1);

    /*Eliminamos el precio del producto del array de precios de producto*/
    var j = arrayPrecioOficialProducto.indexOf(precOfiProd);
    arrayPrecioOficialProducto.splice(j, 1);

    /*Eliminamos el precio facturado del producto del array de precios de producto facturado*/
    var k = arrayPrecioOficialFacturadoProducto.indexOf(precOfiFactProd);
    arrayPrecioOficialFacturadoProducto.splice(k, 1);

    //  console.log(arrayPrecioOficialFacturadoProducto); 



    //ALMACENA EN EL LOCALSTORAGE EL ID DEL PRODUCTO A QUITAR

    if (localStorage.getItem("quitarProducto") == null) {
      idQuitarProducto = []; // sie le local storag viene vacio se crea un array
    } else {
      idQuitarProducto.concat(localStorage.getItem("quitarProducto"));
    }

    idQuitarProducto.push({
      "idcompraprod": idcompraprod
    });
    localStorage.setItem("quitarProducto", JSON.stringify(idQuitarProducto)); // se carga el local storage y se convierte a string el array idquitarProducto

    $("button.recuperarBoton[data-id='" + idcompraprod + "']").removeClass('btn-defaul');
    $("button.recuperarBoton[data-id='" + idcompraprod + "']").addClass('btn-success agregarProducto');

    if ($(".nuevoProducto").children().length == 0) { //preguntamos si es que no queda reagregarProductogistro en la tabla producto
      $("#totalMontoCompra").val(0); //si esque no existe el total de venta va ser 0
      $("#btnguardarCompra").css("display", "none"); /*ocultamos el boton guardar venta*/
    } else {
      //y si hay registro en la tabla producto entonces va llamar a la funcion para calcular el total de ventas
      sumarTotalPrecio();
    }

  });
  /*=====  End of quitar producto y recuperar el boton  ======*/




  /*============================================================
   =           Cuando cargue la tabla cada vez que se navegue en la tabla          =
   ============================================================*/
  $(".tablaProductos").on("draw.dt", function() {
    //console.log("tabla");
    if (localStorage.getItem("quitarProducto") != null) {
      var listaIdProductos = JSON.parse(localStorage.getItem("quitarProducto"));
      //console.log(listaIdProductos);
      for (var i = 0; i < listaIdProductos.length; i++) {
        // console.log(listaIdProductos);
        //console.log(listaIdProductos[i]["idcompraprod"]);
        //console.log(listaIdProductos.length);
        $("button.recuperarBoton[data-id='" + listaIdProductos[i]["idcompraprod"] + "']").removeClass('btn-defaul');
        $("button.recuperarBoton[data-id='" + listaIdProductos[i]["idcompraprod"] + "']").addClass('btn-success agregarProducto');
      }
    }
  })





  /*=============================================
  =            modificar la cantidad            =
  =============================================*/

  $(".formularioVenta").on("change", "input.nuevaCantidadProducto", function() {
    var precio = $(this).parent(".nuevoPrecio");
    //var precioFinal=$(this).val()*precio.attr("precioReal");//multiplicamos la cantidad por el precio
    //  console.log("precio");

    //precio.val(precioFinal)
    //sumarTotalPrecio();
  });

  /*=====  End of modificar la cantidad  ======*/








  /*===============================================
  =            sumar todos los precios            =
  ===============================================*/

  function sumarTotalPrecio() {
    var precioItem = $(".nuevoPrecio");
    var arraySumaPrecio = [];
    for (var i = 0; i < precioItem.length; i++) {
      arraySumaPrecio.push(Number($(precioItem[i]).val()));
    }

    function sumarArrayPrecios(total, numero) {
      return total + numero;
    }
    var sumaTotalPrecios = arraySumaPrecio.reduce(sumarArrayPrecios);

    $("#totalMontoCompra").val(sumaTotalPrecios.toFixed(2)); /*CARGA EL VALOR DE LA SUMA TOTAL CON DECIMAL*/

  }




  /*=====  End of sumar todos los precios  ======*/
  function sumaTotalventa() {
    var montoTotal = 0;
    montoTotal = montoTotal + document.getElementById("preciovendido").value;
    $("#totalMontoCompra").val(montoTotal);
  }
</script>
<script type="text/javascript">
  function calculosubtotal(valor, idprod, precVenta, stockTotal, tipoDato) {
    console.log('datos', valor, idprod, precVenta, stockTotal, tipoDato);
    var idtextCostoUnidad = "txtcostoUnidad" + idprod;
    if (tipoDato === "cantidad") {
      var idtextSubTotalP = "txtSubTotal" + idprod;
      var valorSubtotal = $("#" + idtextSubTotalP + "").val();
      var costoUnidad = (valorSubtotal / valor);
      $("#" + idtextCostoUnidad + "").val(costoUnidad);

    }
    if (tipoDato === "subTotal") {
      var idtextcantidad = "txtcantidad" + idprod;
      var valorCantidad = $("#" + idtextcantidad + "").val();
      var costoUnidad = (valor / valorCantidad);
      $("#" + idtextCostoUnidad + "").val(costoUnidad);
    }
    sumarTotalPrecio()


    var idtextcantidad = "txtcantidad" + idprod;
    var idtextSubTotalP = "txtSubTotal" + idprod;
    var idtextCostoUnidad = "txtcostoUnidad" + idprod;
    var valorSubtotal = $("#" + idtextSubTotalP + "").val();
    console.log('valor sub', valorSubtotal);
    var costoUnidad = (valorSubtotal / valor);

    // $("#"+idtextCostoUnidad+"").val(costoUnidad.toFixed(2));

    var idtextsubtotal = "precVenta" + idprod; /*obtenemos el id del input subtotal*/
    var subtotalprod = valor * precVenta;
    if (parseInt(valor) <= parseInt(stockTotal)) /*preguntamos si la cantidad que vendera no supera el stock*/ {
      $("#" + idtextsubtotal + "").val(subtotalprod.toFixed(2));

      sumarTotalPrecio(); /*FUNCION QUE SUMA EL MONTO TOTAL DE LA VENTA*/
    } else {
      // $("#"+idtextcantidad+"").val(1);/*la cantidad se vuelve 1 porque escogio cantidad fuera de stock*/
      // subtotalprod=precVenta*1;/*hacemos el calculo multiplicado por uno*/
      // $("#"+idtextsubtotal+"").val(subtotalprod.toFixed(2));/*ponemos el sobtotal multiplicado por uno*/
      // sumarTotalPrecio();/*FUNCION QUE SUMA EL MONTO TOTAL DE LA VENTA*/

      /*swal({
           title:"La cantidad supera el Stock",
           text: "¡Solo hay "+stockTotal+" unidades!",
           type: "error",
           confirmButtonText: "¡Cerrar!"
      });*/

    }
  }

  // function funcionsubtotal(subtotal)
  // {
  //  alert(subtotal);
  // }

  /* function ejecutarfunciondedecalculo()
   {
     // txtcantidad.oninput = function() {
     // var cantidadprod=txtcantidad.value;
     //  };
   preciovendido.oninput = function() {
     console.log(preciovendido.value);
   };
   }*/
  //Tickeo al checkbox
 $(document).ready(function() {
  
  $("#checkCredit").on("change", function() {
    $("#cuotaCompra").val(0);
    if ($(this).is(":checked")) {
        $(".contenedor-cuota").fadeIn(); // Aparece suavemente
    } else {
        $(".contenedor-cuota").fadeOut(); // Desaparece suavemente
    }
});
 });

  /*=============================FUNCION QUE GUARDA LA compra=================================*/
  $(document).ready(function() {
    $("#btnguardarCompra").on('click', async function() {
      var idcompraget = 0;
      var formDataVenta = new FormData();
      var totaldeprodventa = arrayIdproductosVenta.length;
      var btnguardarCompra = $('#btnguardarCompra').val();
      var selectprov = $('#selectprov').val();
      var montoCompratotal = $("#totalMontoCompra").val();
      var textiduser = $("#textiduser").val();
      var texttipouser = $("#texttipouser").val();
      var cuotaCompra = $("#cuotaCompra").val();
      var fechaCompra = $("#fechaCompra").val();

      var switchCredit = 0;

      /* preguntamos si los id son menor a cero(osea si no hay producto seleccionado para la venta)*/
      if ((totaldeprodventa < 0)) {
        setTimeout(function() {}, 2000);
        swal('ATENCION', 'No hay productos seleccionados, selecione uno', 'warning');


      } else {
        var checkCredit = document.getElementById("checkCredit").checked
        if (checkCredit == true) {
          switchCredit = 1;
          //Destikeamos el checkbox
          const checkbox = document.getElementById('checkCredit');
          checkbox.checked = false;
        } else {
          switchCredit = 0;
        }
        $('#selectprov').val(0);
        formDataVenta.append('btnguardarCompra', btnguardarCompra);
        formDataVenta.append('montoCompratotal', montoCompratotal);
        formDataVenta.append('textiduser', textiduser);
        formDataVenta.append('selectprov', selectprov);
        formDataVenta.append('texttipouser', texttipouser);
        formDataVenta.append('switchCredit', switchCredit);
        formDataVenta.append('cuotaCompra', cuotaCompra);
        formDataVenta.append('fechaCompra', fechaCompra);
        $.ajax({
          url: 'controladores/compraNueva.controlador.php',
          type: 'post',
          data: formDataVenta,
          contentType: false,
          processData: false,
          success: function(response) {
            //console.info(response);
            /*PREGUNTAMOS SI LA RESPUESTA ES MAYOR A CERO, OSEA ES EL ID DE LA compra, SE REGISTRA EL DETALLE compra*/
            if (response > 0) {
              var contadorid = 0;
              var idcompra = response;
              idcompraget = parseInt(idcompra);
              while (contadorid < totaldeprodventa) {
                console.log('llego', arrayIdproductosVenta)
                var idtextcantidadprod = "txtcantidad" + arrayIdproductosVenta[contadorid]; /*obtenemos el id del txt cantidad*/
                var subTotalCompra = "txtSubTotal" + arrayIdproductosVenta[contadorid];
                var costoUnidad = "txtcostoUnidad" + arrayIdproductosVenta[contadorid];
                var precioVenta = "txtprecioVenta" + arrayIdproductosVenta[contadorid];
                // var idtextsubtotalprod = "precVenta" + arrayIdproductosVenta[contadorid]; /*obtenemos el id del txt subtotal venta*/

                var idproducto = arrayIdproductosVenta[contadorid];
                var subTotalCompra = $("#" + subTotalCompra + "").val();
                var idtextcantidadprod = $("#" + idtextcantidadprod + "").val();
                var costoUnidad = $("#" + costoUnidad + "").val();
                var textPrecioVenta = $("#" + precioVenta + "").val();
                var formDataVentaProd = new FormData();

                formDataVentaProd.append('idproducto', idproducto);
                formDataVentaProd.append('subTotalCompra', subTotalCompra);
                formDataVentaProd.append('idtextcantidadprod', idtextcantidadprod);
                formDataVentaProd.append('costoUnidad', costoUnidad);
                formDataVentaProd.append('idcompra', idcompra);
                formDataVentaProd.append('textPrecioVenta', textPrecioVenta);

                $.ajax({
                  url: 'controladores/compraProducto.controlador.php',
                  type: 'post',
                  data: formDataVentaProd,
                  contentType: false,
                  processData: false,
                  success: function(response) {
                    //console.info(response);

                    // if (response==1) 
                    // {

                    //   setTimeout(function(){ location.href='marcas'; }, 2000); swal('EXELENTE','','success'); 

                    // }
                    // else
                    // {
                    //   setTimeout(function(){  }, 2000); swal('ERROR','Intente nuevamente','error');

                    // } 
                  }
                });

                contadorid++;
              } /*fin del while*/

              resetearTodo()
              setTimeout(function() {

                location.href = 'impresiones/tcpdf/pdf/nota_compra.php?codcompra=' + idcompraget;
              }, 1000);
              swal('EXELENTE', '', 'success');
            } /*FIN DEL IF QUE INSERTA EL DETALLE DE VENTA*/
            else {
              setTimeout(function() {}, 2000);
              swal('ERROR', 'Intente nuevamente', 'error');

            }
          }
        });

      } /*FIN DEL ELSE QUE SE EJECTUTA AL CONFIRMAR QUE TODOS LOS CAMPOS FUERON LLENADOS*/
      return false;


    });
  });
  /*=========================================================================================*/
  /*========================================================================================*/




  /*=========================================================================================
  FUNCION QUE COMPRUEBA SI LOS PRODUCTOS SE ESTAN VENDIENDO CON EL PRECIO CORRECTO
  =========================================================================================*/

  function comprobarPrecioCorectoEnVenta() {
    var contadorPrecioMal = 0;
    var totaldeprodventa = arrayIdproductosVenta.length;
    var contadoridP = 0;

    while (contadoridP < totaldeprodventa) {
      var idprodventa = arrayIdproductosVenta[contadoridP]; /*obtenemos el id del producto en venta*/
      var idtextidentificado = "txtidenti" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt identificador*/
      var idtextcantidadprod = "txtcantidad" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt cantidad*/
      var idtextsubtotalprod = "precVenta" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt subtotal venta*/
      var idtextprecioprod = "txtprecioProd" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt precio del producto venta*/
      var idtextprecioprodFact = "txtprecioProdFact" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt precio del producto venta facturado*/

      var precioRealVentaProd = $("#" + idtextprecioprod + "").val(); /*obtenemos el precio del producto*/
      var precioRealVentaProdFact = $("#" + idtextprecioprodFact + "").val(); /*obtenemos el precio del producto facturado*/
      var cantidaddeProd = $("#" + idtextcantidadprod + "").val();
      var subtotalDeProd = $("#" + idtextsubtotalprod + "").val();
      var subtotalFloat = parseFloat(subtotalDeProd);
      var cantidadEntero = parseInt(cantidaddeProd);
      var precioRealVentaProdFloat = parseFloat(precioRealVentaProd);
      if (cantidadEntero > 0) /*preguntamos si cantidad es mayor a cero*/ {
        var precioUnitarioDeProd = parseFloat((subtotalFloat) / (cantidadEntero));
        if (precioUnitarioDeProd >= precioRealVentaProdFloat) {

        } else {
          return idprodventa; /*devolvemos el id del producto que se esta vendiendo a menor precio*/
        }
      } /*fin del if que pregunta si cantidad es mayor a cero*/
      else {
        var cantidadError = "error";
        return cantidadError;

      }
      contadoridP++;
    } /*fin del while*/
  }




  function comprobarPrecioCorectoEnVentaFacturada() {
    var contadorPrecioMal = 0;
    var totaldeprodventa = arrayIdproductosVenta.length;
    var contadoridP = 0;

    while (contadoridP < totaldeprodventa) {
      var idprodventa = arrayIdproductosVenta[contadoridP]; /*obtenemos el id del producto en venta*/
      var idtextidentificado = "txtidenti" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt identificador*/
      var idtextcantidadprod = "txtcantidad" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt cantidad*/
      var idtextsubtotalprod = "precVenta" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt subtotal venta*/
      var idtextprecioprod = "txtprecioProd" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt precio del producto venta*/
      var idtextprecioprodFact = "txtprecioProdFact" + arrayIdproductosVenta[contadoridP]; /*obtenemos el id del txt precio del producto venta facturado*/

      var precioRealVentaProd = $("#" + idtextprecioprod + "").val(); /*obtenemos el precio del producto*/
      var precioRealVentaProdFact = $("#" + idtextprecioprodFact + "").val(); /*obtenemos el precio del producto facturado*/
      var cantidaddeProd = $("#" + idtextcantidadprod + "").val();
      var subtotalDeProd = $("#" + idtextsubtotalprod + "").val();
      var subtotalFloat = parseFloat(subtotalDeProd);
      var cantidadEntero = parseInt(cantidaddeProd);
      var precioRealVentaProdFacturadaFloat = parseFloat(precioRealVentaProdFact);

      var precioUnitarioDeProd = parseFloat((subtotalFloat) / (cantidadEntero));
      if (precioUnitarioDeProd >= precioRealVentaProdFacturadaFloat) {

      } else {
        return idprodventa; /*devolvemos el id del producto que se esta vendiendo a menor precio*/
      }

      contadoridP++;
    } /*fin del while*/
  }

  /*=========================================================================================
  FIN DE FUNCION QUE COMPRUEBA SI LOS PRODUCTOS SE ESTAN VENDIENDO CON EL PRECIO CORRECTO
  =========================================================================================*/
  const miInput = document.getElementById('miInput');

  miInput.addEventListener('input', function(event) {
    // Acción a realizar cuando cambian los valores en el input
    if (miInput.value.length === 13) {
      obtenerDatosCompraPorCodigoBarra(miInput.value)
      /*console.log('El valor del input ha cambiado:', miInput.value);
      var codigoBarra=miInput.value;
      $.ajax({
       url:"ajax/obtenerCompraProductoCodigoBarra.php",
       data:{"codigoBarra":codigoBarra},
       method:"GET",
       cache: false,
       dataType:"json",
       success:function(respuesta){
        console.log(respuesta);
        if(respuesta>1){
          limpiarInputCodigoProducto();
          return swal({
                           title:"Mas de una compra registrada",
                            text: "Este producto tiene mas de una compra registrada, seleccione manualmente el producto para la venta",
                            type: "warning",
                            confirmButtonText: "¡Cerrar!"
                            });
            
        }
        else{ 
           if(respuesta==0){
            limpiarInputCodigoProducto();
              return swal({
                           title:"Stock vacio",
                            text: "Este producto tiene stock vacio",
                            type: "warning",
                            confirmButtonText: "¡Cerrar!"
                            });
                            
              }
              else{
                console.log('se cargara a la tabla');
                limpiarInputCodigoProducto();
              }

        }
       
      }
     })*/

    }


  });

  async function obtenerDatosCompraPorCodigoBarra(codigoBarra) {
    try {
      console.log('El valor del input ha cambiado:', codigoBarra);
      var codigoBarra = codigoBarra;
      $.ajax({
        url: "ajax/obtenerCompraProductoCodigoBarra.php",
        data: {
          "codigoBarra": codigoBarra
        },
        method: "GET",
        cache: false,
        dataType: "json",
        success: function(respuesta) {
          console.log(respuesta);
          if (respuesta > 1) {
            limpiarInputCodigoProducto();
            return swal({
              title: "Mas de una compra registrada",
              text: "Este producto tiene mas de una compra registrada, seleccione manualmente el producto para la venta",
              type: "warning",
              confirmButtonText: "¡Cerrar!"
            });

          } else {
            if (respuesta == 0) {
              limpiarInputCodigoProducto();
              return swal({
                title: "Stock vacio",
                text: "Este producto tiene stock vacio o el producto no existe",
                type: "warning",
                confirmButtonText: "¡Cerrar!"
              });

            } else {
              let idcompraprod = respuesta.idcompraprod;
              let cantidad = 1;
              generarTablaDetalleVenta(idcompraprod, cantidad)
              console.log('se cargara a la tabla', respuesta.idcompraprod);
              limpiarInputCodigoProducto();
            }

          }

        }
      })
    } catch (error) {

    }
  }



  async function obtenerDatosProducto(codigobarra) {
    // Simular una espera de 1 segundo (puedes reemplazar esto con una operación asíncrona real, como una solicitud HTTP)

    // if(miInput.value.length===13)
    // {
    // console.log('El valor del input ha cambiado:');
    // }
    // Simular datos obtenidos
    var resultadoConsulta;
    $.ajax({
      url: "ajax/obtenerCompraProductoCodigoBarra.php",
      data: {
        "codigoBarra": codigobarra
      },
      type: "GET",
      cache: false,
      //contentType: false,
      //processData: false,
      dataType: "json",
      success: function(respuesta) {
        console.log(respuesta);
        resultadoConsulta = respuesta;
      }
    })
    var result = codigobarra;
    return {
      resultado: resultadoConsulta
    };
  }

  function limpiarInputCodigoProducto() {
    var miInput = document.getElementById('miInput');
    miInput.value = "";
  }

  // Función asíncrona que simula una operación asíncrona (por ejemplo, una solicitud HTTP)
  async function obtenerDatos() {
    // Simular una espera de 1 segundo (puedes reemplazar esto con una operación asíncrona real, como una solicitud HTTP)
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Simular datos obtenidos
    return {
      resultado: 'Datos obtenidos'
    };
  }

  // Función principal que llama a la función asíncrona
  async function principal(codigobarra) {
    // console.log('Inicio de la función principal');

    // Llamar a la función asíncrona y esperar a que se complete
    const datos = await obtenerDatosProducto(codigobarra);

    console.log('Datos obtenidos:', datos);
    limpiarInputCodigoProducto();
    // console.log('Fin de la función principal');
  }

  // Llamar a la función principal
  //principal();

  function resetearTodo() {
    // Vaciar arrays (como vimos antes)
    arrayIdproductosVenta = [];
    arrayPrecioOficialProducto = [];
    arrayPrecioOficialFacturadoProducto = [];
    $(".nuevoProducto").empty();
    $("#totalMontoCompra").val(0); //si esque no existe el total de venta va ser 0
    $("#btnguardarCompra").css("display", "none"); /*ocultamos el boton guardar venta*/

    $("#cuotaCompra").val(0);
    $(".contenedor-cuota").fadeOut(); // Desaparece suavemente
  }
 
</script>