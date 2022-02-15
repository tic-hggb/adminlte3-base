<?php

session_start();
$_admin = false;
if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
	$_admin = true;
else:
	$iestab = $_SESSION['prm_estid'];
endif;

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

array_map('unlink', glob("../upload/*.xlsx"));

/** Include Classes */
include("../class/classMyDBC.php");
include("../class/classParametro.php");
include("../class/classPersona.php");
include("../class/classPersonaEstablecimiento.php");
include("../class/classDistribucionProg.php");
include("../class/classDistHorasProg.php");
include("../src/fn.php");

if (extract($_POST)):
	try {
		$db = new myDBC();
		$par = new Parametro();
		$p = new Persona();
		$pe = new PersonaEstablecimiento();
		$d = new DistribucionProg();
		$dh = new DistHorasProg();

		$t_par = $par->get($iyear);
		$WEEKS = $t_par->par_semanas;
		$idate = setDateBD('01/' . $iperiodo . '/' . $iyear);
		$idate_t = setDateBD('31/12/' . $iyear);
		$tmp = explode('-', $idate);
		$year = $tmp[0];

		$objSS = new Spreadsheet();
		$objSS->getProperties()->setCreator("Ignacio Muñoz J.")
			->setLastModifiedBy("SISPLAN")
			->setTitle("Programación de Planta");

		$obStyle = $objSS->getDefaultStyle();
		$obStyle->getFont()->setName('Calibri')
			->setSize(11);

		$styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['argb' => '00000000']
				]
			]
		];

		$obj = $objSS->getActiveSheet();
		$obj->getDefaultColumnDimension()->setWidth(14.00);
		$obj->getSheetView()->setZoomScale(70);

		// HEADER
		$obj->getRowDimension(1)->setRowHeight(90);
		$obj->getStyle('A1:R1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$obj->getStyle('A1:R1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$obj->getStyle('A1:R1')->getAlignment()->setWrapText(true);
		$obj->getStyle('A1:R1')->applyFromArray($styleArray);

		$obj->setCellValue('A1', 'Id_Dei');
		$obj->setCellValue('B1', 'RUTSDV');
		$obj->setCellValue('C1', 'DV');
		$obj->setCellValue('D1', 'Correlativo Contrato');
		$obj->setCellValue('E1', 'Nombre_Profesional');
		if ($iplanta == 0):
			$obj->setCellValue('F1', 'ID_Especialidad');
			$obj->setCellValue('G1', 'Especialidad');
		else:
			$obj->setCellValue('F1', 'ID_Profesion');
			$obj->setCellValue('G1', 'Profesion');
		endif;
		$obj->getColumnDimension('G')->setWidth(52);
		$obj->setCellValue('H1', 'Id_Actividad');
		$obj->setCellValue('I1', 'Actividad');
		$obj->getColumnDimension('I')->setWidth(60);
		$obj->setCellValue('J1', 'Horas Asignadas');
		$obj->setCellValue('K1', 'Rendimiento por Hora');
		$obj->setCellValue('L1', 'Total Horas semanales del contrato');
		$obj->setCellValue('M1', '% asignado del contrato');
		$obj->setCellValue('N1', 'Total horas semanales contratadas RUN');
		$obj->setCellValue('O1', 'VC');
		$obj->setCellValue('P1', 'V Horas legales descuento (Congreso o capacitación + Descanso complementario + F. Legales + lactancia + Colación + Permisos administrativos) (Proporcional)');
		$obj->getColumnDimension('P')->setWidth(32);
		$obj->setCellValue('Q1', 'Producción Estimada Anual');
		$obj->setCellValue('R1', 'M. General');
		$obj->setCellValue('S1', 'Requiere rendimiento');

		$ds = $d->getConsolidated($idate, $idate_t, $iestab, $iplanta, $db);
		$i = 2;

		foreach ($ds as $in => $dat):
			$cont = $pe->get($dat->pes_id);

			$obj->setCellValue('A' . $i, $dat->est_deis);
			$tmp = explode('-', $dat->per_rut);
			$rut = str_replace('.', '', $tmp[0]);
			$dv = $tmp[1];
			$obj->setCellValue('B' . $i, $rut);
			$obj->setCellValue('C' . $i, $dv);
			$obj->setCellValue('D' . $i, $dat->per_corr);
			$obj->setCellValue('E' . $i, $dat->per_nombre);
			if ($iplanta == 0):
				$obj->setCellValue('F' . $i, $dat->esp_codigo);
				$obj->setCellValue('G' . $i, $dat->esp_descripcion);
			else:
				$obj->setCellValue('F' . $i, $dat->prof_id);
				$obj->setCellValue('G' . $i, $dat->prof_rem);
			endif;
			$obj->setCellValue('H' . $i, $dat->act_id);
			$obj->setCellValue('I' . $i, $dat->act_desc_reporte);
			$obj->setCellValue('J' . $i, $dat->act_horas);
			$obj->setCellValue('K' . $i, $dat->act_rend);
			$obj->setCellValue('L' . $i, $cont->pes_horas);

			$perc = $dat->act_horas * 100 / $cont->pes_horas;
			$obj->setCellValue('M' . $i, number_format($perc, 0, '.', ''));

			$total = $pe->getTotalContratos($dat->per_id, $dat->est_id);
			$obj->setCellValue('N' . $i, $total);

			$medg = ($dat->per_medgeneral == 0) ? 'No' : 'Sí';
			$obj->setCellValue('R' . $i, $medg);

			$rend = ($dat->act_rend == 0) ? 'NR' : 'R';
			$obj->setCellValue('S' . $i, $rend);

			$i++;
		endforeach;

		$i--;
		$obj->getStyle('C1:C' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
		$obj->getStyle('A2:S' . $i)->applyFromArray($styleArray);

		$objSS->setActiveSheetIndex(0);
		$objWriter = new Xlsx($objSS);

		switch ($iplanta):
			case 0:
				$planta = 'Medica';
				break;
			case 1:
				$planta = 'No Medica';
				break;
			case 2:
				$planta = 'Odontologica';
				break;
		endswitch;

		$mark = strtotime('now');
		$objWriter->save('../upload/Planilla Programacion Planta ' . $planta . ' 118' . $iestab . ' ' . $year . '_' . $mark . '.xlsx');

		$response = array('type' => true, 'msg' => $planta . ' 118' . $iestab . ' ' . $year . '_' . $mark);
		echo json_encode($response);
	} catch (Exception $e) {
		$response = array('type' => false, 'msg' => $e->getMessage());
		echo json_encode($response);
	}
endif;