<?php
include_once('conexion.php');
class CuotaVenta extends Conexion
{
	private $id_cuota_venta;
	private $fecha_cuota;
	private $monto_cuota;
	private $id_venta;
	private $usuario_alta;
	private $estado;
	private $tipo_reg;
	private $usuario_baja;
	private $fecha_baja;

	public function CuotaVenta()
	{
		parent::Conexion();
		$this->id_cuota_venta = 0;
		$this->fecha_cuota = "";
		$this->monto_cuota = "";
		$this->id_venta = 0;
		$this->usuario_alta = 0;
		$this->estado = "";
		$this->tipo_reg = "";
		$this->usuario_baja = 0;
		$this->fecha_baja = 0;
	}

	public function setid_cuotaVenta($valor)
	{
		$this->id_cuota_venta = $valor;
	}
	public function getid_cuotaVenta()
	{
		return $this->id_cuota_venta;
	}
	public function set_fechaCuota($valor)
	{
		$this->fecha_cuota = $valor;
	}
	public function get_fechaCuota()
	{
		return $this->fecha_cuota;
	}
	public function set_montoCuota($valor)
	{
		$this->monto_cuota = $valor;
	}
	public function get_montoCuota()
	{
		return $this->monto_cuota;
	}

	public function set_idVenta($valor)
	{
		$this->id_venta = $valor;
	}
	public function get_idVenta()
	{
		return $this->id_venta;
	}


	public function set_idAdmin($valor)
	{
		$this->usuario_alta = $valor;
	}
	public function get_idAdmin()
	{
		return $this->usuario_alta;
	}
	

	public function set_estadoCoutaVenta($valor)
	{
		$this->estado = $valor;
	}
	public function get_estadoCuotaVenta()
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



	public function guardarCuotaVenta()
	{
		$sql = "INSERT INTO tb_cuota_venta(fecha_cuota,
			                        monto_cuota,
			                        id_venta,
			                        usuario_alta,
			                        estado,
			                        tipo_reg,
			                        usuario_baja,
			                        fecha_baja) 
		                      VALUES('$this->fecha_cuota',
		                      	     '$this->monto_cuota',
		                      	     '$this->id_venta',
		                      	     '$this->usuario_alta',
		                      	     '$this->estado',
		                      	     '$this->tipo_reg',
		                      	     '$this->usuario_baja',
		                      	     '$this->fecha_baja')";
		if (parent::ejecutar($sql))
			return true;
		else
			return false;
	}

	public function listarCuotas()
	{
		$sql = "SELECT 
					a.id_cuota_venta  AS idcuotaventa,
					a.fecha_cuota,
					a.monto_cuota,
					a.id_venta
				FROM tb_cuota_venta AS a
				WHERE a.estado = 'Activo'

				ORDER BY a.id_cuota_venta ASC;";
		return parent::ejecutar($sql);
	}

	public function eliminarCuotaVenta()
	{
		$sql = "UPDATE tb_cuota_venta 
		         SET estado='$this->estado',
		             usuario_baja='$this->usuario_baja',
		             fecha_baja='$this->fecha_baja'
		       WHERE id_cuota_venta='$this->id_cuota_venta' ";
		if (parent::ejecutar($sql))
			return true;
		else
			return false;
	}

	public function ReporteGeneralCuotasVentaActivas()
	{
		$sql = "SELECT 
					a.id_cuota_venta AS idcuotaventa,
					a.fecha_cuota,
					a.monto_cuota,
					a.id_venta, 
					(CASE 
						WHEN a.tipo_reg = 'admin' THEN 
							(SELECT h.nombre_administrador FROM tb_administrador AS h WHERE h.id_administrador = a.usuario_alta)
						ELSE
							(SELECT g.nombre_empleado FROM tb_empleado AS g WHERE g.id_empleado = a.usuario_alta)
					END) AS Usuario	
				FROM tb_cuota_venta AS a
				WHERE a.estado = 'Activo'	
				ORDER BY a.id_cuota_venta DESC;";
		return parent::ejecutar($sql);
	}

	public function ReporteCuotaVentasActivasDeUnaVenta($idVenta)
	{
		$sql = "SELECT 
					a.id_cuota_venta AS idcuotaventa,
					a.fecha_cuota,
					a.monto_cuota,
					a.id_venta, 
					(CASE 
						WHEN a.tipo_reg = 'admin' THEN 
							(SELECT h.nombre_administrador FROM tb_administrador AS h WHERE h.id_administrador = a.usuario_alta)
						ELSE
							(SELECT g.nombre_empleado FROM tb_empleado AS g WHERE g.id_empleado = a.usuario_alta)
					END) AS Usuario	
				FROM tb_cuota_venta AS a
				WHERE a.estado = 'Activo'
                AND a.id_venta=$idVenta	
				ORDER BY a.id_cuota_venta DESC;";
		return parent::ejecutar($sql);
	}
    public function sumatoriaDeCuotaDeVenta($idVenta)
	{
		$sql = "SELECT 
					SUM(monto_cuota) sumaCuotas 
				FROM tb_cuota_venta AS a
				WHERE id_venta = $idVenta
                AND estado = 'Activo'";
		return parent::ejecutar($sql);
	}
	
}
