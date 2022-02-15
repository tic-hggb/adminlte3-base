<?php

class User {

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

		$stmt = $db->Prepare("SELECT * FROM prm_usuario WHERE us_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->us_id = $row['us_id'];
		$obj->us_nombres = utf8_encode($row['us_nombres']);
		$obj->us_ap = utf8_encode($row['us_ap']);
		$obj->us_am = utf8_encode($row['us_am']);
		$obj->us_email = $row['us_email'];
		$obj->us_username = $row['us_username'];
		$obj->us_password = $row['us_password'];
		$obj->us_pic = $row['us_pic'];
		$obj->us_fecha = $row['us_fecha'];
		$obj->us_activo = $row['us_activo'];
		$obj->us_existe = $row['us_existe'];

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

		$stmt = $db->Prepare("SELECT us_id FROM prm_usuario WHERE us_existe = TRUE ORDER BY us_ap, us_am, us_nombres");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['us_id']);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @param $db
	 * @return array
	 */
	public function getGroups($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT gr_id FROM prm_usuario_grupo WHERE us_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $row['gr_id'];
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $str
	 * @param $db
	 * @return stdClass
	 */
	public function getByUsername($str, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT us_id FROM prm_usuario WHERE us_username = ?");

		$str = utf8_decode($db->clearText($str));
		$stmt->bind_param("s", $str);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		$obj = $this->get($row['us_id'], $db);

		unset($db);
		return $obj;
	}

	/**
	 * @param $user
	 * @param null $db
	 * @return array|bool[]
	 */
	public function existsUser($user, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("SELECT COUNT(us_id) AS n FROM prm_usuario WHERE us_username = ?");

			if (!$stmt):
				throw new Exception("La búsqueda del usuario falló en su preparación.");
			endif;

			$user = $db->clearText($user);
			$bind = $stmt->bind_param("s", $user);
			if (!$bind):
				throw new Exception("La búsqueda del usuario falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La búsqueda del usuario falló en su ejecución.");
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

	/**
	 * @param $name
	 * @param $ap
	 * @param $am
	 * @param $email
	 * @param $user
	 * @param $pass
	 * @param $db
	 * @return array
	 */
	public function set($name, $ap, $am, $email, $user, $pass, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_usuario (us_nombres, us_ap, us_am, us_email, us_username, us_password, us_fecha, us_activo, us_existe) VALUES (
                    ?, ?, ?, ?, ?, ?, CURRENT_DATE, TRUE, TRUE)");

			if (!$stmt):
				throw new Exception("La inserción del usuario falló en su preparación.");
			endif;

			$name = utf8_decode($db->clearText($name));
			$ap = utf8_decode($db->clearText($ap));
			$am = utf8_decode($db->clearText($am));
			$email = utf8_decode($db->clearText($email));
			$user = utf8_decode($db->clearText($user));
			$pass = md5(utf8_decode($db->clearText($pass)));
			$bind = $stmt->bind_param("ssssss", $name, $ap, $am, $email, $user, $pass);
			if (!$bind):
				throw new Exception("La inserción del usuario falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción del usuario falló en su ejecución.");
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
	 * @param $group
	 * @param $db
	 * @return array
	 */
	public function setGroup($id, $group, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_usuario_grupo (us_id, gr_id) VALUES (?, ?)");

			if (!$stmt):
				throw new Exception("La inserción del grupo falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("ii", $id, $group);
			if (!$bind):
				throw new Exception("La inserción del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción del grupo falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param $pic
	 * @param $db
	 * @return array
	 */
	public function setPicture($id, $pic, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_usuario SET us_pic = ? WHERE us_id = ?");

			if (!$stmt):
				throw new Exception("La inserción de la imagen falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("si", $pic, $id);
			if (!$bind):
				throw new Exception("La inserción de la imagen falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de la imagen falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
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
			$stmt = $db->Prepare("UPDATE prm_usuario SET us_existe = FALSE WHERE us_id = ?");

			if (!$stmt):
				throw new Exception("La eliminación del usuario falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La eliminación del usuario falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La eliminación del usuario falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
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
	public function delGroup($id, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("DELETE FROM prm_usuario_grupo WHERE us_id = ?");

			if (!$stmt):
				throw new Exception("La eliminación del grupo falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La eliminación del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La eliminación del grupo en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param $name
	 * @param $ap
	 * @param $am
	 * @param $email
	 * @param $pass
	 * @param $active
	 * @param $db
	 * @return array
	 */
	public function mod($id, $name, $ap, $am, $email, $pass, $active, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		if ($pass != ''):
			$txt_p = md5(utf8_decode($db->clearText($pass)));
		else:
			$res = $db->runQuery("SELECT us_password FROM prm_usuario WHERE us_id = '$id'");
			$row = $res->fetch_assoc();
			$txt_p = $row['us_password'];
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_usuario SET us_nombres = ?, us_ap = ?, us_am = ?, us_email = ?, us_password = ?, us_activo = ? WHERE us_id = ?");

			if (!$stmt):
				throw new Exception("La modificación del usuario falló en su preparación.");
			endif;

			$name = utf8_decode($db->clearText($name));
			$ap = utf8_decode($db->clearText($ap));
			$am = utf8_decode($db->clearText($am));
			$email = $db->clearText($email);
			$bind = $stmt->bind_param("sssssii", $name, $ap, $am, $email, $txt_p, $active, $id);
			if (!$bind):
				throw new Exception("La modificación del usuario falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La modificación del usuario falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param $name
	 * @param $ap
	 * @param $am
	 * @param $email
	 * @param $db
	 * @return array
	 */
	public function modProfile($id, $name, $ap, $am, $email, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE prm_usuario SET us_nombres = ?, us_ap = ?, us_am = ?, us_email = ? WHERE us_id = ?");

			if (!$stmt):
				throw new Exception("La modificación del usuario falló en su preparación.");
			endif;

			$name = utf8_decode($db->clearText($name));
			$ap = utf8_decode($db->clearText($ap));
			$am = utf8_decode($db->clearText($am));
			$email = $db->clearText($email);
			$bind = $stmt->bind_param("ssssi", $name, $ap, $am, $email, $id);
			if (!$bind):
				throw new Exception("La modificación del usuario falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La modificación del usuario falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}

	/**
	 * @param $id
	 * @param $pass
	 * @param $db
	 * @return array
	 */
	public function modPass($id, $pass, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$txt_p = md5(utf8_decode($db->clearText($pass)));

		try {
			$stmt = $db->Prepare("UPDATE prm_usuario SET us_password = ? WHERE us_id = ?");

			if (!$stmt):
				throw new Exception("La modificación del usuario falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("si", $txt_p, $id);
			if (!$bind):
				throw new Exception("La modificación del usuario falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La modificación del usuario falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $txt_p);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}