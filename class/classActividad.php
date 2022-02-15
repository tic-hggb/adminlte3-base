<?php

class Actividad {

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

		$stmt = $db->Prepare("SELECT * FROM prm_actividad
                                    WHERE act_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->act_id = $row['act_id'];
		$obj->act_nombre = utf8_encode($row['act_nombre']);
		$obj->act_comite = $row['act_comite'];
		$obj->act_multi = $row['act_multi'];

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

		$stmt = $db->Prepare("SELECT act_id FROM prm_actividad ORDER BY act_nombre");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['act_id'], $db);
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

		$stmt = $db->Prepare("SELECT * FROM prm_actividad WHERE act_nombre = ?");

		$name = utf8_decode($db->clearText($name));
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$obj = new stdClass();

		$obj->act_id = $row['act_id'];
		$obj->act_nombre = utf8_encode($row['act_nombre']);
		$obj->act_comite = $row['act_comite'];

		unset($db);
		return $obj;
	}

	/**
	 * @param $id
	 * @param $db
	 * @return array
	 */
	public function getBySubespecialidad($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT act_id FROM prm_actividad_subesp WHERE ssub_id = ?");

		$id = $db->clearText($id);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['act_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $name
	 * @param $comite
	 * @param $db
	 * @return array
	 */
	public function set($name, $comite, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("INSERT INTO prm_actividad (act_nombre, act_comite) VALUES (?, ?)");
		$name = utf8_decode($db->clearText($name));
		$stmt->bind_param("si", $name, $comite);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result):
			return array('estado' => true, 'msg' => $stmt->insert_id);
		else:
			return array('estado' => false, 'msg' => 'false');
		endif;
	}
}

