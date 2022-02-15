<?php

class DistribucionProg {
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

		$stmt = $db->Prepare("SELECT * FROM prm_distribucion_prog d
                                    WHERE d.disp_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->disp_id = $row['disp_id'];
		$obj->disp_pesid = $row['pes_id'];
		$obj->disp_jusid = $row['jus_id'];
		$obj->disp_serid = $row['ser_id'];
		$obj->disp_espid = $row['esp_id'];
		$obj->disp_fecha_ini = $row['disp_fecha_ini'];
		$obj->disp_fecha_ter = $row['disp_fecha_ter'];
		$obj->disp_descripcion = utf8_encode($row['disp_descripcion']);
		$obj->disp_observaciones = utf8_encode($row['disp_observacion']);
		$obj->disp_vacaciones = $row['disp_vacaciones'];
		$obj->disp_permisos = $row['disp_permisos'];
		$obj->disp_congreso = $row['disp_congreso'];
		$obj->disp_descanso = $row['disp_descanso'];
		$obj->disp_med_general = $row['disp_med_general'];
		$obj->disp_ultima = $row['disp_ultima'];
		$obj->disp_aprobada = $row['disp_aprobada'];

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

		$stmt = $db->Prepare("SELECT disp_id FROM prm_distribucion_prog");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['disp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @param $est
	 * @param $year
	 * @param null $db
	 * @return array
	 */
	public function getLastByPer($id, $est, $year, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT disp_id 
									FROM prm_distribucion_prog dp
									JOIN prm_persona_establecimiento ppe on dp.pes_id = ppe.pes_id 
									WHERE per_id = ? AND est_id = ? AND disp_ultima = TRUE AND YEAR(disp_fecha_ini) = ?");

		$stmt->bind_param("iii", $id, $est, $year);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['disp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @param $est
	 * @param $esp
	 * @param $year
	 * @param null $db
	 * @return array
	 */
	public function getLastByPerEsp($id, $est, $esp, $year, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT disp_id 
									FROM prm_distribucion_prog dp
									JOIN prm_persona_establecimiento ppe on dp.pes_id = ppe.pes_id  
									WHERE per_id = ? AND est_id = ? AND esp_id = ? AND YEAR(disp_fecha_ini) = ? AND disp_ultima = TRUE");

		$stmt->bind_param("iiii", $id, $est, $esp, $year);
		$stmt->execute();
		$result = $stmt->get_result();
		$array = [];

		while ($row = $result->fetch_assoc()):
			$array[] = $this->get($row['disp_id'], $db);
		endwhile;

		unset($db);
		return $array;
	}

	/**
	 * @param $id
	 * @param $date_i
	 * @param $date_t
	 * @param $db
	 * @return stdClass
	 */
	public function getByPerDate($id, $date_i, $date_t, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT disp_id 
									FROM prm_distribucion_prog dp
									JOIN prm_persona_establecimiento ppe on dp.pes_id = ppe.pes_id
									WHERE dp.pes_id = ? AND disp_fecha_ini = ? AND disp_fecha_ter = ?");

		$stmt->bind_param("iss", $id, $date_i, $date_t);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		$obj = $this->get($row['disp_id'], $db);

		unset($db);
		return $obj;
	}

	/**
	 * @param $id
	 * @param $est
	 * @param $esp
	 * @param $date_i
	 * @param $date_t
	 * @param $db
	 * @return stdClass
	 */
	public function getByPerDateEsp($id, $est, $esp, $date_i, $date_t, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT disp_id
									FROM prm_distribucion_prog dp
									JOIN prm_persona_establecimiento ppe on dp.pes_id = ppe.pes_id
									WHERE per_id = ? AND est_id = ? AND esp_id = ? AND disp_fecha_ini = ? AND disp_fecha_ter = ?");

		$stmt->bind_param("iiiss", $id, $est, $esp, $date_i, $date_t);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		$obj = $this->get($row['disp_id'], $db);

		unset($db);
		return $obj;
	}

	/**
	 * @param $date_ini
	 * @param $date_ter
	 * @param $est
	 * @param $planta
	 * @param $cr
	 * @param $serv
	 * @param $esp
	 * @param $db
	 * @return array
	 */
	public function getByFilters($date_ini, $date_ter, $est, $planta, $cr, $serv, $esp, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$estab = ($est != '') ? "AND ppe.est_id = $est" : '';

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
		$sesp = ($esp != '') ? "AND d.esp_id = $esp" : '';

		$stmt = $db->Prepare("SELECT disp_id, p.per_rut, p.per_nombres, p.per_sis, pr.prof_nombre, s.ser_nombre, e.esp_nombre, ptc.con_descripcion, ppe.pes_horas
                                    FROM prm_distribucion_prog d
                                    JOIN prm_persona_establecimiento ppe on d.pes_id = ppe.pes_id
									JOIN prm_tipo_contrato ptc on ppe.con_id = ptc.con_id
                                    JOIN prm_persona p ON ppe.per_id = p.per_id
                                    JOIN prm_profesion pr ON p.prof_id = pr.prof_id
                                    JOIN prm_servicio s ON d.ser_id = s.ser_id
                                    JOIN prm_especialidad e ON d.esp_id = e.esp_id
                                    WHERE disp_fecha_ini = ? AND disp_fecha_ter = ? AND disp_ultima IS TRUE
                                    $estab $cond $scr $sserv $sesp
                                    ORDER BY e.esp_nombre ASC, p.per_nombres ASC");

		$date_ini = $db->clearText($date_ini);
		$date_ter = $db->clearText($date_ter);
		$stmt->bind_param("ss", $date_ini, $date_ter);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->disp_id = $row['disp_id'];
			$obj->per_rut = $row['per_rut'];
			$obj->per_nombres = utf8_encode($row['per_nombres']);
			$obj->per_sis = utf8_encode($row['per_sis']);
			$obj->per_profesion = utf8_encode($row['prof_nombre']);
			$obj->per_servicio = utf8_encode($row['ser_nombre']);
			$obj->per_especialidad = utf8_encode($row['esp_nombre']);
			$obj->per_ley = utf8_encode($row['con_descripcion']);
			$obj->pes_horas = $row['pes_horas'];
			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $date_ini
	 * @param $date_ter
	 * @param $est
	 * @param $planta
	 * @param null $db
	 * @return array
	 */
	public function getConsolidated($date_ini, $date_ter, $est, $planta, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$estab = ($est != '') ? "AND ppe.est_id = $est" : '';

		switch ($planta):
			case '0':
				$cond = "AND p.prof_id = 14 AND esp_codigo <> ''";
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

		$stmt = $db->Prepare("SELECT *
									FROM prm_distribucion_prog d
									JOIN prm_persona_establecimiento ppe ON d.pes_id = ppe.pes_id
									JOIN prm_establecimiento pe ON ppe.est_id = pe.est_id
									JOIN prm_persona p ON ppe.per_id = p.per_id
									JOIN prm_profesion pr ON p.prof_id = pr.prof_id
									JOIN prm_especialidad e ON d.esp_id = e.esp_id
									JOIN prm_dist_horas_prog dhp ON d.disp_id = dhp.disp_id
									JOIN prm_actividad_prog ap ON dhp.acp_id = ap.acp_id
									WHERE disp_fecha_ini = ? AND disp_fecha_ter = ?
									AND ap.acp_id <> 1
									AND disp_ultima = 1
									AND acp_vigente = 1
									$cond $estab
									ORDER BY p.per_nombres, pes_correlativo, ap.acp_codigo");

		$date_ini = $db->clearText($date_ini);
		$date_ter = $db->clearText($date_ter);
		$stmt->bind_param("ss", $date_ini, $date_ter);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->disp_id = $row['disp_id'];
			$obj->per_id = $row['per_id'];
			$obj->pes_id = $row['pes_id'];
			$obj->est_id = $row['est_id'];
			$obj->est_deis = '118' . $row['est_id'];
			$obj->per_rut = $row['per_rut'];
			$obj->per_corr = $row['pes_correlativo'];
			$obj->per_nombre = utf8_encode($row['per_nombres']);
			$obj->per_medgeneral = $row['disp_med_general'];
			$obj->esp_codigo = $row['esp_codigo'];
			$obj->esp_descripcion = utf8_encode($row['esp_nombre']);
			$obj->prof_id = $row['prof_id'];
			$obj->prof_descripcion = utf8_encode($row['prof_nombre']);
			$obj->prof_rem = utf8_encode($row['prof_rem']);
			$obj->act_id = $row['acp_codigo'];
			$obj->act_descripcion = mb_strtoupper(utf8_encode($row['acp_descripcion']), 'UTF-8');
			$obj->act_desc_reporte = utf8_encode($row['acp_desc_reporte']);
			$obj->act_horas = $row['dhp_cantidad'];
			$obj->act_rend = $row['dhp_rendimiento'];
			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $year
	 * @param $est
	 * @param $planta
	 * @param $cr
	 * @param $serv
	 * @param $esp
	 * @param $db
	 * @return array
	 */
	public function getReprogsByFilters($year, $est, $planta, $cr, $serv, $esp, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$estab = ($est != '') ? "AND ppe.est_id = $est" : '';

		switch ($planta):
			case '0':
				$cond = "AND p.prof_id = 14 AND esp_codigo <> ''";
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
		$sesp = ($esp != '') ? "AND d.esp_id = $esp" : '';

		$stmt = $db->Prepare("SELECT p.per_id, p.per_rut, p.per_nombres, pr.prof_id, pr.prof_nombre, s.ser_nombre, e.esp_id, e.esp_nombre
                                    FROM prm_distribucion_prog d
                                    JOIN prm_persona_establecimiento ppe on d.pes_id = ppe.pes_id 
                                    JOIN prm_persona p ON ppe.per_id = p.per_id
                                    JOIN prm_profesion pr ON p.prof_id = pr.prof_id
                                    JOIN prm_servicio s ON d.ser_id = s.ser_id
                                    JOIN prm_especialidad e ON d.esp_id = e.esp_id
                                    WHERE YEAR(disp_fecha_ini) = ? AND YEAR(disp_fecha_ter) = ?
                                    $estab $cond $scr $sserv $sesp
                                    GROUP BY p.per_id, e.esp_id
                                    ORDER BY e.esp_nombre ASC, p.per_nombres ASC");

		$var1 = $db->clearText($year);
		$stmt->bind_param("ss", $var1, $var1);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->per_id = $row['per_id'];
			$obj->per_rut = $row['per_rut'];
			$obj->per_nombres = utf8_encode($row['per_nombres']);
			$obj->per_profid = $row['prof_id'];
			$obj->per_servicio = utf8_encode($row['ser_nombre']);
			$obj->per_espid = $row['esp_id'];
			$obj->per_especialidad = utf8_encode($row['esp_nombre']);
			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $pes
	 * @param $date
	 * @param $date_t
	 * @param $esp
	 * @param null $db
	 * @return mixed
	 */
	public function getCountByPerDate($pes, $date, $date_t, $esp, $db = null)
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT COUNT(disp_id) AS num 
									FROM prm_distribucion_prog 
									WHERE disp_fecha_ini = ? AND disp_fecha_ter = ? AND pes_id = ? AND esp_id = ?");

		$date = $db->clearText($date);
		$date_t = $db->clearText($date_t);
		$pes = $db->clearText($pes);
		$esp = $db->clearText($esp);
		$stmt->bind_param("ssii", $date, $date_t, $pes, $esp);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		$obj = $row['num'];

		unset($db);
		return $obj;
	}

	/**
	 * @param $pl
	 * @param $estab
	 * @param $db
	 * @return array
	 */
	public function getByEstabPlanta($pl, $estab, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		switch ($pl):
			case '0':
				$cond = "p.prof_id = 14 AND esp_codigo <> ''";
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

		$stmt = $db->Prepare("SELECT disp_id FROM prm_distribucion_prog d
    								JOIN prm_especialidad pe on d.esp_id = pe.esp_id
    								JOIN prm_persona_establecimiento ppe on d.pes_id = ppe.pes_id
                                    JOIN prm_persona p ON ppe.per_id = p.per_id
                                    WHERE $cond AND est_id = ?");

		$estab = $db->clearText($estab);
		$stmt->bind_param("i", $estab);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['disp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @param $db
	 * @return array
	 */
	public function getDetail($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_dist_horas_prog
                                    WHERE disp_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];
		$obj = $this->get($id);
		$obj->disp_semanas = round(50 - (($obj->disp_vacaciones + $obj->disp_permisos + $obj->disp_congreso + $obj->disp_descanso) / 5));

		$lista['detail'] = $obj;

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->dhp_cantidad = $row['dhp_cantidad'];
			$obj->dhp_rendimiento = $row['dhp_rendimiento'];
			$total = $obj->dhp_cantidad * $obj->dhp_rendimiento;
			$obj->dhp_total = ($total == 0 ? '<span style="color: silver">0.00</span>' : number_format($total, 2, '.', ''));

			$lista['th' . $row['acp_id']] = $obj;
			unset($obj);
		endwhile;

		$result_h = $db->runQuery("SELECT COUNT(acp_id) AS num FROM prm_actividad_prog");
		$row = $result_h->fetch_assoc();

		for ($i = 1; $i < $row['num'] + 1; $i++):
			if (!array_key_exists('th' . $i, $lista)):
				$obj = new stdClass();
				$obj->dhp_cantidad = '<span style="color: silver">0.00</span>';
				$obj->dhp_rendimiento = '<span style="color: silver">0.00</span>';
				$obj->dhp_total = '<span style="color: silver">0.00</span>';

				$lista['th' . $i] = $obj;
				unset($obj);
			endif;
		endfor;

		$tdisp = $lista['th1']->dhp_cantidad + $lista['th2']->dhp_cantidad + $lista['th3']->dhp_cantidad;
		$lista['tdisp'] = ($tdisp == 0 ? '<span style="color: silver">0.00</span>' : number_format($tdisp, 2, '.', ''));

		$tpoli = $lista['th5']->dhp_cantidad + $lista['th6']->dhp_cantidad + $lista['th8']->dhp_cantidad;
		$lista['tpoli'] = ($tpoli == 0 ? '<span style="color: silver">0.00</span>' : number_format($tpoli, 2, '.', ''));

		$tapoli = $lista['th5']->dhp_total + $lista['th6']->dhp_total + $lista['th8']->dhp_total;
		$lista['tapoli'] = ($tapoli == 0 ? '<span style="color: silver">0.00</span>' : number_format($tapoli, 2, '.', ''));

		$total = 0;

		for ($i = 4; $i < 117; $i++):
			$total += (isset($lista['th' . $i]->dhp_cantidad)) ? $lista['th' . $i]->dhp_cantidad : 0;
		endfor;

		$lista['total'] = ($total == 0 ? '<span style="color: silver">0.00</span>' : number_format($total, 2, '.', ''));

		unset($db);
		return $lista;
	}

	/**
	 * @param $dist
	 * @param $db
	 * @return string
	 */
	public function getTotalDisp($dist, $db = null): string
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT SUM(dhp_cantidad) AS suma FROM prm_dist_horas_prog dh
                                    JOIN prm_distribucion_prog d ON dh.disp_id = d.disp_id
                                    WHERE d.disp_id = ? AND (acp_id = 1 OR acp_id = 2 OR acp_id = 3)");

		$stmt->bind_param("i", $dist);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$obj = $row['suma'];
		if ($obj == ''): $obj = '0.00'; endif;

		unset($db);
		return $obj;
	}

	/**
	 * @param $dist
	 * @param $db
	 * @return string
	 */
	public function getTotalPoli($dist, $db = null): string
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT SUM(dhp_cantidad) AS suma FROM prm_dist_horas_prog dh
                                    JOIN prm_distribucion_prog d ON dh.disp_id = d.disp_id
                                    WHERE d.disp_id = ? AND (acp_id = 4 OR acp_id = 5 OR acp_id = 21)");

		$stmt->bind_param("i", $dist);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$obj = $row['suma'];
		if ($obj == ''): $obj = '0.00'; endif;

		unset($db);
		return $obj;
	}

	/**
	 * @param $dist
	 * @param $db
	 * @return string
	 */
	public function getTotal($dist, $db = null): string
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT SUM(dhp_cantidad) AS suma FROM prm_dist_horas_prog dh
                                    JOIN prm_distribucion_prog d ON dh.disp_id = d.disp_id
                                    WHERE d.disp_id = ? AND (acp_id = 1 OR acp_id = 2 OR acp_id = 3)");

		$stmt->bind_param("i", $dist);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$obj = $row['suma'];
		if ($obj == ''): $obj = '0.00'; endif;

		unset($db);
		return $obj;
	}

	/**
	 * @param $est
	 * @param $esp
	 * @param $per
	 * @param $db
	 * @return array
	 */
	public function getProgrammedCC($est, $esp, $per, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_distribucion_prog d
                                JOIN prm_dist_horas_prog dh ON d.disp_id = dh.disp_id
								JOIN prm_persona_establecimiento ppe on d.pes_id = ppe.pes_id
                                WHERE (dh.acp_id = 4 OR dh.acp_id = 5 OR dh.acp_id = 21)
                                AND est_id = ?
                                AND esp_id = ?
                                AND d.pes_id <> ?
                                AND disp_ultima = TRUE");

		$est = $db->clearText($est);
		$esp = $db->clearText($esp);
		$per = $db->clearText($per);
		$stmt->bind_param("iii", $est, $esp, $per);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->per = $row['per_id'];
			$obj->vacas = $row['disp_vacaciones'];
			$obj->permisos = $row['disp_permisos'];
			$obj->congreso = $row['disp_congreso'];
			$obj->disponibles = $row['dhp_cantidad'] * $row['dhp_rendimiento'];

			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $est
	 * @param $esp
	 * @param $per
	 * @param $db
	 * @return array
	 */
	public function getProgrammedIQ($est, $esp, $per, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_distribucion_prog d
                                JOIN prm_dist_horas_prog dh ON d.disp_id = dh.disp_id
								JOIN prm_persona_establecimiento ppe on d.pes_id = ppe.pes_id
                                WHERE dh.acp_id = 10
                                AND est_id = ?
                                AND esp_id = ?
                                AND d.pes_id <> ?
                                AND disp_ultima = TRUE");

		$est = $db->clearText($est);
		$esp = $db->clearText($esp);
		$per = $db->clearText($per);
		$stmt->bind_param("iii", $est, $esp, $per);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->per = $row['per_id'];
			$obj->vacas = $row['disp_vacaciones'];
			$obj->permisos = $row['disp_permisos'];
			$obj->congreso = $row['disp_congreso'];
			$obj->disponibles = $row['dhp_cantidad'] * $row['dhp_rendimiento'];

			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $est
	 * @param $esp
	 * @param $per
	 * @param $db
	 * @return array
	 */
	public function getProgrammedEsp($est, $esp, $per, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_distribucion_prog d
                                JOIN prm_dist_horas_prog dh ON d.disp_id = dh.disp_id
								JOIN prm_persona_establecimiento ppe on d.pes_id = ppe.pes_id
                                WHERE dh.acp_id = 1
                                AND est_id = ?
                                AND esp_id = ?
                                AND d.pes_id <> ?
                                AND disp_ultima = TRUE");

		$est = $db->clearText($est);
		$esp = $db->clearText($esp);
		$per = $db->clearText($per);
		$stmt->bind_param("iii", $est, $esp, $per);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->per = $row['per_id'];
			$obj->vacas = $row['disp_vacaciones'];
			$obj->permisos = $row['disp_permisos'];
			$obj->congreso = $row['disp_congreso'];
			$obj->disponibles = $row['dhp_cantidad'];

			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $type
	 * @param $est
	 * @param $period
	 * @param null $db
	 * @return mixed
	 */
	public function getProgramsByPeriodEstab($type, $est, $period, $db = null)
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$cond = ($type == 1) ? 'IN' : 'NOT IN';
		$estab = (!is_null($est)) ? 'AND est_id = ?' : '';

		$stmt = $db->Prepare("SELECT COUNT(DISTINCT(d.pes_id)) AS contratos
									FROM prm_distribucion_prog d
									JOIN prm_persona_establecimiento pe ON d.pes_id = pe.pes_id
									JOIN prm_persona p ON pe.per_id = p.per_id
                                    WHERE pes_activo IS TRUE  AND disp_ultima IS TRUE AND prof_id $cond (4,14,16) $estab
                                    AND disp_fecha_ini = ?");

		if (!is_null($est)) $stmt->bind_param('is', $est, $period);
		else $stmt->bind_param('s', $period);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		return $row['contratos'];
	}

	/**
	 * @param $pes
	 * @param $esp
	 * @param $ser
	 * @param null $db
	 * @return \stdClass
	 */
	public function getPreviousLast($pes, $esp, $ser, $db = null)
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT MAX(disp_id) AS id FROM prm_distribucion_prog d
                                    WHERE pes_id = ? AND esp_id = ? AND ser_id = ? AND disp_ultima IS NOT TRUE");

		$pes = $db->clearText($pes);
		$esp = $db->clearText($esp);
		$ser = $db->clearText($ser);
		$stmt->bind_param("iii", $pes, $esp, $ser);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$obj = $this->get($row['id']);

		unset($db);
		return $obj;
	}

	/**
	 * @param $pes
	 * @param $desc
	 * @param $obs
	 * @param $date_ini
	 * @param $date_ter
	 * @param $justif
	 * @param $serv
	 * @param $esp
	 * @param $vacas
	 * @param $perm
	 * @param $cong
	 * @param $descan
	 * @param $general
	 * @param $user
	 * @param null $db
	 * @return array
	 */
	public function set($pes, $desc, $obs, $date_ini, $date_ter, $justif, $serv, $esp, $vacas, $perm, $cong, $descan, $general, $user, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$jus = ($justif != '') ? $justif : NULL;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_distribucion_prog (pes_id, us_id, disp_descripcion, disp_observacion, disp_fecha_ini, disp_fecha_ter, jus_id, ser_id, esp_id, disp_vacaciones, 
                                   disp_permisos, disp_congreso, disp_descanso, disp_med_general, disp_ultima) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)");

			if (!$stmt):
				throw new Exception("La inserción de la distribución falló en su preparación.");
			endif;

			$pes = $db->clearText($pes);
			$user = $db->clearText($user);
			$desc = utf8_decode($db->clearText($desc));
			$obs = utf8_decode($db->clearText($obs));
			$date_ini = $db->clearText($date_ini);
			$date_ter = $db->clearText($date_ter);
			$vacas = $db->clearText($vacas);
			$perm = $db->clearText($perm);
			$cong = $db->clearText($cong);
			$descan = $db->clearText($descan);
			$bind = $stmt->bind_param("iissssiiiiiiii", $pes, $user, $desc, $obs, $date_ini, $date_ter, $jus, $serv, $esp, $vacas, $perm, $cong, $descan, $general);
			if (!$bind):
				throw new Exception("La inserción de la distribución falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de la distribución falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $stmt->insert_id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $pes
	 * @param $esp
	 * @param $ser
	 * @param null $db
	 * @return array
	 */
	public function setLast($pes, $esp, $ser, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_distribucion_prog SET disp_ultima = FALSE 
                                    WHERE pes_id = ? AND esp_id = ? AND ser_id = ?");

			if (!$stmt):
				throw new Exception("La actualización de la distribución falló en su preparación.");
			endif;

			$pes = $db->clearText($pes);
			$esp = $db->clearText($esp);
			$ser = $db->clearText($ser);
			$bind = $stmt->bind_param("iii", $pes, $esp, $ser);
			if (!$bind):
				throw new Exception("La actualización de la distribución falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La actualización de la distribución falló en su ejecución.");
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
	 * @param $db
	 * @return array
	 */
	public function setApproved($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_distribucion_prog SET disp_aprobada = TRUE WHERE disp_id = ?");

			if (!$stmt):
				throw new Exception("La aprobación de la distribución falló en su preparación.");
			endif;

			$id = $db->clearText($id);
			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La aprobación de la distribución falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La aprobación de la distribución falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param null $db
	 * @return array
	 */
	public function setPreviousLast($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_distribucion_prog SET disp_ultima = TRUE 
                                    WHERE disp_id = ?");

			if (!$stmt):
				throw new Exception("La actualización de la distribución anterior falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La actualización de la distribución anterior falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La actualización de la distribución anterior falló en su ejecución.");
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
	 * @param $desc
	 * @param $obs
	 * @param $date_ini
	 * @param $date_ter
	 * @param $justif
	 * @param $serv
	 * @param $esp
	 * @param $vacas
	 * @param $perm
	 * @param $cong
	 * @param $descan
	 * @param $general
	 * @param $user
	 * @param null $db
	 * @return array
	 */
	public function mod($id, $desc, $obs, $date_ini, $date_ter, $justif, $serv, $esp, $vacas, $perm, $cong, $descan, $general, $user, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$jus = ($justif != '') ? $justif : NULL;

		try {
			$stmt = $db->Prepare("UPDATE prm_distribucion_prog SET us_id = ?, disp_descripcion = ?, disp_observacion = ?, disp_fecha_ini = ?, disp_fecha_ter = ?, jus_id = ?, ser_id = ?, esp_id = ?, 
                                 	disp_vacaciones = ?, disp_permisos = ?, disp_congreso = ?, disp_descanso = ?, disp_med_general = ?, disp_fecha_ing = CURRENT_TIMESTAMP
                                    WHERE disp_id = ?");

			if (!$stmt):
				throw new Exception("La edición de la distribución falló en su preparación.");
			endif;

			$user = $db->clearText($user);
			$desc = utf8_decode($db->clearText($desc));
			$obs = utf8_decode($db->clearText($obs));
			$date_ini = $db->clearText($date_ini);
			$date_ter = $db->clearText($date_ter);
			$vacas = $db->clearText($vacas);
			$perm = $db->clearText($perm);
			$cong = $db->clearText($cong);
			$descan = $db->clearText($descan);
			$id = $db->clearText($id);
			$bind = $stmt->bind_param("issssiiiiiiiii", $user, $desc, $obs, $date_ini, $date_ter, $jus, $serv, $esp, $vacas, $perm, $cong, $descan, $general, $id);
			if (!$bind):
				throw new Exception("La edición de la distribución falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La edición de la distribución falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param null $db
	 * @return array
	 */
	public function del($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("DELETE FROM prm_distribucion_prog WHERE disp_id = ?");

			if (!$stmt):
				throw new Exception("La eliminación de la distribución falló en su preparación.");
			endif;

			$id = $db->clearText($id);
			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La eliminación de la distribución falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La eliminación de la distribución falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => 'OK');
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}

