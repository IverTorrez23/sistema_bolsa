<?php
include_once('conexion.php');
class CuotaCompra extends Conexion
{
	private $id_cuota_compra;
	private $fecha_cuota;
	private $monto_cuota;
	private $id_compra;
	private $usuario_alta;
	private $estado;
	private $tipo_reg;
	private $usuario_baja;
	private $fecha_baja;

	public function CuotaCompra()
	{
		parent::Conexion();
		$this->id_cuota_compra = 0;
		$this->fecha_cuota = "";
		$this->monto_cuota = "";
		$this->id_compra = 0;
		$this->usuario_alta = 0;
		$this->estado = "";
		$this->tipo_reg = "";
		$this->usuario_baja = 0;
		$this->fecha_baja = 0;
	}

	public function setid_cuotaCompra($valor)
	{
		$this->id_cuota_compra = $valor;
	}
	public function getid_cuotaCompra()
	{
		return $this->id_cuota_compra;
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

	public function set_idCompra($valor)
	{
		$this->id_compra = $valor;
	}
	public function get_idCompra()
	{
		return $this->id_compra;
	}


	public function set_idAdmin($valor)
	{
		$this->usuario_alta = $valor;
	}
	public function get_idAdmin()
	{
		return $this->usuario_alta;
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



	public function guardarCuotaCompra()
	{
		$sql = "INSERT INTO tb_cuota_compra(fecha_cuota,
			                        monto_cuota,
			                        id_compra,
			                        usuario_alta,
			                        estado,
			                        tipo_reg,
			                        usuario_baja,
			                        fecha_baja) 
		                      VALUES('$this->fecha_cuota',
		                      	     '$this->monto_cuota',
		                      	     '$this->id_compra',
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
					a.id_cuota_compra  AS idcuotacompra,
					a.fecha_cuota,
					a.monto_cuota,
					a.id_compra
				FROM tb_cuota_compra AS a
				WHERE a.estado = 'Activo'

				ORDER BY a.id_cuota_compra ASC;";
		return parent::ejecutar($sql);
	}

	public function eliminarCompra()
	{
		$sql = "UPDATE tb_cuota_compra 
		         SET estado='$this->estado',
		             usuario_baja='$this->usuario_baja',
		             fecha_baja='$this->fecha_baja'
		       WHERE id_cuota_compra='$this->id_cuota_compra' ";
		if (parent::ejecutar($sql))
			return true;
		else
			return false;
	}

	public function ReporteGeneralCuotasCompraActivas()
	{
		$sql = "SELECT 
					a.id_cuota_compra AS idcuotacompra,
					a.fecha_cuota,
					a.monto_cuota,
					a.id_compra, 
					(CASE 
						WHEN a.tipo_reg = 'admin' THEN 
							(SELECT h.nombre_administrador FROM tb_administrador AS h WHERE h.id_administrador = a.usuario_alta)
						ELSE
							(SELECT g.nombre_empleado FROM tb_empleado AS g WHERE g.id_empleado = a.usuario_alta)
					END) AS Usuario	
				FROM tb_cuota_compra AS a
				WHERE a.estado = 'Activo'	
				ORDER BY a.id_cuota_compra DESC;";
		return parent::ejecutar($sql);
	}

	public function ReporteCuotaComprasActivasDeUnaCompra($idCompra)
	{
		$sql = "SELECT 
					a.id_cuota_compra AS idcuotacompra,
					a.fecha_cuota,
					a.monto_cuota,
					a.id_compra, 
					(CASE 
						WHEN a.tipo_reg = 'admin' THEN 
							(SELECT h.nombre_administrador FROM tb_administrador AS h WHERE h.id_administrador = a.usuario_alta)
						ELSE
							(SELECT g.nombre_empleado FROM tb_empleado AS g WHERE g.id_empleado = a.usuario_alta)
					END) AS Usuario	
				FROM tb_cuota_compra AS a
				WHERE a.estado = 'Activo'
                AND a.id_compra=$idCompra	
				ORDER BY a.id_cuota_compra DESC;";
		return parent::ejecutar($sql);
	}
    public function sumatoriaDeCuotaDeCompra($idCompra)
	{
		$sql = "SELECT 
					SUM(monto_cuota) sumaCuotas 
				FROM tb_cuota_compra AS a
				WHERE id_compra = $idCompra
                AND estado = 'Activo'";
		return parent::ejecutar($sql);
	}
	
}
