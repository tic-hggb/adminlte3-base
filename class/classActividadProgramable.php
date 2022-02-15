<?php

class ActividadProgramable {

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

		$stmt = $db->Prepare("SELECT * FROM prm_actividad_prog
                                    WHERE acp_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->acp_id = $row['acp_id'];
		$obj->acp_tacid = $row['tac_id'];
		$obj->acp_espid = $row['esp_id'];
		$obj->acp_codigo = $row['acp_codigo'];
		$obj->acp_descripcion = utf8_encode($row['acp_descripcion']);
		$obj->acp_desc_corta = utf8_encode($row['acp_desc_reporte']);
		$obj->acp_rendimiento = $row['acp_rendimiento'];
		$obj->acp_vigente = $row['acp_vigente'];

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

		$stmt = $db->Prepare("SELECT acp_id FROM prm_actividad_prog");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['acp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $type
	 * @param $exclude
	 * @param null $db
	 * @return array
	 */
	public function getByType($type, $exclude, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$string = '';
		foreach ($exclude as $item):
			$string .= ',' . $item;
		endforeach;

		$stmt = $db->Prepare('SELECT * FROM prm_actividad_prog
								WHERE acp_id NOT IN (1,2,3,4,5' . $string . ')
								AND tac_id = ?
								AND acp_vigente IS TRUE
								ORDER BY acp_codigo');

		$stmt->bind_param("i", $type);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['acp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param null $db
	 * @return array
	 */
	public function getNoPoli($db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare('SELECT * FROM prm_actividad_prog WHERE tac_id = 1 AND acp_vigente IS TRUE AND acp_id NOT IN (4,5,21)');

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['acp_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}
}

