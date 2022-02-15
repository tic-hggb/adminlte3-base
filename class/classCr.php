<?php

class Cr {

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

		$stmt = $db->Prepare("SELECT * FROM prm_cr
                                    WHERE cr_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->cr_id = $row['cr_id'];
		$obj->cr_nombre = utf8_encode($row['cr_nombre']);

		unset($db);
		return $obj;
	}

	/**
	 * @param $id
	 * @param $db
	 * @return stdClass
	 */
	public function getByService($id, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_cr c
									JOIN prm_servicio s ON c.cr_id = s.cr_id
                                    WHERE ser_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->cr_id = $row['cr_id'];
		$obj->cr_nombre = utf8_encode($row['cr_nombre']);

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

		$stmt = $db->Prepare("SELECT cr_id FROM prm_cr ORDER BY cr_nombre");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['cr_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}
}

