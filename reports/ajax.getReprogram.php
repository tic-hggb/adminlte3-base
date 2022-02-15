<?php

session_start();
$_admin = false;
if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
	$_admin = true;
else:
	$iestab = $_SESSION['prm_estid'];
endif;

/** Include \PhpOffice\PhpSpreadsheet\Spreadsheet */
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/** Include Classes */
include("../class/classMyDBC.php");
include("../class/classParametro.php");
include("../class/classCr.php");
include("../class/classServicio.php");
include("../class/classDistribucionProg.php");
include("../class/classDistHorasProg.php");
include("../src/fn.php");

if (extract($_POST)):
	$par = new Parametro();
	$cr = new Cr();
	$se = new Servicio();
	$d = new DistribucionProg();
	$dh = new DistHorasProg();

	$t_par = $par->get($iyear);
	$WEEKS = $t_par->par_semanas;
	$cresp = $cr->get($icr);
	$serv = $se->get($iserv);
	$meds = $d->getReprogsByFilters($iyear, $iestab, $iplanta, $icr, $iserv, $iesp);

	// Create new \PhpOffice\PhpSpreadsheet\Spreadsheet object
	$objSS = new Spreadsheet();

	// Set document properties
	$objSS->getProperties()->setCreator("Ignacio Muñoz J.")
		->setLastModifiedBy("SISPLAN")
		->setTitle("Programación de Planta");

	$objSS->getDefaultStyle()->getFont()->setName('Calibri');
	$objSS->getDefaultStyle()->getFont()->setSize(11);
	$objSS->getActiveSheet()->getDefaultColumnDimension()->setWidth(14.00);
	$objSS->getActiveSheet()->getSheetView()->setZoomScale(80);

	$styleArrayHeader = array(
		'font' => array(
			'bold' => true,
			'color' => array('rgb' => '000000')
		),
		'alignment' => array(
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
		),
		'borders' => array(
			'top' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
			),
			'bottom' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
			),
			'right' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
			),
			'left' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
			),
		)
	);

	$styleArrayHeaderCell = array(
		'alignment' => array(
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
		),
		'borders' => array(
			'top' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
			'right' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
		)
	);

	$styleArrayHeaderLastCell = array(
		'alignment' => array(
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
		),
		'borders' => array(
			'top' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
			'right' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
			)
		)
	);

	$styleArrayCell = array(
		'borders' => array(
			'bottom' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
			'right' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
		)
	);

	$styleArrayLastCell = array(
		'borders' => array(
			'bottom' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
			'right' => array(
				'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
			)
		)
	);

	// HEADER
	$objSS->getActiveSheet()->getRowDimension(1)->setRowHeight(45);
	$objSS->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
	$objSS->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
	$objSS->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
	$objSS->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$objSS->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

	if ($cresp->cr_nombre == ''): $cresp->cr_nombre = 'VARIOS'; endif;
	if ($serv->ser_nombre == ''): $serv->ser_nombre = 'VARIOS'; endif;
	$objSS->getActiveSheet()->setCellValue('A1', 'CR: ' . $cresp->cr_nombre . ' / Servicio: ' . $serv->ser_nombre);
	$objSS->getActiveSheet()->mergeCells('A1:D1');

	$i = 2;

	// TABLA 2
	// Descuento feriados
	$objSS->getActiveSheet()->getStyle('E' . $i . ':J' . $i)->getFont()->setSize(12);
	$objSS->getActiveSheet()->getStyle('E' . $i . ':J' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('4F6228');
	$objSS->getActiveSheet()->getRowDimension($i)->setRowHeight(45.75);

	$objSS->getActiveSheet()->setCellValue('E' . $i, 'Descuento de Feriados Legales, Permisos Administrativos y Capacitación');
	$objSS->getActiveSheet()->getStyle('E' . $i)->getAlignment()->setWrapText(true);

	$objSS->getActiveSheet()->mergeCells('E' . $i . ':G' . $i);
	$objSS->getActiveSheet()->getStyle('E' . $i . ':J' . $i)->applyFromArray($styleArrayHeader);

	// Produccion anual
	$objSS->getActiveSheet()->setCellValue('H' . $i, 'REPROGRAMACIONES');
	$objSS->getActiveSheet()->getStyle('H' . $i)->getAlignment()->setWrapText(true);

	$objSS->getActiveSheet()->mergeCells('H' . $i . ':J' . $i);
	$objSS->getActiveSheet()->getStyle('H' . $i . ':J' . $i)->applyFromArray($styleArrayHeader);
	$objSS->getActiveSheet()->getStyle('E' . $i . ':J' . $i)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
	$i++;

	// Programacion anual actividades
	$objSS->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('76933C');
	$objSS->getActiveSheet()->getRowDimension($i)->setRowHeight(45.75);
	$objSS->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->getAlignment()->setWrapText(true);

	$objSS->getActiveSheet()->setCellValue('A' . $i, 'Programación Anual de Actividades por Profesional');

	$objSS->getActiveSheet()->mergeCells('A' . $i . ':D' . $i);
	$objSS->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($styleArrayHeader);

	// Vacaciones
	$objSS->getActiveSheet()->getStyle('E' . $i . ':G' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
	$objSS->getActiveSheet()->getStyle('E' . $i . ':G' . $i)->getFont()->setBold(true);

	$objSS->getActiveSheet()->setCellValue('E' . $i, 'Vacaciones');
	$objSS->getActiveSheet()->getStyle('E' . $i)->applyFromArray($styleArrayHeaderCell);

	// Dias permiso
	$objSS->getActiveSheet()->setCellValue('F' . $i, 'Días de Permiso Administrativo');
	$objSS->getActiveSheet()->getStyle('F' . $i)->applyFromArray($styleArrayHeaderCell);

	// Dias congreso
	$objSS->getActiveSheet()->setCellValue('G' . $i, 'Días de Congreso o Capacitación');
	$objSS->getActiveSheet()->getStyle('G' . $i)->applyFromArray($styleArrayHeaderLastCell);

	// Consultas
	$objSS->getActiveSheet()->setCellValue('H' . $i, 'N° Consultas');
	$objSS->getActiveSheet()->getStyle('H' . $i)->applyFromArray($styleArrayHeader);

	// Procedimientos
	$objSS->getActiveSheet()->setCellValue('I' . $i, 'Teleconsultas');
	$objSS->getActiveSheet()->getStyle('I' . $i)->applyFromArray($styleArrayHeader);

	// Visitas a sala
	$objSS->getActiveSheet()->setCellValue('J' . $i, 'Programaciones');
	$objSS->getActiveSheet()->getStyle('J' . $i)->applyFromArray($styleArrayHeader);
	$i++;

	// Nombre Profesional
	$objSS->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('C4D79B');
	$objSS->getActiveSheet()->getRowDimension($i)->setRowHeight(115);
	$objSS->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->getAlignment()->setWrapText(true);

	$objSS->getActiveSheet()->setCellValue('A' . $i, 'Nombre del Profesional');

	$objSS->getActiveSheet()->mergeCells('A' . $i . ':B' . $i);
	$objSS->getActiveSheet()->getStyle('A' . $i . ':B' . $i)->applyFromArray($styleArrayHeader);

	// Especialidad
	$objSS->getActiveSheet()->setCellValue('C' . $i, 'Especialidad o Programa');

	$objSS->getActiveSheet()->getStyle('C' . $i)->applyFromArray($styleArrayHeader);

	// Profesion
	$objSS->getActiveSheet()->setCellValue('D' . $i, 'Profesión');

	$objSS->getActiveSheet()->getStyle('D' . $i)->applyFromArray($styleArrayHeader);

	// Dias anuales
	$objSS->getActiveSheet()->getStyle('E' . $i . ':J' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
	$objSS->getActiveSheet()->getStyle('E' . $i . ':J' . $i)->getFont()->setBold(true);

	$objSS->getActiveSheet()->setCellValue('E' . $i, 'Días Anuales');
	$objSS->getActiveSheet()->getStyle('E' . $i)->applyFromArray($styleArrayHeaderCell);

	$objSS->getActiveSheet()->setCellValue('F' . $i, 'Días Anuales');
	$objSS->getActiveSheet()->getStyle('F' . $i)->applyFromArray($styleArrayHeaderCell);

	$objSS->getActiveSheet()->setCellValue('G' . $i, 'Días Anuales');
	$objSS->getActiveSheet()->getStyle('G' . $i)->applyFromArray($styleArrayHeaderLastCell);

	// Pacientes anuales
	$objSS->getActiveSheet()->setCellValue('H' . $i, 'N° Pacientes Anuales');
	$objSS->getActiveSheet()->getStyle('H' . $i)->applyFromArray($styleArrayHeaderLastCell);

	// Teleconsultas
	$objSS->getActiveSheet()->setCellValue('I' . $i, 'N° Teleconsultas');
	$objSS->getActiveSheet()->getStyle('I' . $i)->applyFromArray($styleArrayHeaderLastCell);

	// Reprogramaciones
	$objSS->getActiveSheet()->setCellValue('J' . $i, 'Programaciones Registradas');
	$objSS->getActiveSheet()->getStyle('J' . $i)->applyFromArray($styleArrayHeaderLastCell);

	$i++;

	// data
	$t_vacaciones = $t_permiso = $t_congreso = $t_c_c = $t_telecons = 0;

	foreach ($meds as $k => $v):
		$vacaciones = $permisos = $congreso = $c_c = $telec = 0;
		$primer_per = $segundo_per = $tercer_per = $cuarto_per = false;
		$reprog = '';

		// Progr Ene-Dic
		$dist_e_d = $d->getByPerDateEsp($v->per_id, $iestab, $v->per_espid, $iyear . '/01/01', $iyear . '/12/31');
		if ($dist_e_d->disp_id != null):
			$primer_per = true;
			$vacaciones = $dist_e_d->disp_vacaciones;
			$permisos = $dist_e_d->disp_permisos;
			$congreso = $dist_e_d->disp_congreso;

			$dias = $vacaciones + $permisos + $congreso;
			$tmp = round($dias / 5);
			$total = ($WEEKS - $tmp);
			$total_pp = $total * 0.24;

			$c_c_pp = $dh->getByConsCont($dist_e_d->disp_id) * $total_pp;
			$c_c += $c_c_pp;

			$tel = $dh->getByTHDate($dist_e_d->disp_id, $iyear . '/01/01', $iyear . '/12/31', 11);
			$tel_pp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_pp;
			$telec += $tel_pp;

			$reprog .= 'ENE ';
		endif;

		// Progr Abr-Dic
		$dist_m_d = $d->getByPerDateEsp($v->per_id, $iestab, $v->per_espid, $iyear . '/04/01', $iyear . '/12/31');
		if ($dist_m_d->disp_id != null):
			$segundo_per = true;
			$vacaciones = $dist_m_d->disp_vacaciones;
			$permisos = $dist_m_d->disp_permisos;
			$congreso = $dist_m_d->disp_congreso;

			$dias = $vacaciones + $permisos + $congreso;
			$tmp = round($dias / 5);
			$total = ($WEEKS - $tmp);
			$total_sp = $total * 0.26;

			$c_c_sp = $dh->getByConsCont($dist_m_d->disp_id) * $total_sp;
			$c_c += $c_c_sp;

			$tel = $dh->getByTHDate($dist_m_d->disp_id, $iyear . '/04/01', $iyear . '/12/31', 11);
			$tel_sp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_sp;
			$telec += $tel_sp;

			$reprog .= 'MAR ';
		// no tiene Abr-Dic se rellena con Ene-Mar * 0.26 si lo tiene
		elseif ($primer_per):
			$total_pp = $total * 0.26;

			$c_c_pp = $dh->getByConsCont($dist_e_d->disp_id) * $total_pp;
			$c_c += $c_c_pp;

			$tel = $dh->getByTHDate($dist_e_d->disp_id, $iyear . '/01/01', $iyear . '/12/31', 11);
			$tel_pp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_pp;
			$telec += $tel_pp;
		endif;

		// Progr Jun-Dic
		$dist_j_d = $d->getByPerDateEsp($v->per_id, $iestab, $v->per_espid, $iyear . '/07/01', $iyear . '/12/31');
		if ($dist_j_d->disp_id != null):
			$tercer_per = true;
			$vacaciones = $dist_j_d->disp_vacaciones;
			$permisos = $dist_j_d->disp_permisos;
			$congreso = $dist_j_d->disp_congreso;

			$dias = $vacaciones + $permisos + $congreso;
			$tmp = round($dias / 5);
			$total = ($WEEKS - $tmp);
			$total_tp = $total * 0.26;

			$c_c_tp = $dh->getByConsCont($dist_j_d->disp_id) * $total_tp;
			$c_c += $c_c_tp;

			$tel = $dh->getByTHDate($dist_j_d->disp_id, $iyear . '/07/01', $iyear . '/12/31', 11);
			$tel_tp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_tp;
			$telec += $tel_tp;

			$reprog .= 'JUN ';
		// no tiene Jul-Dic
		else:
			// si tiene Abr-Dic se rellena
			if ($segundo_per):
				$total_sp = $total * 0.26;

				$c_c_sp = $dh->getByConsCont($dist_m_d->disp_id) * $total_sp;
				$c_c += $c_c_sp;

				$tel = $dh->getByTHDate($dist_m_d->disp_id, $iyear . '/04/01', $iyear . '/12/31', 11);
				$tel_sp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_sp;
				$telec += $tel_sp;
			// no tiene Abr-Dic se rellena con Ene-Mar * 0.26 si lo tiene
			elseif ($primer_per):
				$total_pp = $total * 0.26;

				$c_c_pp = $dh->getByConsCont($dist_e_d->disp_id) * $total_pp;
				$c_c += $c_c_pp;

				$tel = $dh->getByTHDate($dist_e_d->disp_id, $iyear . '/01/01', $iyear . '/12/31', 11);
				$tel_pp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_pp;
				$telec += $tel_pp;
			endif;
		endif;

		// Progr Sep-Dic
		$dist_s_d = $d->getByPerDateEsp($v->per_id, $iestab, $v->per_espid, $iyear . '/10/01', $iyear . '/12/31');
		if ($dist_s_d->disp_id != ''):
			$cuarto_per = true;
			$vacaciones = $dist_s_d->disp_vacaciones;
			$permisos = $dist_s_d->disp_permisos;
			$congreso = $dist_s_d->disp_congreso;

			$dias = $vacaciones + $permisos + $congreso;
			$tmp = round($dias / 5);
			$total = ($WEEKS - $tmp);
			$total_cp = $total * 0.24;

			$c_c_cp = $dh->getByConsCont($dist_s_d->disp_id) * $total_cp;
			$c_c += $c_c_cp;

			$tel = $dh->getByTHDate($dist_s_d->disp_id, $iyear . '/10/01', $iyear . '/12/31', 11);
			$tel_cp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_cp;
			$telec += $tel_cp;

			$reprog .= 'SEP';
		// no tiene Sep-Dic
		else:
			// si tiene Jul-Dic se rellena con Jul-Dic * 0.24
			if ($tercer_per):
				$total_tp = $total * 0.24;

				$c_c_tp = $dh->getByConsCont($dist_j_d->disp_id) * $total_tp;
				$c_c += $c_c_tp;

				$tel = $dh->getByTHDate($dist_j_d->disp_id, $iyear . '/07/01', $iyear . '/12/31', 11);
				$tel_tp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_tp;
				$telec += $tel_tp;
			// no tiene Jul-Dic se rellena con Abr-Dic * 0.24 si lo tiene
			elseif ($segundo_per):
				$total_sp = $total * 0.24;

				$c_c_sp = $dh->getByConsCont($dist_m_d->disp_id) * $total_sp;
				$c_c += $c_c_sp;

				$tel = $dh->getByTHDate($dist_m_d->disp_id, $iyear . '/04/01', $iyear . '/12/31', 11);
				$tel_sp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_sp;
				$telec += $tel_sp;
			// no tiene Abr-Dic se rellena con Ene-Mar si lo tiene
			elseif ($primer_per):
				$total_pp = $total * 0.24;

				$c_c_pp = $dh->getByConsCont($dist_e_d->disp_id) * $total_pp;
				$c_c += $c_c_pp;

				$tel = $dh->getByTHDate($dist_e_d->disp_id, $iyear . '/01/01', $iyear . '/12/31', 11);
				$tel_pp = round($tel->dhp_cantidad * $tel->dhp_rendimiento, 2) * $total_pp;
				$telec += $tel_pp;
			endif;
		endif;

		$objSS->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('DDD9C4');
		$objSS->getActiveSheet()->getStyle('E' . $i . ':G' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFFFFF');

		$objSS->getActiveSheet()->setCellValue('A' . $i, $v->per_nombres);
		$objSS->getActiveSheet()->getStyle('A' . $i)->applyFromArray($styleArrayCell);
		$objSS->getActiveSheet()->setCellValue('B' . $i, $v->per_ap . ' ' . $v->per_am);
		$objSS->getActiveSheet()->getStyle('B' . $i)->applyFromArray($styleArrayLastCell);
		$objSS->getActiveSheet()->setCellValue('C' . $i, $v->per_especialidad);
		$objSS->getActiveSheet()->getStyle('C' . $i)->applyFromArray($styleArrayLastCell);

		$objSS->getActiveSheet()->setCellValue('D' . $i, $v->per_profesion);
		$objSS->getActiveSheet()->getStyle('D' . $i)->applyFromArray($styleArrayLastCell);

		$objSS->getActiveSheet()->getStyle('C' . $i . ':J' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$objSS->getActiveSheet()->setCellValue('E' . $i, $vacaciones);
		$t_vacaciones += $vacaciones;
		$objSS->getActiveSheet()->getStyle('E' . $i)->applyFromArray($styleArrayCell);
		$objSS->getActiveSheet()->setCellValue('F' . $i, $permisos);
		$t_permiso += $permisos;
		$objSS->getActiveSheet()->getStyle('F' . $i)->applyFromArray($styleArrayCell);
		$objSS->getActiveSheet()->setCellValue('G' . $i, $congreso);
		$t_congreso += $congreso;
		$objSS->getActiveSheet()->getStyle('G' . $i)->applyFromArray($styleArrayLastCell);

		$objSS->getActiveSheet()->getStyle('H' . $i)->applyFromArray($styleArrayLastCell);
		$objSS->getActiveSheet()->getStyle('I' . $i)->applyFromArray($styleArrayLastCell);
		$objSS->getActiveSheet()->getStyle('J' . $i)->applyFromArray($styleArrayLastCell);

		// Total consultas
		$objSS->getActiveSheet()->setCellValue('H' . $i, round($c_c));
		$t_c_c += round($c_c);
		$objSS->getActiveSheet()->getStyle('H' . $i)->applyFromArray($styleArrayLastCell);
		$objSS->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode('##,##0');

		// Total teleconsultas
		$objSS->getActiveSheet()->setCellValue('I' . $i, round($telec));
		$t_telecons += round($telec);
		$objSS->getActiveSheet()->getStyle('I' . $i)->applyFromArray($styleArrayLastCell);
		$objSS->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode('##,##0');

		$objSS->getActiveSheet()->setCellValue('J' . $i, $reprog);
		$i++;

	endforeach;

	// Totales
	$objSS->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('DDD9C4');
	$objSS->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->getFont()->setBold(true);
	$objSS->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$objSS->getActiveSheet()->getStyle('D' . $i . ':I' . $i)->getNumberFormat()->setFormatCode('##,##0');

	$objSS->getActiveSheet()->setCellValue('A' . $i, 'TOTAL');
	$objSS->getActiveSheet()->mergeCells('A' . $i . ':D' . $i);
	$objSS->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($styleArrayHeader);

	$objSS->getActiveSheet()->setCellValue('E' . $i, $t_vacaciones);
	$objSS->getActiveSheet()->getStyle('E' . $i)->applyFromArray($styleArrayHeaderCell);
	$objSS->getActiveSheet()->setCellValue('F' . $i, $t_permiso);
	$objSS->getActiveSheet()->getStyle('F' . $i)->applyFromArray($styleArrayHeaderCell);
	$objSS->getActiveSheet()->setCellValue('G' . $i, $t_congreso);
	$objSS->getActiveSheet()->getStyle('G' . $i)->applyFromArray($styleArrayHeaderLastCell);

	$objSS->getActiveSheet()->setCellValue('H' . $i, $t_c_c);
	$objSS->getActiveSheet()->getStyle('H' . $i)->applyFromArray($styleArrayHeader);
	$objSS->getActiveSheet()->setCellValue('I' . $i, $t_telecons);
	$objSS->getActiveSheet()->getStyle('I' . $i)->applyFromArray($styleArrayHeader);

	$objSS->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
	$objSS->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

	// Autoadjust widths
	foreach (range('A', 'J') as $columnID):
		$objSS->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	endforeach;

	// Rename worksheet
	$objSS->getActiveSheet()->setTitle('Programación Por Profesional');

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objSS->setActiveSheetIndex(0);

	// Save Excel 2007 file
	$objWriter = new Xlsx($objSS);
	$objWriter->save('../upload/Planilla Reprogramacion ' . $iyear . '.xlsx');

	$response = array('type' => true, 'msg' => $iyear);
	echo json_encode($response);
endif;