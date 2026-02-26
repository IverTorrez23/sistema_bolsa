<?php
include_once('conexion.php');
class Compra extends Conexion
{
	private $id_compra;
	private $fecha_compra;
	private $monto_compra;
	private $compra_facturada;
	private $costo_factura;
	private $usuario_alta;
	private $id_proveedor;
	private $compra_credito;
	private $cancelado;
	private $estado;
	private $tipo_reg;
	private $usuario_baja;
	private $fecha_baja;

	public function Compra()
	{
		parent::Conexion();
		$this->id_compra = 0;
		$this->fecha_compra = "";
		$this->monto_compra = "";
		$this->compra_facturada = 0;
		$this->costo_factura = "";
		$this->usuario_alta = 0;
		$this->id_proveedor = 0;
		$this->compra_credito = 0;
		$this->cancelado = 0;
		$this->estado = "";
		$this->tipo_reg = "";
		$this->usuario_baja = 0;
		$this->fecha_baja = 0;
	}

	public function setid_compra($valor)
	{
		$this->id_compra = $valor;
	}
	public function getid_compra()
	{
		return $this->id_compra;
	}
	public function set_fechaCompra($valor)
	{
		$this->fecha_compra = $valor;
	}
	public function get_fechaCompra()
	{
		return $this->fecha_compra;
	}
	public function set_montoCompra($valor)
	{
		$this->monto_compra = $valor;
	}
	public function get_montoCompra()
	{
		return $this->monto_compra;
	}

	public function set_CompraFacturada($valor)
	{
		$this->compra_facturada = $valor;
	}
	public function get_conpraFacturada()
	{
		return $this->compra_facturada;
	}


	public function set_CostoFactura($valor)
	{
		$this->costo_factura = $valor;
	}
	public function get_CostoFactura()
	{
		return $this->costo_factura;
	}
	public function set_idAdmin($valor)
	{
		$this->usuario_alta = $valor;
	}
	public function get_idAdmin()
	{
		return $this->usuario_alta;
	}
	public function set_idProveedor($valor)
	{
		$this->id_proveedor = $valor;
	}
	public function get_idProveedor()
	{
		return $this->id_proveedor;
	}

	public function set_compraCredito($valor)
	{
		$this->compra_credito = $valor;
	}
	public function get_compraCredito()
	{
		return $this->compra_credito;
	}

	public function set_cancelado($valor)
	{
		$this->cancelado = $valor;
	}
	public function get_cancelado()
	{
		return $this->cancelado;
	}

	public function set_estadoCompra($valor)
	{
		$this->estado = $valor;
	}
	public function get_estadoCompra()
	{
		return $this->estado;
	}
	public function set_tipoReg($valor)
	{
		$this->tipo_reg = $valor;
	}
	public function get_tipoReg()
	{
		return $this->tipo_reg;
	}
	public function set_usuarioBaja($valor)
	{
		$this->usuario_baja = $valor;
	}
	public function get_usuarioBaja()
	{
		return $this->usuario_baja;
	}
	public function set_fechaBaja($valor)
	{
		$this->fecha_baja = $valor;
	}
	public function get_fechaBaja()
	{
		return $this->fecha_baja;
	}



	public function guardarCompra()
	{
		$sql = "INSERT INTO tb_compra(fecha_compra,
			                        monto_compra,
			                        compra_facturada,
			                        costo_factura,
			                        usuario_alta,
			                        id_proveedor,
									compra_credito,
									cancelado,
			                        estado,
			                        tipo_reg,
			                        usuario_baja,
			                        fecha_baja) 
		                      VALUES('$this->fecha_compra',
		                      	     '$this->monto_compra',
		                      	     '$this->compra_facturada',
		                      	     '$this->costo_factura',
		                      	     '$this->usuario_alta',
		                      	     '$this->id_proveedor',
									 '$this->compra_credito',
									 '$this->cancelado',
		                      	     '$this->estado',
		                      	     '$this->tipo_reg',
		                      	     '$this->usuario_baja',
		                      	     '$this->fecha_baja')";
		if (parent::ejecutar($sql))
			return true;
		else
			return false;
	}

	public function listarCompras()
	{
		$sql = "SELECT 
					a.id_compra AS idcompra,
					a.compra_facturada,
					a.id_proveedor,
					b.precio_venta_prod,
					b.precio_venta_prod_Fact,
					b.precio_tope,
					b.cantidad_compra AS cantidad,
					a.monto_compra,
					b.precio_unit_compra,
					b.precio_unit_compraFacturado, 
					a.fecha_compra,
					c.nombre_producto AS nameProducto,       
					a.costo_factura,
					d.nombre_proveedor AS nameProveedor,
					c.id_producto AS idproducto,
					c.descripcion,
					b.id_compra_producto,
					e.nombre_almacen,
					a.compra_credito,
					a.cancelado
				FROM tb_compra AS a
				INNER JOIN tb_compra_producto AS b ON a.id_compra = b.id_compra
				INNER JOIN tb_producto AS c ON b.id_producto = c.id_producto
				left JOIN tb_proveedor AS d ON a.id_proveedor = d.id_proveedor
				INNER JOIN tb_almacen AS e ON c.id_almacen = e.id_almacen
				WHERE a.estado = 'Activo'
				AND b.estado = 'Activo' 
				GROUP BY a.id_compra 
				ORDER BY a.id_compra ASC;";
		return parent::ejecutar($sql);
	}

	public function mostrarUltimaCompra()
	{
		$sql = "SELECT MAX(id_compra)AS idultimacompra  FROM tb_compra ";
		return parent::ejecutar($sql);
	}

	public function eliminarCompra()
	{
		$sql = "UPDATE tb_compra 
		         SET estado='$this->estado',
		             usuario_baja='$this->usuario_baja',
		             fecha_baja='$this->fecha_baja'
		       WHERE id_compra='$this->id_compra' ";
		if (parent::ejecutar($sql))
			return true;
		else
			return false;
	}

	public function editarCompra($idcompra)
	{
		$sql = "UPDATE tb_compra
		    set monto_compra     ='$this->monto_compra',
		        compra_facturada ='$this->compra_facturada',
		        usuario_alta     ='$this->usuario_alta',
		        id_proveedor     ='$this->id_proveedor'
		        WHERE id_compra  = $idcompra";
		if (parent::ejecutar($sql))
			return true;
		else
			return false;
	}
	public function ReporteComprasActivas($fechaini, $fechafin)
	{
		$sql = "SELECT 
					a.id_compra AS idcompra,
					a.compra_facturada,
					a.id_proveedor,	
					a.monto_compra,
					a.fecha_compra, 
					a.costo_factura,
					d.nombre_proveedor AS nameProveedor,
					a.compra_credito,
					a.cancelado,
					(CASE 
						WHEN a.tipo_reg = 'admin' THEN 
							(SELECT h.nombre_administrador FROM tb_administrador AS h WHERE h.id_administrador = a.usuario_alta)
						ELSE
							(SELECT g.nombre_empleado FROM tb_empleado AS g WHERE g.id_empleado = a.usuario_alta)
					END) AS Usuario	
				FROM tb_compra AS a
			LEFT JOIN tb_proveedor AS d ON a.id_proveedor = d.id_proveedor
				WHERE a.estado = 'Activo'	
			AND CAST(a.fecha_compra AS DATE) BETWEEN '$fechaini' AND '$fechafin'
				ORDER BY a.id_compra DESC;";
		return parent::ejecutar($sql);
	}

	public function ReporteComprasActivasDeAlmacen($fechaini, $fechafin, $idalmacen)
	{
		$sql = "SELECT 
					a.id_compra AS idcompra,
					a.compra_facturada,
					a.id_proveedor,
					b.precio_venta_prod,
					b.precio_venta_prod_Fact,
					b.precio_tope,
					b.cantidad_compra AS cantidad,
					b.stock_actual,
					a.monto_compra,
					b.precio_unit_compra,
					b.precio_unit_compraFacturado, 
					a.fecha_compra,
					c.nombre_producto AS nameProducto,
					c.descripcion,       
					a.costo_factura,
					d.nombre_proveedor AS nameProveedor,
					c.id_producto AS idproducto,
					b.id_compra_producto,
					a.compra_credito,
					a.cancelado,
					(CASE 
						WHEN a.tipo_reg = 'admin' THEN 
							(SELECT h.nombre_administrador FROM tb_administrador AS h WHERE h.id_administrador = a.usuario_alta)
						ELSE
							(SELECT g.nombre_empleado FROM tb_empleado AS g WHERE g.id_empleado = a.usuario_alta)
					END) AS Usuario,
					e.nombre_almacen 
				FROM tb_compra AS a
				INNER JOIN tb_compra_producto AS b ON a.id_compra = b.id_compra
				INNER JOIN tb_producto AS c ON b.id_producto = c.id_producto
				INNER JOIN tb_almacen AS e ON c.id_almacen = e.id_almacen
				LEFT JOIN tb_proveedor AS d ON a.id_proveedor = d.id_proveedor
				WHERE a.estado = 'Activo'
				AND b.estado = 'Activo'
				AND CAST(a.fecha_compra AS DATE) BETWEEN '$fechaini' AND '$fechafin'
				AND c.id_almacen = $idalmacen
				GROUP BY a.id_compra 
				ORDER BY a.id_compra DESC;";
		return parent::ejecutar($sql);
	}
	public function mostrarDatosDeCompra($idCompra)
	{
		$sql = "SELECT a.fecha_compra, 
			               concat(c.nombre_proveedor,' ',c.apellido_proveedor) as proveedor,
						a.compra_credito 
					  FROM tb_compra as a 
				 LEFT JOIN tb_proveedor as c 
				        ON a.id_proveedor=c.id_proveedor 
					 WHERE a.id_compra=$idCompra";
		return parent::ejecutar($sql);
	}
	public function nota_Compra($idCompra)
	{
		$sql = "SELECT a.id_compra_producto,
		               a.cantidad_compra, 
		               b.nombre_producto, 
					   b.codigo_producto, 
					   b.descripcion, 
					   COALESCE(c.nombre_marca, 'Sin Marca') AS nombre_marca,
					   a.precio_unit_compra,
					   a.precio_venta_prod, 
					   a.subtotal_compra, 
					   e.monto_compra,
					   e.compra_credito,
					   e.cancelado
				  FROM tb_compra_producto AS a 
			INNER JOIN tb_compra AS e ON a.id_compra = e.id_compra 
			INNER JOIN tb_producto AS b ON a.id_producto = b.id_producto 
			 LEFT JOIN tb_marca AS c ON b.id_marca = c.id_marca 
			     WHERE e.id_compra = $idCompra";
		return parent::ejecutar($sql);
	}
	public function marcarCanceladoCompra($idcompra)
	{
		$sql = "UPDATE tb_compra
		           set cancelado = 1
		         WHERE id_compra  = $idcompra";
		if (parent::ejecutar($sql))
			return true;
		else
			return false;
	}
}
