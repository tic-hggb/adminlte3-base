<?php

class EstablecimientoLugar {

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

		$stmt = $db->Prepare("SELECT e.* FROM prm_estab_lugar e
                                    WHERE e.lug_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->lug_id = $row['lug_id'];
		$obj->lug_estid = $row['est_id'];
		$obj->lug_nombre = utf8_encode($row['lug_nombre']);

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

		$stmt = $db->Prepare("SELECT lug_id FROM prm_estab_lugar ORDER BY lug_nombre");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['lug_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $est
	 * @param $db
	 * @return array
	 */
	public function getByEstab($est, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT lug_id FROM prm_estab_lugar WHERE est_id = ? ORDER BY lug_nombre");

		$stmt->bind_param("i", $est);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['lug_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}
}