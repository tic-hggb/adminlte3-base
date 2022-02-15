<?php

class BoxCaracteristica {
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
									FROM prm_box_caracteristica bc 
									JOIN prm_caracteristica_tipo ct ON bc.tcar_id = ct.tcar_id
                                    WHERE bc.bca_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();

		$obj->bca_id = $row['bca_id'];
		$obj->tcar_id = $row['tcar_id'];
		$obj->box_id = $row['box_id'];
		$obj->bca_descripcion = utf8_encode($row['bca_descripcion']);
		$obj->tcar_descripcion = utf8_encode($row['tcar_descripcion']);

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

		$stmt = $db->Prepare("SELECT bc.bca_id 
									FROM prm_box_caracteristica bc 
									ORDER BY bc.bca_descripcion");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['bca_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}
}