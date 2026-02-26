<?php
include_once("../modelos/venta.modelo.php");
include_once("../modelos/ventaCliente.modelo.php");
include_once("../modelos/ventaProducto.modelo.php");
include_once("../modelos/compraProducto.modelo.php");
include_once("../modelos/cuotaCompra.modelo.php");
include_once("../modelos/compra.modelo.php");
include_once("../modelos/cuotaVenta.modelo.php");

if (isset($_POST["btnsaveCuota"])) {
    ctrlguardarCuotaVenta();
}


function ctrlguardarCuotaVenta()
{
    ini_set('date.timezone', 'America/La_Paz');
    $fecha = date("Y-m-d");
    $hora = date("H:i");
    $fechaHora = $fecha . ' ' . $hora;

    $montoVenta = $_POST['txtmontoVenta'];

    $objcuota = new CuotaVenta();
    $objcuota->set_fechaCuota($fechaHora);
    $objcuota->set_montoCuota($_POST['montoCuota']);
    $objcuota->set_idVenta($_POST['idventa']);
    $objcuota->set_tipoReg($_POST['texttipouser']);/*QUE TIPO DE USUARIO HIZO LA VENTA*/
    $objcuota->set_idAdmin($_POST['textiduser']);
    $objcuota->set_estadoCoutaVenta("Activo");
    $objcuota->set_usuarioBaja(0);
    $objcuota->set_fechaBaja('');

    if ($objcuota->guardarCuotaVenta()) {
        $resultSuma = $objcuota->sumatoriaDeCuotaDeVenta($_POST['idventa']);
        $filacuota = mysqli_fetch_object($resultSuma);
        $sumaTotalCuotas = $filacuota->sumaCuotas;
        if ($sumaTotalCuotas >= $montoVenta) {
            $objCom = new Venta();
            $objCom->marcarCanceladoVenta($_POST['idventa']);
        }
        /*preguntamos si hay cliente seleccionado*/


        echo 1;/*devuelve el codigo de la venta para registrar el detalle*/
    } else {
        echo 0;
    }
}


