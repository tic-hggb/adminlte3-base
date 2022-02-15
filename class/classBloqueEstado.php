<?php

class BloqueEstado
{
	public function __construct() {}

	/**
	 * @param $id
	 * @param null $db
	 * @return stdClass
	 */
	public function get($id, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT be.* FROM prm_bloque_estado be 
                                    WHERE be.bles_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();

		$obj->bles_id = $row['bles_id'];
		$obj->bles_descripcion= utf8_encode($row['bles_descripcion']);
		unset($db);
		return $obj;
	}

	/**
	 * @param null $db
	 * @return array
	 */
	public function getAll($db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT be.bles_id FROM prm_bloque_estado be ORDER BY be.bles_descripcion");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['bles_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}
}