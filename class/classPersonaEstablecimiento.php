<?php

class PersonaEstablecimiento {
	public function __construct()
	{
	}

	/**
	 * @param $id
	 * @param $db
	 * @return stdClass
	 */
	public function get($id, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT *
                                    FROM prm_persona_establecimiento pe
									JOIN prm_tipo_contrato ptc on pe.con_id = ptc.con_id
                                    WHERE pes_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->pes_id = $row['pes_id'];
		$obj->per_id = $row['per_id'];
		$obj->est_id = $row['est_id'];
		$obj->con_id = $row['con_id'];
		$obj->con_descripcion = $row['con_descripcion'];
		$obj->pes_correlativo = $row['pes_correlativo'];
		$obj->pes_horas = $row['pes_horas'];
		$obj->pes_activo = $row['pes_activo'];

		unset($db);
		return $obj;
	}

	/**
	 * @param $db
	 * @return array
	 */
	public function getAll($db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT per_id FROM prm_persona");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['per_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $per
	 * @param $est
	 * @param null $db
	 * @return mixed
	 */
	public function getTotalContratos($per, $est, $db = null)
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT SUM(pes_horas) as horas
                                    FROM prm_persona_establecimiento pe
									JOIN prm_tipo_contrato ptc on pe.con_id = ptc.con_id
                                    WHERE per_id = ? AND est_id = ?");

		$stmt->bind_param("ii", $per, $est);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		return $row['horas'];
	}

	/**
	 * @param $type
	 * @param $est
	 * @param null $db
	 * @return mixed
	 */
	public function getTotalContratosByType($type, $est, $db = null)
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$cond = ($type == 1) ? 'IN' : 'NOT IN';
		$estab = (!is_null($est)) ? 'AND est_id = ?' : '';

		$stmt = $db->Prepare("SELECT COUNT(p.per_id) as contratos
                                    FROM prm_persona p
									JOIN prm_persona_establecimiento pe ON p.per_id = pe.per_id
                                    WHERE pes_activo IS TRUE AND prof_id $cond (4,14,16) $estab");

		if (!is_null($est)) $stmt->bind_param("i", $est);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		return $row['contratos'];
	}

	/**
	 * @param $id
	 * @param $estab
	 * @param $cont
	 * @param $corr
	 * @param $horas
	 * @param null $db
	 * @return array
	 */
	public function set($id, $estab, $cont, $corr, $horas, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_persona_establecimiento (per_id, est_id, con_id, pes_correlativo, pes_horas, pes_activo) 
                                     VALUES (?, ?, NULLIF(?,''), NULLIF(?,''), NULLIF(?,''), TRUE)");

			if (!$stmt):
				throw new Exception("La inserción del contrato falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("iisss", $id, $estab, $cont, $corr, $horas);
			if (!$bind):
				throw new Exception("La inserción del contrato falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción del contrato falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $stmt->insert_id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param $con
	 * @param $corr
	 * @param $horas
	 * @param null $db
	 * @return array|bool[]
	 */
	public function mod($id, $con, $corr, $horas, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_persona_establecimiento SET con_id = ?, pes_correlativo = ?, pes_horas = ? WHERE pes_id = ?");

			if (!$stmt):
				throw new Exception("La edición del contrato falló en su preparación.");
			endif;

			$con = $db->clearText($con);
			$corr = $db->clearText($corr);
			$horas = $db->clearText($horas);
			$bind = $stmt->bind_param("iiii", $con, $corr, $horas, $id);
			if (!$bind):
				throw new Exception("La edición del contrato falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La edición del contrato falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param $state
	 * @param $db
	 * @return array|bool[]
	 */
	public function setState($id, $state, $db): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_persona_establecimiento SET pes_activo = ? WHERE pes_id = ?");

			if (!$stmt):
				throw new Exception("El cambio de estado de contrato falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("ii", $state, $id);
			if (!$bind):
				throw new Exception("El cambio de estado de contrato falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("El cambio de estado de contrato falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}