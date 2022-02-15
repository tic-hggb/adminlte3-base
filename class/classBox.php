<?php

class Box {

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
									FROM prm_box b
									JOIN prm_estab_lugar pel on b.lug_id = pel.lug_id
									LEFT JOIN prm_box_tipo tipo on b.box_id = tipo.box_id
									LEFT JOIN prm_tipo_box ptb on tipo.tbox_id = ptb.tbox_id
                                    WHERE b.box_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->box_id = $row['box_id'];
		$obj->lugar_id = $row['lug_id'];
		$obj->lugar_nombre = utf8_encode($row['lug_nombre']);
		$obj->box_numero = $row['box_numero'];
		$obj->box_pasillo = utf8_encode($row['box_pasillo']);
		$obj->box_descripcion = utf8_encode($row['box_descripcion']);
		$obj->box_telemedicina = $row['box_telemedicina'];
		$obj->box_activo = $row['box_activo'];
		$obj->bot_descripcion = utf8_encode($row['tbox_descripcion']);

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

		$stmt = $db->Prepare("SELECT b.box_id FROM prm_box b ORDER BY b.box_descripcion");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['box_id']);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $floor
	 * @param $type
	 * @param $db
	 * @return array
	 */
	public function getByFloor($floor, $type, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$str_type = (!empty($type)) ? ' AND tbox_id = ' . $type : '';

		$stmt = $db->Prepare("SELECT b.box_id 
								FROM prm_box b
								LEFT JOIN prm_box_tipo tipo on b.box_id = tipo.box_id
								WHERE b.lug_id = ? $str_type
								GROUP BY box_id");

		$stmt->bind_param("s", $floor);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['box_id']);
		endwhile;

		unset($db);
		return $lista;
	}

}