 <?php
	include_once('conexion.php');
	class Venta extends Conexion
	{
		private $id_venta;
		private $fecha_venta;
		private $monto_venta;
		private $venta_facturada;
		private $precio_facturaV;
		private $tipo_venta;
		private $id_empleado;
		private $venta_credito;
		private $cancelado;
		private $venta_cerrada;
		private $estado;
		private $usuario_baja;
		private $fecha_baja;
		private $fecha_registro;

		public function Venta()
		{
			parent::Conexion();
			$this->id_venta = 0;
			$this->fecha_venta = "";
			$this->monto_venta = "";
			$this->venta_facturada = "";
			$this->precio_facturaV = 0;
			$this->tipo_venta = "";
			$this->id_empleado = 0;
			$this->venta_credito = 0;
			$this->cancelado = 0;
			$this->venta_cerrada = 0;
			$this->estado = "";
			$this->usuario_baja = 0;
			$this->fecha_baja = "";
			$this->fecha_registro= "";
		}

		public function setid_venta($valor)
		{
			$this->id_venta = $valor;
		}
		public function getid_venta()
		{
			return $this->id_venta;
		}
		public function set_fechaventa($valor)
		{
			$this->fecha_venta = $valor;
		}
		public function get_fechaventa()
		{
			return $this->fecha_venta;
		}
		public function set_montoVenta($valor)
		{
			$this->monto_venta = $valor;
		}
		public function get_montoVenta()
		{
			return $this->monto_venta;
		}

		public function set_ventaFacturada($valor)
		{
			$this->venta_facturada = $valor;
		}
		public function get_ventaFacturada()
		{
			return $this->venta_facturada;
		}

		public function set_precioFacturaV($valor)
		{
			$this->precio_facturaV = $valor;
		}
		public function get_precioFacturaV()
		{
			return $this->precio_facturaV;
		}

		public function set_tipoVenta($valor)
		{
			$this->tipo_venta = $valor;
		}
		public function get_tipoVenta()
		{
			return $this->tipo_venta;
		}

		public function set_idempleado($valor)
		{
			$this->id_empleado = $valor;
		}
		public function get_idempleado()
		{
			return $this->id_empleado;
		}

		public function set_ventaCredito($valor)
		{
			$this->venta_credito = $valor;
		}
		public function get_ventaCredito()
		{
			return $this->venta_credito;
		}
		public function set_cancelado($valor)
		{
			$this->cancelado = $valor;
		}
		public function get_cancelado()
		{
			return $this->cancelado;
		}

		public function set_ventaCerrada($valor)
		{
			$this->venta_cerrada = $valor;
		}
		public function get_ventaCerrada()
		{
			return $this->venta_cerrada;
		}


		public function set_estadoVenta($valor)
		{
			$this->estado = $valor;
		}
		public function get_estadoVenta()
		{
			return $this->estado;
		}

		public function set_usuarioBaja($valor)
		{
			$this->usuario_baja = $valor;
		}
		public function get_usuarioBaja()
		{
			return $this->usuario_baja;
		}

		public function set_fechBaja($valor)
		{
			$this->fecha_baja = $valor;
		}
		public function get_fechaBaja()
		{
			return $this->fecha_baja;
		}
		public function set_fechaRegistro($valor)
		{
			$this->fecha_registro = $valor;
		}
		public function get_fechaRegistro()
		{
			return $this->fecha_registro;
		}



		public function guardarVenta()
		{
			$sql = "INSERT INTO tb_venta(fecha_venta,
			                             monto_venta,
										 venta_facturada,
										 precio_facturaV,
										 tipo_venta,
										 id_empleado,
										 venta_credito,
										 cancelado,
										 venta_cerrada,
										 estado,
										 usuario_baja,
										 fecha_baja,
										 fecha_registro) 
								  VALUES('$this->fecha_venta',
								         '$this->monto_venta',
										 '$this->venta_facturada',
										 '$this->precio_facturaV',
										 '$this->tipo_venta',
										 '$this->id_empleado',
										 '$this->venta_credito',
										 '$this->cancelado',
										 '$this->venta_cerrada',
										 '$this->estado',
										 '$this->usuario_baja',
										 '$this->fecha_baja',
										 '$this->fecha_registro')";
			if (parent::ejecutar($sql))
				return true;
			else
				return false;
		}
		/*public function guardarVenta()
		{
			$sql = "INSERT INTO tb_venta(fecha_venta, monto_venta, venta_facturada, precio_facturaV, tipo_venta, id_empleado, estado, usuario_baja, fecha_baja) 
            VALUES('$this->fecha_venta', '$this->monto_venta', '$this->venta_facturada', '$this->precio_facturaV', '$this->tipo_venta', '$this->id_empleado', '$this->estado', '$this->usuario_baja', NULL)";

			$query = parent::ejecutar($sql);

			if ($query) {
				return true;
			} else {
				die("Error en SQL: " . mysqli_error($this->conectar()) . " | Consulta: " . $sql);
				return false;
			}
		}*/

		public function mostrarUltimaVenta()
		{
			$sql = "SELECT MAX(id_venta)as ultVenta FROM tb_venta  ";
			return parent::ejecutar($sql);
		}

		public function reporteVentas($fechaIni, $fechaFin)
		{
			$sql = "SELECT 
						a.id_venta,
						d.nombre_producto,
						d.codigo_producto,
						d.descripcion,
						f.nombre_marca,
						a.fecha_venta,
						a.venta_facturada,
						b.cantidad_prod,
						b.precio_unitario_venta,
						b.subtotal_venta,
						b.precio_venta_establecido,
						e.id_cliente,
						c.id_compra, 
						c.id_compra_producto,
						b.precio_compra_prod,
					
                        (CASE  WHen a.tipo_venta= 'admin'
                                                THEN (SELECT h.nombre_administrador FROM tb_administrador as h WHERE h.id_administrador=a.id_empleado)
                                                ELSE
                                                     (SELECT g.nombre_empleado FROM tb_empleado as g WHERE g.id_empleado=a.id_empleado)
                                                END )AS Usuario,
                            h.nombre_almacen
			        FROM tb_venta as a 
			  INNER JOIN tb_venta_producto as b 
			          ON a.id_venta=b.id_venta
			  INNER JOIN tb_compra_producto as c 
			          ON b.id_compra_producto=c.id_compra_producto
			  INNER JOIN tb_producto as d 
			          ON c.id_producto=d.id_producto
			  LEFT JOIN tb_marca as f 
			          ON d.id_marca=f.id_marca
			  INNER JOIN tb_almacen as h 
			          ON d.id_almacen=h.id_almacen

			  LEFT  JOIN tb_venta_cliente as e ON a.id_venta=e.id_venta
			  WHERE a.fecha_venta BETWEEN '$fechaIni' AND '$fechaFin'
			    and a.estado='Activo'
			    and b.estado='Activo'
              ORDER BY a.id_venta ASC";
			return parent::ejecutar($sql);
		}

		public function reporteVentasDeUnAlmacen($fechaIni, $fechaFin, $idemp)
		{
			$sql = "SELECT 
						a.id_venta,
						d.nombre_producto,
						d.codigo_producto,
						d.descripcion,
						f.nombre_marca,
						a.fecha_venta,
						a.venta_facturada,
						b.cantidad_prod,
						b.precio_unitario_venta,
						b.subtotal_venta,
						b.precio_venta_establecido,
						e.id_cliente,
						c.id_compra,
						c.id_compra_producto,
						b.precio_compra_prod,
					
                        (CASE  WHen a.tipo_venta= 'admin'
                                                THEN (SELECT h.nombre_administrador FROM tb_administrador as h WHERE h.id_administrador=a.id_empleado)
                                                ELSE
                                                     (SELECT g.nombre_empleado FROM tb_empleado as g WHERE g.id_empleado=a.id_empleado)
                                                END )AS Usuario,
                           h.nombre_almacen
			       FROM tb_venta as a 
			  INNER JOIN tb_venta_producto as b 
			          ON a.id_venta=b.id_venta
			  INNER JOIN tb_compra_producto as c 
			          ON b.id_compra_producto=c.id_compra_producto
			  INNER JOIN tb_producto as d 
			          ON c.id_producto=d.id_producto
			  LEFT JOIN tb_marca as f 
			          ON d.id_marca=f.id_marca
			  INNER JOIN tb_almacen as h 
			         ON d.id_almacen=h.id_almacen

			  LEFT  JOIN tb_venta_cliente as e ON a.id_venta=e.id_venta
			  WHERE a.fecha_venta BETWEEN '$fechaIni' AND '$fechaFin'
			    and a.estado='Activo'
			    and b.estado='Activo'
			    and d.id_almacen=$idemp
              ORDER BY a.id_venta ASC";
			return parent::ejecutar($sql);
		}


		public function reporteVentasDeUnEmpleadoParaCierre($fechaIni, $fechaFin)
		{
			$sql = "SELECT 
				    a.id_venta,
				    a.fecha_venta,
				    a.venta_facturada,			
				    e.id_cliente,
                    sum(b.cantidad_prod) as cantidad_productos,
                    a.monto_venta,
                    sum(b.precio_compra_prod*b.cantidad_prod) as monto_costoProducto, 
                    (a.monto_venta-sum(b.precio_compra_prod*b.cantidad_prod)) as ganancia,
					(CASE  WHen a.tipo_venta= 'admin'
                           THEN (SELECT h.nombre_administrador FROM tb_administrador as h WHERE h.id_administrador=a.id_empleado)
                           ELSE
                                (SELECT g.nombre_empleado FROM tb_empleado as g WHERE g.id_empleado=a.id_empleado)
                           END )AS Usuario,
                       -- g.nombre_empleado  AS Usuario,
					
                    (case WHEN e.id_cliente IS NULL AND a.id_venta>0
                          THEN 'Sin cliente'
                          WHEN e.id_cliente>0 AND a.id_venta>0
                         THEN (SELECT concat(x.nombre_cliente,' ',x.apellido_cliente)
                                 FROM tb_cliente as x
                                WHERE x.id_cliente=e.id_cliente)
                              ELSE ''
                           END) as cliente  /*Muestra el cliente de la venta si tuviera*/
			        FROM tb_venta as a 
			  INNER JOIN tb_venta_producto as b 
			          ON a.id_venta=b.id_venta	  
              -- INNER JOIN tb_empleado as g
                   --   on g.id_empleado=a.id_empleado	
			  LEFT  JOIN tb_venta_cliente as e 
			          ON a.id_venta=e.id_venta
			       -- WHERE cast(a.fecha_venta as date) = '$fechaIni'
				   WHERE a.fecha_venta BETWEEN '$fechaIni' AND '$fechaFin'
			         and a.estado='Activo'
					 and a.venta_cerrada=0
			         and b.estado='Activo'
			         -- and a.id_empleado=
			         -- and a.tipo_venta= 'empl'
                   
                   GROUP BY a.id_venta 
                   ORDER BY a.id_venta ASC";
			return parent::ejecutar($sql);
		}
		/*----------  REPORTE DE MIS VENTAS (de un usuario)-------*/
		public function reporteMisVentasDia($idemp, $tipoUser, $fecha)
		{
			$sql = "SELECT 
						a.id_venta,
						d.nombre_producto,
						d.codigo_producto,
						d.descripcion,
						f.nombre_marca,
						a.fecha_venta,
						a.venta_facturada,
						b.cantidad_prod,
						b.precio_unitario_venta,
						b.subtotal_venta,
						b.precio_venta_establecido,
						e.id_cliente,
						c.id_compra_producto,
						b.precio_compra_prod,
					
                        (CASE  WHen a.tipo_venta= 'admin'
                                                THEN (SELECT h.nombre_administrador FROM tb_administrador as h WHERE h.id_administrador=a.id_empleado)
                                                ELSE
                                                     (SELECT g.nombre_empleado FROM tb_empleado as g WHERE g.id_empleado=a.id_empleado)
                                                END )AS Usuario
			FROM 
			             tb_venta as a 
			  INNER JOIN tb_venta_producto as b ON a.id_venta=b.id_venta
			  INNER JOIN tb_compra_producto as c ON b.id_compra_producto=c.id_compra_producto
			  INNER JOIN tb_producto as d ON c.id_producto=d.id_producto
			  INNER JOIN tb_marca as f ON d.id_marca=f.id_marca
			 -- INNER JOIN tb_empleado as g ON a.id_empleado=g.id_empleado

			  LEFT  JOIN tb_venta_cliente as e ON a.id_venta=e.id_venta
			  WHERE cast(a.fecha_venta as date) ='$fecha'
			    and a.estado='Activo'
			    and b.estado='Activo'
			    and a.id_empleado=$idemp
			    and a.tipo_venta='$tipoUser'
              ORDER BY a.id_venta ASC";
			return parent::ejecutar($sql);
		}


		public function reporteGanacias($fechaIni, $fechaFin)
		{
			$sql = "SELECT 
						a.id_venta,
						d.nombre_producto,
						d.codigo_producto,
						d.descripcion,
						f.nombre_marca,
						a.fecha_venta,
						a.venta_facturada,
						b.cantidad_prod,
						b.precio_unitario_venta,
						b.subtotal_venta,
						b.precio_venta_establecido,
						e.id_cliente,
						c.id_compra_producto,
						b.precio_compra_prod,
					
                        (CASE  WHen a.tipo_venta= 'admin'
                                                THEN (SELECT h.nombre_administrador FROM tb_administrador as h WHERE h.id_administrador=a.id_empleado)
                                                ELSE
                                                     (SELECT g.nombre_empleado FROM tb_empleado as g WHERE g.id_empleado=a.id_empleado)
                                                END )AS Usuario,
                            h.nombre_almacen
			       FROM  tb_venta as a 
			  INNER JOIN tb_venta_producto as b 
			          ON a.id_venta=b.id_venta
			  INNER JOIN tb_compra_producto as c 
			          ON b.id_compra_producto=c.id_compra_producto
			  INNER JOIN tb_producto as d 
			          ON c.id_producto=d.id_producto
			  LEFT JOIN tb_marca as f 
			          ON d.id_marca=f.id_marca
			  LEFT JOIN tb_almacen as h 
			         ON d.id_almacen=h.id_almacen

			  LEFT  JOIN tb_venta_cliente as e ON a.id_venta=e.id_venta
			  WHERE a.fecha_venta BETWEEN '$fechaIni' AND '$fechaFin'
			    and a.estado='Activo'
			    and b.estado='Activo'
              ORDER BY a.id_venta ASC";
			return parent::ejecutar($sql);
		}
		public function reporteGanaciasUnAlmacen($fechaIni, $fechaFin, $idemp)
		{
			$sql = "SELECT 
						a.id_venta,
						d.nombre_producto,
						d.codigo_producto,
						d.descripcion,
						f.nombre_marca,
						a.fecha_venta,
						a.venta_facturada,
						b.cantidad_prod,
						b.precio_unitario_venta,
						b.subtotal_venta,
						b.precio_venta_establecido,
						e.id_cliente,
						c.id_compra_producto,
						b.precio_compra_prod,
					
                        (CASE  WHen a.tipo_venta= 'admin'
                                                THEN (SELECT h.nombre_administrador FROM tb_administrador as h WHERE h.id_administrador=a.id_empleado)
                                                ELSE
                                                     (SELECT g.nombre_empleado FROM tb_empleado as g WHERE g.id_empleado=a.id_empleado)
                                                END )AS Usuario,
                            h.nombre_almacen
			       FROM  tb_venta as a 
			  INNER JOIN tb_venta_producto as b 
			          ON a.id_venta=b.id_venta
			  INNER JOIN tb_compra_producto as c 
			          ON b.id_compra_producto=c.id_compra_producto
			  INNER JOIN tb_producto as d 
			          ON c.id_producto=d.id_producto
			  INNER JOIN tb_marca as f 
			          ON d.id_marca=f.id_marca
			  INNER JOIN tb_almacen as h 
			         ON d.id_almacen=h.id_almacen

			  LEFT  JOIN tb_venta_cliente as e ON a.id_venta=e.id_venta
			  WHERE a.fecha_venta BETWEEN '$fechaIni' AND '$fechaFin'
			    and a.estado='Activo'
			    and b.estado='Activo'
			    and d.id_almacen=$idemp
              ORDER BY a.id_venta ASC";
			return parent::ejecutar($sql);
		}


		public function nota_Venta($idventa)
		{
			$sql = "SELECT 
			            a.id_venta_producto,
						a.cantidad_prod, 
						b.nombre_producto, 
						b.codigo_producto,
						b.descripcion,
						COALESCE(c.nombre_marca, 'Sin Marca') AS nombre_marca, 
						a.codigo_prod, 
						a.precio_unitario_venta, 
						a.subtotal_venta, 
						e.monto_venta,
						d.id_compra_producto
					FROM tb_venta_producto AS a
					INNER JOIN tb_venta AS e ON a.id_venta = e.id_venta
					INNER JOIN tb_compra_producto AS d ON a.id_compra_producto = d.id_compra_producto
					INNER JOIN tb_producto AS b ON d.id_producto = b.id_producto
					LEFT JOIN tb_marca AS c ON b.id_marca = c.id_marca
					-- Si quisieras agregar categoría en el futuro:
					-- LEFT JOIN tb_categoria AS cat ON b.id_categoria = cat.id_categoria 
					WHERE a.estado = 'Activo'
					     AND e.id_venta = $idventa";
			return parent::ejecutar($sql);
		}

		public function mostrarDatosDeVenta($idventa)
		{
			$sql = "SELECT   a.fecha_venta,
				       concat(c.nombre_cliente,' ',c.apellido_cliente) as cliente,
					   venta_credito,
					   (CASE 
						WHEN a.tipo_venta = 'admin' THEN 
							(SELECT h.nombre_administrador FROM tb_administrador AS h WHERE h.id_administrador = a.id_empleado)
						ELSE
							(SELECT g.nombre_empleado FROM tb_empleado AS g WHERE g.id_empleado = a.id_empleado)
					    END) AS Usuario
				  FROM tb_venta as a 
			 LEFT JOIN tb_venta_cliente as b ON a.id_venta=b.id_venta
			 LEFT JOIN tb_cliente as c ON b.id_cliente=c.id_cliente
			 	 WHERE a.id_venta=$idventa";
			return parent::ejecutar($sql);
		}
		/*da de baja una venta*/
		public function darBajaVenta($idventa)
		{
			$sql = "UPDATE tb_venta
		         set usuario_baja='$this->usuario_baja',
		             estado='Inactivo',
		             fecha_baja='$this->fecha_baja'
		       WHERE id_venta=$idventa
		         and estado='Activo'";
			if (parent::ejecutar($sql))
				return true;
			else
				return false;
		}
		public function ReporteVentasActivas($fechaini, $fechafin)
		{
			$sql = "SELECT 
					a.id_venta AS idventa,
					a.venta_facturada,
					a.monto_venta,
					a.fecha_venta, 
					c.nombre_cliente AS nameCliente,
					a.venta_credito,
					a.cancelado,
					(CASE 
						WHEN a.tipo_venta = 'admin' THEN 
							(SELECT h.nombre_administrador FROM tb_administrador AS h WHERE h.id_administrador = a.id_empleado)
						ELSE
							(SELECT g.nombre_empleado FROM tb_empleado AS g WHERE g.id_empleado = a.id_empleado)
					END) AS Usuario	
				FROM tb_venta AS a
			LEFT JOIN tb_venta_cliente as b ON a.id_venta=b.id_venta
			 LEFT JOIN tb_cliente as c ON b.id_cliente=c.id_cliente
				WHERE a.estado = 'Activo'	
			AND CAST(a.fecha_venta AS DATE) BETWEEN '$fechaini' AND '$fechafin'
				ORDER BY a.id_venta DESC;";
			return parent::ejecutar($sql);
		}

		public function marcarCanceladoVenta($idventa)
		{
			$sql = "UPDATE tb_venta
		           set cancelado = 1
		         WHERE id_venta  = $idventa";
			if (parent::ejecutar($sql))
				return true;
			else
				return false;
		}
		public function marcarCerradoVenta($idventa)
		{
			$sql = "UPDATE tb_venta
		           set venta_cerrada = 1
		         WHERE id_venta  = $idventa";
			if (parent::ejecutar($sql))
				return true;
			else
				return false;
		}
		public function actualizarMontoVenta($idventa,$montoVenta)
		{
			$sql = "UPDATE tb_venta
		           set monto_venta = $montoVenta
		         WHERE id_venta  = $idventa";
			if (parent::ejecutar($sql))
				return true;
			else
				return false;
		}
	}
	?>