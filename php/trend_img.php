<?php
require_once ('/jpgraph/jpgraph.php');
require_once ('/jpgraph/jpgraph_line.php');
require_once ('/jpgraph/jpgraph_date.php');

$data=explode("&",urldecode($_GET["a"]));
$data['Database']=$data[0];
$data['Tag']=$data[1];
$data['Begintime']=$data[2];
$data['Endtime']=$data[3];
$data['Width']=$data[4];
$data['Height']=$data[5];
$data['Ratio']=$data[6];
$data['y_title']=$data[7];

define ("SQLCHARSET", "utf8");

$date_begin = new DateTime($data['Begintime']);
$date_end = new DateTime($data['Endtime']);
$data['Begintime']=$date_begin->format('Y-m-d H:i:s');
$data['Endtime']=$date_end->format('Y-m-d H:i:s');

switch ($data['Database']) {
	case 'PFU':
		$dbhost = 'tpl-server'; $dbname = 'pfu'; $dbuser = 'sa'; $dbpass = 'tpl';
		$sql = "SELECT TagValue*".$data['Ratio'].", DT
				FROM in_a
				WHERE (ID = '".$data['Tag']."') AND (DT >= '".$data['Begintime']."') AND (DT <= '".$data['Endtime']."') AND (Quality=192)
				ORDER BY DT";
	break;
	case 'PFU5':
				$dbhost = 'press5-server'; $dbname = 'PRESS_5'; $dbuser = 'sa'; $dbpass = 'admintp';
			$sql = "SELECT Value, DATEADD(hh, 5, TS) AS Date
				FROM in_a
				WHERE (Name = 'PRESS_5.IN_A.IN_A_".str_pad($data['Tag'], 2, '0', STR_PAD_LEFT)."') AND (DATEADD(hh, 5, TS) >= '".$data['Begintime']."') AND (DATEADD(hh, 5, TS) <= '".$data['Endtime']."') AND (Quality=192)
				ORDER BY Date";
			break;
	case 'RF':
		$dbhost = 'rotate-SERVER'; $dbname = 'Rotate_furn'; $dbuser = 'sa'; $dbpass = 'admin';
		$sql = "SELECT Value*".$data['Ratio'].", DATEADD(hh, 5, TS) AS Date
				FROM TREND_IN_A
				WHERE (Name = '".$data['Tag']."') AND (DATEADD(hh, 5, TS) >= '".$data['Begintime']."') AND (DATEADD(hh, 5, TS) <= '".$data['Endtime']."') AND (Quality=192)
				ORDER BY Date";
	break;
	case 'CSI':
		$dbhost = 'csi-server'; $dbname = 'CSI_11_1'; $dbuser = 'sa'; $dbpass = 'oup';
		$sql = "SELECT Value*".$data['Ratio'].", DATEADD(hh, 5, TS) AS Date
				FROM trendtable_in_a
				WHERE (Name = '".$data['Tag']."') AND (DATEADD(hh, 5, TS) >= '".$data['Begintime']."') AND (DATEADD(hh, 5, TS) <= '".$data['Endtime']."') AND (Quality=192)
				ORDER BY Date";
	break;
	case 'CSI_task':
		$dbhost = 'csi-server'; $dbname = 'CSI_11_1'; $dbuser = 'sa'; $dbpass = 'oup';
		$sql = "SELECT Value*".$data['Ratio'].", DATEADD(hh, 5, TS) AS Date
				FROM trendtable_task
				WHERE (Name = '".$data['Tag']."') AND (DATEADD(hh, 5, TS) >= '".$data['Begintime']."') AND (DATEADD(hh, 5, TS) <= '".$data['Endtime']."') AND (Quality=192)
				ORDER BY Date";
	break;
	case 'Tun_furn':
		$dbhost = 'tunnel-server'; $dbname = 'tun_furn'; $dbuser = 'sa'; $dbpass = 'ogneupor';
		$sql = "SELECT Value*".$data['Ratio'].", DATEADD(hh, 5, TS) AS Date
				FROM trend_in_a
				WHERE (Name = '".$data['Tag']."') AND (DATEADD(hh, 5, TS) >= '".$data['Begintime']."') AND (DATEADD(hh, 5, TS) <= '".$data['Endtime']."') AND (Quality=192)
				ORDER BY Date";
	break;
	case 'ASKU30':
		$dbhost = 'askuserver2'; $dbname = 'oup'; $dbuser = 'sa'; $dbpass = 'metallurg';
		$sql = "SELECT Value*".$data['Ratio'].", DATEADD(hh, 1, MeasureDate) AS Date
				FROM mains
				WHERE (ID_Channel = '".$data['Tag']."') AND (DATEADD(hh, 1, MeasureDate) >= '".$data['Begintime']."') AND (DATEADD(hh, 1, MeasureDate) <= '".$data['Endtime']."')
				ORDER BY Date";
	break;
	case 'ASKU3':
		$dbhost = 'askuserver2'; $dbname = 'oup'; $dbuser = 'sa'; $dbpass = 'metallurg';
		$sql = "SELECT Value*".$data['Ratio'].", DATEADD(hh, 1, MeasureDate) AS Date
				FROM shorts
				WHERE (ID_Channel = '".$data['Tag']."') AND (DATEADD(hh, 1, MeasureDate) >= '".$data['Begintime']."') AND (DATEADD(hh, 1, MeasureDate) <= '".$data['Endtime']."')
				ORDER BY Date";
	break;
	default:
		return ; //Неправильно указан источник данных
}
try
{
	set_time_limit(600);
	$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	$result = $db->prepare($sql);
	$result->execute();
	while ($row = $result->fetch())
	{
		$values[] = $row[0];
		$time[] = strtotime($row[1]);
	}
	if (count($values)==0) return; //Нет данных в этом диапазоне
	$width=950;
	//$height=605;
	$height=570;
	$graph = new Graph($width,$height);
	$graph->SetMargin(70,40,10,80);
	$graph->SetScale('datlin');
	$graph->xaxis->SetPos("min");
	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
	$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
	$graph->yaxis->SetTitle($data['y_title'],'middle');
	$graph->yaxis->SetTitlemargin(50);
	$graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,8);
	$graph->xaxis->SetLabelAngle(30);
	$graph->SetTickDensity(TICKD_NORMAL,TICKD_VERYSPARSE);
	$graph->xaxis->scale->SetDateFormat('d.m.Y H:i');
	$graph->xgrid->Show();
	$graph->xaxis->SetLabelAlign('center','top');
	$lineplot=new LinePlot($values, $time);
	$lineplot->SetStepStyle();
	$graph->Add($lineplot);
	$lineplot->SetColor("blue");
	//if (file_exists($file)) unlink($file);
	//$graph->img->SetImgFormat('jpeg');
	$graph->Stroke();
}
catch( PDOException $err )
{
	return; //Ошибка связи с базой данных
}
?>