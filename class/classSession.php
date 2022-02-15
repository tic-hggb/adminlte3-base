<?php

class Session {

	public function __construct()
	{
	}

	/**
	 * @param $id
	 * @return stdClass
	 */
	public function get($id): stdClass
	{
		$db = new myDBC();
		$stmt = $db->Prepare("SELECT * FROM prm_sesion WHERE ses_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->ses_id = $row['ses_id'];
		$obj->us_id = $row['us_id'];
		$obj->us_time = $row['ses_time'];
		$obj->us_ip = $row['ses_ip'];

		unset($db);
		return $obj;
	}

	/**
	 * @param $user
	 * @param $ip
	 * @return bool
	 */
	public function set($user, $ip): bool
	{
		$db = new myDBC();
		$stmt = $db->Prepare("INSERT INTO prm_sesion (us_id, ses_ip) VALUES (?, ?)");

		$stmt->bind_param("is", $user, $ip);

		if ($stmt->execute()):
			unset($db);
			return true;
		else:
			return false;
		endif;
	}
}
