<?php

class CaracteristicaTipo {

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

		$stmt = $db->Prepare("SELECT ct.* FROM prm_caracteristica_tipo ct 
                                    WHERE ct.tcar_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();
		$row = $result->fetch_assoc();

		$obj->tcar_id = $row['tcar_id'];
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

		$stmt = $db->Prepare("SELECT ct.tcar_id FROM prm_caracteristica_tipo ct ORDER BY ct.tcar_descripcion");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['tcar_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}
}