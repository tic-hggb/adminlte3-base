<?php

class Persona {

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
                                    FROM prm_persona p
                                    LEFT JOIN prm_profesion ps ON p.prof_id = ps.prof_id
                                    WHERE p.per_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->per_id = $row['per_id'];
		$obj->per_prid = $row['prof_id'];
		$obj->per_profesion = utf8_encode($row['prof_nombre']);
		$obj->per_rut = utf8_encode($row['per_rut']);
		$obj->per_nombres = utf8_encode($row['per_nombres']);
		$obj->per_sis = utf8_encode($row['per_sis']);

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
	 * @param $rut
	 * @param $db
	 * @return stdClass
	 */
	public function getByRut($rut, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT p.* FROM prm_persona p
                                    WHERE p.per_rut = ?");

		$stmt->bind_param("s", $rut);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->per_id = $row['per_id'];
		$obj->per_profid = $row['prof_id'];
		$obj->per_rut = utf8_encode($row['per_rut']);
		$obj->per_nombres = utf8_encode($row['per_nombres']);
		$obj->per_sis = utf8_encode($row['per_sis']);

		unset($db);
		return $obj;
	}

	/**
	 * @param $rut
	 * @param $est
	 * @param $ley
	 * @param $corr
	 * @param null $db
	 * @return stdClass
	 */
	public function getByRutEstLey($rut, $est, $ley, $corr, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_persona p
                                    LEFT JOIN prm_persona_establecimiento pe ON p.per_id = pe.per_id
                                    WHERE p.per_rut = ? AND pe.con_id = ? AND pe.est_id = ? AND pe.pes_correlativo = ? AND pe.pes_activo IS TRUE");

		$stmt->bind_param("siii", $rut, $ley, $est, $corr);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->per_id = $row['per_id'];
		$obj->pes_estid = $row['est_id'];
		$obj->pes_conid = $row['con_id'];
		$obj->per_profid = $row['prof_id'];
		$obj->per_rut = utf8_encode($row['per_rut']);
		$obj->per_nombres = utf8_encode($row['per_nombres']);

		unset($db);
		return $obj;
	}

	/**
	 * @param $date
	 * @param $date_t
	 * @param $planta
	 * @param $est
	 * @param $npr
	 * @param null $db
	 * @return array
	 */
	public function getNPrByDate($date, $date_t, $planta, $est, $npr, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		switch ($planta):
			case '0':
				$cond = "p.prof_id = 14";
				break;
			case '1':
				$cond = "p.prof_id <> 14 AND p.prof_id <> 4 AND p.prof_id <> 16";
				break;
			case '2':
				$cond = "p.prof_id = 4 OR p.prof_id = 16";
				break;
			default:
				$cond = '';
				break;
		endswitch;

		$estab = ($est != '') ? "AND pe.est_id = $est" : "";

		$str_npr = ($npr == 'on') ? "AND pes_id NOT IN (SELECT pes_id FROM prm_distribucion_prog WHERE disp_fecha_ini = ? AND disp_fecha_ter = ?)" : "";

		$stmt = $db->Prepare("SELECT p.per_id, p.per_rut, per_nombres, prof_nombre, con_descripcion, pes_id, pes_correlativo, pes_horas
                                    FROM prm_persona p
                                    LEFT JOIN prm_profesion ps ON p.prof_id = ps.prof_id
                                    JOIN prm_persona_establecimiento pe ON p.per_id = pe.per_id
									JOIN prm_tipo_contrato ptc on pe.con_id = ptc.con_id
                                    WHERE $cond $estab $str_npr
                                    AND pes_activo IS TRUE");

		if ($npr == 'on'):
			$stmt->bind_param("ss", $date, $date_t);
		endif;

		$stmt->execute();
		$result = $stmt->get_result();
		$lista['data'] = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->per_id = $row['per_id'];
			$obj->pes_id = $row['pes_id'];
			$obj->per_rut = utf8_encode($row['per_rut']);
			$obj->per_nombres = utf8_encode($row['per_nombres']);
			$obj->per_profesion = utf8_encode($row['prof_nombre']);
			$obj->con_descripcion = utf8_encode($row['con_descripcion']);
			$obj->pes_correlativo = $row['pes_correlativo'];
			$obj->pes_horas = $row['pes_horas'];
			$lista['data'][] = $obj;
		endwhile;

		$lista['fecha_ini'] = $date;
		$lista['fecha_ter'] = $date_t;

		unset($db);
		return $lista;
	}

	/**
	 * @param $est
	 * @param $date
	 * @param $planta
	 * @param $db
	 * @return array
	 */
	public function getNPMedics($est, $date, $planta, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$estab = ($est != '') ? " AND pe.est_id = $est" : '';

		switch ($planta):
			case '0':
				$cond = "p.prof_id = 14";
				break;
			case '1':
				$cond = "p.prof_id <> 14 AND p.prof_id <> 4 AND p.prof_id <> 16";
				break;
			case '2':
				$cond = "p.prof_id = 4 OR p.prof_id = 16";
				break;
			default:
				$cond = '';
				break;
		endswitch;

		$stmt = $db->Prepare("SELECT p.*, prof_nombre
                                    FROM prm_persona p
                                    LEFT JOIN prm_profesion pr ON p.prof_id = pr.prof_id
                                    JOIN prm_persona_establecimiento pe ON p.per_id = pe.per_id
                                    WHERE $cond $estab
                                    AND pes_id NOT IN (
                                            SELECT pes_id FROM prm_distribucion
                                            WHERE dist_fecha = ?)");

		$stmt->bind_param("s", $date);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->per_id = $row['per_id'];
			$obj->per_profesion = utf8_encode($row['prof_nombre']);
			$obj->per_rut = utf8_encode($row['per_rut']);
			$obj->per_nombres = utf8_encode($row['per_nombres']);

			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $est
	 * @param $date_i
	 * @param $date_t
	 * @param $planta
	 * @param $db
	 * @return array
	 */
	public function getJustifyMeds($est, $date_i, $date_t, $planta, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$estab = ($est != '') ? " AND pe.est_id = $est" : '';

		switch ($planta):
			case '0':
				$cond = "p.prof_id = 14";
				break;
			case '1':
				$cond = "p.prof_id <> 14 AND p.prof_id <> 4 AND p.prof_id <> 16";
				break;
			case '2':
				$cond = "p.prof_id = 4 OR p.prof_id = 16";
				break;
			default:
				$cond = '';
				break;
		endswitch;

		$stmt = $db->Prepare("SELECT p.*, prof_nombre, jus_descripcion
                                    FROM prm_persona p
                                    LEFT JOIN prm_profesion pr ON p.prof_id = pr.prof_id
                                    JOIN prm_persona_establecimiento pe ON p.per_id = pe.per_id
                                    JOIN prm_distribucion_prog d ON pe.pes_id = d.pes_id
                                    JOIN prm_justificacion j ON d.jus_id = j.jus_id
                                    WHERE disp_fecha_ini = ? AND disp_fecha_ter = ?
                                    AND $cond $estab
                                    AND d.jus_id <> ''");

		$stmt->bind_param("ss", $date_i, $date_t);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->per_id = $row['per_id'];
			$obj->per_justif = utf8_encode($row['jus_descripcion']);
			$obj->per_rut = utf8_encode($row['per_rut']);
			$obj->per_nombres = utf8_encode($row['per_nombres']);
			$obj->per_profesion = utf8_encode($row['prof_nombre']);

			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $string
	 * @return array
	 * @throws Exception
	 */
	public function getByString($string): array
	{
		$db = new myDBC();
		$str = '%' . utf8_decode($db->clearText($string)) . '%';

		$stmt = $db->Prepare("SELECT p.per_id, p.prof_id, p.per_rut, p.per_nombres
                                        FROM prm_persona p                                       
                                        WHERE p.per_nombres LIKE ?
                                        GROUP BY p.per_id");

		$bind = $stmt->bind_param("s", $str);

		if (!$stmt->execute()):
			throw new Exception("La búsqueda de la persona falló en su preparación.");
		endif;

		if (!$bind):
			throw new Exception("La búsqueda de la persona falló en su binding.");
		endif;

		if (!$stmt->execute()):
			throw new Exception("La búsqueda de la persona falló en su ejecución.");
		endif;

		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = array('value' => utf8_encode($row['per_id']) . ': ' . utf8_encode($row['per_nombres']));
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $name
	 * @return stdClass
	 */
	public function getByName($name): stdClass
	{
		$db = new myDBC();
		$stmt = $db->Prepare("SELECT * FROM prm_persona p WHERE p.per_id = ?");

		$stmt->bind_param("i", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->per_id = $row['per_id'];
		$obj->per_nombres = utf8_encode($row['per_nombres']);

		unset($db);
		return $obj;
	}

	/**
	 * @param $rut
	 * @param $nombre
	 * @param $prof
	 * @param $espec
	 * @param null $db
	 * @return array
	 */
	public function set($rut, $nombre, $prof, $espec, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_persona (per_rut, prof_id, per_nombres, per_sis) 
                                     VALUES (?, ?, ?, ?)");

			if (!$stmt):
				throw new Exception("La inserción de la persona falló en su preparación.");
			endif;

			$rut = utf8_decode($db->clearText($rut));
			$prof = $db->clearText($prof);
			$nombre = utf8_decode($db->clearText(mb_strtoupper($nombre, 'UTF-8')));
			$espec = utf8_decode($db->clearText($espec));
			$bind = $stmt->bind_param("siss", $rut, $prof, $nombre, $espec);
			if (!$bind):
				throw new Exception("La inserción de la persona falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de la persona falló en su ejecución.");
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
	 * @param $nombre
	 * @param $prof
	 * @param $espec
	 * @param null $db
	 * @return array
	 */
	public function mod($id, $nombre, $prof, $espec, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_persona SET per_nombres = ?, prof_id = ?, per_sis = ? WHERE per_id = ?");

			if (!$stmt):
				throw new Exception("La edición de la persona falló en su preparación.");
			endif;

			$nombre = utf8_decode($db->clearText($nombre));
			$prof = $db->clearText($prof);
			$espec = utf8_decode($db->clearText($espec));
			$bind = $stmt->bind_param("sisi", $nombre, $prof, $espec, $id);
			if (!$bind):
				throw new Exception("La edición de la persona falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La edición de la persona falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}

