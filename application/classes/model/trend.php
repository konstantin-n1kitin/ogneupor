<?php
class Model_Trend extends Kohana_Model
 {
	public function Check($data)
	{
		//require_once ('/jpgraph/jpgraph.php');
		//require_once ('/jpgraph/jpgraph_line.php');
		//require_once ('/jpgraph/jpgraph_date.php');
		define ("SQLCHARSET", "utf8");
		$data['Begintime']=str_replace("%20"," ",$data['Begintime']);
		$data['Endtime']=str_replace("%20"," ",$data['Endtime']);
		$date_begin = new DateTime($data['Begintime']);
		$date_end = new DateTime($data['Endtime']);
		$data['Begintime']=$date_begin->format('Y-m-d H:i:s');
		$data['Endtime']=$date_end->format('Y-m-d H:i:s');
		$data['Tag']=str_replace("-",".",$data['Tag']);
		switch ($data['Database']) {
			case 'PFU':
				$dbhost = 'tpl-server'; $dbname = 'pfu'; $dbuser = 'sa'; $dbpass = 'tpl';
				$sql = "SELECT TagValue, DT
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
				$dbhost = 'rotate-server'; $dbname = 'Rotate_furn'; $dbuser = 'sa'; $dbpass = 'admin';
				$sql = "SELECT TOP(1) *
						FROM TREND_IN_A
						WHERE (Name = '".$data['Tag']."') AND (DATEADD(hh, 5, TS) >= '".$data['Begintime']."') AND (DATEADD(hh, 5, TS) <= '".$data['Endtime']."') AND (Quality=192)";
				//print_r($sql);
			break;
			case 'CSI':
				$dbhost = 'csi-server'; $dbname = 'CSI_11_1'; $dbuser = 'sa'; $dbpass = 'oup';
				$sql = "SELECT Value, DATEADD(hh, 5, TS) AS Date
						FROM trendtable_in_a
						WHERE (Name = '".$data['Tag']."') AND (DATEADD(hh, 5, TS) >= '".$data['Begintime']."') AND (DATEADD(hh, 5, TS) <= '".$data['Endtime']."') AND (Quality=192)
						ORDER BY Date";
			break;
			case 'CSI_task':
				$dbhost = 'csi-server'; $dbname = 'CSI_11_1'; $dbuser = 'sa'; $dbpass = 'oup';
				$sql = "SELECT Value, DATEADD(hh, 5, TS) AS Date
						FROM trendtable_task
						WHERE (Name = '".$data['Tag']."') AND (DATEADD(hh, 5, TS) >= '".$data['Begintime']."') AND (DATEADD(hh, 5, TS) <= '".$data['Endtime']."') AND (Quality=192)
						ORDER BY Date";
			break;
			case 'Tun_furn':
				$dbhost = 'tunnel-server'; $dbname = 'tun_furn'; $dbuser = 'sa'; $dbpass = 'ogneupor';
				$sql = "SELECT Value, DATEADD(hh, 5, TS) AS Date
						FROM trend_in_a
						WHERE (Name = '".$data['Tag']."') AND (DATEADD(hh, 5, TS) >= '".$data['Begintime']."') AND (DATEADD(hh, 5, TS) <= '".$data['Endtime']."') AND (Quality=192)
						ORDER BY TS";
			break;
			case 'ASKU30':
				$dbhost = 'askuserver2'; $dbname = 'oup'; $dbuser = 'sa'; $dbpass = 'metallurg';
				$sql = "SELECT Value, DATEADD(hh, 1, MeasureDate) AS Date
						FROM mains
						WHERE (ID_Channel = '".$data['Tag']."') AND (DATEADD(hh, 1, MeasureDate) >= '".$data['Begintime']."') AND (DATEADD(hh, 1, MeasureDate) <= '".$data['Endtime']."')
						ORDER BY Date";
			break;
			case 'ASKU3':
				$dbhost = 'askuserver2'; $dbname = 'oup'; $dbuser = 'sa'; $dbpass = 'metallurg';
				$sql = "SELECT Value, DATEADD(hh, 1, MeasureDate) AS Date
						FROM shorts
						WHERE (ID_Channel = '".$data['Tag']."') AND (DATEADD(hh, 1, MeasureDate) >= '".$data['Begintime']."') AND (DATEADD(hh, 1, MeasureDate) <= '".$data['Endtime']."')
						ORDER BY Date";
			break;
			case 'CMDO':
				$dbhost = 'CMDO-ASKU'; $dbname = 'cmdo'; $dbuser = 'sa'; $dbpass = '';
				$sql = "SELECT Value, DATEADD(hh, 1, MeasureDate) AS Date
						FROM shorts
						WHERE (ID_Channel = '".$data['Tag']."') AND (DATEADD(hh, 1, MeasureDate) >= '".$data['Begintime']."') AND (DATEADD(hh, 1, MeasureDate) <= '".$data['Endtime']."')
						ORDER BY Date";
			break;
			default:
				return -1; //Неправильно указан источник данных
		}
		try
		{
			set_time_limit(600);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result = $db->prepare($sql);
			$result->execute();
			$count=$result->fetch(PDO::FETCH_NUM);
			//print_r($count);
			if ($count[0]=='') return -2; //Нет данных в этом диапазоне
			else return 1; //Все хорошо
		}
		catch( PDOException $err )
		{
			return -3; //Ошибка связи с базой данных
		}
	}
 }
?>