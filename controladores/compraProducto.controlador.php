<?php

include_once("../modelos/ventaProducto.modelo.php");
include_once("../modelos/compraProducto.modelo.php");

if (isset($_POST["idcompra"]))/*preguntamos si la variable idproductosventa no esta vacia*/ {
    ctrlguardarCompraProducto();
}

/*=============================CREACIONES DE OBJETOS=====================================*/


function ctrlguardarCompraProducto()
{

    $objcomProd = new Compra_Producto();
    
    $objcomProd->set_subtotalCompra($_POST['subTotalCompra']);
    $objcomProd->set_cantidadCompra($_POST['idtextcantidadprod']);
    $objcomProd->set_idCompra($_POST['idcompra']);
    $objcomProd->set_idProducto($_POST['idproducto']);
    $objcomProd->set_precioUnitCompra($_POST['costoUnidad']);
    $objcomProd->set_precioUnitCompraFactu(0);
    $objcomProd->set_precioVentaProd($_POST['textPrecioVenta']);
    $objcomProd->set_precioVentaProduFact($_POST['textPrecioVenta']);/*ES EL PRECIO QUE SE ESTA VENDIENDO*/
    $objcomProd->set_stockActual($_POST['idtextcantidadprod']);
    $objcomProd->set_precioTope($_POST['textPrecioVenta']);
    $objcomProd->set_estadoCompProd("Activo");
    if ($objcomProd->guardarCompraProducto()) {
            echo 1;
        
    } else {
        echo 0;
    }
}
