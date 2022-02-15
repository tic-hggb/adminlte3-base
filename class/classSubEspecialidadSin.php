<?php

class SubEspecialidadSin {

	public function __construct()
	{
	}

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

		$stmt = $db->Prepare("SELECT e.* FROM prm_sin_subespecialidad e
                                    WHERE e.ssub_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->ssub_id = $row['ssub_id'];
		$obj->ssub_nombre = utf8_encode($row['ssub_nombre']);

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

		$stmt = $db->Prepare("SELECT ssub_id FROM prm_sin_subespecialidad ORDER BY ssub_nombre");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['ssub_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @param null $db
	 * @return array
	 */
	public function getByEspecialidad($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT ssub_id FROM prm_sin_subespecialidad WHERE sesp_id = ? ORDER BY ssub_nombre");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['ssub_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}
}