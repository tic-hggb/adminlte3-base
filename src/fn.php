<?php

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
	if ($errno != 2048):
		$tmp = explode('\\', $errfile);
		$route = $tmp[(count($tmp) - 2)] . '\\' . $tmp[(count($tmp) - 1)];
		$err = array('type' => false, 'msg' => 'ERROR: [' . $errno . '] ' . $errstr . " (" . $route . ' : ' . $errline . ')');
		echo json_encode($err);
		//die();
	endif;
}

set_error_handler('customErrorHandler');

/**
 * @param $str
 * @return array
 */
function descom($str): array
{
	return explode(',', $str);
}

/**
 * @param $id
 * @param $date
 * @return string
 */
function encryptLink($id, $date): string
{
	$date_enc = str_replace(['-', ' ', ':'], '_', $date);
	return base64_encode($date_enc . '_id_' . $id);
}

/**
 * @param $str
 * @return mixed
 */
function decryptLink($str)
{
	$tmp = base64_decode($str);
	$arr = explode('_', $tmp);
	return $arr[7];
}

/**
 * @param $str
 * @return string
 */
function decrypt($str): string
{
	$tmp = base64_decode($str);
	return utf8_encode($tmp);
}

/**
 * Simple helper to debug to the console
 *
 * @param $d
 */
function toConsole($d)
{
	if (is_array($d)):
		$output = "<script>console.log( 'Debug Objects: " . implode(',', $d) . "' );</script>";
	else:
		$output = "<script>console.log( 'Debug Objects: " . $d . "' );</script>";
	endif;

	echo $output;
}

/**
 * @param $d
 * @return string
 */
function getDateBD($d): string
{
	$aux = explode('-', $d);
	return $aux[2] . '/' . $aux[1] . '/' . $aux[0];
}

/**
 * @param $d
 * @return string
 */
function getDateHourBD($d): string
{
	$aux = explode(' ', $d);
	$aux2 = explode('-', $aux[0]);
	return $aux2[2] . '/' . $aux2[1] . '/' . $aux2[0] . ' ' . $aux[1];
}

/**
 * @param $d
 * @return string
 */
function getDateToForm($d): string
{
	$aux = explode('-', $d);
	return $aux[2] . '/' . $aux[1] . '/' . $aux[0];
}

/**
 * @param $d
 * @return string
 */
function getDateHourToForm($d): string
{
	$aux = explode(' ', $d);
	$aux2 = explode('-', $aux[0]);
	return $aux2[2] . '/' . $aux2[1] . '/' . $aux2[0] . ' ' . $aux[1];
}

/**
 * @param $d
 * @return string
 */
function getDateMonthToForm($d): string
{
	$aux = explode('-', $d);
	return $aux[1] . '/' . $aux[0];
}

/**
 * @param $d
 * @return string
 */
function getDateOnlyMonthToForm($d): string
{
	$aux = explode('-', $d);
	return $aux[1];
}

/**
 * @param $d
 * @return string
 */
function getDateOnlyHourToForm($d): string
{
	$aux = explode(':', $d);
	$num = (float)$aux[0];
	return number_format($num, 0, '', '.');
}

/**
 * @param $d
 * @return string
 *
 */
function getDateYearToForm($d): string
{
	$aux = explode('-', $d);
	return $aux[0];
}

/**
 * @param $d
 * @return string
 */
function getFullDate($d): string
{
	$date = strtotime($d);
	$week_days = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
	$months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

	$aux_d = date("w", $date);
	$day_w = $week_days[$aux_d];

	$day = date("d", $date);

	$aux_m = date("n", $date);
	$month = $months[$aux_m - 1];

	$year = date("Y", $date);
	return $day_w . ", " . $day . " de " . $month . " de " . $year;
}

/**
 * @param $d
 * @return string
 */
function getMonthDate($d): string
{
	$date = strtotime($d);
	$months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

	$aux_m = date("n", $date);
	$month = $months[$aux_m - 1];

	$year = date("Y", $date);
	return $month . " de " . $year;
}

/**
 * @param $d
 * @return array
 */
function getArrayDate($d): array
{
	$date = strtotime($d);
	$week_days = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
	$months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

	$aux_d = date("w", $date);
	$day_w = $week_days[$aux_d];

	$day = date("d", $date);

	$aux_m = date("n", $date);
	$month_w = $months[$aux_m - 1];

	$month = date("m", $date);

	$year = date("Y", $date);
	return array('day_w' => $day_w, 'day' => $day, 'month_w' => $month_w, 'month' => $month, 'year' => $year);
}

/**
 * @param $d1
 * @param $d2
 * @return string
 * @throws Exception
 */
function getDiffDates($d1, $d2): string
{
	$datetime1 = new DateTime($d1);
	$datetime2 = new DateTime($d2);
	$interval = $datetime1->diff($datetime2);

	if ($interval->s > 0):
		if ($interval->i > 0):
			if ($interval->h > 0):
				if ($interval->d > 0):
					if ($interval->m > 0):
						if ($interval->y > 0):
							return $interval->y . "a";
						endif;
						return $interval->m . "m";
					endif;
					return $interval->d . "d";
				endif;
				return $interval->h . " hrs";
			endif;
			return $interval->i . " mins";
		endif;
		return $interval->s . " secs";
	endif;

	return '';
}

/**
 * @param $day
 * @param $month
 * @param $year
 * @return false|string
 * @throws \Exception
 */
function getFirstDay($day, $month, $year)
{
	$per = $year . '-' . $month;
	$fdate = date("Y-m-d", strtotime("first monday of " . $per));
	$date = '';

	switch ($day):
		case 0:
			$date = $fdate;
			break;
		case 1:
			$date = new DateTime($fdate);
			$date->modify('next tuesday');
			$date = $date->format('Y-m-d');
			break;
		case 2:
			$date = new DateTime($fdate);
			$date->modify('next wednesday');
			$date = $date->format('Y-m-d');
			break;
		case 3:
			$date = new DateTime($fdate);
			$date->modify('next thursday');
			$date = $date->format('Y-m-d');
			break;
		case 4:
			$date = new DateTime($fdate);
			$date->modify('next friday');
			$date = $date->format('Y-m-d');
			break;
		case 5:
			$date = new DateTime($fdate);
			$date->modify('next saturday');
			$date = $date->format('Y-m-d');
			break;
		case 6:
			$date = new DateTime($fdate);
			$date->modify('next sunday');
			$date = $date->format('Y-m-d');
			break;
		default:
			break;
	endswitch;

	return $date;
}

/**
 * @param $d
 * @return string
 */
function setDateBD($d): string
{
	$aux = explode('/', $d);
	return $aux[2] . '-' . $aux[1] . '-' . $aux[0];
}

/**
 * @param $start
 * @param $end
 * @param string $format
 * @return array
 * @throws Exception
 */
function daysBetweenDates($start, $end, string $format = 'Y-m-d'): array
{
	$start = new DateTime($start);
	$end = new DateTime($end);
	$invert = $start > $end;

	$dates = array();
	$dates[] = $start->format($format);
	while ($start != $end):
		$start->modify(($invert ? '-' : '+') . '1 day');
		$dates[] = $start->format($format);
	endwhile;
	return $dates;
}

/**
 * @param $start
 * @param $end
 * @param string $format
 * @return array
 * @throws Exception
 */
function workingDaysBetweenDates($start, $end, $format = 'Y-m-d'): array
{
	$start = new DateTime($start);
	$end = new DateTime($end);
	$invert = $start > $end;

	$dates = array();
	$dates[] = $start->format($format);
	while ($start != $end):
		$start->modify(($invert ? '-' : '+') . '1 day');

		if ($start->format('N') != 6 and $start->format('N') != 7):
			$dates[] = $start->format($format);
		endif;
	endwhile;
	return $dates;
}

/**
 * @param string $format
 * @return stdClass
 */
function firstLastWeekDay(string $format = 'Y-m-d'): stdClass
{
	$date = new stdClass();
	$date->start = (date('D') != 'Mon') ? date($format, strtotime('last Monday')) : date($format);
	$date->finish = (date('D') != 'Fri') ? date($format, strtotime('next Friday')) : date($format);
	return $date;
}

/**
 * @param $e
 * @return string
 */
function getExtension($e): string
{
	switch (strtolower($e)):
		// Image
		case "png":
		case "jpeg":
		case "jpg":
		case "gif":
			$ext = "img";
			break;
		// Video
		case "avi":
		case "mp4":
		case "mov":
		case "wmv":
			$ext = "vid";
			break;
		// Zipped
		case "zip":
		case "rar":
			$ext = "rar";
			break;
		// Excel
		case "csv":
		case "xlsx":
		case "xls":
			$ext = "xls";
			break;
		// Powerpoint
		case "pptx":
		case "ppt":
			$ext = "ppt";
			break;
		// AReader
		case "pdf":
			$ext = "pdf";
			break;
		// Word
		case "rtf":
		case "docx":
		case "doc":
			$ext = "doc";
			break;
		// Other
		default:
			$ext = "unk";
			break;
	endswitch;

	return $ext;
}

/**
 * @param $f
 * @return string
 */
function getFilesize($f): string
{
	$decimals = 2;
	$bytes = filesize($f);
	$size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $size[$factor];
}

/**
 * @param $str
 * @return mixed
 */
function removeAccents($str)
{
	$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ',
		'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç',
		'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý',
		'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē',
		'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ',
		'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ',
		'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő',
		'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť',
		'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź',
		'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ',
		'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ',
		'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή', 'º', '°');
	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N',
		'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c',
		'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y',
		'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E',
		'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H',
		'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L',
		'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o',
		'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't',
		'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z',
		'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U',
		'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω',
		'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η', 'o', 'o');
	return str_replace($a, $b, $str);
}

/**
 * @param $string
 * @param int $width
 * @param string $break
 * @return string
 */
function smart_wordwrap($string, int $width = 75, string $break = "\n"): string
{
	// split on problem words over the line length
	$pattern = sprintf('/([^ ]{%d,})/', $width);
	$output = '';
	$words = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

	foreach ($words as $word):
		if (false !== strpos($word, ' ')):
			// normal behaviour, rebuild the string
			$output .= $word;
		else:
			// work out how many characters would be on the current line
			$wrapped = explode($break, wordwrap($output, $width, $break));
			$count = $width - (strlen(end($wrapped)) % $width);

			// fill the current line and add a break
			$output .= substr($word, 0, $count) . $break;

			// wrap any remaining characters from the problem word
			$output .= wordwrap(substr($word, $count), $width, $break, true);
		endif;
	endforeach;

	// wrap the final output
	return wordwrap($output, $width, $break);
}

/**
 * @param $_rol
 * @return string
 */
function fullRut($_rol): string
{
	while ($_rol[0] == "0"):
		$_rol = substr($_rol, 1);
	endwhile;

	$factor = 2;
	$suma = 0;

	for ($i = strlen($_rol) - 1; $i >= 0; $i--):
		$suma += $factor * $_rol[$i];
		$factor = $factor % 7 == 0 ? 2 : $factor + 1;
	endfor;

	$dv = 11 - $suma % 11;

	$dv = $dv == 11 ? 0 : ($dv == 10 ? "K" : $dv);
	return number_format($_rol, 0, '', '.') . "-" . $dv;
}