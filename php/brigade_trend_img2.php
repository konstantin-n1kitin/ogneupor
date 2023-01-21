<?php
require_once ('/jpgraph/jpgraph.php');
require_once ('/jpgraph/jpgraph_line.php');
require_once ('/jpgraph/jpgraph_date.php');

$data=explode("&",urldecode($_GET["a"]));
$data['Begintime']=$data[0];
$data['Endtime']=$data[1];
$data['brigade']=$data[2];
$data['furnace']=$data[3];


define ("SQLCHARSET", "utf8");

$date_begin = new DateTime($data['Begintime']);
$date_end = new DateTime($data['Endtime']);
$data['Begintime']=$date_begin->format('Y-m-d H:i:s');
$data['Endtime']=$date_end->format('Y-m-d H:i:s');

$arg_dt_begin=$data['Begintime'];
$arg_dt_end=$data['Endtime'];
$arg_furnace=$data['furnace'];
$arg_brig=$data['brigade'];
//print_r($data);

try
{
	set_time_limit(600);
	//error_reporting( error_reporting() & ~E_NOTICE );
	$data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
	$date_now = new DateTime();
	$date_shift=new DateTime(date("Y-m-d"));
	$date_shift->add(new DateInterval('PT20H00M'));
	if ($date_now>$date_shift)
		$date_now=$date_shift;
	else {
		$date_shift->sub(new DateInterval('PT12H00M'));
		if ($date_now>$date_shift)
			$date_now=$date_shift;
		else {
			$date_shift->sub(new DateInterval('PT12H00M'));
			$date_now=$date_shift;
		}
	}		
    $date_begin->sub(new DateInterval('PT4H00M'));
    $date_end->add(new DateInterval('PT20H00M'));
	if ($date_end>$date_now) $date_end=$date_now;
	if ($date_begin>$date_end) $date_begin=$date_end;
   	//Анализируем какой материал грузили в печь
	$glina=array(array(),array());
	$dbhost = "TPL-server";$dbname = "tpl";$dbuser = "sa";$dbpass = "tpl";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    //Читаем предыдущие значения
	for ($i=1;$i<=2;$i++) {
		if ($i==$arg_furnace or $arg_furnace==0) {
			$sql = "SELECT TOP 1 [state]
					FROM Glina
					WHERE dt <= '".$date_begin->format('Y-m-d H:i:s')."' and furnace=".$i."
					ORDER BY dt desc;";
			$st=$db->prepare($sql);
			$st->execute();
			$result=$st->fetchAll(PDO::FETCH_ASSOC);
			array_push($glina[$i-1],array($date_begin->format('Y-m-d H:i:s'),$result[0]['state']));
		}
	}
	//Заполняем остальные значения
	$sql = "SELECT [dt],[state],[furnace]
			FROM Glina
			WHERE dt > '".$date_begin->format('Y-m-d H:i:s')."' and dt < '".$date_end->format('Y-m-d H:i:s')."'
			ORDER BY dt;";
	$st=$db->prepare($sql);
	$st->execute();
	$result=$st->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $key=>$record) array_push($glina[$record['furnace']-1],array($record['dt'],$record['state']));
	//Фильтруем лишнее
	for ($i=1;$i<=2;$i++) {
		$last_value=99;
		$tmp_array=array();
		foreach($glina[$i-1] as $key=>$record)
			if ($record[1]!=$last_value) {
				array_push($tmp_array,$record);
				$last_value=$record[1];
			}
		$glina[$i-1]=$tmp_array;
	}

	//Анализируем какие маршруты работали
	$routes=array(array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array(),array());
	//Читаем предыдущие значения
	for ($i=21;$i<=36;$i++) {
		if ($i==$arg_furnace or $arg_furnace==0) {
			$sql = "SELECT TOP 1 [state]
					FROM fl_marsh
					WHERE dt <= '".$date_begin->format('Y-m-d H:i:s')."' and marsh=".$i."
					ORDER BY dt desc;";
			$st=$db->prepare($sql);
			$st->execute();
			$result=$st->fetchAll(PDO::FETCH_ASSOC);
			array_push($routes[$i-21],array($date_begin->format('Y-m-d H:i:s'),$result[0]['state']));
		}
	}
	//Заполняем остальные значения
	$sql = "SELECT [dt],[state],[marsh]
			FROM fl_marsh
			WHERE dt > '".$date_begin->format('Y-m-d H:i:s')."' and dt < '".$date_end->format('Y-m-d H:i:s')."' and marsh>=21 and marsh<=36
			ORDER BY dt;";
	$st=$db->prepare($sql);
	$st->execute();
	$result=$st->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $key=>$record) array_push($routes[$record['marsh']-21],array($record['dt'],$record['state']));
	//Фильтруем лишнее
	for ($i=21;$i<=36;$i++)
		foreach($routes[$i-21] as $key=>$record)
			if ($record[1]==2)
				$routes[$i-21][$key][1]=1;
			else
				$routes[$i-21][$key][1]=0;
	for ($i=21;$i<=36;$i++) {
		$last_value=99;
		foreach($routes[$i-21] as $key=>$record) {
			if ($record[1]==$last_value)
				unset($routes[$i-21][$key]);
			else
				$last_value=$record[1];
		}
	}
	//Делим все маршруты на 4 направления
	$direction=array(array(),array(),array(),array());
	for ($i=21;$i<=36;$i++)
		foreach($routes[$i-21] as $key=>$record)
			array_push($direction[(($i-21)-($i-21)%4)/4],$record);
	function cmp($a, $b)
	{
		if (strtotime($a[0]) == strtotime($b[0])) {
			return 0;
		}
		return strtotime($a[0])<strtotime($b[0]) ? -1: 1;
	}
	foreach($direction as $key=>$record)
		usort($direction[$key], "cmp");
	foreach($direction as $key=>$record) {
		$last_value=99;
		$routes_running=0;
		$tmp_array=array();
		foreach ($record as $key2=>$record2) {
			if ($record2[1]>0)
				$routes_running++;
			else if ($routes_running>0)
				$routes_running--;
			if ((bool)$routes_running!=$last_value) {
				array_push($tmp_array,$record2);
				$last_value=(bool)$routes_running;
			}
		}
		$direction[$key]=$tmp_array;
	}
	//Если маршрут в любом из направлений запущен в конце периода, то добавляем еще одну запись
	foreach($direction as $key=>$record) {
		$last_record=end($record);
		if ($last_record[1]==1)
			array_push($direction[$key],array($date_end->format('Y-m-d H:i:s'),0));
	}
	//Добавляем в результирующий массив данные из массива направлений
	$Brig=array(array(2,1,3),array(3,4,1),array(1,2,4),array(4,3,2));
	$month=array(0,31,59,90,120,151,181,212,243,273,304,334);
	$furnace=array(array(),array());
	foreach($direction as $key=>$record) {
		switch ($key) {
			case 0:
				$pech_number=1;
				$conv_number=0;
				break;
			case 1:
				$pech_number=1;
				$conv_number=1;
				break;
			case 2:
				$pech_number=0;
				$conv_number=0;
				break;
			case 3:
				$pech_number=0;
				$conv_number=1;
				break;
		}
		$interval_begin=$date_begin;
		foreach ($record as $key2=>$record2) {
			if ($record2[1]==1) {
				$interval_begin=new DateTime($record2[0]);
			}
			else {
				$interval_end=new DateTime($record2[0]);
				if ($interval_begin<$interval_end) {
					if ($interval_begin->format('H')<8)
						$shift=0;
					else if ($interval_begin->format('H')<20)
						$shift=1;
					else
						$shift=2;
					$brigade_number=$Brig[((($interval_begin->format('Y')-2016)*365+(integer)(($interval_begin->format('Y')-2016)/4)+1)+$month[$interval_begin->format('m')-1]+$interval_begin->format('d')+(($interval_begin->format('m')>2 & (($interval_begin->format('Y')) % 4)==0) ? 1 : 0)-1)%4][$shift];
					array_push($furnace[$pech_number],array("dt_begin"=>$interval_begin->format('Y-m-d H:i:s'),"dt_end"=>$interval_end->format('Y-m-d H:i:s'),"furnace"=>$pech_number,
								"brigade"=>$brigade_number,"conveyer"=>$conv_number,"glina"=>""));
				}
			}
		}
	}
	//Разбиваем интервалы на бригады
	foreach($furnace as $key=>$record) {
		foreach ($record as $key2=>$record2) {
			$dt_begin=new DateTime($record2['dt_begin']);
			$dt_end=new DateTime($record2['dt_end']);
			if ($dt_begin->format('H')<8)
				$shift1=0;
			else if ($dt_begin->format('H')<20)
				$shift1=1;
			else
				$shift1=2;
			$brigade1=$Brig[((($dt_begin->format('Y')-2016)*365+(integer)(($dt_begin->format('Y')-2016)/4)+1)+$month[$dt_begin->format('m')-1]+$dt_begin->format('d')+(($dt_begin->format('m')>2 & (($dt_begin->format('Y')) % 4)==0) ? 1 : 0)-1)%4][$shift1];
			$dt_end_minus_second=new DateTime($dt_end->format('Y-m-d H:i:s'));
			$dt_end_minus_second->sub(new DateInterval('PT1S'));
			if ($dt_end_minus_second->format('H')<8)
				$shift2=0;
			else if ($dt_end_minus_second->format('H')<20)
				$shift2=1;
			else
				$shift2=2;
			$brigade2=$Brig[((($dt_end->format('Y')-2016)*365+(integer)(($dt_end->format('Y')-2016)/4)+1)+$month[$dt_end->format('m')-1]+$dt_end->format('d')+(($dt_end->format('m')>2 & (($dt_end->format('Y')) % 4)==0) ? 1 : 0)-1)%4][$shift2];
			if ($brigade1!=$brigade2 or $dt_end->getTimestamp()-$dt_begin->getTimestamp()>43200) {
				$interval_begin=$dt_begin;
				$tmp_record=$record2;
				unset($furnace[$key][$key2]);
				while ($interval_begin<$dt_end) {
					switch ($shift1) {
						case 0:
							$interval_end->setTimestamp(mktime(8,0,0,$interval_begin->format('m'),$interval_begin->format('d'),$interval_begin->format('Y')));
							break;
						case 1:
							$interval_end->setTimestamp(mktime(20,0,0,$interval_begin->format('m'),$interval_begin->format('d'),$interval_begin->format('Y')));
							break;
						case 2:
							$interval_end->setTimestamp(mktime(8,0,0,$interval_begin->format('m'),$interval_begin->format('d')+1,$interval_begin->format('Y')));
							break;
					}
					if ($interval_end>$dt_end) $interval_end=$dt_end;
					$brigade1=$Brig[((($interval_begin->format('Y')-2016)*365+(integer)(($interval_begin->format('Y')-2016)/4)+1)+$month[$interval_begin->format('m')-1]+$interval_begin->format('d')+(($interval_begin->format('m')>2 & (($interval_begin->format('Y')) % 4)==0) ? 1 : 0)-1)%4][$shift1];
					array_push($furnace[$key],array("dt_begin"=>$interval_begin->format('Y-m-d H:i:s'),"dt_end"=>$interval_end->format('Y-m-d H:i:s'),"furnace"=>$tmp_record["furnace"],
													"brigade"=>$brigade1,"conveyer"=>$tmp_record["conveyer"],"glina"=>""));
					$interval_begin=new DateTime($interval_end->format('Y-m-d H:i:s'));
					$shift1++;
					if ($shift1>2) $shift1=1;
				}
			}
		}
	}
	//Сортируем результирующий массив
	function cmp2($a, $b)
	{
		if (strtotime($a["dt_begin"]) == strtotime($b["dt_begin"])) {
			return 0;
		}
		return strtotime($a["dt_begin"])<strtotime($b["dt_begin"]) ? -1: 1;
	}
	foreach($furnace as $key=>$record)
		usort($furnace[$key], "cmp2");
	//Добавляем информацию про глину и разбиваем на интервалы по глине
	foreach($glina as $key=>$record) {
		foreach($record as $key2=>$record2) {
			if ($key2==0) {
				$interval_begin=new DateTime($record2[0]);
				$interval_value=$record2[1];
			}
			else {
				$interval_end=new DateTime($record2[0]);
				foreach ($furnace[$key] as $key3=>$record3) { //Цикл 3
					if (strtotime($record3["dt_begin"])>=$interval_end->getTimestamp()) //Маршрут запущен после интервала
						break;//выходим из цикла 3
					else if (strtotime($record3["dt_begin"])>=$interval_begin->getTimestamp() and strtotime($record3["dt_end"])<=$interval_end->getTimestamp()) //Маршрут внутри интервала
						$furnace[$key][$key3]["glina"]=$interval_value;
					else if (strtotime($record3["dt_begin"])>=$interval_begin->getTimestamp() and strtotime($record3["dt_end"])>=$interval_end->getTimestamp()) {
						array_push($furnace[$key],array("dt_begin"=>$record3["dt_begin"],"dt_end"=>$interval_end->format('Y-m-d H:i:s'),"furnace"=>$record3["furnace"],
														 "brigade"=>$record3["brigade"],"conveyer"=>$record3["conveyer"],"glina"=>$interval_value));
						array_push($furnace[$key],array("dt_begin"=>$interval_end->format('Y-m-d H:i:s'),"dt_end"=>$record3["dt_end"],"furnace"=>$record3["furnace"],
														 "brigade"=>$record3["brigade"],"conveyer"=>$record3["conveyer"],"glina"=>""));
						unset($furnace[$key][$key3]);
					}
				}
				$interval_begin=new DateTime($record2[0]);
				$interval_value=$record2[1];
			}
		}
		$interval_end=new DateTime($date_end->format('Y-m-d H:i:s'));
		foreach ($furnace[$key] as $key3=>$record3) { //Цикл 3
			if (strtotime($record3["dt_begin"])>=$interval_end->getTimestamp()) //Маршрут запущен после интервала
				break;//выходим из цикла 3
			else if (strtotime($record3["dt_begin"])>=$interval_begin->getTimestamp() and strtotime($record3["dt_end"])<=$interval_end->getTimestamp()) //Маршрут внутри интервала
				$furnace[$key][$key3]["glina"]=$interval_value;
			else if (strtotime($record3["dt_begin"])>=$interval_begin->getTimestamp() and strtotime($record3["dt_end"])>=$interval_end->getTimestamp()) {
				array_push($furnace[$key],array("dt_begin"=>$record3["dt_begin"],"dt_end"=>$interval_end->format('Y-m-d H:i:s'),"furnace"=>$record3["furnace"],
												 "brigade"=>$record3["brigade"],"conveyer"=>$record3["conveyer"],"glina"=>$interval_value));
				array_push($furnace[$key],array("dt_begin"=>$interval_end->format('Y-m-d H:i:s'),"dt_end"=>$record3["dt_end"],"furnace"=>$record3["furnace"],
												 "brigade"=>$record3["brigade"],"conveyer"=>$record3["conveyer"],"glina"=>""));
				unset($furnace[$key][$key3]);
			}
		}
	}
	//Выгружаем данные по полученным интервалам
	$dbhost = "weight-server";$dbname = "Conv_vesy";$dbuser = "sa";$dbpass = "123Oup123";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$sql = "SELECT TOP(1) Z3
            FROM Archive
            WHERE DT <= :dt and ID_W= :id_w
            ORDER BY dt desc;";
	$data=array();
	foreach ($furnace as $key=>$record) {
		foreach ($record as $key2=>$record2) {
			if (($arg_brig==$record2["brigade"] or $arg_brig==0) and ($arg_furnace==($record2["furnace"]+1) or $arg_furnace==0)) {
				$date_begin = new DateTime($record2["dt_begin"]);
				$date_begin->add(new DateInterval('PT4H00M'));
				//Запрашиваем показание весов на начало периода
				$result = $db->prepare($sql);
				$result->execute(array(':dt'=>$record2["dt_begin"],':id_w'=>$record2["conveyer"]+1));
				$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
				$z1=$tmp_array[0]["Z3"];
				//Запрашиваем показание весов на конец периода
				$result = $db->prepare($sql);
				$result->execute(array(':dt'=>$record2["dt_end"],':id_w'=>$record2["conveyer"]+1));
				$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
				$z2=$tmp_array[0]["Z3"];
				//Считаем данные
				//print_r("Z1=".$z1.";Z2=".$z2."; ");
				if (!isset($data[$record2["furnace"]])) $data[$record2["furnace"]]=array();
				if (!isset($data[$record2["furnace"]][$record2["brigade"]])) $data[$record2["furnace"]][$record2["brigade"]]=array();
				if (!isset($data[$record2["furnace"]][$record2["brigade"]][$date_begin->format('d.m.Y')])) $data[$record2["furnace"]][$record2["brigade"]][$date_begin->format('d.m.Y')]=array('arkalyk'=>0,'berlinka'=>0,'gase'=>0,'elec'=>0,'ud_gase'=>0,'ud_elec'=>0,'water'=>0,'density'=>0);
				$data[$record2["furnace"]][$record2["brigade"]][$date_begin->format('d.m.Y')][($record2["glina"]==0)?'arkalyk':'berlinka']+=$z2-$z1;
			}
		}
	}

	//Добавляем информацию по электроэнергии
	$furnace_1_ids = array(4254,4470,4578,4614,4660,4434);
	$furnace_2_ids = array(4326,4506,4542,4686,4722,4398);
	$dbhost = "ASKUSERVER2";$dbname = "oup";$dbuser = "sa";$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$sql = "SELECT ID_Channel, MeasureDate, Value
            FROM Mains
            WHERE (ID_Channel = 4254 or ID_Channel = 4326 or ID_Channel = 4470 or ID_Channel = 4506 or ID_Channel = 4542 or ID_Channel = 4578 or ID_Channel = 4614 or ID_Channel = 4660
			or ID_Channel = 4686 or ID_Channel = 4722 or ID_Channel = 4434 or ID_Channel = 4398) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
	$date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT4H00M'));
    $date_end->add(new DateInterval('PT20H00M'));
	$result = $db->prepare($sql);
    $result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    $tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach ($tmp_array as $key=>$record) {
		$dt=new DateTime($record['MeasureDate']);
		if ($dt->format('H')<8)
			$shift=0;
		else if ($dt->format('H')<20)
			$shift=1;
		else
			$shift=2;
		$brigade=$Brig[((($dt->format('Y')-2016)*365+(integer)(($dt->format('Y')-2016)/4)+1)+$month[$dt->format('m')-1]+$dt->format('d')+(($dt->format('m')>2 & (($dt->format('Y')) % 4)==0) ? 1 : 0)-1)%4][$shift];
		$furnace_num = in_array($record['ID_Channel'], $furnace_1_ids) ? 1 : 2;
		if (($arg_brig==$brigade or $arg_brig==0) and ($arg_furnace==$furnace_num or $arg_furnace==0)) {
			$dt->add(new DateInterval('PT4H00M'));
			if (!isset($data[$furnace_num-1])) $data[$furnace_num-1]=array();
			if (!isset($data[$furnace_num-1][$brigade])) $data[$furnace_num-1][$brigade]=array();
			if (!isset($data[$furnace_num-1][$brigade][$dt->format('d.m.Y')])) $data[$furnace_num-1][$brigade][$dt->format('d.m.Y')]=array('arkalyk'=>0,'berlinka'=>0,'gase'=>0,'elec'=>0,'ud_gase'=>0,'ud_elec'=>0,'water'=>0,'density'=>0);
			$data[$furnace_num-1][$brigade][$dt->format('d.m.Y')]['elec']+=$record['Value']*1000;
		}
	}

	//Считаем удельный расход
	foreach ($data as $furnace_number=>$record) {
		foreach ($record as $brigate_number=>$record2) {
			foreach ($record2 as $dt=>$record3) {
				//if (!isset($record3['arkalyk'])) $data[$furnace_number][$brigate_number][$dt]['arkalyk']=0;
				//if (!isset($record3['berlinka'])) $data[$furnace_number][$brigate_number][$dt]['berlinka']=0;
				//if (!isset($record3['gase'])) $data[$furnace_number][$brigate_number][$dt]['gase']=0;
				//if (!isset($record3['elec'])) $data[$furnace_number][$brigate_number][$dt]['elec']=0;
				//$data[$furnace_number][$brigate_number][$dt]['ud_gase']=($record3['arkalyk']+$record3['berlinka']!=0)?$record3['gase']/($record3['arkalyk']+$record3['berlinka']):'н/о';
				$data[$furnace_number][$brigate_number][$dt]['ud_elec']=($record3['arkalyk']+$record3['berlinka']!=0)?$record3['elec']/($record3['arkalyk']+$record3['berlinka']):'н/о';
			}
		}
	}
	//Формируем отчет
	$result_array=array();
	$column_titles=array('Дата','Аркалык','Берлинка','Суммарный расход коксового газа, м<sup>3','Суммарный расход электричества, кВт','Удельный расход коксового газа', 'Удельный расход электричества','Водопоглощение','Кажущаяся плотность');
    foreach ($data as $furnace_number=>$record) {
		foreach ($record as $brigate_number=>$record2) {
			if (($arg_brig==$brigate_number or $arg_brig==0) and ($arg_furnace==$furnace_number+1 or $arg_furnace==0)) {
				$result_array[($furnace_number*10)+$brigate_number]['caption']='Вращающаяся печь №'.($furnace_number+1).' Бригада №'.$brigate_number;
				$result_array[($furnace_number*10)+$brigate_number]['column_titles']=$column_titles;
				$result_array[($furnace_number*10)+$brigate_number]['data']=array();
				foreach ($record2 as $dt=>$record3) {
					array_push($result_array[($furnace_number*10)+$brigate_number]['data'],array($dt,($record3['arkalyk']!='')?round($record3['arkalyk'],1):0,($record3['berlinka']!='')?round($record3['berlinka'],1):0,($record3['gase']!='')?round($record3['gase'],1):0,($record3['elec']!='')?round($record3['elec'],1):0,
																								($record3['ud_gase']!='')?round($record3['ud_gase'],2):0,($record3['ud_elec']!='')?round($record3['ud_elec'],2):0,($record3['water']!='')?round($record3['water'],1):0,($record3['density']!='')?round($record3['density'],1):0));
				}
			}
		}
	}
	//Сортируем отчет
	function cmp3($a, $b)
	{
		if (($a["caption"]) == ($b["caption"])) {
			return 0;
		}
		return ($a["caption"])<($b["caption"]) ? -1: 1;
	}
	usort($result_array, "cmp3");
	foreach ($result_array as $table_number=>$record) {
		usort($result_array[$table_number]['data'], "cmp");
	}
	//print_r($result_array);
	$width=950;
	//$height=605;
	$height=570;
	$graph = new Graph($width,$height);
	$graph->img->SetAntiAliasing(false);
	$graph->SetMargin(70,40,90,80);
	$graph->SetScale('datlin');
	$graph->xaxis->SetPos("min");
	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
	$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
	$graph->yaxis->SetTitle("Удельный расход электричества, кВт*ч/т",'middle');
	$graph->yaxis->SetTitlemargin(50);
	$graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,8);
	$graph->xaxis->SetLabelAngle(30);
	$graph->SetTickDensity(TICKD_NORMAL,TICKD_VERYSPARSE);
	$graph->xaxis->scale->SetDateFormat('d.m.Y');
	$graph->xgrid->Show();
	$graph->xaxis->SetLabelAlign('center','top');
	foreach ($result_array as $key=>$record) {
			$values=array();
			$time=array();
			foreach ($record['data'] as $key2 => $record2) {
				array_push($time,strtotime($record2[0]));
				array_push($values,$record2[6]>0.0?$record2[6]:0);
			}
			//print_r($time);
			//print_r($values);
			$lineplot=new LinePlot($values, $time);
			$lineplot->SetLegend($record['caption']);
			//$lineplot->SetStepStyle();
			$graph->Add($lineplot);
			$lineplot->SetWeight(2);
			$lineplot->SetStyle("solid");
			
	}
	//$lineplot->SetColor("blue");
	//if (file_exists($file)) unlink($file);
	//$graph->img->SetImgFormat('jpeg');
	$graph->legend->Pos(0.5,0.01,'center','top');
	$graph->Stroke();
}
catch( PDOException $err )
{
	return; //Ошибка связи с базой данных
}
?>
