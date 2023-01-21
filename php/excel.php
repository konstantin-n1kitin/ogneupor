<?php
$name='test.xls';
$XLSX = new Spreadsheet();
$data = array(
	'Users' => array(
		1 => array('ID', 'Name'),
		2 => array(1, 'Jane Doe'),
		3 => array(2, 'Fred Smith')
	),
	'Products' => array(
		1 => array('ID', 'Name'),
		2 => array(1, 'Torch'),
		3 => array(2, 'Hat')
	),
);
$XLSX->setData( $data, 1 );
$XLSX->save(array('name'=>$name));
?>