<?php

class AtencionTipo {

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

		$stmt = $db->Prepare("SELECT a.* FROM prm_atencion_tipo a
                                    WHERE a.tat_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->tat_id = $row['tat_id'];
		$obj->tat_nombre = $row['tat_nombre'];

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

		$stmt = $db->Prepare("SELECT tat_id FROM prm_atencion_tipo");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['tat_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $name
	 * @param $db
	 * @return stdClass
	 */
	public function getByName($name, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_atencion_tipo
                                    WHERE tat_nombre = ?");

		$stmt->bind_param("s", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->tat_id = $row['tat_id'];
		$obj->tat_nombre = utf8_encode($row['tat_nombre']);

		unset($db);
		return $obj;
	}
}

