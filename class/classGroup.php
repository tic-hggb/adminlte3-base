<?php

class Group {

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

		$stmt = $db->Prepare("SELECT * FROM prm_grupo g
                            JOIN prm_perfil p ON g.perf_id = p.perf_id
                            WHERE g.gr_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->gr_id = $row['gr_id'];
		$obj->gr_nombre = utf8_encode($row['gr_nombre']);
		$obj->gr_pid = $row['perf_id'];
		$obj->gr_pnombre = utf8_encode($row['perf_nombre']);
		$obj->gr_fecha = $row['gr_fecha'];

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

		$stmt = $db->Prepare("SELECT gr_id FROM prm_grupo WHERE gr_existe = TRUE ORDER BY gr_id");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['gr_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @param $db
	 * @return array
	 */
	public function getIsEmpty($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("SELECT COUNT(ug.us_id) AS n FROM prm_usuario_grupo ug
                                    JOIN prm_usuario u ON ug.us_id = u.us_id
                                    WHERE ug.gr_id = ? AND u.us_existe = TRUE");

			if (!$stmt):
				throw new Exception("La consulta del grupo falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La consulta del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La consulta del grupo falló en su ejecución.");
			endif;

			$result = $stmt->get_result();
			$tnum = $result->fetch_assoc();

			if ($tnum['n'] > 0):
				$result = array('estado' => true, 'msg' => 'El grupo seleccionado tiene usuarios asociados y debe eliminarlos antes de eliminar el grupo.');
			else:
				$result = array('estado' => true, 'msg' => 'OK');
			endif;

			$stmt->close();
			unset($db);
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $name
	 * @param $profile
	 * @param $db
	 * @return array
	 */
	public function set($name, $profile, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_grupo (gr_nombre, perf_id, gr_fecha, gr_existe) VALUES (?, ?, CURRENT_DATE, TRUE)");

			if (!$stmt):
				throw new Exception("La inserción del grupo falló en su preparación.");
			endif;

			$name = utf8_decode($db->clearText($name));
			$bind = $stmt->bind_param("si", $name, $profile);
			if (!$bind):
				throw new Exception("La inserción del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción del grupo falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $stmt->insert_id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param $db
	 * @return array
	 */
	public function del($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_grupo SET gr_existe = FALSE WHERE gr_id = ?");

			if (!$stmt):
				throw new Exception("La eliminación del grupo falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La eliminación del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La eliminación del grupo falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => 'OK');
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param $name
	 * @param $pro_id
	 * @param $db
	 * @return array
	 */
	public function mod($id, $name, $pro_id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_grupo SET gr_nombre = ?, perf_id = ? WHERE gr_id = ?");

			if (!$stmt):
				throw new Exception("La modificación del grupo falló en su preparación.");
			endif;

			$name = utf8_decode($db->clearText($name));
			$bind = $stmt->bind_param("sii", $name, $pro_id, $id);
			if (!$bind):
				throw new Exception("La modificación del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La modificación del grupo falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $group
	 * @param $db
	 * @return array
	 */
	public function existsGroup($group, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("SELECT COUNT(gr_id) AS n FROM prm_grupo WHERE gr_nombre = ?");

			if (!$stmt):
				throw new Exception("La búsqueda del grupo falló en su preparación.");
			endif;

			$group = utf8_decode($db->clearText($group));
			$bind = $stmt->bind_param("s", $group);
			if (!$bind):
				throw new Exception("La búsqueda del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La búsqueda del grupo falló en su ejecución.");
			endif;

			$result = $stmt->get_result();
			$tnum = $result->fetch_assoc();

			if ($tnum['n'] > 0):
				$result = array('estado' => true, 'msg' => true);
			else:
				$result = array('estado' => true, 'msg' => false);
			endif;

			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}