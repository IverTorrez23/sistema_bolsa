<?php
error_reporting(E_ERROR);
session_start();
$idCierre = $_GET["codcierre"];

require_once('tcpdf_include.php');


include_once('../../../modelos/venta.modelo.php');
include_once('../../../modelos/compra.modelo.php');
include_once('../../../modelos/cuotaCompra.modelo.php');
include_once("../../../modelos/cierre_caja.modelo.php");
class PDF extends TCPDF
{
  //Cabecera de página
  function Header()
  {

    // $this->Image('images/logo-sonido2.png',5,3, 70, 20, '', '', '', false, 300, '', false, false, 0, false,false,false);

    // $this->SetFont('','B',12);

    // $this->Cell(30,10,'Title',1,0,'C');

  }

  function Footer()
  {



    $this->SetY(-10);

    $this->SetFont('', 'I', 8);
    // $this->Image('images/footer3.png',120,200,'', 10, '', '', '', false, 300, '', false, false, 0, false,false,false);
    $this->Cell(0, 10, 'GRACIAS POR SU PREFERENCIA ', 0, 0, 'C');
  }
}




$pdf = new PDF('H', 'mm', 'A4', true, 'UTF-8', false);/*PARA HOJA TAMAÑO LEGAL SE PONE LEGAL EN MAYUSCULA*/


$pdf->startPageGroup();
$pdf->AddPage();
$pdf->SetFont('', '', 10);
#Establecemos los márgenes izquierda, arriba y derecha:
$pdf->SetMargins(3, 30, 2.5);
#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true, 20);





/*===============================================================
TAMAÑO TOTAL DE LA HOJA OFICIO ECHADA(ORIZONTAL)=905px DISPONIBLE PARA OCUPAR CON DATOS, RESPETANDO LOSMARGENES ESTABLECIDOS POR CODIGO EN LA FUNCION ->SetMargins(34, 30 ,2.5)
/*===============================================================*/

$obj2 = new Compra();
$resultado2 = $obj2->mostrarDatosDeCompra($idCierre);
$fila1 = mysqli_fetch_object($resultado2);
if ($fila1->proveedor == NULL) {
  $proveedor = "Sin nombre";
} else {
  $proveedor = $fila1->proveedor;
}
$tipoCompra = '';
if ($fila1->compra_credito == 1) {
  $tipoCompra = 'Credito';
} else {
  $tipoCompra = 'Contado';
}

$fechaCompra = $fila1->fecha_compra;
$fechaComoEntero = strtotime($fechaCompra);


$pdf->Ln(0);
$pdf->Cell(0, 0, '                                                                                       NOTA DE CIERRE', 0, 6, 'C', 0, '', 10);
$pdf->Cell(0, 0, '                                                                                       Nº 00' . $idCierre, 0, 6, 'C', 0, '', 10);
$pdf->Ln(1);
// $pdf->Cell(0, 0, 'TEST CELL STRETCH: force scaling', 1, 1, 'R', 0, '', 2);   



$pdf->Ln(5);


$pdf->SetFont('', '', 8);

#Establecemos el margen inferior:
/*$bloqueInfoEmpresa=<<<EOF
<table  style="width: 150px;">
  <thead>
  <tr >
      <th style="text-align:center; ">Propietario: Alex Ventura Montero</th>                             
    </tr>
     <tr >
      <th style="text-align:center; ">CASA MATRIZ</th>                             
    </tr>
    <tr >
      <th style="text-align:center; ">Dir. Calle Arenales Nº123 Zona/Barrio:</th>                       
    </tr>
    <tr >
      <th style="text-align:center; ">25 de Diciembre Telf.: 9227894</th>                       
    </tr>
    <tr >
      <th style="text-align:center; ">Montero - Santa Cruz - Bolivia</th>                       
    </tr>

  </thead>
</table>
EOF;
$pdf->writeHTML($bloqueInfoEmpresa,false,false,false,false,'');*/


$pdf->Ln(1);


// ini_set('date.timezone','America/La_Paz');
//          $dia=date("d");
//          $mes=date("m");
//          $anio=date("Y");
//          $hora=date("H:i");
//          $fechaHora=$fecha.' '.$hora;

$pdf->Ln(2);

$pdf->Ln(2);
$bloqueCabeceraDetalle = <<<EOF
<table border="0.1px">
  <thead>
     <tr id="fila1" style="background-color:#e8e8e8;">
                  <th style="text-align:center;width: 15%; ">Fecha inicio de ventas</th>
                  <th style="text-align:center;width: 15%; ">Fecha fin de ventas</th>
                  <th style="text-align:center;width: 7%; ">Cantidad Ventas</th>
                  <th style="text-align:center;width: 8%; ">Cantidad Productos</th>
                  <th style="text-align:center;width: 15%; ">Monto Ventas</th>
                  <th style="text-align:center;width: 10%; ">Monto entregado caja</th>
                  <th style="text-align:center;width: 10%; ">Monto diferencia</th>
                  <th style="text-align:center;width: 20%; ">Fecha cierre</th>               
    </tr>
  </thead>
</table>
EOF;
$pdf->writeHTML($bloqueCabeceraDetalle, false, false, false, false, '');


$contador = 1;
$totalCompra = 0;
$obj = new Cierre_caja();
$resultado = $obj->cierreCaja($idCierre);
while ($fila = mysqli_fetch_object($resultado)) {
  $totalCompra = $fila->monto_compra;

  $costoUnitarioCompra=ajustarDecimales($fila->precio_unit_compra);
  $bloqueDatosDetalle = <<<EOF
                                  <table border="0.1px" cellpadding="1">
                                  <thead>
                                  <tr style="page-break-inside: avoid; " nobr="true">
                                  <th style="text-align:center; width: 15%;">$fila->fecha_cierre</th>
                                  <th style="text-align:center; width: 15%;">$fila->fecha_cierre_fin</th>
                                     <th style="text-align:center; width: 7%;">$fila->cantidad_ventas</th>
                                     <th style="text-align:center; width: 8%;">$fila->cantidad_productos</th>
                                     <th style="text-align:right; width: 15%;">$fila->monto_venta_cierre</th>
                                     
                                     <th style="text-align:right; width: 10%;">$fila->monto_caja</th>
                                     <th style="text-align:right; width: 10%;">$fila->monto_sobrante</th>
                                     <th style="text-align:right;width: 20%; ">$fila->fecha_alta</th>                                
                                  </tr>
                                  </thead>
                                  </table>
                                  EOF;

  $pdf->writeHTML($bloqueDatosDetalle, false, false, false, false, '');
  $pdf->SetMargins(34, 30, 2.5);


  // $contador++;

}
/***************************TABLA TOTAL EGRESOS DE LAS ORDENES*************/
// $totalventasDecimal=number_format((float)$totalMontoVentas, 2, '.', '');

function ajustarDecimales($num) {
    // Si tiene 2 decimales o menos, forzar formato 0.00
    if (round($num, 2) == $num) {
        return number_format($num, 2, '.', '');
    }
    // Si tiene más, devolver el número tal cual
    return $num;
}
$nameFile = 'nota_compra.pdf';
ob_end_clean(); //LIMPIA ESPACIOS EN BLANCO PARA NO GENERAR ERROREA
$pdf->Output($nameFile);
