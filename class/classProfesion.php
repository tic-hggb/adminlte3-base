<?php

class Profesion {

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

		$stmt = $db->Prepare("SELECT * FROM prm_profesion
                                    WHERE prof_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->prof_id = $row['prof_id'];
		$obj->prof_nombre = utf8_encode($row['prof_nombre']);
		$obj->prof_rem = utf8_encode($row['prof_rem']);

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

		$stmt = $db->Prepare("SELECT prof_id FROM prm_profesion ORDER BY prof_nombre");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['prof_id'], $db);
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

		switch ($name):
			case 'MEDICO':
				$name = 'MEDICO_CIRUJANO\A';
				break;
			case 'ENFERMERO':
				$name = 'ENFERMERIA';
				break;
			case 'MATRONA':
				$name = 'MATRONERIA';
				break;
			case 'PSICOLOGO':
				$name = 'PSICOLOGIA_CLINICA';
				break;
		endswitch;

		$stmt = $db->Prepare("SELECT * FROM prm_profesion WHERE prof_rem = ?");

		$name = utf8_decode($name);
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->prof_id = $row['prof_id'];
		$obj->prof_nombre = utf8_encode($row['prof_nombre']);
		$obj->prof_rem = utf8_encode($row['prof_rem']);

		unset($db);
		return $obj;
	}

	/**
	 * @param $name
	 * @param $db
	 * @return array
	 */
	public function set($name, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_profesion (prof_nombre) VALUES (?)");

			if (!$stmt):
				throw new Exception("La inserción de la profesión falló en su preparación.");
			endif;

			$name = utf8_decode($db->clearText($name));
			$bind = $stmt->bind_param("s", $name);
			if (!$bind):
				throw new Exception("La inserción de la profesión falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de la profesión falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $stmt->insert_id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}

