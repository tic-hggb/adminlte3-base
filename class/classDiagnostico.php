<?php

class Diagnostico {

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

		$stmt = $db->Prepare("SELECT * FROM prm_diagnostico
                                    WHERE dia_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->dia_id = $row['dia_id'];
		$obj->dia_espid = $row['esp_id'];
		$obj->dia_serid = $row['ser_id'];
		$obj->dia_fecha = $row['dia_fecha'];
		$obj->dia_total_esp = $row['dia_total_esp'];
		$obj->dia_lista = $row['dia_lista'];
		$obj->dia_total_esp_iq = $row['dia_total_esp_iq'];
		$obj->dia_lista_iq = $row['dia_lista_iq'];
		$obj->dia_disp_atc = $row['dia_disp_atc'];
		$obj->dia_disp_ata = $row['dia_disp_ata'];
		$obj->dia_disp_pro = $row['dia_disp_pro'];

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

		$stmt = $db->Prepare("SELECT dia_id FROM prm_diagnostico");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['dia_id'], $db);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $est
	 * @param $esp
	 * @param $serv
	 * @param $date
	 * @param $db
	 * @return stdClass
	 */
	public function getByEspDate($est, $esp, $serv, $date, $db = null): stdClass
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		$stmt = $db->Prepare("SELECT * FROM prm_diagnostico
                                    WHERE est_id = ? AND esp_id = ? AND ser_id = ? AND dia_fecha = ?");

		$est = $db->clearText($est);
		$esp = $db->clearText($esp);
		$serv = $db->clearText($serv);
		$date = $db->clearText($date);
		$stmt->bind_param("iiis", $est, $esp, $serv, $date);
		$stmt->execute();
		$result = $stmt->get_result();

		$row = $result->fetch_assoc();
		$obj = $this->get($row['dia_id'], $db);

		unset($db);
		return $obj;
	}

	/**
	 * @param $est
	 * @param $esp
	 * @param $ser
	 * @param $us
	 * @param $fecha
	 * @param $total
	 * @param $lista
	 * @param $total_iq
	 * @param $lista_iq
	 * @param $disp_ata
	 * @param $disp_atc
	 * @param $disp_pro
	 * @param $db
	 * @return array
	 */
	public function set($est, $esp, $ser, $us, $fecha, $total, $lista, $total_iq, $lista_iq, $disp_ata, $disp_atc, $disp_pro, $db = null): array
	{
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO prm_diagnostico (est_id, esp_id, ser_id, us_id, dia_fecha, dia_total_esp, dia_lista, dia_total_esp_iq, dia_lista_iq, dia_disp_ata, dia_disp_atc, dia_disp_pro) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

			if (!$stmt):
				throw new Exception("La inserción del diagnóstico falló en su preparación.");
			endif;

			$est = $db->clearText($est);
			$esp = $db->clearText($esp);
			$ser = $db->clearText($ser);
			$us = $db->clearText($us);
			$fecha = $db->clearText($fecha);
			$total = $db->clearText($total);
			$lista = $db->clearText($lista);
			$total_iq = $db->clearText($total_iq);
			$lista_iq = $db->clearText($lista_iq);
			$disp_ata = $db->clearText($disp_ata);
			$disp_atc = $db->clearText($disp_atc);
			$disp_pro = $db->clearText($disp_pro);
			$bind = $stmt->bind_param("iiiisiiiiiii", $est, $esp, $ser, $us, $fecha, $total, $lista, $total_iq, $lista_iq, $disp_ata, $disp_atc, $disp_pro);
			if (!$bind):
				throw new Exception("La inserción del diagnóstico falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción del diagnóstico falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $stmt->insert_id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			return array('estado' => false, 'msg' => $e->getMessage());
		}
	}
}

