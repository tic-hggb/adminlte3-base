<?php

class SmsResponse {

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
		$stmt = $db->Prepare("SELECT * FROM prm_sms_response WHERE res_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->sms_id = $row['sms_id'];
		$obj->sms_rut = $row['sms_rut'];

		unset($db);
		return $obj;
	}

	/**
	 * @param $num
	 * @param $msj
	 * @param $date
	 * @param $db
	 * @return array
	 */
	public function set($num, $msj, $date, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_sms_response (res_numero, res_texto, res_fecha) VALUES (?, ?, ?)");

			if (!$stmt):
				throw new Exception("La inserción de la respuesta falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("sss", $num, $msj, $date);
			if (!$bind):
				throw new Exception("La inserción de la respuesta falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción de la respuesta falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $stmt->insert_id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}
