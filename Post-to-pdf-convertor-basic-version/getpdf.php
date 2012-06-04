<?php
	error_reporting(E_ALL);
$p = urldecode($_GET["p"]);
//echo $p;

echo "<pre>";
//print_r($_GET);
//print_r($_POST);

echo "</pre>";
require_once("dompdf/dompdf_config.inc.php");
$dompdf = new DOMPDF();

$dompdf->load_html($p);
$dompdf->render();
$dompdf->stream("sample.pdf");
?>
