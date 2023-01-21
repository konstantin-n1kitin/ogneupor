<?php
defined('SYSPATH') or die('No direct script access.');
class Model_CshiRFReports extends Kohana_Model
{
	public function CalcStopRunMechTime($data, $mode, $dt_end)  //$data - многомерный массив с данными $mode - поиск  единичек/нулей (1/0)
	{
		$cmd = $mode;
		$last_dt = ''; //последняя дата в массиве
		$first_dt = ''; //первая дата в массиве
		$dt_sum = 0; // Суммарное время работы/простоя механизма
		$index = 0;  // текущий ндекс
		$dt_1 = ''; $dt_2 = ''; $dt_3 = '';
//		print_r ($data);
		while ($index < count($data))
		{
//						echo "cmd=$cmd | state=";	echo $data[$index]['Value']; echo " | dt="; echo $data[$index]['TS'];  echo " ";
			if ($data[$index]['quality'] != 0) //Проверка Quality
			{
				if ($index == 0) $first_dt = strtotime($data[$index]['TS']);
				switch ($mode)
				{
					case 0:
						if ($cmd == 0 and $data[$index]['Value'] == 0) //Ищем время выключения механизма
						{
							$dt_1 = strtotime($data[$index]['TS']); //нашли дату работы механихма
							$cmd = 1;
							$last_dt = $dt_1;
						}
						if ($cmd == 1 and $data[$index]['Value'] != 0) //Ищем время выключения механизма
						{
							$dt_2 = strtotime($data[$index]['TS']); // нашли дату остановки механизма
							$dt_3 = $dt_2 - $dt_1; // вычислили время не работы механизма
							$dt_sum = $dt_sum + $dt_3; // Суммируем времена не работы механизма
							$cmd = 0;
							$last_dt = $dt_2;
						}
						if ($index == count($data)-1)
						{
							if ($data[$index]['Value'] == 0)
							{
								if ($dt_end == null) $dt_end = $first_dt;
								$dt_sum = $dt_sum + (strtotime($dt_end) - $last_dt); //считаем до текущего времени
							}
						}
					break;
					case 1:
						if ($cmd == 1 and $data[$index]['Value'] != 0) //Ищем время включения механизма
						{
							$dt_1 = strtotime($data[$index]['TS']); //нашли дату работы механихма
							$cmd = 0;
							$last_dt = $dt_1;
						}
						if ($cmd == 0 and $data[$index]['Value'] == 0) //Ищем время включения механизма
						{
							$dt_2 = strtotime($data[$index]['TS']); // нашли дату остановки механизма
							$dt_3 = $dt_2 - $dt_1; // вычислили время работы механизма
							$dt_sum = $dt_sum + $dt_3; // Суммируем времена работы механизма
							$cmd = 1;
							$last_dt = $dt_1;
						}
//						echo $dt_sum; echo "<br>";
						if ($index == count($data)-1)
						{
//						echo "cmd=$cmd | state=";	echo $data[$index]['Value']; echo " | dt="; echo $data[$index]['DT'];  echo " ";
							if ($data[$index]['Value'] == 1 or $data[$index]['Value'] == 555)
							{
								if ($dt_end == null) $dt_end = $first_dt;
								$dt_sum = $dt_sum + (strtotime($dt_end) - $last_dt); //считаем до текущего времени
							}
						}
					break;
				}
			}
			$index++;
		}
		return $dt_sum;
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public function UniteTimeInterval($dt1, $dt2, $dt3, $dt4, &$dt5, &$dt6)
	{
		// dt1 - начальная дата 1го механизма dt2 - конечная дата 1го механизма
		// dt3 - начальная дата 2го механизма dt4 - конечная дата 2го механизма
		$dt5 = $dt6 = 0;
		if ((strtotime($dt1) >= strtotime($dt3)) and (strtotime($dt1) >= strtotime($dt4))) return false;
		if ((strtotime($dt2) <= strtotime($dt3)) and (strtotime($dt2) <= strtotime($dt4))) return false;
		if (strtotime($dt1) > strtotime($dt3)) $dt5 = $dt1;
		else $dt5 = $dt3;
		if (strtotime($dt2) > strtotime($dt4)) $dt6 = $dt4;
		else $dt6 = $dt2;
		return true;
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function cshi_rf_balloon_working_time_report($date, $date2, $mechnum)
  {
    $dt = new DateTime($date);
		$dt_beg = $dt->format('Y-m-d H:i:s');
    $dt = new DateTime($date2);
    $dt->add(new DateInterval('PT1S'));
		$dt_end = $dt->format('Y-m-d H:i:s');

		$dt = new DateTime(date('Y-m-d H:i:s'));
		$current_dt = $dt->format('Y-m-d H:i:s');
		if (strtotime($dt_end) > strtotime($current_dt))	$dt_end = $current_dt;
		if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;
		$time_array = array('08:00:00', '08:00:01', '08:00:02', '20:00:00', '20:00:01', '20:00:02', '00:00:00', '00:00:01', '00:00:02');
		$dbhost = "rotate-server";
		$dbname = "Rotate_furn";
		$dbuser = "sa";
		$dbpass = "admin";
		$data = $data2 = array(); //data2 массив для передачи в функцио подсчета времени работы механизма; data - массив для передачи в отчёт (дана расшифровка состояний механизма)
		$tmp_data = array(); //Если в массиве данные только из массива time_array, то делаем 1ну запись в данный массив
		$dt_sum = 0;
		$state = 1; //поиск 0-лей
		$checkDT = false; //флаг отсева не нужных дат для ТПЛ (массив time_array)
		$flag = false;

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		$sql = "SELECT	Name, TS, Value, quality
						FROM	trend_in_d
						WHERE	(TS >= (SELECT	MAX(TS) AS Expr1
                          FROM	trend_in_d AS trend_in_d_1
                          WHERE	(TS <= '$dt_beg') AND (Value = 0) AND (Name = '$mechnum') and (quality = 192)))
						AND (Name = '$mechnum') and TS <= '$dt_end' order by TS";
		try
		{
			set_time_limit(600);
			$result = $db->query($sql);
			$index = 0;
			$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
			if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
			foreach($tmp_array as $str_num => $rec)
			{
				if (count($tmp_array) == 1) //Если запись всего одна то время этой записи должно быть равно $date1 а не времени которое мы нашли, когда искали дополнительную запись в SQK запросе
				{
					$data[$index]['TS'] = $date;
					$data[$index]['Value'] = $rec['Value'];
					$data[$index]['quality'] = $rec['quality'];
					$index++;
				}
				else
				{
					if (strtotime($rec['TS']) <= strtotime($dt_beg))
					{
						$data[0]['TS'] = date("d.m.Y H:i:s", strtotime($dt_beg));
						$data[0]['Value'] = $rec['Value'];
						$data[0]['quality'] = 192;
						$flag = true;
					}
					else
					{
						if ($flag == true)
						{
							$index++;
							$flag = false;
						}

						$checkDT = false;
						if ($str_num == count($tmp_array)-1)
						{
							$tmp_data[0]['TS'] = date("d.m.Y H:i:s", strtotime($rec['TS']));
							$tmp_data[0]['Value'] = $rec['Value'];
							$tmp_data[0]['quality'] = $rec['quality'];
						}
						for ($i=0; $i < count($time_array); $i++)
						{
							if (date("H:i:s", strtotime($rec['TS'])) == $time_array[$i])
							{
								$checkDT = true;
								break;
							}
						}
						if ($checkDT == true)
							{} //отфильтрованные данные
						else
						{
							$data[$index]['TS'] = date("d.m.Y H:i:s", strtotime($rec['TS']));
							$data[$index]['Value'] = $rec['Value'];
							$data[$index]['quality'] = $rec['quality'];
							$index++;
						}
					}
				}
			}
			if (count($data) == 0)
				$data = $tmp_data;
			else
			{
				//Прописываем дополнительное состояние, чтобы время работы считалось до конечной даты запроса
				if ($data[count($data)-1]['Value'] == 0)
				{
					$data[$index]['TS'] = date("d.m.Y H:i:s", strtotime($dt_end));
					$data[$index]['Value'] = 1;
					$data[$index]['quality'] = 192;
				}
				else
				{
					$data[$index]['TS'] = date("d.m.Y H:i:s", strtotime($dt_end));
					$data[$index]['Value'] = 0;
					$data[$index]['quality'] = 192;
				}
			}
//			print_r($data);
			$data2 = $data;
			$tmp_array = array();// промежуточный массив только с данными
			$result_array = array(); //окончательный массив с данными и футером
			$cmd = 1; //1 - поиск единичек, 0 - поиск нулей
			$index = 0;
			$oldvalue = 999; //отсев одинаковых значений при изменении quality (когда скачет с 0 на 192)
			foreach($data as $str_num => $rec)
			{
				if ($index == count($data)-1)
				{}
				else
				{
					if ($oldvalue != $data[$index]['Value'])
					{
						$oldvalue = $data[$index]['Value'];
						if ($data[$index]['Value'] == 0)
							$data[$index]['Value'] = "выкл.";
						else
							$data[$index]['Value'] = "вкл.";
						$tmp_rec = array($data[$index]['TS'], $data[$index]['Value']);
						array_push($tmp_array, $tmp_rec);
					}	
//					else {print_r("old2=$oldvalue;");}	//Отсеенные значения
				}
				$index++;
			}
			$dt_sum = $this->CalcStopRunMechTime($data2, $cmd, $dt_end);
//			print_r($dt_sum);
			//Перевод секунд в формат  час/минута/секунда
			$hour = ($dt_sum - $dt_sum % (60*60))/(60*60);//Нашли количество часов
			$dt_sum = $dt_sum - $hour*60*60;
			$minute = ($dt_sum - $dt_sum % (60))/60;//Нашли количество минут
			$dt_sum = $dt_sum - $minute*60;
			$second = $dt_sum;//Нашли количество секунд
			if ($second >= 30)
			{
				if ($minute == 59)
				{
					$hour = $hour + 1;
					$minute = 0;
				}
				else
					$minute = $minute + 1;
			}
			$dt_sum = "$hour ч. $minute мин.";
//			print_r($dt_sum);
			$result_array['column_titles'] = array('Время', 'Состояние механизма');
			$result_array['data'] = $tmp_array;
			$result_array['footer'] = 'Время работы механизма: '.$dt_sum;
			return $result_array;
		}
		catch (PDOException $err)
		{
			return -3; //Ошибка связи с базой данных
		}
	}
//------------------------------------------------------------------------------	
	
	public function cshi_rotary_furn($date1,$date2,$id,$title)
	{
		define ("SQLCHARSET", "utf8");
		
		$date_begin = new DateTime($date1);
		$date_end = new DateTime($date2);
		$date1=$date_begin->format('Y-m-d H:i:s');
		$date2=$date_end->format('Y-m-d H:i:s');

		$dbhost = 'askuserver2'; $dbname = 'oup'; $dbuser = 'sa'; $dbpass = 'metallurg';
		$sql = "SELECT DATEADD(hh, 1, MeasureDate) AS Date, Value
				FROM shorts
				WHERE (ID_Channel = '".$id."') AND (DATEADD(hh, 1, MeasureDate) >= '".$date1."') AND (DATEADD(hh, 1, MeasureDate) <= '".$date2."')
				ORDER BY Date";		
		//print_r($sql);exit;
		try
		{
			set_time_limit(600);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result = $db->prepare($sql);
			$result->execute();
			$result_array['data']=$result->fetchAll(PDO::FETCH_ASSOC);
			
			foreach ($result_array['data'] as $rec => $value)
			{
				$tmp_date=new DateTime($value['Date']);
				$result_array['data'][$rec]['Date']=$tmp_date->format('d.m.Y H:i:s');
				$result_array['data'][$rec]['Value']=round($value['Value'],1);
			}
			$result_array['column_titles']=array("Дата", $title);
			$result_array['footer']="";
			return $result_array;
		}
		catch( PDOException $err )
		{
			return -3; //Ошибка связи с базой данных
		}
	}
	
//------------------------------------------------------------------------------

	public function cshi_rotary_furn2($date1,$date2,$id,$title)
	{
		define ("SQLCHARSET", "utf8");
		
		$date_begin = new DateTime($date1);
		$date_end = new DateTime($date2);
		$date1=$date_begin->format('Y-m-d H:i:s');
		$date2=$date_end->format('Y-m-d H:i:s');

		$dbhost = 'rotate-SERVER'; $dbname = 'Rotate_furn'; $dbuser = 'sa'; $dbpass = 'admin';
		$sql = "SELECT DATEADD(hh, 5, TS) AS Date, Value
				FROM TREND_IN_A
				WHERE (Name = '".$id."') AND (DATEADD(hh, 5, TS) >= '".$date1."') AND (DATEADD(hh, 5, TS) <= '".$date2."')
				ORDER BY Date";		
		//print_r($sql);exit;
		try
		{
			set_time_limit(600);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result = $db->prepare($sql);
			$result->execute();
			$result_array['data']=$result->fetchAll(PDO::FETCH_ASSOC);
			
			foreach ($result_array['data'] as $rec => $value)
			{
				$tmp_date=new DateTime($value['Date']);
				$result_array['data'][$rec]['Date']=$tmp_date->format('d.m.Y H:i:s');
				$result_array['data'][$rec]['Value']=round($value['Value'],1);
			}
			$result_array['column_titles']=array("Дата", $title);
			$result_array['footer']="";
			return $result_array;
		}
		catch( PDOException $err )
		{
			return -3; //Ошибка связи с базой данных
		}
	}
	//------------------------------------------------------------------------------

	public function cshi_rotary_furn_mech_working_time($date, $date2, $mechname)
	{
		$dt = new DateTime($date);
		$dt->sub(new DateInterval('PT5H'));
		$dt_beg = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime($date2);
		$dt->sub(new DateInterval('PT5H'));
		$dt_end = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime(date('Y-m-d H:i:s'));
		$current_dt = $dt->format('Y-m-d H:i:s');
		if (strtotime($dt_end) > strtotime($current_dt)) $dt_end = $current_dt;
		if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;
		$dbhost = 'rotate-SERVER'; $dbname = 'Rotate_furn'; $dbuser = 'sa'; $dbpass = 'admin';
		$data = $data2 = array(); //data2 массив для передачи в функцию подсчета времени работы механизма; data - массив для передачи в отчёт (дана расшифровка состояний механизма)
		$dt_sum = 0;
		$state = 1; //поиск 0-лей
		$name = str_replace("-", ".", $mechname);

		$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT *
					  FROM TREND_IN_D
					  WHERE (TS >= (SELECT MAX(TS) AS Expr1
                         FROM TREND_IN_D AS IN_D_1
                         WHERE (TS <= '$dt_beg') AND (Name = '$name'))) AND (TS < '$dt_end') AND (Name = '$name') AND (Quality = 192)
					  ORDER BY TS";		
//		echo $sql;
		try
		{
			set_time_limit(600);
			$result = $db->query($sql);
			$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
			$count=$result->fetch(PDO::FETCH_NUM);
			$index=0;
			$OldVal = 999;
			$flag = false;
			if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
			foreach($tmp_array as $str_num => $rec)
			{
				if (count($tmp_array) == 1) //Если запись всего одна то время этой записи должно быть равно $date1 а не времени которое мы нашли, когда искали дополнительную запись в SQL запросе
				{
//					$data[$index]['DT'] = $date;
					$data[$index]['DT'] = $dt_beg;
					$data[$index]['state'] = $rec['Value'];
					$data[$index]['quality'] = $rec['Quality'];
					$index++;
				}	
				else
				{
					if (strtotime($rec['TS']) <= strtotime($dt_beg))
					{
						$data[0]['DT'] = date("d.m.Y H:i:s", strtotime($dt_beg));
						$data[0]['state'] = $rec['Value'];
						$data[0]['quality'] = 192;
						$flag = true;
						$OldVal = $rec['Value'];
					}
					else	
					{
						if ($flag == true)
						{
							$index++;
							$flag = false;
						}	
						// Убираем лишние нолики из массива
						if (($OldVal == 0 and $rec['Value'] == 0) or ($OldVal == 1 and $rec['Value'] == 1))
						{}
						else
						{
							$data[$index]['DT'] = date("d.m.Y H:i:s", strtotime($rec['TS']));
							$data[$index]['state'] = $rec['Value'];
							$data[$index]['quality'] = $rec['Quality'];
							$index++;
							$OldVal = $rec['Value'];
						}	
					}	
				}
			}
			//Прописываем дополнительное состояние, чтобы время работы считалось до конечной даты запроса
			if ($data[count($data)-1]['state'] == 0)
			{	
				$data[$index]['DT'] = date("d.m.Y H:i:s", strtotime($dt_end));
				$data[$index]['state'] = 1;
				$data[$index]['quality'] = 192;
			}	
			else
			{
				$data[$index]['DT'] = date("d.m.Y H:i:s", strtotime($dt_end));
				$data[$index]['state'] = 0;
				$data[$index]['quality'] = 192;
			}
			$data2 = $data;
			$tmp_array = array(); // промежуточный массив только с данными
			$result_array = array(); // окончательный массив с данными и футером
			$cmd = 1; //1 - поиск единичек, 0 - поиск нулей
			$index = 0;
			foreach($data as $str_num => $rec)
			{
				if ($index == count($data)-1)
				{}
				else
				{
					if ($data[$index]['state'] == 0)
						$data[$index]['state'] = "выкл.";
					else
						$data[$index]['state'] = "вкл.";
						
					$tmp_date = new DateTime($data[$index]['DT']);
					$tmp_date->add(new DateInterval('PT5H'));
					$tmp_rec = array($tmp_date->format('d.m.Y H:i:s'), $data[$index]['state']);
					array_push($tmp_array, $tmp_rec);
				}	
				$index++;
			}
			$dt_sum = $this->line_5_CalcStopRunMechTime($data2, $cmd, $dt_end);
			//Перевод секунд в формат  час/минута/секунда
			$hour = ($dt_sum - $dt_sum % (60*60))/(60*60);//Нашли количество часов
			$dt_sum = $dt_sum - $hour*60*60;
			$minute = ($dt_sum - $dt_sum % (60))/60;//Нашли количество минут
			$dt_sum = $dt_sum - $minute*60;
			$second = $dt_sum;//Нашли количество секунд
			if ($second >= 30)
			{
				if ($minute == 59)
				{	
					$hour = $hour + 1;
					$minute = 0;
				}	
				else
					$minute = $minute + 1;
			}
			$dt_sum = "$hour ч. $minute мин.";
			$result_array['column_titles'] = array('Время', 'Состояние механизма');
			$result_array['data'] = $tmp_array;
			$result_array['footer'] = 'Время работы механизма: '.$dt_sum;
			return $result_array; 
		}
		catch (PDOException $err)
		{
			return -3; //Ошибка связи с базой данных
		}	
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	//Время работы механизмов линия 5

	public function line_5_CalcStopRunMechTime($data, $mode, $dt_end)  //$data - многомерный массив с данными $mode - поиск  единичек/нулей (1/0)
	{
		$cmd = $mode;
		$last_dt = ''; //последняя дата в массиве
		$first_dt = ''; //первая дата в массиве
		$dt_sum = 0; // Суммарное время работы/простоя механизма
		$index = 0;  // текущий ндекс
		$dt_1 = ''; $dt_2 = ''; $dt_3 = '';
		while ($index < count($data))
		{
			if ($data[$index]['quality'] = 192) //Проверка Quality
			{
				if ($index == 0) $first_dt = strtotime($data[$index]['DT']);
				switch ($mode)
				{
					case 0:
						if ($cmd == 0 and $data[$index]['state'] == 0) //Ищем время выключения механизма
						{
							$dt_1 = strtotime($data[$index]['DT']); //нашли дату работы механихма
							$cmd = 1;
							$last_dt = $dt_1;
						}
						if ($cmd == 1 and $data[$index]['state'] != 0) //Ищем время включения механизма
						{
							$dt_2 = strtotime($data[$index]['DT']); // нашли дату остановки механизма
							$dt_3 = $dt_2 - $dt_1; // вычислили время не работы механизма
							$dt_sum = $dt_sum + $dt_3; // Суммируем времена не работы механизма
							$cmd = 0;
							$last_dt = $dt_2;
						}
						if ($index == count($data)-1)
						{
							if ($data[$index]['state'] == 0)
							{
								if ($dt_end == null) $dt_end = $first_dt;
								$dt_sum = $dt_sum + (strtotime($dt_end) - $last_dt); //считаем до текущего времени
							}
						}
					break;
					case 1:
						if ($cmd == 1 and $data[$index]['state'] != 0) //Ищем время включения механизма
						{
							$dt_1 = strtotime($data[$index]['DT']); //нашли дату работы механихма
							$cmd = 0;
							$last_dt = $dt_1;
						}
						if ($cmd == 0 and $data[$index]['state'] == 0) //Ищем время выключения механизма
						{
							$dt_2 = strtotime($data[$index]['DT']); // нашли дату остановки механизма
							$dt_3 = $dt_2 - $dt_1; // вычислили время работы механизма
							$dt_sum = $dt_sum + $dt_3; // Суммируем времена работы механизма
							$cmd = 1;
							$last_dt = $dt_1;
						}
						if ($index == count($data)-1)
						{
							if ($data[$index]['state'] == 1 or $data[$index]['state'] == 555)
							{
								if ($dt_end == null) $dt_end = $first_dt;
								$dt_sum = $dt_sum + (strtotime($dt_end) - $last_dt); //считаем до текущего времени
							}
						}
					break;
				}
			}
			$index++;
		}
		return $dt_sum;
	}
}
?>