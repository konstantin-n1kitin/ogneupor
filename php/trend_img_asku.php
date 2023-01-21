<?php // content="text/plain; charset=utf-8"
require_once ('/jpgraph/jpgraph.php');
require_once ('/jpgraph/jpgraph_line.php');
require_once ('/jpgraph/jpgraph_date.php');
require_once ('/asku_reports/Askueshorts.php');

$data=explode("&",urldecode($_GET["a"]));
$data['r_type']=$data[0];
$data['parameter']=$data[1];
$data['begin_date']=$data[2];
$data['end_date']=$data[3];
$data['y_title']=$data[4];

set_time_limit(600);
$report = new Model_Askueshorts();
$result = $report->get_askue_short($data['begin_date'],$data['end_date'],str_replace(array('_daily','_monthly'),'',$data['r_type']),$data['parameter']);

$values = array();
$time = array();
foreach ($result["data"] as $i => $point) {
		$time[$i]=strtotime($point['Date']);
		$values[$i]=$point['Value'];
}
$width=950;
$height=605;
$graph = new Graph($width,$height);
$graph->SetMargin(70,40,10,80);
$graph->SetScale('datlin');
$graph->xaxis->SetPos("min");
$graph->yaxis->SetTitle($data['y_title'],'middle');
$graph->yaxis->SetTitlemargin(50);
$graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,8);
$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
$graph->xaxis->SetLabelAngle(30);
$graph->SetTickDensity(TICKD_NORMAL,TICKD_VERYSPARSE);
$graph->xgrid->Show();
$graph->xaxis->SetLabelAlign('center','top');
$lineplot=new LinePlot($values, $time);
//$lineplot->SetStepStyle();
$graph->Add($lineplot);
$lineplot->SetColor("blue");

//Рисуем второй график

if ($data['r_type']=='cshi_thermalclamping_water_daily'||$data['r_type']=='cshi_thermalclamping_water_monthly'||$data['r_type']=='csi_thermalclamping_water_daily'||$data['r_type']=='csi_thermalclamping_water_monthly') {
	$result = $report->get_askue_short($data['begin_date'],$data['end_date'],str_replace(array('_daily','_monthly'),'',$data['r_type']),$data['parameter']+90);
	foreach ($result["data"] as $i => $point) {
			$time[$i]=strtotime($point['Date']);
			$values[$i]=$point['Value'];
	}
	$lineplot2=new LinePlot($values, $time);
	$graph->Add($lineplot2);
	$lineplot2->SetColor("red");
}
//if (file_exists($file)) unlink($file);
$graph->img->SetImgFormat('jpeg');
$graph->Stroke();
?>