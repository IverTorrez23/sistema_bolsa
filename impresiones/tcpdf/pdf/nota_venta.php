<?php
error_reporting(E_ERROR);
session_start();
$idventa = $_GET["codventa"];

require_once('tcpdf_include.php');


include_once('../../../modelos/venta.modelo.php');
include_once('../../../modelos/cuotaVenta.modelo.php');
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

$obj2 = new Venta();
$resultado2 = $obj2->mostrarDatosDeVenta($idventa);
$fila1 = mysqli_fetch_object($resultado2);
if ($fila1->cliente == NULL) {
  $cliente = "Sin cliente";
} else {
  $cliente = $fila1->cliente;
}

$tipoVenta = '';
if ($fila1->venta_credito == 1) {
  $tipoVenta = 'Credito';
} else {
  $tipoVenta = 'Contado';
}

$fechaventa = $fila1->fecha_venta;
$vendedor = $fila1->Usuario;
$fechaComoEntero = strtotime($fechaventa);


$pdf->Ln(0);
$pdf->Cell(0, 0, '                                                                                       NOTA DE VENTA', 0, 6, 'C', 0, '', 10);
$pdf->Cell(0, 0, '                                                                                       Nº 00' . $idventa, 0, 6, 'C', 0, '', 10);
$pdf->Ln(1);
// $pdf->Cell(0, 0, 'TEST CELL STRETCH: force scaling', 1, 1, 'R', 0, '', 2);   



$pdf->Ln(5);


$pdf->SetFont('', '', 8);

#Establecemos el margen inferior:
$bloqueInfoEmpresa = <<<EOF
<table   border="0.1px">
  <thead>
  <tr >
      <th style="text-align:center; width: 10%;">Cliente </th>
      <th style="text-align:center; width: 40%;">$cliente</th>
      <th style="text-align:center; width: 10%;">Vendedor</th>
      <th style="text-align:center; width: 40%;">$vendedor</th>                             
    </tr>

     <tr >
      <th style="text-align:center; width: 10%;">Contacto</th>
      <th style="text-align:center; width: 40%;">superbolsasmontero@gmail.com</th>
      <th style="text-align:center; width: 10%;">Fecha</th> 
      <th style="text-align:center; width: 40%;">$fechaventa</th>                              
    </tr>

    <tr >
      <th style="text-align:center; width: 10%;">Direccion</th>
      <th style="text-align:center; width: 40%;">Montero</th>
      <th style="text-align:center; width: 10%;">Tipo venta</th> 
      <th style="text-align:center; width: 40%;">$tipoVenta</th>                        
    </tr>

  </thead>
</table>
EOF;
$pdf->writeHTML($bloqueInfoEmpresa, false, false, false, false, '');


$pdf->Ln(1);
/*$bloqueFecha = <<<EOF

<table  style="width: 150px;">
  <thead>
     <tr style="background-color:#e8e8e8;">
                  <th style="text-align:center; ">Lugar</th>
                  <th style="text-align:center; ">Dia</th>
                  <th style="text-align:center; ">Mes</th>  
                  <th style="text-align:center; ">Año</th>               
    </tr>

  </thead>
</table>
EOF;
$pdf->writeHTML($bloqueFecha, false, false, false, false, '');*/

// ini_set('date.timezone','America/La_Paz');
//          $dia=date("d");
//          $mes=date("m");
//          $anio=date("Y");
//          $hora=date("H:i");
//          $fechaHora=$fecha.' '.$hora;

$anio = date("Y", $fechaComoEntero);
$mes = date("m", $fechaComoEntero);
$dia = date("d", $fechaComoEntero);
/*$bloqueFechaDatos = <<<EOF

<table  style="width: 150px;">
  <thead>
     <tr >
                  <th style="text-align:center; ">Montero</th>
                  <th style="text-align:center; ">$dia</th>
                  <th style="text-align:center; ">$mes</th>  
                  <th style="text-align:center; ">$anio</th>               
    </tr>

  </thead>
</table>
EOF;
$pdf->writeHTML($bloqueFechaDatos, false, false, false, false, '');*/

$pdf->Ln(2);




//$pdf->Cell(0, 0, 'Señor(es) cliente: ' . $cliente, 0, 6, 'L', 0, '', 1);
//$pdf->Cell(0, 0, 'Venta: ' . $tipoVenta, 0, 6, 'L', 0, '', 1);

$pdf->Ln(2);
$bloqueCabeceraDetalle = <<<EOF

<table border="0.1px">
  <thead>
     <tr id="fila1" style="background-color:#e8e8e8;">
                  <th style="text-align:center;width: 10%; ">N°</th>
                  <th style="text-align:center;width: 60%; ">Producto</th>
                  <th style="text-align:center;width: 10%; ">Cantidad</th>
                  <th style="text-align:center;width: 10%; ">Valor Unitario Bs.</th>
                  <th style="text-align:center;width: 10%; ">Valor Total Bs.</th>
                 
                 
    </tr>

  </thead>

  

</table>
EOF;
$pdf->writeHTML($bloqueCabeceraDetalle, false, false, false, false, '');


$contador = 1;
$totalVenta = 0;
$obj = new Venta();
$resultado = $obj->nota_Venta($idventa);
while ($fila = mysqli_fetch_object($resultado)) {
  $totalVenta = $fila->monto_venta;
  $bloqueDatosDetalle = <<<EOF
                                  <table border="0.1px" cellpadding="1">
                                  <thead>
                                  <tr style="page-break-inside: avoid; " nobr="true">
                                  <th style="text-align:center; width: 10%;">$contador</th>
                                     <th style="text-align:left; width: 60%;">$fila->nombre_producto - $fila->descripcion</th>
                                     <th style="text-align:right; width: 10%;">$fila->cantidad_prod</th>
                                     <th style="text-align:right; width: 10%;">$fila->precio_unitario_venta</th>
                                     <th style="text-align:right;width: 10%; ">$fila->subtotal_venta</th>                                
                                  </tr>
                                  </thead>
                                  </table>
                                  EOF;

  $pdf->writeHTML($bloqueDatosDetalle, false, false, false, false, '');
  $pdf->SetMargins(34, 30, 2.5);


  $contador++;
}
/***************************TABLA TOTAL EGRESOS DE LAS ORDENES*************/
// $totalventasDecimal=number_format((float)$totalMontoVentas, 2, '.', '');
$bloqueTotal = <<<EOF
<table border="0.1px" cellpadding="1" >
<tr style="page-break-inside: avoid;" nobr="true">
   <td style="text-align:center; width:90%;">TOTAL</td>
   <td style="text-align:right; width:10%;">$totalVenta</td>

</tr>

</table>
EOF;

$pdf->writeHTML($bloqueTotal, false, false, false, false, '');



/*****************LISTADO DE CUOTAS******************** */
if ($fila1->venta_credito == 1) {
  $pdf->SetMargins(3, 30, 101);
  $pdf->Ln(5);
  $pdf->SetFont('', '', 10);
  $pdf->Cell(0, 0, 'CUOTAS DE LA VENTA', 0, 6, 'L', 0, '', 1);
  $pdf->SetFont('', '', 8);
  $bloqueHeadCuota = <<<EOF
<table  style="width: 300px;" border="0.1px">
  <thead>
     <tr style="background-color:#e8e8e8;">
                  <th style="text-align:center; width:150px;">Fecha</th>
                  <th style="text-align:center; width:150px;">Monto</th>           
    </tr>
  </thead>
</table>
EOF;
  $pdf->writeHTML($bloqueHeadCuota, false, false, false, false, '');


  $totalCuota = 0;
  $saldoVenta = 0;
  $obj = new CuotaVenta();
  $resultado = $obj->ReporteCuotaVentasActivasDeUnaVenta($idventa);
  while ($fila = mysqli_fetch_object($resultado)) {
    $totalCuota = $fila->monto_cuota + $totalCuota;
    $bloqueDatosCuota = <<<EOF
                                  <table style="width: 300px;" border="0.1px">
                                  <thead>
                                  <tr style="page-break-inside: avoid; " nobr="true">
                                     <th style="text-align:center; width:150px;">$fila->fecha_cuota</th>
                                     <th style="text-align:center; width:150px;">$fila->monto_cuota</th>                                
                                  </tr>
                                  </thead>
                                  </table>
                                  EOF;
    $pdf->writeHTML($bloqueDatosCuota, false, false, false, false, '');
  }
  $saldoVenta = $totalVenta - $totalCuota;
  $saldoFormateado = number_format($saldoVenta, 2, '.', '');
  $pdf->SetFont('', '', 10);
  $pdf->Cell(0, 0, 'Saldo: ' . $saldoFormateado, 0, 6, 'L', 0, '', 1);
}


$pdf->SetMargins(3, 30, 2.5);
$pdf->Ln(5);
$pdf->SetFont('', 'regularB', 17);
$pdf->Cell(0, 0, 'BOLSAS A LA VENTA:', 0, 6, 'C', 0, '', 1);
$pdf->Ln(1);
$pdf->SetMargins(3, 30, 2.5);

$pdf->Cell(0, 0, '60kg - 50kg - 46kg - 25kg - 11.5kg', 0, 6, 'C', 0, '', 1);

$pdf->SetMargins(3, 30, 2.5);
$pdf->SetFont('', '', 10);
$pdf->Ln(4);
$pdf->Cell(0, 0, '                   LAS BOLSAS ENTREGADAS A CREDITO DEBEN SER CANCELADAS DENTRO DE 15 DIAS HABILES', 0, 6, 'L', 0, '', 1);
$pdf->SetFont('', '', 15);
$pdf->Cell(0, 0, '       ¡IMPRESIÓN DE BOLSAS CON LOGOS PERZONALIZADOS!', 0, 6, 'C', 0, '', 1);
$pdf->SetFont('', '', 12);
$pdf->Cell(0, 0, '       REALIZA TUS PEDIDOS AQUÍ: 77334165', 0, 6, 'C', 0, '', 1);




$pdf->Ln(20); // Espacio para que firmen

// 'T' significa borde superior (Top)
$pdf->Cell(90, 0, 'FIRMA CLIENTE', 'T', 0, 'C'); 
$pdf->Cell(15, 0, '', 0, 0, 'C'); // Celda vacía de separación
$pdf->Cell(90, 0, 'FIRMA VENDEDOR', 'T', 1, 'C');

$nameFile = 'nota_Venta.pdf';
ob_end_clean(); //LIMPIA ESPACIOS EN BLANCO PARA NO GENERAR ERROREA
$pdf->Output($nameFile);
