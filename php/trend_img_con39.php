<?php
require_once ('/jpgraph/jpgraph.php');
require_once ('/jpgraph/jpgraph_line.php');
require_once ('/jpgraph/jpgraph_date.php');

define ("SQLCHARSET", "utf8");

$skip=$_GET["skip"];

$date=new DateTime();
$date->add(new DateInterval('PT5H'));
$date_begin=new DateTime();
$date_end=new DateTime();

for ($i=1;$i<=$skip;$i++) {
	$date->sub(new DateInterval('PT12H'));
}
$date_begin = clone $date;
$date20=clone $date;
$date20->setTime(20,0,0);
$date8=clone $date;
$date8->setTime(8,0,0);
if ($date>$date20)
	$date_begin->setTime(20,0,0);
else if ($date>$date8)
	$date_begin->setTime(8,0,0);
else {
	$date_begin->setTime(8,0,0);
	$date_begin->sub(new DateInterval('PT12H'));
}
$date_end = clone $date_begin;
$date_end->add(new DateInterval('PT12H'));

$data['Begintime']=$date_begin->format('Y-m-d H:i:s');
$data['Endtime']=$date_end->format('Y-m-d H:i:s');

$dbhost = 'weight-server'; $dbname = 'Conv_vesy'; $dbuser = 'sa'; $dbpass = '123Oup123';
$sql = "SELECT DT, Q= 
					CASE
						when Q>=0 then Q
						else 0
					END
				FROM Archive
				WHERE (ID_W = 4) AND (DT >= '".$data['Begintime']."') AND (DT <= '".$data['Endtime']."')
				ORDER BY DT";
try
{
	set_time_limit(600);
	$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	$result = $db->prepare($sql);
	$result->execute();
	while ($row = $result->fetch())
	{
		$values[] = $row[1];
		$time[] = strtotime($row[0]);
	}
	if (count($values)==0) return; //Нет данных в этом диапазоне
	$width=950;
	$height=350;
	$graph = new Graph($width,$height);
	$graph->SetMargin(70,40,10,80);
	$graph->SetScale('datlin');
	$graph->xaxis->SetPos("min");
	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
	$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
	$graph->yaxis->SetTitle('Конвейерные высы №39','middle');
	$graph->yaxis->SetTitlemargin(50);
	$graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,8);
	$graph->xaxis->SetLabelAngle(0);
	$graph->SetTickDensity(TICKD_NORMAL,TICKD_VERYSPARSE);
	$graph->xaxis->scale->SetDateFormat('H:i');
	$graph->xgrid->Show();
	$graph->xaxis->SetLabelAlign('center','top');
	$lineplot=new LinePlot($values, $time);
	//$lineplot->SetStepStyle();
	$graph->Add($lineplot);
	$lineplot->SetColor("blue");
	//if (file_exists($file)) unlink($file);
	//$graph->img->SetImgFormat('jpeg');
	$graph->Stroke();
}
catch( PDOException $err )
{
	print_r($err);
	return; //Ошибка связи с базой данных
}
?>