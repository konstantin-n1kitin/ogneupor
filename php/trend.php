<?php
class Trend
 {
	public function Make($data)
    {
		require_once ('/jpgraph/jpgraph.php');
		require_once ('/jpgraph/jpgraph_line.php');
		require_once ('/jpgraph/jpgraph_date.php');
		
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
			$sql = "SELECT Value, TS
				FROM in_a
				WHERE (Name = 'PRESS_5.IN_A.IN_A_".str_pad($data['Tag'], 2, '0', STR_PAD_LEFT)."') AND (TS >= '".$data['Begintime']."') AND (TS <= '".$data['Endtime']."') AND (Quality=192)
				ORDER BY TS";
			break;
			case 'CSI':
				$dbhost = 'csi-server'; $dbname = 'CSI_11_1'; $dbuser = 'sa'; $dbpass = 'oup';
				$sql = "SELECT Value*".$data['Ratio'].", TS
						FROM trendtable_in_a
						WHERE (Name = '".$data['Tag']."') AND (TS >= '".$data['Begintime']."') AND (TS <= '".$data['Endtime']."') AND (Quality=192)
						ORDER BY TS";
			break;
			case 'CSI_task':
				$dbhost = 'csi-server'; $dbname = 'CSI_11_1'; $dbuser = 'sa'; $dbpass = 'oup';
				$sql = "SELECT Value*".$data['Ratio'].", TS
						FROM trendtable_task
						WHERE (Name = '".$data['Tag']."') AND (TS >= '".$data['Begintime']."') AND (TS <= '".$data['Endtime']."') AND (Quality=192)
						ORDER BY TS";
			break;
			case 'Tun_furn':
				$dbhost = 'tunnel-server'; $dbname = 'tun_furn'; $dbuser = 'sa'; $dbpass = 'ogneupor';
				$sql = "SELECT Value*".$data['Ratio'].", TS
						FROM trend_in_a
						WHERE (Name = '".$data['Tag']."') AND (TS >= '".$data['Begintime']."') AND (TS <= '".$data['Endtime']."') AND (Quality=192)
						ORDER BY TS";
			break;
			case 'ASKU30':
				$dbhost = 'askuserver2'; $dbname = 'oup'; $dbuser = 'sa'; $dbpass = 'metallurg';
				$sql = "SELECT Value*".$data['Ratio'].", MeasureDate
						FROM mains
						WHERE (ID_Channel = '".$data['Tag']."') AND (MeasureDate >= '".$data['Begintime']."') AND (MeasureDate <= '".$data['Endtime']."')
						ORDER BY MeasureDate";
			break;
			case 'ASKU3':
				$dbhost = 'askuserver2'; $dbname = 'oup'; $dbuser = 'sa'; $dbpass = 'metallurg';
				$sql = "SELECT Value*".$data['Ratio'].", MeasureDate
						FROM shorts
						WHERE (ID_Channel = '".$data['Tag']."') AND (MeasureDate >= '".$data['Begintime']."') AND (MeasureDate <= '".$data['Endtime']."')
						ORDER BY MeasureDate";
			break;
			default:
				return ; //Неправильно указан источник данных
		}
		try
		{
			set_time_limit(600);
			$time0=time();
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result = $db->prepare($sql);
			$result->execute();
			$values = array();
			$time = array();
			while ($row = $result->fetch())
			{
				$values[] = $row[0];
				$time[] = strtotime($row[1]);
			}
			if (count($values)==0) return; //Нет данных в этом диапазоне
			$width=950;
			$height=605;
			$graph = new Graph($width,$height);
			$graph->SetMargin(70,40,10,80);
			$graph->SetScale('datlin');
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->yaxis->SetTitle($data['y_title'],'middle');
			$graph->yaxis->SetTitlemargin(50);
			$graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,8);
			$graph->xaxis->SetLabelAngle(30);
			$graph->SetTickDensity(TICKD_NORMAL,TICKD_VERYSPARSE);
			$graph->xgrid->Show();
			$graph->xaxis->SetLabelAlign('center','top');
			// Create the linear plot
			$lineplot=new LinePlot($values, $time);
			//$lineplot->SetStepStyle();
			// Add the plot to the graph
			$graph->Add($lineplot);
			$lineplot->SetColor("blue");
			// Display the graph
			//if (file_exists($file)) unlink($file);
			//$graph->img->SetImgFormat('jpeg');
			$graph->Stroke();
		}
		catch( PDOException $err )
		{
			return; //Ошибка связи с базой данных
		}
    }
	public function Make_array($data)
    {
		require_once ('/jpgraph/jpgraph.php');
		require_once ('/jpgraph/jpgraph_line.php');
		require_once ('/jpgraph/jpgraph_date.php');
		require_once ('/asku_reports/Askueshorts.php');

			set_time_limit(600);
			$report = new Model_Askueshorts();
			$result = $report->get_askue_short($data['begin_date'],$data['end_date'],str_replace(array('_daily','_monthly'),'',$data['r_type']),$data['parameter']);
//			print_r($result);
			/*
			switch ($data['r_type']) {
			case 'cshi_oxygen_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_oxygen_daily_report($data['begin_date']);
			break;
			case 'cshi_rotating_oven_1_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotating_oven_1_coke_gas_daily_report($data['begin_date']);
			break;
			case 'cshi_rotating_oven_2_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotating_oven_2_coke_gas_daily_report($data['begin_date']);
			break;
			case 'cshi_rotary_drier1_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotary_drier1_coke_gas_daily_report($data['begin_date']);
			break;
			case 'cshi_rotary_drier2_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotary_drier2_coke_gas_daily_report($data['begin_date']);
			break;
			case 'cshi_rotary_drier3_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_rotary_drier3_coke_gas_daily_report($data['begin_date']);
			break;
			case 'csi_rotary_driers_coke_gas_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_rotary_driers_coke_gas_daily_report($data['begin_date']);
			break;
			case 'cshi_tunnel_furnaces_coke_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_tunnel_furnaces_coke_gas_daily_report($data['begin_date']);
			break;
			case 'cshi_natural_gas_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_natural_gas_daily_report($data['begin_date']);
			break;
			case 'cshi_compressed_air_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_compressed_air_daily_report($data['begin_date']);
			break;
			case 'cshi_thermalclamping_water_daily':
				$report = new Model_Cshidailyreports();
				$result = $report->cshi_thermalclamping_water_daily_report($data['begin_date']);
			break;
			//Месячные отчёты;
			case 'cshi_oxygen_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_oxygen_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'cshi_rotating_oven_1_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotating_oven_1_coke_gas_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'cshi_rotating_oven_2_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotating_oven_2_coke_gas_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'cshi_tunnel_furnaces_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_tunnel_furnaces_coke_gas_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'cshi_rotary_drier1_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotary_drier1_coke_gas_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'cshi_rotary_drier2_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotary_drier2_coke_gas_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'cshi_rotary_drier3_coke_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_rotary_drier3_coke_gas_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'csi_rotary_driers_coke_gas_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_rotary_driers_coke_gas_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'cshi_natural_gas_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_natural_gas_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'cshi_compressed_air_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_compressed_air_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'cshi_thermalclamping_water_monthly':
				$report = new Model_Cshimonthlyreports();
				$result = $report->cshi_thermalclamping_water_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'csi_oxygen_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_oxygen_daily_report($data['begin_date']);
			break;
			case 'csi_steam_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_steam_daily_report($data['begin_date']);
			break;
			case 'csi_compressed_air_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_compressed_air_daily_report($data['begin_date']);
			break;
			case 'csi_thermalclamping_water_daily':
				$report = new Model_Csidailyreports();
				$result = $report->csi_thermalclamping_water_daily_report($data['begin_date']);
			break;
			//
			case 'csi_oxygen_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_oxygen_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'csi_steam_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_steam_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'csi_compressed_air_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_compressed_air_monthly_report($data['begin_date'],$data['end_date']);
			break;
			case 'csi_thermalclamping_water_monthly':
				$report = new Model_Csimonthlyreports();
				$result = $report->csi_thermalclamping_water_monthly_report($data['begin_date'],$data['end_date']);
			break;
			}
			*/
			
			$values = array();
			$time = array();
			foreach ($result["data"] as $i => $point) {
					$time[$i]=strtotime($point['MeasureDate']);
					$values[$i]=$point['Value'];
			}
			//print_r($result);
			/*for ($i=0; $i<count($result); $i++) {
				reset($result[$i]);
				for ($j=1;$j<$data['parameter'];$j++) next($result[$i]);
				$values[$i]=next($result[$i]);
				if (reset($result[$i])=="00:00:00") $time[$i]=strtotime("24:00:00");
				else $time[$i]=strtotime(reset($result[$i]));
			}*/
			//print_r($values);
			//print_r($time);
			$width=950;
			$height=605;
			$graph = new Graph($width,$height);
			$graph->SetMargin(70,40,10,80);
			$graph->SetScale('datlin');
			$graph->yaxis->SetTitle($data['y_title'],'middle');
			$graph->yaxis->SetTitlemargin(50);
			$graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,8);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->xaxis->SetLabelAngle(30);
			$graph->SetTickDensity(TICKD_NORMAL,TICKD_VERYSPARSE);
			$graph->xgrid->Show();
			$graph->xaxis->SetLabelAlign('center','top');
			// Create the linear plot
			$lineplot=new LinePlot($values, $time);
			//$lineplot->SetStepStyle();
			// Add the plot to the graph
			$graph->Add($lineplot);
			$lineplot->SetColor("blue");
			//$lineplot->SetWeight(10);
			// Display the graph
			//if (file_exists($file)) unlink($file);
			$graph->img->SetImgFormat('jpeg');
			$graph->Stroke();
    }
 }
?>