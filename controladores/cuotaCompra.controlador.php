<?php
include_once("../modelos/venta.modelo.php");
include_once("../modelos/ventaCliente.modelo.php");
include_once("../modelos/ventaProducto.modelo.php");
include_once("../modelos/compraProducto.modelo.php");
include_once("../modelos/cuotaCompra.modelo.php");
include_once("../modelos/compra.modelo.php");

if (isset($_POST["btnsaveCuota"])) {
    ctrlguardarCuotaCompra();
}
if (isset($_POST["btnelimventa"])) {
    ctrlElimVenta();
}
// if (isset($_POST["btnelimMarca"])) 
// {
// 	ctrlDarBajaMarca();
// }


function ctrlguardarCuotaCompra()
{
    ini_set('date.timezone', 'America/La_Paz');
    $fecha = date("Y-m-d");
    $hora = date("H:i");
    $fechaHora = $fecha . ' ' . $hora;

    $montoCompra = $_POST['txtmontoCompra'];

    $objcuota = new CuotaCompra();
    $objcuota->set_fechaCuota($fechaHora);
    $objcuota->set_montoCuota($_POST['montoCuota']);
    $objcuota->set_idCompra($_POST['idcompra']);
    $objcuota->set_tipoReg($_POST['texttipouser']);/*QUE TIPO DE USUARIO HIZO LA VENTA*/
    $objcuota->set_idAdmin($_POST['textiduser']);
    $objcuota->set_estadoCompra("Activo");
    $objcuota->set_usuarioBaja(0);
    $objcuota->set_fechaBaja('');

    if ($objcuota->guardarCuotaCompra()) {
        $resultSuma = $objcuota->sumatoriaDeCuotaDeCompra($_POST['idcompra']);
        $filacuota = mysqli_fetch_object($resultSuma);
        $sumaTotalCuotas = $filacuota->sumaCuotas;
        if ($sumaTotalCuotas >= $montoCompra) {
            $objCom = new Compra();
            $objCom->marcarCanceladoCompra($_POST['idcompra']);
        }
        /*preguntamos si hay cliente seleccionado*/


        echo 1;/*devuelve el codigo de la venta para registrar el detalle*/
    } else {
        echo 0;
    }
}



/*DAR BAJA UNA VENTA*/
function ctrlElimVenta()
{
    /*verificacion que la venta no este en un cierre de caja*/
    $objventacierre = new VentaProducto();
    $resultventcierre = $objventacierre->mostrarVentaQueEstaEncierre($_POST['idventa']);
    $filventcierre = mysqli_fetch_object($resultventcierre);
    if ($filventcierre->id_venta > 0) {
        echo 3; //muestra el mensaje que dice que la venta esta en un cierre
    } else //por falso hace la eliminacion
    {

        /*damos de baja la venta*/
        ini_set('date.timezone', 'America/La_Paz');
        $fecha = date("Y-m-d");
        $hora = date("H:i");
        $fechaHora = $fecha . ' ' . $hora;
        $objventa = new Venta();
        $objventa->set_usuarioBaja($_POST['idusuario_1']);
        $objventa->set_fechBaja($fechaHora);
        if ($objventa->darBajaVenta($_POST['idventa'])) {
            /*obtenemos la cantidad de items de una venta*/
            $objventprod = new VentaProducto();
            $resulcant = $objventprod->listarVentaProdDeVenta($_POST['idventa']);
            while ($f_datos = mysqli_fetch_object($resulcant)) {  /*asignacion de valores*/
                $idventaproducto = $f_datos->id_venta_producto;
                $idcompraproducto = $f_datos->id_compra_producto;
                $cantidadVentaproducto = $f_datos->cantidad_prod;

                /*da de baja ventaproducto*/
                if ($objventprod->darBajaVentaProducto($idventaproducto)) {
                    /*ACTUALIZACION DEL STOCK DE tb_compra_producto*/
                    $objComPProd = new Compra_Producto();
                    $resultStock = $objComPProd->mostrarStockActualDeCompraProd($idcompraproducto);
                    $filstock = mysqli_fetch_object($resultStock);
                    $stockActualizado = ($filstock->stock_actual) + ($cantidadVentaproducto);

                    if ($objComPProd->actualizarStockDeLoteProd($stockActualizado, $idcompraproducto)) {
                        echo 1;
                    } else {
                        echo 0;/*no se pudo actualizar stock*/
                    }
                }/*fin del if que da de baja venta producto*/ else {
                    echo 2;/*no se pudo dar e baja venta producto*/
                }
            }/*fin del while*/
        }/*fin del if que da de baja una venta*/ else {
            echo 9;/*no se pudo dar de baja una venta*/
        }
    } //fin del else que hace la eliminacion
} //fin de la funcion 
