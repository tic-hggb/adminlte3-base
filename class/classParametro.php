<?php

class Parametro {

	public function __construct()
	{
	}

	/**
	 * @param $year
	 * @return stdClass
	 */
	public function get($year): stdClass
	{
		$db = new myDBC();
		$stmt = $db->Prepare("SELECT * FROM prm_parametros WHERE par_year = ?");

		$stmt->bind_param("i", $year);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->par_id = $row['par_id'];
		$obj->par_semanas = $row['par_semanas'];

		unset($db);
		return $obj;
	}

	/**
	 * @param $year
	 * @param $weeks
	 * @return bool
	 */
	public function set($year, $weeks): bool
	{
		$db = new myDBC();
		$stmt = $db->Prepare("INSERT INTO prm_parametros (par_year, par_semanas) VALUES (?, ?)");

		$stmt->bind_param("id", $year, $weeks);

		if ($stmt->execute()):
			unset($db);
			return true;
		else:
			return false;
		endif;
	}
}
