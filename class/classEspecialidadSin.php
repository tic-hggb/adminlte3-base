<?php

class EspecialidadSin {

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

		$stmt = $db->Prepare("SELECT e.* FROM prm_sin_especialidad e
                                    WHERE e.sesp_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->sesp_id = $row['sesp_id'];
		$obj->sesp_nombre = utf8_encode($row['sesp_nombre']);

		unset($db);
		return $obj;
	}

	/**
	 * @return array
	 */
	public function getAll(): array
	{
		$db = new myDBC();

		$stmt = $db->Prepare("SELECT sesp_id FROM prm_sin_especialidad ORDER BY sesp_nombre");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['sesp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @return array
	 */
	public function getByAgrupacion($id): array
	{
		$db = new myDBC();

		$stmt = $db->Prepare("SELECT sesp_id FROM prm_sin_especialidad WHERE sagr_id = ? ORDER BY sesp_nombre");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['sesp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}
}