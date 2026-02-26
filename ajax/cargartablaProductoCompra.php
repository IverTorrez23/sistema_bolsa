<?php
include_once("../modelos/compraProducto.modelo.php");
include_once("../modelos/productos.modelo.php");
$objp=new Producto();
$resultcomp=$objp->mostrarUnProducto($_POST["idcompraprod"]);

		$ejecutar=mysqli_fetch_array($resultcomp);
		echo json_encode($ejecutar);
		
?>