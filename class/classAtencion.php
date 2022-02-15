<?php

class Atencion {

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

		$stmt = $db->Prepare("SELECT a.* FROM prm_atencion a
                                    WHERE a.at_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->at_id = $row['at_id'];
		$obj->at_actid = $row['act_id'];
		$obj->at_perid = $row['per_id'];
		$obj->at_fecha = $row['at_fecha'];
		$obj->at_cantidad = $row['at_cantidad'];
		$obj->at_estabid = $row['est_id'];

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

		$stmt = $db->Prepare("SELECT at_id FROM prm_atencion");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['at_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $per
	 * @param $fecha
	 * @param $comite
	 * @param $est
	 * @param $db
	 * @return array
	 */
	public function getByPerComite($per, $fecha, $comite, $est, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT a.* FROM prm_atencion a
                                    JOIN prm_actividad ac ON a.act_id = ac.act_id
                                    WHERE a.per_id = ? AND a.at_fecha = ? AND ac.act_comite = ? AND a.est_id = ?");

		$per = $db->clearText($per);
		$fecha = $db->clearText($fecha);
		$est = $db->clearText($est);
		$stmt->bind_param("isii", $per, $fecha, $comite, $est);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['at_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $tat
	 * @param $act
	 * @param $per
	 * @param $fecha
	 * @param $estab
	 * @param null $db
	 * @return stdClass
	 */
	public function getByParams($tat, $act, $per, $fecha, $estab, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT a.* FROM prm_atencion a
                                    WHERE a.tat_id = ? AND a.act_id = ? AND a.per_id = ? AND a.at_fecha = ? AND a.est_id = ?");

		$tat = $db->clearText($tat);
		$act = $db->clearText($act);
		$per = $db->clearText($per);
		$fecha = $db->clearText($fecha);
		$estab = $db->clearText($estab);
		$stmt->bind_param("iiisi", $tat, $act, $per, $fecha, $estab);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->at_id = $row['at_id'];
		$obj->at_actid = $row['act_id'];
		$obj->at_perid = $row['per_id'];
		$obj->at_fecha = $row['at_fecha'];
		$obj->at_cantidad = $row['at_cantidad'];
		$obj->at_estabid = $row['est_id'];

		unset($db);
		return $obj;
	}

	/**
	 * @param $est
	 * @param $planta
	 * @param $year
	 * @param null $db
	 * @return array
	 */
	public function getTotalByEstYear($est, $planta, $year, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$string = '';
		if (!is_null($est)) $string .= 'AND a.est_id = ?';

		$planta = ($planta == 1) ? 'AND (p.prof_id = 4 OR p.prof_id = 14 OR p.prof_id = 16)' : 'AND (p.prof_id != 4 AND p.prof_id != 14 AND p.prof_id != 16)';

		$stmt = $db->Prepare("SELECT SUM(at_cantidad) AS suma, DATE_FORMAT(at_fecha, '%b') AS mes
								FROM prm_atencion a
								JOIN prm_actividad ac ON a.act_id = ac.act_id
								JOIN prm_persona p ON a.per_id = p.per_id
								WHERE ac.act_comite IS FALSE $string AND YEAR(at_fecha) = ?
								$planta
								GROUP BY mes, at_fecha
								ORDER BY at_fecha");

		if (is_null($est)):
			$year = $db->clearText($year);
			$stmt->bind_param("i", $year);
		else:
			$est = $db->clearText($est);
			$year = $db->clearText($year);
			$stmt->bind_param("ii", $est, $year);
		endif;

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = array('mes' => $row['mes'], 'cantidad' => $row['suma']);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $tat
	 * @param $act
	 * @param $per
	 * @param $fecha
	 * @param $estab
	 * @param null $db
	 * @return array
	 */
	public function set($tat, $act, $per, $fecha, $estab, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_atencion (tat_id, act_id, per_id, at_fecha, at_cantidad, est_id) 
                                     VALUES (?, ?, ?, ?, 1, ?)");

			if (!$stmt):
				throw new Exception("La inserción de la atención falló en su preparación.");
			endif;

			$tat = $db->clearText($tat);
			$act = $db->clearText($act);
			$per = $db->clearText($per);
			$fecha = $db->clearText($fecha);
			$estab = $db->clearText($estab);
			$bind = $stmt->bind_param("iiisi", $tat, $act, $per, $fecha, $estab);
			if (!$bind):
				throw new Exception("La inserción de la atención falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de la atención falló en su ejecución.");
			endif;

			$stmt->close();
			return array('estado' => true, 'msg' => $stmt->insert_id);
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param $db
	 * @return array
	 */
	public function update($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_atencion SET at_cantidad = at_cantidad + 1 WHERE at_id = ?");

			if (!$stmt):
				throw new Exception("La inserción de la atención falló en su preparación.");
			endif;

			$id = $db->clearText($id);
			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La inserción de la atención falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de la atención falló en su ejecución.");
			endif;

			$stmt->close();
			return array('estado' => true, 'msg' => $stmt->insert_id);
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}

