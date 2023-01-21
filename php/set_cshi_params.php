<?php
if( isset( $_POST['save_button'] ) ) {
    $dt=$_GET["dt"];
	$shift=$_GET["shift"];
	$water=$_GET["water"];
	$density=$_GET["density"];
	print_r($dt.$shift.$water.$density);
}
?>