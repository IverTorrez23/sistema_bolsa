<?php

include_once("../modelos/compra.modelo.php");
include_once("../modelos/compraProducto.modelo.php");
include_once("../modelos/productos.modelo.php");
include_once("../modelos/ventaProducto.modelo.php");
include_once("../modelos/cuotaCompra.modelo.php");


if (isset($_POST["btnguardarCompra"])) {
	ctrlGuardarCompra();
}

if (isset($_POST["btnelimcomp"])) {
	eliminarCompra();
}
if (isset($_POST["btnguardarCompraEdit"])) {
	ctrlEditarCompra();
}


function ctrlGuardarCompra()
{
	$sw_facura = "no";
	ini_set('date.timezone', 'America/La_Paz');
	$fecha = date("Y-m-d");
	$hora = date("H:i");
	$fechaHora = $fecha . ' ' . $hora;

	$montoCompra = $_POST["montoCompratotal"];
	$esCancelado = 0;
	if ($_POST["switchCredit"] == 1) {
		$esCancelado = 0;
	} else {
		$esCancelado = 1;
	}

	$objcomp = new Compra();
	$objcomp->set_fechaCompra($fechaHora);
	$objcomp->set_montoCompra($montoCompra);
	$objcomp->set_CompraFacturada($sw_facura);
	$objcomp->set_CostoFactura(0);
	$objcomp->set_idAdmin($_POST["textiduser"]);
	$objcomp->set_tipoReg($_POST["texttipouser"]);
	$objcomp->set_idProveedor($_POST["selectprov"]);
	$objcomp->set_compraCredito($_POST["switchCredit"]);
	$objcomp->set_cancelado($esCancelado);
	$objcomp->set_estadoCompra("Activo");
	if ($objcomp->guardarCompra()) {
		#SE REGISTRA LA TERCER TABLA COMPRA_PRODUCTO
		$resultultima = $objcomp->mostrarUltimaCompra();
		$filaul = mysqli_fetch_object($resultultima);
		$idutimacompra = $filaul->idultimacompra;
		if ($_POST["switchCredit"] == 1 && $_POST["cuotaCompra"] > 0) {
			guardarCuotaCompra($idutimacompra);
		}

		echo $idutimacompra;/*devuelve el codigo de la compra para registrar el detalle*/
	} else {
		echo 0;
	}
}



function eliminarCompra()
{
	ini_set('date.timezone', 'America/La_Paz');
	$fecha = date("Y-m-d");
	$hora = date("H:i");
	$fechaHora = $fecha . ' ' . $hora;
	#validacion que no se haya echo alguna venta de esta compra(es decir, que no se haya vendido algun producto)
	$objventap = new VentaProducto();
	$resultvent = $objventap->sumatoriaDeProdVendidosDeCompra($_POST["idcompraelim"]);
	$filvent = mysqli_fetch_object($resultvent);
	if ($filvent->total_ventas > 0) {
		echo 2; //muestra la alerta que indica que hay ventas con el codigo de lote(id_compra_producto)
	} else //por falso elimina 
	{
			$objcompProd = new Compra_Producto();
			$objcompProd->set_idCompra($_POST["idcompraelim"]);
			if ($objcompProd->eliminarCompraProductoDeCompra()) {
				$objcomp = new Compra();
				$objcomp->set_estadoCompra('Inactivo');
				$objcomp->set_usuarioBaja($_POST["idadmin_elim"]);
				$objcomp->set_fechaBaja($fechaHora);
				$objcomp->setid_compra($_POST["idcompraelim"]);
				if ($objcomp->eliminarCompra()) {
					echo 1;
				} else {
					echo 0;
				}
			}
			//}
	
	} //fin del else cuando no hay ventas con el codigo de compra
} //fin de la funcion eliminar

function guardarCuotaCompra($idCompra)
{
	ini_set('date.timezone', 'America/La_Paz');
	$fecha = date("Y-m-d");
	$hora = date("H:i");
	$fechaHora = $fecha . ' ' . $hora;
	$objcuota = new CuotaCompra();
	$objcuota->set_fechaCuota($fechaHora);
	$objcuota->set_montoCuota($_POST["cuotaCompra"]);
	$objcuota->set_idCompra($idCompra);
	$objcuota->set_idAdmin($_POST["textiduser"]);
	$objcuota->set_estadoCompra("Activo");
	$objcuota->set_tipoReg($_POST["texttipouser"]);
	//$objcuota->set_usuarioBaja();
	//$objcuota->set_fechaBaja();
	$objcuota->guardarCuotaCompra();
}
