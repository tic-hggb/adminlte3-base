<?php

class DistHorasProg {

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

		$stmt = $db->Prepare("SELECT * FROM prm_dist_horas_prog h
                                    JOIN prm_distribucion_prog d ON h.disp_id = d.disp_id
                                    WHERE h.dhp_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->dhp_id = $row['dhp_id'];
		$obj->dhp_distid = $row['disp_id'];
		$obj->dhp_pesid = $row['pes_id'];
		$obj->dhp_acpid = $row['acp_id'];
		$obj->dhp_fechaini = $row['disp_fecha_ini'];
		$obj->dhp_fechater = $row['disp_fecha_ter'];
		$obj->dhp_descripcion = $row['disp_descripcion'];
		$obj->dhp_cantidad = $row['dhp_cantidad'];
		$obj->dhp_rendimiento = $row['dhp_rendimiento'];
		$obj->dhp_observacion = utf8_encode($row['dhp_observacion']);

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

		$stmt = $db->Prepare("SELECT dhp_id FROM prm_dist_horas_prog");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['dhp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @param $db
	 * @return array
	 */
	public function getByDist($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT dhp_id FROM prm_dist_horas_prog WHERE disp_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['dhp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @param $date_i
	 * @param $date_t
	 * @param $db
	 * @return mixed
	 */
	public function getNumByPerDate($id, $date_i, $date_t, $db = null)
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT COUNT(dhp_id) AS num FROM prm_dist_horas_prog dh
                                    JOIN prm_distribucion_prog d ON dh.disp_id = d.disp_id
                                    WHERE d.pes_id = ? AND d.disp_fecha_ini = ? AND d.disp_fecha_ter = ?");

		$stmt->bind_param("iss", $id, $date_i, $date_t);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		$obj = $row['num'];

		unset($db);
		return $obj;
	}

	/**
	 * @param $id
	 * @param $th
	 * @param $date
	 * @param $db
	 * @return stdClass
	 */
	public function getByPerTHDate($id, $th, $date, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_dist_horas_prog dh
                                    JOIN prm_distribucion_prog d ON dh.disp_id = d.disp_id
									JOIN prm_actividad_prog pap on dh.acp_id = pap.acp_id
                                    WHERE d.pes_id = ? AND dh.acp_id = ? AND d.disp_fecha_ini = ?");

		$stmt->bind_param("iis", $id, $th, $date);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();

		$obj->dh_id = $row['dhp_id'];
		$obj->dh_pesid = $row['pes_id'];
		$obj->dh_acpid = $row['acp_id'];
		$obj->dh_acpdescripcion = utf8_encode($row['acp_descripcion']);
		$obj->dh_acpdescorta = utf8_encode($row['acp_desc_reporte']);
		$obj->dh_fecha = $row['disp_fecha_ini'];
		$obj->dh_cantidad = $row['dhp_cantidad'];
		$obj->dh_rendimiento = $row['dhp_rendimiento'];
		$obj->dh_observacion = utf8_encode($row['dhp_observacion']);

		if ($obj->dh_id == ''):
			$obj->dh_fecha = $date;
			$obj->dh_cantidad = '0.00';
			$obj->dh_rendimiento = '0.00';
			$obj->dh_observacion = '';
		endif;

		unset($db);
		return $obj;
	}

	/**
	 * @param $id
	 * @param $th
	 * @param $db
	 * @return stdClass
	 */
	public function getByDistTH($id, $th, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_dist_horas_prog dh
                                    JOIN prm_distribucion_prog d ON dh.disp_id = d.disp_id
                                    WHERE d.disp_id = ? AND dh.acp_id = ?");

		$stmt->bind_param("ii", $id, $th);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();

		$obj->dhp_id = $row['dhp_id'];
		$obj->dhp_pesid = $row['pes_id'];
		$obj->dhp_acpid = $row['acp_id'];
		$obj->dhp_fecha_ini = $row['disp_fecha_ini'];
		$obj->dhp_fecha_ter = $row['disp_fecha_ter'];
		$obj->dhp_cantidad = $row['dhp_cantidad'];
		$obj->dhp_rendimiento = $row['dhp_rendimiento'];
		$obj->dhp_observacion = utf8_encode($row['dhp_observacion']);

		if ($obj->dhp_id == ''):
			$obj->dhp_cantidad = '0.00';
			$obj->dhp_rendimiento = '0.00';
			$obj->dhp_observacion = '';
		endif;

		unset($db);
		return $obj;
	}

	/**
	 * @param $dist
	 * @param $date_i
	 * @param $date_t
	 * @param $thor
	 * @param $db
	 * @return stdClass
	 */
	public function getByTHDate($dist, $date_i, $date_t, $thor, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_dist_horas_prog dh
                                    JOIN prm_distribucion_prog d ON dh.disp_id = d.disp_id
                                    WHERE d.disp_id = ? AND d.disp_fecha_ini = ? AND d.disp_fecha_ter = ? AND acp_id = ?");

		$stmt->bind_param("issi", $dist, $date_i, $date_t, $thor);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();

		$obj->dhp_id = $row['dhp_id'];
		$obj->dhp_pesid = $row['pes_id'];
		$obj->dhp_acpid = $row['acp_id'];
		$obj->dhp_fecha_ini = $row['disp_fecha_ini'];
		$obj->dhp_fecha_ter = $row['disp_fecha_ter'];
		$obj->dhp_cantidad = $row['dhp_cantidad'];
		$obj->dhp_rendimiento = $row['dhp_rendimiento'];
		$obj->dhp_observacion = utf8_encode($row['dhp_observacion']);

		if ($obj->dhp_id == ''):
			$obj->dhp_cantidad = '0.00';
			$obj->dhp_rendimiento = '0.00';
			$obj->dhp_observacion = '';
		endif;

		unset($db);
		return $obj;
	}

	/**
	 * @param $disp
	 * @param $db
	 * @return string
	 */
	public function getByConsCont($disp, $db = null): string
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT SUM(dhp_cantidad * dhp_rendimiento) AS total FROM prm_dist_horas_prog dh
                                    JOIN prm_distribucion_prog d ON dh.disp_id = d.disp_id
                                    WHERE d.disp_id = ?
                                    AND (acp_id = 4 OR acp_id = 5 OR acp_id = 21)");

		$disp = $db->clearText($disp);
		$stmt->bind_param("i", $disp);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$obj = $row['total'];
		if ($obj == ''): $obj = '0.00';
		else: $obj = number_format($obj, 2, '.', '');
		endif;

		unset($db);
		return $obj;
	}

	/**
	 * @param $disp
	 * @param $cat
	 * @param null $db
	 * @return string
	 */
	public function getByCategory($disp, $cat, $db = null): string
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT SUM(dhp_cantidad) AS total 
									FROM prm_dist_horas_prog dh
									JOIN prm_actividad_prog pap on dh.acp_id = pap.acp_id
                                    WHERE dh.disp_id = ?
                                    AND pap.acp_clasificacion = ?");

		$stmt->bind_param("is", $disp, $cat);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$obj = $row['total'];
		$obj = number_format($obj, 2, '.', '');

		unset($db);
		return $obj;
	}

	/**
	 * @param $dist
	 * @param $thor
	 * @param $cant
	 * @param $rend
	 * @param $obs
	 * @param $db
	 * @return array
	 */
	public function set($dist, $thor, $cant, $rend, $obs, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_dist_horas_prog (disp_id, acp_id, dhp_cantidad, dhp_rendimiento, dhp_observacion) 
                                    VALUES (?, ?, ?, ?, ?)");

			if (!$stmt):
				throw new Exception("La inserción de la distribución de horas falló en su preparación.");
			endif;

			$dist = $db->clearText($dist);
			$thor = $db->clearText($thor);
			$cant = $db->clearText($cant);
			$rend = $db->clearText($rend);
			$obs = utf8_decode($db->clearText($obs));
			$bind = $stmt->bind_param("iidds", $dist, $thor, $cant, $rend, $obs);
			if (!$bind):
				throw new Exception("La inserción de la distribución de horas falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de la distribución de horas falló en su ejecución.");
			endif;

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
	public function delByDist($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("DELETE FROM prm_dist_horas_prog WHERE disp_id = ?");

			if (!$stmt):
				throw new Exception("La eliminación de la distribución de horas falló en su preparación.");
			endif;

			$id = $db->clearText($id);
			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La eliminación de la distribución de horas falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La eliminación de la distribución de horas falló en su ejecución.");
			endif;

			return array('estado' => true, 'msg' => $stmt->insert_id);
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}

