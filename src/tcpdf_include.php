<?php
//============================================================+
// File name   : tcpdf_include.php
// Begin       : 2008-05-14
// Last Update : 2014-12-10
//
// Description : Search and include the TCPDF library.
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Search and include the TCPDF library.
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Include the main class.
 * @author Nicola Asuni
 * @since 2013-05-14
 */

// always load alternative config file for examples
// require_once('config/tcpdf_config_alt.php');

// Include the main TCPDF library (search the library on the following directories).
$tcpdf_include_dirs = array(
        'c:\xampp\htdocs\tcpdf\tcpdf.php',
        'f:\xampp\htdocs\tcpdf\tcpdf.php',
        '/var/www/html/tcpdf/tcpdf.php',
);
foreach ($tcpdf_include_dirs as $tcpdf_include_path):
	if (file_exists($tcpdf_include_path)):
            require_once($tcpdf_include_path);
            break;
	endif;
endforeach;

$tcpdf_include_langs = array(
        'c:\xampp\htdocs\tcpdf\include\lang\spa.php',
        'f:\xampp\htdocs\tcpdf\include\lang\spa.php',
        '/var/www/html/tcpdf/include/lang/spa.php',
);
foreach ($tcpdf_include_langs as $tcpdf_include_l):
        if (file_exists($tcpdf_include_l)):
            require_once($tcpdf_include_l);
            break;
        endif;
endforeach;

//============================================================+
// END OF FILE
//============================================================+
