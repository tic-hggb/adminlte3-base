<?php

class Especialidad {

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

		$stmt = $db->Prepare("SELECT e.* FROM prm_especialidad e
                                    WHERE e.esp_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->esp_id = $row['esp_id'];
		$obj->esp_nombre = utf8_encode($row['esp_nombre']);

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

		$stmt = $db->Prepare("SELECT esp_id FROM prm_especialidad ORDER BY esp_nombre");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['esp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $serv
	 * @param null $db
	 * @return array
	 */
	public function getByServicio($serv, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT e.esp_id
                                    FROM prm_especialidad e
                                    JOIN prm_especialidad_servicio es ON e.esp_id = es.esp_id
                                    WHERE ser_id = ?
                                    ORDER BY e.esp_nombre");

		$stmt->bind_param("i", $serv);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['esp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $date
	 * @param $est
	 * @param $serv
	 * @param $db
	 * @return array
	 */
	public function getNoDiagnose($date, $est, $serv, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT e.esp_id FROM prm_especialidad e
                                    JOIN prm_especialidad_servicio es ON e.esp_id = es.esp_id
                                    WHERE es.ser_id = ?
                                    AND e.esp_id NOT IN (
                                        SELECT esp_id 
                                        FROM prm_diagnostico
                                        WHERE dia_fecha = ? AND ser_id = ? AND est_id = ?)");

		$stmt->bind_param("isii", $serv, $date, $serv, $est);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['esp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}
}

