<?php
error_reporting(E_ERROR);
session_start();
if ($_SESSION["usuarioAdmin"] != "") {
    $datosUsuario = $_SESSION["usuarioAdmin"];
    $_SESSION["nombuser"] = $datosUsuario["nombre_administrador"];
    $iduseractual = $datosUsuario["id_administrador"];
    $_SESSION["tipouser"] = "admin";
}
if ($_SESSION["usuarioEmp"] != "") {
    $datosUsuario = $_SESSION["usuarioEmp"];
    $_SESSION["nombuser"] = $datosUsuario["nombre_empleado"];
    $iduseractual = $datosUsuario["id_empleado"];
    $_SESSION["tipouser"] = "empl";
}

$fechaIni = $_SESSION["frchaini"];
$fechaFin = $_SESSION["fechafin"];
$idemp = $_SESSION["idemp"];
require_once('tcpdf_include.php');

include_once('../../../modelos/venta.modelo.php');
include_once('../../../modelos/empleado.modelo.php');
include_once("../../../modelos/almacen.modelo.php");

include_once('../../../modelos/compra.modelo.php');
class PDF extends TCPDF
{
    //Cabecera de página
    function Header()
    {
        $nombreUser = $_SESSION["nombuser"];

        //$this->Image('images/logo-sonido2.png',5,3, 70, 20, '', '', '', false, 300, '', false, false, 0, false,false,false);
        // $this->SetFont('','B',12);
        // $this->Cell(30,10,'Title',1,0,'C');
        $this->SetFont('', 'B', 9);
        $this->Ln(3);
        ini_set('date.timezone', 'America/La_Paz');
        //$fecha=date("Y-m-d");
        $fecha = date("d-m-Y");
        $hora = date("H:i");
        $fechaHora = $fecha . ' ' . $hora;
        $this->Cell(100, 5, '                                                                                                                                                                                                                                     ' . $fechaHora, 0, 0, 'C', 0, '', 0);
        $this->Ln(3);
        $this->Cell(00, 0, ' Usuario: ' . $nombreUser, 0, 0, 'R', 0, '', 0);
    }

    function Footer()
    {



        $this->SetY(-1000);

        $this->SetFont('', 'I', 8);
        // $this->Image('images/footer3.png',120,200,'', 10, '', '', '', false, 300, '', false, false, 0, false,false,false);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
    }
}




$pdf = new PDF('H', 'mm', 'A4', true, 'UTF-8', false);/*PARA HOJA TAMAÑO LEGAL SE PONE LEGAL EN MAYUSCULA*/


$pdf->startPageGroup();
$pdf->AddPage();
$pdf->SetFont('', '', 10);
#Establecemos los márgenes izquierda, arriba y derecha:
$pdf->SetMargins(3, 30, 5);
#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true, 20);





/*===============================================================
TAMAÑO TOTAL DE LA HOJA OFICIO ECHADA(ORIZONTAL)=905px DISPONIBLE PARA OCUPAR CON DATOS, RESPETANDO LOSMARGENES ESTABLECIDOS POR CODIGO EN LA FUNCION ->SetMargins(34, 30 ,2.5)
/*===============================================================*/
if ($idemp == 0) {
    $nombreVendedor = 'Todos';
} else {

    $objemep = new Almacen();
    $resultEmp = $objemep->mostarUnAlmacen($idemp);
    $filaemp = mysqli_fetch_object($resultEmp);
    $nombreEmp = $filaemp->nombre_almacen;
    //$apellido=$filaemp->apellido_empleado;
    $nombreVendedor = $nombreEmp;
}


//$pdf->Image('images/logoserrate3.jpg',10, 10, 70, 15, '', '', '', false, 300, '', false, false, 0, false,false,false);

$pdf->Ln(10);
$pdf->Cell(100, 0, '                                                                                       REPORTE DE COMPRAS EN EL RANGO DE FECHAS', 0, 0, 'C', 0, '', 0);
$pdf->Ln(4);
$pdf->Cell(95, 0, 'Desde: ' . $fechaIni . '      Hasta: ' . $fechaFin, 0, 0, 'C', 0, '', 0);
$pdf->Ln(4);
//$pdf->Cell(0, 0, 'Almacen :' . $nombreVendedor, 0, 0, 'L', 0, '', 0);





$pdf->Ln(5);


$pdf->SetFont('', '', 8);
/*===============================================================
CABECERA DE LA TABLA
/*===============================================================*/
$bloqueCabeceraDetalle = <<<EOF

<table border="0.1px">
  <thead>
     <tr id="fila1" style="background-color:#e8e8e8;">
                  <th style="text-align:center; ">Cod. Compra</th>
                  <th style="text-align:center; ">Fecha compra</th>
                  <th style="text-align:center; ">Proveedor</th>     
                  <th style="text-align:center; ">Compra credito</th>
                  <th style="text-align:center; ">Cancelado</th>
                  <th style="text-align:center; ">Usuario</th>
                  <th style="text-align:center; ">Costo total</th>
    </tr>

  </thead>

  

</table>
EOF;
$pdf->writeHTML($bloqueCabeceraDetalle, false, false, false, false, '');


$contador = 1;
$totalMontoCompras = 0;
$sub_costo = 0;
$ganacia = 0;
$GananciasTotales = 0;
$ganaciaDecimal = 0;
$alertaColorfila = '';
$colorTexto = '';
$obj = new Compra();

if ($idemp == 0)/*reporte de todos los que realizaron ventas*/ {
    $resultado = $obj->ReporteComprasActivas($fechaIni, $fechaFin);
} else/*reporte de un ventero especifico*/ {
    $resultado = $obj->ReporteComprasActivasDeAlmacen($fechaIni, $fechaFin, $idemp);
}

///$resultado=$obj->reporteVentas($fechaIni,$fechaFin);
while ($fila = mysqli_fetch_object($resultado)) {

    /*obtenemos la ganacia de total vendido del producto*/
$imgRuta = ($fila->compra_credito == 1) ? './images/only-check.png' : './images/cercle-gris.png';
// Nota: En TCPDF a veces es mejor usar la ruta completa del servidor
$htmlIconoCredito = '<img src="' . $imgRuta . '" width="8" height="6" />';
///Para cancelado
$imgRutaCan = ($fila->cancelado == 1) ? './images/only-check.png' : './images/cercle-gris.png';
// Nota: En TCPDF a veces es mejor usar la ruta completa del servidor
$htmlIconoCancelado = '<img src="' . $imgRutaCan . '" width="8" height="6" />';

    $bloqueDatosDetalle = <<<EOF
                                  <table border="0.1px">
                                  <thead>
                                  <tr style="page-break-inside: avoid; " nobr="true">
                                     <th style="text-align:center; ">$fila->idcompra</th>
                                     <th style="text-align:center; ">$fila->fecha_compra</th>
                                     <th style="text-align:center; ">$fila->nameProveedor</th>
                                     <th style="text-align:center; ">$htmlIconoCredito</th>
                                     <th style="text-align:center; ">$htmlIconoCancelado</th>
                                     <th style="text-align:center; ">$fila->Usuario</th>
                                     <th style="text-align:center; ">$fila->monto_compra</th>                               
                                  </tr>
                                  </thead>
                                  </table>
                                  EOF;

    $pdf->writeHTML($bloqueDatosDetalle, false, false, false, false, '');
    $pdf->SetMargins(34, 30, 5);


    // $contador++;
    $totalMontoCompras = $totalMontoCompras + $fila->monto_compra;

}
/***************************TABLA TOTALES*************/
$totalMontoComprasDecimal = number_format((float)$totalMontoCompras, 2, '.', '');

$bloqueTotalesCostosOrdenes = <<<EOF
<table border="0.1px" cellpadding="1" >
<tr style="page-break-inside: avoid;" nobr="true">
   <td style="text-align:center; width:490px;">TOTALES</td>
   <td style="text-align:center; width:83px;">$totalMontoComprasDecimal</td>
</tr>

</table>
EOF;

$pdf->writeHTML($bloqueTotalesCostosOrdenes, false, false, false, false, '');











$nameFile = 'reporte_de_compras.pdf';
ob_end_clean(); //LIMPIA ESPACIOS EN BLANCO PARA NO GENERAR ERROREA
$pdf->Output($nameFile);
