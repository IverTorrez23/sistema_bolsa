<?php
include_once("../modelos/venta.modelo.php");
include_once("../modelos/ventaCliente.modelo.php");
include_once("../modelos/ventaProducto.modelo.php");
include_once("../modelos/compraProducto.modelo.php");
include_once("../modelos/cuotaVenta.modelo.php");

if (isset($_POST["btnguardarventa"])) {
	ctrlguardarVenta();
}
if (isset($_POST["btnelimventa"])) {
	ctrlElimVenta();
}
// if (isset($_POST["btnelimMarca"])) 
// {
// 	ctrlDarBajaMarca();
// }


function ctrlguardarVenta()
{
	#verificamos si la venta es facturada
	if ($_POST["checkfact"] == "true") {
		$sw_facura = "si";
	} else {
		if ($_POST["checkfact"] == "null") {
			$sw_facura = "no";
		}
	}

	ini_set('date.timezone', 'America/La_Paz');
	$fecha = date("Y-m-d");
	$hora = date("H:i");
	$fechaHora = $fecha . ' ' . $hora;

	$esCancelado = 0;
	if ($_POST["switchCredit"] == 1) {
		$esCancelado = 0;
	} else {
		$esCancelado = 1;
	}

	$objventa = new Venta();
	$objventa->set_fechaventa($_POST['fechaVenta']);
	$objventa->set_montoVenta($_POST['montoventatotal']);
	$objventa->set_ventaFacturada($sw_facura);
	$objventa->set_precioFacturaV(0);
	$objventa->set_tipoVenta($_POST['texttipouser']);/*QUE TIPO DE USUARIO HIZO LA VENTA*/
	$objventa->set_idempleado($_POST['textiduser']);
	$objventa->set_ventaCredito($_POST["switchCredit"]);
	$objventa->set_cancelado($esCancelado);
	$objventa->set_ventaCerrada(0);
	$objventa->set_estadoVenta("Activo");
	$objventa->set_usuarioBaja(0);
	$objventa->set_fechBaja('');
    $objventa->set_fechaRegistro($fechaHora);
	if ($objventa->guardarVenta()) {
		/*preguntamos si hay cliente seleccionado*/
		$resultultventa = $objventa->mostrarUltimaVenta();
		$filult = mysqli_fetch_object($resultultventa);
		if ($_POST['slectcli'] > 0) {
			$objcliv = new Venta_Clienta();
			$objcliv->set_idCliente($_POST['slectcli']);
			$objcliv->set_idVenta($filult->ultVenta);
			if ($objcliv->guardarVentaClienta()) {
			}
		}
		//Registra la cuota si es a credito
		if ($_POST["switchCredit"] == 1 && $_POST["cuotaVenta"] > 0) {
			guardarCuotaVenta($filult->ultVenta);
		}

		echo $filult->ultVenta;/*devuelve el codigo de la venta para registrar el detalle*/
	} else {
		echo 0;
	}
}



/*DAR BAJA UNA VENTA*/
function ctrlElimVenta()
{
	/*verificacion que la venta no este en un cierre de caja*/
	$contadorVentaProd = 0;
	$contadorSucces = 0;
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
						//echo 1;
						$contadorSucces++;
					} else {
						echo 0;/*no se pudo actualizar stock*/
					}
				}/*fin del if que da de baja venta producto*/ else {
					echo 2;/*no se pudo dar e baja venta producto*/
				}
				$contadorVentaProd++;
			}/*fin del while*/
			//Verificacion que el total de ventaproducto coincida con las actualizaciones de stock
			if ($contadorVentaProd == $contadorSucces) {
				echo 1;
			} else {
				echo 0;
			}
		}/*fin del if que da de baja una venta*/ else {
			echo 9;/*no se pudo dar de baja una venta*/
		}
	} //fin del else que hace la eliminacion
} //fin de la funcion 


function guardarCuotaVenta($idVenta)
{
	ini_set('date.timezone', 'America/La_Paz');
	$fecha = date("Y-m-d");
	$hora = date("H:i");
	$fechaHora = $fecha . ' ' . $hora;
	$objcuota = new CuotaVenta();
	$objcuota->set_fechaCuota($fechaHora);
	$objcuota->set_montoCuota($_POST["cuotaVenta"]);
	$objcuota->set_idVenta($idVenta);
	$objcuota->set_idAdmin($_POST["textiduser"]);
	$objcuota->set_estadoCoutaVenta("Activo");
	$objcuota->set_tipoReg($_POST["texttipouser"]);
	//$objcuota->set_usuarioBaja();
	//$objcuota->set_fechaBaja();
	$objcuota->guardarCuotaVenta();
}
