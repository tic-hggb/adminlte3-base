<?php

class BloqueHora {

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

		$stmt = $db->Prepare("SELECT bh.*, DAYOFWEEK(bh.bh_fecha) AS dia, a.*, des.*, be.*, pe.*, pro.*, us.*, bo.*, bot.*
									FROM prm_bloque_hora bh
									LEFT JOIN prm_motivo_ausencia a on bh.mau_id = a.mau_id
									LEFT JOIN prm_bloque_destino des on bh.bdes_id = des.bdes_id
									JOIN prm_bloque_estado be ON bh.bles_id = be.bles_id
									JOIN prm_persona pe ON bh.per_id = pe.per_id
									JOIN prm_profesion pro on pe.prof_id = pro.prof_id
									JOIN prm_usuario us ON bh.us_id = us.us_id
									JOIN prm_box bo ON bh.box_id = bo.box_id
									LEFT JOIN prm_box_tipo bot ON bo.box_id = bot.box_id
									LEFT JOIN prm_tipo_box ptb on bot.tbox_id = ptb.tbox_id
                                    WHERE bh.bh_id = ?");

		$id = $db->clearText($id);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();

		/*Bloque Horario*/
		$obj->bh_id = $row['bh_id'];
		$obj->bh_fecha = utf8_encode($row['bh_fecha']);
		$obj->bh_hora_ini = utf8_encode($row['bh_hora_ini']);
		$obj->bh_hora_ter = utf8_encode($row['bh_hora_ter']);
		$obj->bh_descripcion = utf8_encode($row['bh_descripcion']);
		$obj->bh_programado = $row['bh_programado'];
		$obj->bh_dia = utf8_encode($row['dia']);

		/*Motivo ausencia*/
		$obj->mau_id = $row['mau_id'];
		$obj->mau_descripcion = utf8_encode($row['mau_descripcion']);

		/*Destino pacientes*/
		$obj->bdes_id = $row['bdes_id'];
		$obj->bdes_descripcion = utf8_encode($row['bdes_descripcion']);

		/*Bloque estado*/
		$obj->bles_id = $row['bles_id'];
		$obj->bles_descripcion = utf8_encode($row['bles_descripcion']);

		/*Persona*/
		$obj->per_id = $row['per_id'];
		$obj->per_prid = $row['prof_id'];
		$obj->per_profesion = utf8_encode($row['prof_nombre']);
		$obj->per_rut = utf8_encode($row['per_rut']);
		$obj->per_nombres = utf8_encode($row['per_nombres']);

		/*Usuario*/
		$obj->us_id = $row['us_id'];
		$obj->us_nombres = utf8_encode($row['us_nombres']);
		$obj->us_ap = utf8_encode($row['us_ap']);
		$obj->us_am = utf8_encode($row['us_am']);
		$obj->us_email = utf8_encode($row['us_email']);
		$obj->us_username = utf8_encode($row['us_username']);

		/*Box*/
		$obj->box_id = $row['box_id'];
		$obj->box_numero = utf8_encode($row['box_numero']);
		$obj->box_piso = utf8_encode($row['lug_id']);
		$obj->box_descripcion = utf8_encode($row['box_descripcion']);
		$obj->box_activo = $row['box_activo'];

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

		$stmt = $db->Prepare("SELECT bh.bh_id FROM prm_bloque_hora bh ORDER BY bh.bh_descripcion");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['bh_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $f_ini
	 * @param $f_ter
	 * @param $per
	 * @param $db
	 * @return array
	 */
	public function getAbsenceByDate($f_ini, $f_ter, $per, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT bh_id 
									FROM prm_bloque_hora
									WHERE bh_fecha BETWEEN ? AND ? 
									AND mau_id IS NOT NULL AND bdes_id IS NOT NULL
									AND per_id = ?");

		$f_ini = $db->clearText($f_ini);
		$f_ter = $db->clearText($f_ter);
		$per = $db->clearText($per);
		$stmt->bind_param("ssi", $f_ini, $f_ter, $per);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['bh_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $year
	 * @param $period
	 * @param $est
	 * @param $planta
	 * @param $cr
	 * @param $serv
	 * @param $db
	 * @return array
	 */
	public function getAbsencesByFilters($year, $period, $est, $planta, $cr, $serv, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$sper = '';
		if ($period != ''):
			switch ($period):
				case '01':
					$sper = "AND bh_fecha BETWEEN '" . $year . "-01-01' AND '" . $year . "-03-31'";
					break;
				case '04':
					$sper = "AND bh_fecha BETWEEN '" . $year . "-03-01' AND '" . $year . "-06-30'";
					break;
				case '07':
					$sper = "AND bh_fecha BETWEEN '" . $year . "-07-01' AND '" . $year . "-09-30'";
					break;
				default:
					$sper = "AND bh_fecha BETWEEN '" . $year . "-10-01' AND '" . $year . "-12-31'";
					break;
			endswitch;
		endif;

		$estab = ($est != '') ? "AND pel.est_id = $est" : '';

		switch ($planta):
			case '0':
				$cond = "AND p.prof_id = 14";
				break;
			case '1':
				$cond = "AND p.prof_id <> 14 AND p.prof_id <> 4 AND p.prof_id <> 16";
				break;
			case '2':
				$cond = "AND p.prof_id = 4 OR p.prof_id = 16";
				break;
			default:
				$cond = '';
				break;
		endswitch;

		$scr = ($cr != '') ? "AND s.cr_id = $cr" : '';
		$sserv = ($serv != '') ? "AND d.ser_id = $serv" : '';

		$stmt = $db->Prepare("SELECT MIN(bh_id) AS begin, MAX(bh_id) AS end
									FROM prm_bloque_hora bh
									JOIN prm_persona p ON bh.per_id = p.per_id
									JOIN prm_profesion pr ON p.prof_id = pr.prof_id
									JOIN prm_persona_establecimiento ppe on p.per_id = ppe.per_id
                                    JOIN prm_distribucion_prog d on ppe.pes_id = d.pes_id
                                    JOIN prm_servicio s ON d.ser_id = s.ser_id
									JOIN prm_box pb on bh.box_id = pb.box_id
									JOIN prm_estab_lugar pel on pb.lug_id = pel.lug_id
									WHERE YEAR(bh_fecha) = ? $sper
									$estab $cond $scr $sserv
									AND mau_id IS NOT NULL AND bdes_id IS NOT NULL
									AND bh_ultima IS TRUE
									GROUP BY bh.per_id, bh_bloqid, bh_programado");

		$year = $db->clearText($year);
		$stmt->bind_param("s", $year);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->id_begin = $this->get($row['begin'], $db);
			$obj->id_end = $this->get($row['end'], $db);

			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $per
	 * @param $est
	 * @param $fecha
	 * @param $db
	 * @return stdClass
	 */
	public function getDiasOff($per, $est, $fecha, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		/* Vacaciones */
		$stmt = $db->Prepare("SELECT COUNT(bh_id) AS vac
									FROM prm_bloque_hora bh
									JOIN prm_box pb on bh.box_id = pb.box_id
									JOIN prm_estab_lugar pel on pb.lug_id = pel.lug_id
                                    WHERE bh.per_id = ? AND pel.est_id = ? AND YEAR(bh.bh_fecha) = ? AND bh_ultima IS TRUE AND mau_id = 13");

		$per = $db->clearText($per);
		$est = $db->clearText($est);
		$fecha = $db->clearText($fecha);
		$stmt->bind_param("iis", $per, $est, $fecha);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->dias_vacaciones = $row['vac'];

		/* Permisos */
		$stmt = $db->Prepare("SELECT COUNT(bh_id) AS per
									FROM prm_bloque_hora bh
									JOIN prm_box pb on bh.box_id = pb.box_id
									JOIN prm_estab_lugar pel on pb.lug_id = pel.lug_id
                                    WHERE bh.per_id = ? AND pel.est_id = ? AND YEAR(bh.bh_fecha) = ? AND bh_ultima IS TRUE AND mau_id = 17");

		$per = $db->clearText($per);
		$est = $db->clearText($est);
		$fecha = $db->clearText($fecha);
		$stmt->bind_param("iis", $per, $est, $fecha);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		$obj->dias_permiso = $row['per'];

		/* Congreso */
		$stmt = $db->Prepare("SELECT COUNT(bh_id) AS cong
									FROM prm_bloque_hora bh
									JOIN prm_box pb on bh.box_id = pb.box_id
									JOIN prm_estab_lugar pel on pb.lug_id = pel.lug_id
                                    WHERE bh.per_id = ? AND pel.est_id = ? AND YEAR(bh.bh_fecha) = ? AND bh_ultima IS TRUE AND mau_id = 18");

		$per = $db->clearText($per);
		$est = $db->clearText($est);
		$fecha = $db->clearText($fecha);
		$stmt->bind_param("iis", $per, $est, $fecha);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		$obj->dias_congreso = $row['cong'];

		unset($db);
		return $obj;
	}

	/**
	 * @param $per
	 * @param $est
	 * @param $year
	 * @param $db
	 * @return array
	 */
	public function getBlocks($per, $est, $year, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT MIN(bh_id) AS begin, MAX(bh_id) AS end, bh_descripcion
									FROM prm_bloque_hora pbh 
									JOIN prm_box pb on pbh.box_id = pb.box_id
									JOIN prm_estab_lugar pel on pb.lug_id = pel.lug_id
									WHERE bh_programado IS TRUE
									AND bh_ultima IS TRUE
									AND mau_id IS NOT NULL AND bdes_id IS NOT NULL
									AND per_id = ? AND est_id = ? and YEAR(bh_fecha) = ?
									GROUP BY bh_bloqid
									ORDER BY begin");

		$per = $db->clearText($per);
		$est = $db->clearText($est);
		$year = $db->clearText($year);
		$stmt->bind_param("iis", $per, $est, $year);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->id_begin = $row['begin'];
			$obj->id_end = $row['end'];

			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $period
	 * @param $dia
	 * @param $box
	 * @param $h_ini
	 * @param $h_ter
	 * @param $db
	 * @return bool
	 */
	public function getIsFilled($period, $dia, $box, $h_ini, $h_ter, $db = null): bool
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT COUNT(*) AS num
									FROM prm_agenda a
									WHERE age_periodo = ?
									AND age_dia = ?
									AND box_id = ?
									AND (? BETWEEN age_hora_ini AND age_hora_ter OR ? BETWEEN age_hora_ini AND age_hora_ter)
									AND age_ultima IS TRUE");

		$period = $db->clearText($period);
		$dia = $db->clearText($dia);
		$box = $db->clearText($box);
		$h_ini = $db->clearText($h_ini);
		$h_ter = $db->clearText($h_ter);
		$stmt->bind_param("siiss", $period, $dia, $box, $h_ini, $h_ter);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		$filled = $row['num'] > 0;

		unset($db);
		return $filled;
	}

	/**
	 * @param $per
	 * @param $us
	 * @param $act
	 * @param $subesp
	 * @param $box
	 * @param $motivo
	 * @param $destino
	 * @param $fecha
	 * @param $hinicio
	 * @param $htermino
	 * @param $c_obs
	 * @param $descripcion
	 * @param $programado
	 * @param $uniq_id
	 * @param null $db
	 * @return array
	 */
	public function set($per, $us, $act, $subesp, $box, $motivo, $destino, $fecha, $hinicio, $htermino, $c_obs, $descripcion, $programado, $uniq_id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_bloque_hora (bles_id, per_id, act_id, ssub_id, us_id, box_id, mau_id, bdes_id, bh_fecha, bh_hora_ini, bh_hora_ter, bh_observacion, bh_descripcion, bh_programado, bh_bloqid, bh_ultima) 
									   VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
			if (!$stmt):
				throw new Exception("La inserción del bloqueo horario falló en su preparación.");
			endif;

			$fecha = $db->clearText($fecha);
			$hinicio = $db->clearText($hinicio);
			$htermino = $db->clearText($htermino);
			$c_obs = utf8_decode($db->clearText($c_obs));
			$descripcion = utf8_decode($db->clearText($descripcion));
			$bind = $stmt->bind_param("iiiiiiisssssis", $per, $act, $subesp, $us, $box, $motivo, $destino, $fecha, $hinicio, $htermino, $c_obs, $descripcion, $programado, $uniq_id);
			if (!$bind):
				throw new Exception("La inserción del bloqueo horario falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción del bloqueo horario falló en su ejecución.");
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
	 * @param $tipo
	 * @param $numero
	 * @param $db
	 * @return array
	 */
	public function setCupos($id, $tipo, $numero, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_bloque_hora_cupos (bh_id, tcu_id, bhcu_numero) VALUES (?, ?, ?)");

			if (!$stmt):
				throw new Exception("La inserción de los cupos de agenda falló en su preparación.");
			endif;

			$id = $db->clearText($id);
			$tipo = $db->clearText($tipo);
			$numero = $db->clearText($numero);
			$bind = $stmt->bind_param("iid", $id, $tipo, $numero);
			if (!$bind):
				throw new Exception("La inserción de los cupos de agenda falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de los cupos de agenda falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $stmt->insert_id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $per
	 * @param $db
	 * @return array
	 */
	public function setLast($per, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_bloque_hora SET bh_ultima = FALSE WHERE per_id = ? AND bh_programado IS TRUE");

			if (!$stmt):
				throw new Exception("La actualización de los bloqueos falló en su preparación.");
			endif;

			$per = $db->clearText($per);
			$bind = $stmt->bind_param("i", $per);
			if (!$bind):
				throw new Exception("La actualización de los bloqueos falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La actualización de los bloqueos falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => 'OK');
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}