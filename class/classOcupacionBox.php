<?php

class OcupacionBox {

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

		$stmt = $db->Prepare("SELECT *, WEEKDAY(bh.bh_fecha) AS dia
									FROM prm_bloque_hora bh
									JOIN prm_box bx ON bh.box_id = bx.box_id
									JOIN prm_estab_lugar pel on bx.lug_id = pel.lug_id
									JOIN prm_persona per ON bh.per_id = per.per_id
									JOIN prm_profesion pr ON per.prof_id = pr.prof_id
									LEFT JOIN prm_actividad ac ON bh.act_id = ac.act_id
									LEFT JOIN prm_motivo_ausencia a on bh.mau_id = a.mau_id
									LEFT JOIN prm_sin_subespecialidad pss on bh.ssub_id = pss.ssub_id
									LEFT JOIN prm_sin_especialidad pse on pss.sesp_id = pse.sesp_id
									LEFT JOIN prm_sin_agrupacion psa on pse.sagr_id = psa.sagr_id
									LEFT JOIN prm_bloque_destino des on bh.bdes_id = des.bdes_id
									WHERE bh.bh_id = ? AND bh.bles_id = 1
									GROUP BY bh_id, bh_fecha, bh_hora_ini
									ORDER BY bh.bh_fecha, bh.bh_hora_ini");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->bh_id = $row['bh_id'];
		$obj->per_id = $row['per_id'];
		$obj->mau_id = $row['mau_id'];
		$obj->mau_descripcion = utf8_encode($row['mau_descripcion']);
		$obj->bdes_id = $row['bdes_id'];
		$obj->sagr_id = $row['sagr_id'];
		$obj->sesp_id = $row['sesp_id'];
		$obj->sesp_nombre = utf8_encode($row['sesp_nombre']);
		$obj->ssub_id = $row['ssub_id'];
		$obj->ssub_nombre = utf8_encode($row['ssub_nombre']);
		$obj->act_id = $row['act_id'];
		$obj->act_nombre = utf8_encode($row['act_nombre']);
		$obj->act_multi = $row['act_multi'];
		$obj->act_comite = $row['act_comite'];
		$obj->box_id = $row['box_id'];
		$obj->box_numero = $row['box_numero'];
		$obj->box_descripcion = utf8_encode($row['box_descripcion']);
		$obj->box_activo = $row['box_activo'];
		$obj->lugar_id = $row['lug_id'];
		$obj->lugar_nombre = utf8_encode($row['lug_nombre']);
		$obj->dia = $row['dia'];
		$obj->fecha = $row['bh_fecha'];
		$obj->hora_ini = $row['bh_hora_ini'];
		$obj->hora_ter = $row['bh_hora_ter'];
		$obj->rango_hora = $row['bh_hora_ini'] . " a " . $row['bh_hora_ter'];
		$obj->programado = $row['bh_programado'];
		$obj->prof_nombre = $row['prof_nombre'];
		$obj->per_nombres = $row['per_nombres'];

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

		$stmt = $db->Prepare("SELECT b.bh_id FROM prm_bloque_hora b ORDER BY b.bh_hora_ini");

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
	 * @param $id
	 * @param $db
	 * @return array
	 */
	public function getCupos($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT bhcu_id, bc.tcu_id, ptc.tcu_descripcion, bhcu_numero
									FROM prm_bloque_hora_cupos bc
									JOIN prm_tipo_cupo ptc on bc.tcu_id = ptc.tcu_id
									WHERE bh_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = new stdClass();
			$obj->bhcu_id = $row['bhcu_id'];
			$obj->bh_id = $id;
			$obj->tcu_id = $row['tcu_id'];
			$obj->tcu_descripcion = utf8_encode($row['tcu_descripcion']);
			$obj->bhcu_numero = $row['bhcu_numero'];

			$lista[] = $obj;
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $box
	 * @param $fechai
	 * @param $fechat
	 * @param $db
	 * @return array
	 */
	public function getOccupationByFecha($box, $fechai, $fechat, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;
		$stmt = $db->Prepare("SELECT bh.bh_id 
									FROM prm_bloque_hora bh
									WHERE bh.box_id = ? and bh.bh_fecha BETWEEN ? AND ? AND bh.bles_id = 1
									GROUP BY bh.bh_id
									ORDER BY bh.bh_fecha, bh.bh_hora_ini");

		$stmt->bind_param("iss", $box, $fechai, $fechat);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$obj = $this->get($row['bh_id'], $db);

			if ($obj->mau_id != ''):
				$lista['ausencia'][] = $obj;
			else:
				$lista['presencia'][] = $obj;
			endif;
		endwhile;

		unset($db);
		return $lista;
	}
}