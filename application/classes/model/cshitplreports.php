<?php
defined('SYSPATH') or die('No direct script access.');
class Model_CshiTPLReports extends Kohana_Model
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
//						echo "cmd=$cmd | state=";	echo $data[$index]['state']; echo " | dt="; echo $data[$index]['DT'];  echo " ";
			if ($data[$index]['quality'] != 0) //Проверка Quality
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
						if ($cmd == 1 and $data[$index]['state'] != 0) //Ищем время выключения механизма
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
						if ($cmd == 0 and $data[$index]['state'] == 0) //Ищем время включения механизма
						{
							$dt_2 = strtotime($data[$index]['DT']); // нашли дату остановки механизма
							$dt_3 = $dt_2 - $dt_1; // вычислили время работы механизма
							$dt_sum = $dt_sum + $dt_3; // Суммируем времена работы механизма
							$cmd = 1;
							$last_dt = $dt_1;
						}
//						echo $dt_sum; echo "<br>";
						if ($index == count($data)-1)
						{
//						echo "cmd=$cmd | state=";	echo $data[$index]['state']; echo " | dt="; echo $data[$index]['DT'];  echo " ";
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

  public function cshi_tpl_mech_working_time_report($date, $date2, $mechnum)
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
   	$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";
		$data = $data2 = array(); //data2 массив для передачи в функцио подсчета времени работы механизма; data - массив для передачи в отчёт (дана расшифровка состояний механизма)
		$tmp_data = array(); //Если в массиве данные только из массива time_array, то делаем 1ну запись в данный массив
		$dt_sum = 0;
		$state = 1; //поиск 0-лей
		$checkDT = false; //флаг отсева не нужных дат для ТПЛ (массив time_array)
		$flag = false;

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		$sql = "SELECT	state, dt, num, quality
						FROM	in_d
						WHERE	(dt >= (SELECT	MAX(dt) AS Expr1
                          FROM	in_d AS in_d_1
                          WHERE	(dt <= '$dt_beg') AND (state = 0) AND (num = $mechnum)))
						AND (num = $mechnum) and dt <= '$dt_end' order by dt";
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
  				$data[$index]['DT'] = $date;
					$data[$index]['state'] = $rec['state'];
					$data[$index]['quality'] = $rec['quality'];
					$index++;
				}
				else
				{
					if (strtotime($rec['dt']) <= strtotime($dt_beg))
					{
						$data[0]['DT'] = date("d.m.Y H:i:s", strtotime($dt_beg));
						$data[0]['state'] = $rec['state'];
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
							$tmp_data[0]['DT'] = date("d.m.Y H:i:s", strtotime($rec['dt']));
							$tmp_data[0]['state'] = $rec['state'];
							$tmp_data[0]['quality'] = $rec['quality'];
						}
						for ($i=0; $i < count($time_array); $i++)
						{
							if (date("H:i:s", strtotime($rec['dt'])) == $time_array[$i])
							{
								$checkDT = true;
								break;
							}
						}
						if ($checkDT == true)
							{} //отфильтрованные данные
						else
						{
							$data[$index]['DT'] = date("d.m.Y H:i:s", strtotime($rec['dt']));
							$data[$index]['state'] = $rec['state'];
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
			}
//			print_r($data);
			$data2 = $data;
			$tmp_array = array();// промежуточный массив только с данными
			$result_array = array(); //окончательный массив с данными и футером
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
					if ($data[$index]['state']!=$data[$index-1]['state']) {
						$tmp_rec = array($data[$index]['DT'], $data[$index]['state']);
						array_push($tmp_array, $tmp_rec);
					}
				}
				$index++;
			}
			$dt_sum = $this->CalcStopRunMechTime($data2, $cmd, $dt_end);
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

  public function cshi_tpl_marsh_state_report($date, $date2, $marsh)
  {
    $dt = new DateTime($date);
 		$dt_beg = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime($date2);
		$dt_end = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime(date('Y-m-d H:i:s'));
		$current_dt = $dt->format('Y-m-d H:i:s');
		if (strtotime($dt_end) > strtotime($current_dt))	$dt_end = $current_dt;
		if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;
   	$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";
		$data = array();

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT *
					  FROM fl_marsh
					  WHERE (dt >= (SELECT MAX(dt) AS Expr1
                          FROM fl_marsh AS fl_marsh_1
                          WHERE (dt <= '$dt_beg') AND (marsh = $marsh))) AND (dt < '$dt_end') AND (marsh = $marsh)
					  ORDER BY dt";
		try
		{
			set_time_limit(600);
			$result = $db->query($sql);
			$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
			foreach($tmp_array as $str_num => $rec)
			{
				$data[$str_num]['DT'] = date("Y.m.d H:i:s", strtotime($rec['dt']));
				$data[$str_num]['state'] = $rec['state'];
				$data[$str_num]['quality'] = $rec['quality'];
			}
			$tmp_array = array(); // промежуточный массив только с данными
			$result_array = array(); //окончательный массив с данными и футером

			foreach($data as $str_num => $rec)
			{
				if ($data[$str_num]['quality'] != 0) //Проверка Quality
				{
					switch ($data[$str_num]['state'])
					{
						case 0:
							$data[$str_num]['state'] = "стоит";
							break;
						case 1:
							$data[$str_num]['state'] = "собирается";
							break;
						case 2:
							$data[$str_num]['state'] = "работает";
							break;
						case 3:
							$data[$str_num]['state'] = "нормально разбирается";
							break;
						case 4:
							$data[$str_num]['state'] = "аварийно работает";
							break;
						default:
							$data[$str_num]['state'] = sprintf("%s%s", "неизвестное состояние", " ($data[$str_num]['state'])");
					}
					$tmp_rec = array($data[$str_num]['DT'], $data[$str_num]['state']);
					array_push($tmp_array, $tmp_rec);
				}
				else {}
			}

			$result_array['column_titles'] = array('Время', 'Состояние маршрута');
			$result_array['data'] = $tmp_array;
			$result_array['footer'] = '';
			return $result_array;
		}
		catch (PDOException $err)
		{
			return -3; //Ошибка связи с базой данных
		}
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function cshi_tpl_refregirator_stop_time_report($date, $date2)
  {
		// 2 - холодильник 071
		// 8 - холодильник 072
    $IDs[1] = 2;
    $IDs[2] = 8;
	$date_begin = new DateTime($date);
    $date_end = new DateTime($date2);
	$current_dt = new DateTime(date('Y-m-d H:i:s'));
// -------------------- Вычитаем  час (проблема зимне-летнего времени) -------------
	$current_dt->sub(new DateInterval('PT1H')); 
// ---------------------------------------------------------------------------------	
    if ($date2!='')
    {
    	$date_end = new DateTime($date2);
		$date_end->add(new DateInterval('PT1S'));
    }
    else
    {
    	$date_end = new DateTime($date);
		$date_end->add(new DateInterval('PT24H1S')); 
    }
		if ($date_end >= $current_dt)	$date_end = $current_dt;
		$dt_beg = $date_begin->format('Y-m-d H:i:s');
		$dt_end = $date_end->format('Y-m-d H:i:s');
		if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;
		$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";
		$tmp_rec = null;
		$result_array = array(); //окончательный массив с данными и футером
		$time_array = array('00:00:00', '00:00:01', '00:00:02', '08:00:00', '08:00:01', '08:00:02', '20:00:00', '20:00:01', '20:00:02');
		$tmp_data = array();
		$checkDT = false; //флаг отсева не нужных дат для ТПЛ (массив time_array)
		$flag = false;

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		try
		{
			for ($i = 1; $i <= 2; $i++)
			{
				$data = $data2 = array();
				$num = $IDs[$i];
				$sql = "SELECT	state, dt, num, quality
								FROM	in_d
								WHERE	(dt >= (SELECT	MAX(dt) AS Expr1
															FROM	in_d AS in_d_1
															WHERE	(dt <= '$dt_beg') AND (num = $num))) AND (num = $num) AND (dt < '$dt_end')
															ORDER by dt";
//				echo $sql;
				$result = $db->query($sql);
				$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
				if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
				$index = 0;
				foreach($tmp_array as $str_num => $rec)
				{
					if (count($tmp_array) == 1) //Если запись всего одна то время этой записи должно быть равно $date1 а не времени которое мы нашли, когда искали дополнительную запись в SQK запросе
					{
						$data[$index]['DT'] = $date;
						$data[$index]['state'] = $rec['state'];
						$data[$index]['quality'] = $rec['quality'];
						$index++;
					}
					else
					{
						if (strtotime($rec['dt']) <= strtotime($dt_beg))
						{
							$data[0]['DT'] = date("d.m.Y H:i:s", strtotime($dt_beg));
							$data[0]['state'] = $rec['state'];
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
								$tmp_data[0]['DT'] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$tmp_data[0]['state'] = $rec['state'];
								$tmp_data[0]['quality'] = $rec['quality'];
							}
							for ($j=0; $j < count($time_array); $j++)
							{
								if (date("H:i:s", strtotime($rec['dt'])) == $time_array[$j])
								{
									$checkDT = true;
									break;
								}
							}
							if ($checkDT == true)
								{} //отфильтрованные данные
							else
							{
								$data[$index]['DT'] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$data[$index]['state'] = $rec['state'];
								$data[$index]['quality'] = $rec['quality'];
								$index++;
							}
						}
					}
				}
//				print_r($data);
				if (count($data) == 0)
					$data = $tmp_data;
				else
				{
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
				}

//				print_r($data);
				$data2 = $data;
				$tmp_array = array();// промежуточный массив только с данными
				switch ($i)
				{
					case 1:
						$dt_1 = $dt_2 = $dt_3 = $dt_sum071 = 0;
						$dt_1_str = $dt_2_str = '';
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
								$tmp_rec = array($data[$index]['DT'], $data[$index]['state']);
								array_push($tmp_array, $tmp_rec);
								$index++;
							}
						}

						$dt_sum071 = $this->CalcStopRunMechTime($data2, 0, $dt_end);
//						echo($dt_sum071);
						//Перевод секунд в формат  час/минута/секунда
						$hour = ($dt_sum071 - $dt_sum071 % (60*60))/(60*60);//Нашли количество часов
//						echo($hour);
						$dt_sum071 = $dt_sum071 - $hour*60*60;
						$minute = ($dt_sum071 - $dt_sum071 % (60))/60;//Нашли количество минут
						$dt_sum071 = $dt_sum071 - $minute*60;
						$second = $dt_sum071;//Нашли количество секунд
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
						$dt_sum071 = "$hour ч. $minute мин.";
						$result_array['column_titles'] = array('Время', 'Состояние');
						$result_array['data'] = $tmp_array;
						$result_array['footer'] = 'Время простоя холодильника 071: '.$dt_sum071;
					break;
					case 2:
						$dt_1 = $dt_2 = $dt_3 = $dt_sum072 = 0;
						$dt_1_str = $dt_2_str = '';
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
								$tmp_rec = array($data[$index]['DT'], $data[$index]['state']);
								array_push($tmp_array, $tmp_rec);
								$index++;
							}
						}
						$dt_sum072 = $this->CalcStopRunMechTime($data2, 0, $dt_end);
						//Перевод секунд в формат  час/минута/секунда
						$hour = ($dt_sum072 - $dt_sum072 % (60*60))/(60*60);//Нашли количество часов
						$dt_sum072 = $dt_sum072 - $hour*60*60;
						$minute = ($dt_sum072 - $dt_sum072 % (60))/60;//Нашли количество минут
						$dt_sum072 = $dt_sum072 - $minute*60;
						$second = $dt_sum072;//Нашли количество секунд
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
						$dt_sum072 = "$hour ч. $minute мин.";
						$result_array['column_titles2'] = array('Время', 'Состояние');
						$result_array['data2'] = $tmp_array;
						$result_array['footer2'] = 'Время простоя холодильника 072: '.$dt_sum072;
					break;
				}
			}
			return $result_array;
		}
		catch (PDOException $err)
		{
			return -3; //Ошибка связи с базой данных
		}
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  public function cshi_tpl_mill_report()
  {
		$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";
		$data = array();
		$millID = array(31, 26, 21, 16);
		$millnum = array('(792)', '(782)', '(772)', '(762)');
		$dt_sum = 0;
		$tmp_rec = '';
		$state = 1; //поиск 0-лей
		$dt = new DateTime(date('Y-m-d H:i:s'));
		$dt_end = $dt->format('Y-m-d H:i:s');

		$result_array = array(); //окончательный массив с данными и футером
		$array = array(); //сводный массив только с данными
		try
		{
			for ($index = 0; $index < 4; $index++)
			{
				$data = array();
				$sql = "select dt from in_d where num = $millID[$index] and state = 555 order by dt desc";
				$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
											 ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
				$result = $db->query($sql);
				if (count($result) < 1) return -2;
				foreach($result->fetchAll() as $rec)
				{
					$date[$index] = date("Y-m-d H:i:s", strtotime($rec[0]));  //Время начала переборки шаров
					break;
				}
				$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
											 ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
				$sql = "select * from in_d where num = $millID[$index] and dt >= '$date[$index]' order by dt";
				$result = $db->query($sql);
				$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
				foreach($tmp_array as $str_num => $rec)
				{
					$data[$str_num]['DT'] = date("d.m.Y H:i:s", strtotime($rec['dt']));
					$data[$str_num]['state'] = $rec['state'] & 1;
					$data[$str_num]['quality'] = 192;
				}
				//print_r($data);
				$cmd = 1; //1 - поиск единичек, 0 - поиск нулей
				$dt_sum = $this->CalcStopRunMechTime($data, $cmd, $dt_end);
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
				$date[$index] = date("d.m.Y H:i:s", strtotime($date[$index]));
				$tmp_rec = array(($index+1).' '.$millnum[$index], $date[$index], $dt_sum);
				array_push($array, $tmp_rec);
			}
			$result_array['column_titles'] = array('Номер мельницы', 'Дата переборки', 'Время работы');
			$result_array['data'] = $array;
			$result_array['footer'] = '';
			return $result_array;
		}
		catch (PDOException $err)
		{
			return -3; //Ошибка связи с базой данных
		}
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public function cshi_tpl_vent_system_working_time_report($date, $date2)
	{
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date2);
		$current_dt = new DateTime(date('Y-m-d H:i:s'));
    if ($date2!='')
    {
    	$date_end = new DateTime($date2);
    }
    else
    {
    	$date_end = new DateTime($date);
      $date_end->add(new DateInterval('PT24H'));
    }
		if ($date_end >= $current_dt)	$date_end = $current_dt;
		$dt_beg = $date_begin->format('Y-m-d H:i:s');
		$dt_end = $date_end->format('Y-m-d H:i:s');
		if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;
   	$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";
		$tmp_rec = array();
		$data = array();
		$array = array();
		$array2 = array();
		$dt_max = "";

		$mechnum_1 = array(array(73, 115, 116, 117, 89, 102, 104), array(114, 113), array(6, 122), array(94, 75, 91, 112, 110), array(93, 74, 90, 106, 108), array(54, 55, 56, 57, 58, 59), array(78, 69), array(77, 68), array(76, 67));
		$mechnum_2 = array(array(22, 21), array(17, 16), array(12, 5), array(118), array(32, 27, 26, 41), array(32, 30, 31, 42), array(45, 47, 43, 44), array(5, 120, 11), array(1), array(7));
		$mechnum_3 = array(array(129, 130), array(119));
		$table_1_row_1 = array('ВС №329; №49 СИОТ № 3 ЦН 15 Ø700х2 шт.',
													 'ВС №336; №50 СИОТ № 3 ЦН 15 Ø700х2 шт.',
													 'ВС №26; №51 СИОТ № 3',
													 'ВС №322; №52 СИОТ № 3 ЦН 15 Ø700х2 шт.',
													 'ВС №325; №53 СИОТ № 3 ЦН 15 Ø700х2 шт.',
													 'ВС №435; №54 СИОТ № 3 Ø700х2 шт.',
													 'Дымосос №53; №55 СИОТ № 7 циклон Давидсона',
													 'Дымосос №43; №56 СИОТ № 7 циклон Давидсона',
													 'Дымосос №33; №57 СИОТ № 7 циклон Давидсона');
		$table_1_row_3 = array('Элеватор 332, Элеватор 48, Элеватор 49, Транспортер 47, Дезинтегратор 313, Бункер 91, Бункер 93',
													 'Транспортер 481, Транспортер 491',
													 'Транспортер 00, Транспортер 10',
													 'Элеватор 512, Элеватор 532, Дезинтегратор 513, Бункер 59, Бункер 99',
													 'Элеватор 412, Элеватор 432, Дезинтегратор 413, Бункер 95, Бункер 97',
													 'Глинорезная машина 1-6',
													 'Транпортер 531, Сушильный барабан 533',
													 'Транпортер 431, Сушильный барабан 433',
													 'Транпортер 331, Сушильный барабан 333');
		$table_2_row_1 = array('ВС №201; №46 СИОТ № 3 ВЦИНИОТ Ø1000.',
													 'ВС №202; №47 СИОТ № 3 ВЦИНИОТ Ø1000.',
													 'ВС №203; №43 СИОТ № 3 Ø1000.',
													 'ВС №204; №45 ЦН15 № 15 Ø600.',
													 'ВС №199; №48 СИОТ № 3 ВЦИНИОТ №14 Ø1000.',
													 'ВС №198; №42 СИОТ № 3 ВЦИНИОТ №14 Ø1000.',
													 'ВС №315; №44 СИОТ № 3 ВЦИНИОТ',
													 'ВС №316; №41 СИОТ № 3',
													 'ДН №21 (Д №25; Д №26); №39 ЦТ-24, 2х4шт. Электрофильтр ЭГА 1-30-9-6-3',
													 'ДН №19 (Д №27;  Д №28); №40 ЦТ-24, 2х4шт. Электрофильтр ЭГБМ 12-4-5-4-3');
		$table_2_row_3 = array('Элеватор 771, Шаровая мельница 772',
													 'Элеватор 761, Шаровая мельница 762',
													 'Элеватор 141, Траспортер 01',
													 'Траспортер 53',
													 'Элеватор 791, Элеватор 781, Шаровая мельница 782, Бункер 86',
													 'Элеватор 791, Траспортер 793, Шаровая мельница 792, Бункер 89',
													 'Траспортер 552, Транспортер 542, Элеватор 551, Элеватор 541',
													 'Конвейер 01, Элеватор 041, Элеватор 241',
													 'Вращ. печь 073',
													 'Вращ. печь 074');
		$table_3_row_1 = array('ВС №57; №69 СИОТ № 3',
													 'ВС №272; №58 СИОТ № 3 ЦН 15 Ø500х4 шт.');
	  $table_3_row_3 = array('Конвейер 51, Конвейер 52',
													 'Конвейер 50');

		$table[1]['row1'] = $table_1_row_1;
		$table[1]['mechnum'] = $mechnum_1;
		$table[1]['row3'] = $table_1_row_3;
		$table[2]['row1'] = $table_2_row_1;
		$table[2]['mechnum'] = $mechnum_2;
		$table[2]['row3'] = $table_2_row_3;
		$table[3]['row1'] = $table_3_row_1;
		$table[3]['mechnum'] = $mechnum_3;
		$table[3]['row3'] = $table_3_row_3;

		try
		{
			for ($j = 1; $j <= 3; $j++)
			{
				for ($i = 0; $i < count($table[$j]['mechnum']); $i++)
				{
					$dt_sum = array();
					for ($index = 0; $index < count($table[$j]['mechnum'][$i]); $index++)
					{
						$data = null;
						$num = $table[$j]['mechnum'][$i][$index];
						$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
													 ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
						$sql = "SELECT *
										FROM in_d
										WHERE (dt >= isnull((SELECT MAX(dt) AS Expr1
																 FROM in_d AS in_d_1
																 WHERE (dt <= '$dt_beg') AND (num = '$num')),'$dt_beg')) AND (dt < '$dt_end') AND (num = '$num')
										ORDER BY dt";
						//print_r($sql);
	/*					$sql = "SELECT *
										FROM in_d
										WHERE (dt >= '$dt_beg') AND (num = '$num') AND (dt <= '$dt_end')
										ORDER BY dt";	 */
						$result = $db->query($sql);
						$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
						foreach($tmp_array as $str_num => $rec)
						{
							$data[$str_num]['DT'] = date("d.m.Y H:i:s", strtotime($rec['dt']));
							if (strtotime($rec['dt'])<strtotime($dt_beg))
							{
								$data[$str_num]['DT'] = $dt_beg;
							}
							$data[$str_num]['state'] = $rec['state'];
							$data[$str_num]['quality'] = 192;
						}
						//print_r($data);
						$dt_sum[$index] = $this->CalcStopRunMechTime($data, 1, $dt_end);
					}
					$dt_max = null;
					$dt_max = max($dt_sum);
					$hour = ($dt_max - $dt_max % (60*60))/(60*60);//Нашли количество часов
					$dt_max = $dt_max - $hour*60*60;
					$minute = ($dt_max - $dt_max % (60))/60;//Нашли количество минут
					$dt_max = $dt_max - $minute*60;
					$second = $dt_max;//Нашли количество секунд
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
					$dt_max = "$hour ч. $minute мин.";
//					echo "MAX=$dt_max <br>";
					$tmp_rec = array('index'=>$i+1, 'row1'=>$table[$j]['row1'][$i], 'value'=>$dt_max, 'row3'=>$table[$j]['row3'][$i]);
//					$tmp_rec = array('index'=>1, 'row1'=>1, 'value'=>1, 'row3'=>1);
					array_push($array, $tmp_rec);
				}
				$array2[$j] = $array;
				$array = array();
			}
			$result_array = array('data', 'column_titles', 'footer');
			$result_array['column_titles'] = array('№',
																						 'Газоочистная пылеулавливающая установка; номер источника выброса; тип очистки',
																						 'Количество отработанных часов технологическим оборудованием, связанным с данной пылегазоулавливающей установкой',
																						 'Наименование группы источников выделения');
			$result_array['data'] = array('1'=>$array2[1], '2'=>$array2[2], '3'=>$array2[3]);
			$result_array['footer'] = '';
			return $result_array;
		}
		catch (PDOException $err)
		{
			return -3; //Ошибка связи с базой данных
		}
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public function cshi_tpl_rorate_furnace_working_time_report($date, $date2)
	{
    $date_begin = new DateTime($date);
		$date_end = null;
		if ($date2 == null)
		{
			$date_end = new DateTime($date);
			$date_end->add(new DateInterval('PT24H'));
		}
		else
			$date_end = new DateTime($date2);
		$dt_beg = $date_begin->format('Y-m-d H:i:s');
		$dt_end = $date_end->format('Y-m-d H:i:s');
   	$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";

		$data1 = $data2 = array();
		$array1 = $array2 = array();
		$dt_sum = array();

		$data_pit_1_on = array();
		$data_pit_1_off = array();
		$data_pit_2_on = array();
		$data_pit_2_off = array();
		$data_furn1_on = array();
		$data_furn1_off = array();
		$data_pit_3_on = array();
		$data_pit_3_off = array();
		$data_pit_4_on = array();
		$data_pit_4_off = array();
		$data_furn2_on = array();
		$data_furn2_off = array();

		$furn1_on = array();
		$furn1_off = array();
		$furn2_on = array();
		$furn2_off = array();

		$mechnum = array('1'=>'48', '2'=>'49', '3'=>'1', '4'=>'50', '5'=>'51', '6'=>'7');
		$mechDT = array(); //массив  с данными включения/выключения механизмов

		$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
									 ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		try
		{
			set_time_limit(600);
			for ($i=1; $i <= 6; $i++)
			{
				$sql = "SELECT	state, dt, quality, num
								FROM	in_d
								WHERE	dt >= '$dt_beg' AND (num = $mechnum[$i]) and dt < '$dt_end' and quality = 192 order by dt";
//				echo "$i = $sql <br>";
				$result = $db->query($sql);
				$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
				$index1 = $index2 = $index3 = $index4 = 0;
				$cmd = 0;
				if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
				foreach($tmp_array as $str_num => $rec)
				{
					switch ($i)
					{
						case 1: //Для 1го питателя печи №1
							if ($rec['state'] == 0 and $cmd == 0)
							{
								$data_pit_1_off['value'][] = 0;
								$data_pit_1_off['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 1;
							}
							if (($rec['state'] == 1 or $rec['state'] == 3) and $cmd == 1)
							{
								$data_pit_1_on['value'][] = 1;
								$data_pit_1_on['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 0;
							}
						break;
						case 2: //Для 2го питателя печи №1
							if ($rec['state'] == 0 and $cmd == 0)
							{
								$data_pit_2_off['value'][] = 0;
								$data_pit_2_off['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 1;
							}
							if (($rec['state'] == 1 or $rec['state'] == 3) and $cmd == 1)
							{
								$data_pit_2_on['value'][] = 1;
								$data_pit_2_on['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 0;
							}
						break;
						case 3: //Для 1й печи
							if ($rec['state'] == 0 and $cmd == 1)
							{
								$data_furn1_off['value'][] = 0;
								$data_furn1_off['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 0;
							}
							if (($rec['state'] == 1 or $rec['state'] == 3) and $cmd == 0)
							{
								$data_furn1_on['value'][] = 1;
								$data_furn1_on['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 1;
							}
						break;
						case 4: //Для 1го питателя печи №2
							if ($rec['state'] == 0 and $cmd == 0)
							{
								$data_pit_3_off['value'][] = 0;
								$data_pit_3_off['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 1;
							}
							if (($rec['state'] == 1 or $rec['state'] == 3) and $cmd == 1)
							{
								$data_pit_3_on['value'][] = 1;
								$data_pit_3_on['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 0;
							}
						break;
						case 5: //Для 2го питателя печи №2
							if ($rec['state'] == 0 and $cmd == 0)
							{
								$data_pit_4_off['value'][] = 0;
								$data_pit_4_off['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 1;
							}
							if (($rec['state'] == 1 or $rec['state'] == 3) and $cmd == 1)
							{
								$data_pit_4_on['value'][] = 1;
								$data_pit_4_on['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 0;
							}
						break;
						case 6: //Для 2й печи
							if ($rec['state'] == 0 and $cmd == 1)
							{
								$data_furn2_off['value'][] = 0;
								$data_furn2_off['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 0;
							}
							if (($rec['state'] == 1 or $rec['state'] == 3) and $cmd == 0)
							{
								$data_furn2_on['value'][] = 1;
								$data_furn2_on['dt'][] = date("d.m.Y H:i:s", strtotime($rec['dt']));
								$cmd = 1;
							}
						break;
					}
				}
			}
			//Сравниваем работу 2х питателей для печи №1 и находим общее время их неработы
			$dt_pit1_tmp=$dt_pit2_tmp=$dt_1=$dt_2=null;
			$dt_pit1 = $dt_pit2 = array(); $index_x = 0;
			for ($i=0; $i < count($data_pit_1_off['dt']); $i++)
			{
				for ($j=0; $j < count($data_pit_2_off['dt']); $j++)
				{
//					echo "pit1_off = "; echo $data_pit_1_off['dt'][$i]; echo " | pit1_on = "; echo $data_pit_1_on['dt'][$i]; echo "  ||  ";
//					echo "pit2_off = "; echo $data_pit_2_off['dt'][$j]; echo " | pit2_on = "; echo $data_pit_2_on['dt'][$j]; echo "  <br>  ";
					$this->UniteTimeInterval($data_pit_1_off['dt'][$i], $data_pit_1_on['dt'][$i], $data_pit_2_off['dt'][$j], $data_pit_2_on['dt'][$j], $dt_pit1_tmp, $dt_pit2_tmp);
					if ($dt_pit1_tmp != 0 and $dt_pit2_tmp != 0)
					{
//						echo "dt_pit1 = "; echo $dt_pit1_tmp; echo " | dt_pit2 = "; echo $dt_pit2_tmp; echo "<br>";
						$dt_pit1[$index_x] = $dt_pit1_tmp;
						$dt_pit2[$index_x] = $dt_pit2_tmp;
						$index_x++;
//						echo "dt_furn_on_off = "; echo $data_furn2_on['dt'][$j]; echo " | dt_furn_off = "; echo $data_furn2_off['dt'][$j]; echo "  <br>  ";
					}
					else {}
					//dt_pit1 начало общеего времени неработы 2х питателей , dt_pit2 окончание общего времени неработы 2х питателей
//					echo "pit2_off = "; echo $dt_pit1; echo " | pit2_on = "; echo $dt_pit2; echo "  <br>  ";
//					echo "furn2_on = "; echo $data_furn1_on['dt'][$j]; echo " | furn2_off = "; echo $data_furn1_off['dt'][$j]; echo "  <br>  ";
//					$this->UniteTimeInterval($dt_pit1, $dt_pit2, $data_furn1_on['dt'][$j], $data_furn1_off['dt'][$j], $dt_1, $dt_2);
//					echo "dt_1 = "; echo $dt_1; echo " | dt_2 = "; echo $dt_2; echo "  <br>  ";
					//dt_1 время начала работы печи в режиме холостого хода (без питателей), dt_2 время окончания работы печи в режиме холостого хода (без питателей)
				}
			}
			for ($i=0; $i < count($dt_pit1); $i++)
			{
				for ($j=0; $j < count($data_furn1_on['dt']); $j++)
				{
					$this->UniteTimeInterval($dt_pit1[$i], $dt_pit2[$i], $data_furn1_on['dt'][$j], $data_furn1_off['dt'][$j], $dt_1, $dt_2);
					if (($dt_1 != 0 and $dt_2 != 0 and $dt_1 != $dt_2))
					{
						$furn1_on[] = $dt_1;
						$furn1_off[] = $dt_2;
						break;
					}
				}
			}
			$dt_pit1_tmp=$dt_pit2_tmp=$dt_1=$dt_2=null;
			//Сравниваем работу 2х питателей для печи №2 и находим общее время их неработы
			$dt_pit1 = $dt_pit2 = array(); $index_x = 0;
			for ($i=0; $i < count($data_pit_3_off['dt']); $i++)
			{
				for ($j=0; $j < count($data_pit_4_off['dt']); $j++)
				{
//					echo "pit1_off = "; echo $data_pit_3_off['dt'][$i]; echo " | pit1_on = "; echo $data_pit_3_on['dt'][$i]; echo "  ||  ";
//					echo "pit2_off = "; echo $data_pit_4_off['dt'][$j]; echo " | pit2_on = "; echo $data_pit_4_on['dt'][$j]; echo "  <br>  ";
					$this->UniteTimeInterval($data_pit_3_off['dt'][$i], $data_pit_3_on['dt'][$i], $data_pit_4_off['dt'][$j], $data_pit_4_on['dt'][$j], $dt_pit1_tmp, $dt_pit2_tmp);
					//dt_pit1 начало общеего времени неработы 2х питателей , dt_pit2 окончание общего времени неработы 2х питателей
					if ($dt_pit1_tmp != 0 and $dt_pit2_tmp != 0)
					{
//						echo "dt_pit1 = "; echo $dt_pit1_tmp; echo " | dt_pit2 = "; echo $dt_pit2_tmp; echo "<br>";
						$dt_pit1[$index_x] = $dt_pit1_tmp;
						$dt_pit2[$index_x] = $dt_pit2_tmp;
						$index_x++;
//						echo "dt_furn_on_off = "; echo $data_furn2_on['dt'][$j]; echo " | dt_furn_off = "; echo $data_furn2_off['dt'][$j]; echo "  <br>  ";
					}
					else {}
//					echo "dt_1 = "; echo $dt_1; echo " | dt_2 = "; echo $dt_2; echo "  <br>  ";
					//dt_1 время начала работы печи в режиме холостого хода (без питателей), dt_2 время окончания работы печи в режиме холостого хода (без питателей)
				}
			}
			for ($i=0; $i < count($dt_pit1); $i++)
			{
				for ($j=0; $j < count($data_furn2_on['dt']); $j++)
				{
					$this->UniteTimeInterval($dt_pit1[$i], $dt_pit2[$i], $data_furn2_on['dt'][$j], $data_furn2_off['dt'][$j], $dt_1, $dt_2);
					if (($dt_1 != 0 and $dt_2 != 0 and $dt_1 != $dt_2))
					{
						$furn2_on[] = $dt_1;
						$furn2_off[] = $dt_2;
						break;
					}
				}
			}

			if ($furn1_on == null and $furn2_on == null) return -2;
			$sum_furn_1 = $sum_furn_2 = 0; // временя работы печи в режиме холостого хода
			$count = max(count($furn1_on), count($furn1_off), count($furn2_on), count($furn2_off)); //Ищем максимальное кол-во записей в массивах
			for ($i=0; $i < $count; $i++)
			{
				$array1 = array($furn1_on[$i], $furn1_off[$i]);
				$array2 = array($furn2_on[$i], $furn2_off[$i]);
				array_push($data1, $array1);
				array_push($data2, $array2);
				//Считаем время работы печи в режиме холостого хода
				$sum_furn_1 = $sum_furn_1 + (strtotime($furn1_off[$i]) - strtotime($furn1_on[$i]));
				$sum_furn_2 = $sum_furn_2 + (strtotime($furn2_off[$i]) - strtotime($furn2_on[$i]));
			}
			//Перевод секунд в формат  час/минута/секунда
			$hour = ($sum_furn_1 - $sum_furn_1 % (60*60))/(60*60);//Нашли количество часов
			$sum_furn_1 = $sum_furn_1 - $hour*60*60;
			$minute = ($sum_furn_1 - $sum_furn_1 % (60))/60;//Нашли количество минут
			$sum_furn_1 = $sum_furn_1 - $minute*60;
			$second = $sum_furn_1;//Нашли количество секунд
			$sum_furn_1 = "$hour ч. $minute мин.";

			$hour = ($sum_furn_2 - $sum_furn_2 % (60*60))/(60*60);//Нашли количество часов
			$sum_furn_2 = $sum_furn_2 - $hour*60*60;
			$minute = ($sum_furn_2 - $sum_furn_2 % (60))/60;//Нашли количество минут
			$sum_furn_2 = $sum_furn_2 - $minute*60;
			$second = $sum_furn_2;//Нашли количество секунд
			$sum_furn_2 = "$hour ч. $minute мин.";
		}
		catch (PDOException $err)
		{
			return -3; //Ошибка связи с базой данных
		}
//		print_r($array);
	  $result_array['column_titles'] = array('Начало работы', 'Окончание работы');
		$result_array['data1'] = $data1;
		$result_array['data2'] = $data2;
		$result_array['footer1'] = 'Время холостого хода печи: '.$sum_furn_1;
		$result_array['footer2'] = 'Время холостого хода печи: '.$sum_furn_2;
  	return $result_array;
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public function cshi_tpl_alarm_report($date, $date2)
	{
    $date_begin = new DateTime($date);
    if ($date2!='')
    {
    	$date_end = new DateTime($date2);
    }
    else
    {
    	$date_end = new DateTime($date);
      $date_end->add(new DateInterval('PT24H'));
    }
		$current_dt = new DateTime(date('Y-m-d H:i:s'));
		$current_dt->add(new DateInterval('PT11H'));
		if ($date_end >= $current_dt)	$date_end = $current_dt;
		$dt_beg = $date_begin->format('Y-m-d H:i:s');
		$dt_end = $date_end->format('Y-m-d H:i:s');

   	$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";
		$index = 0;

		$data = array();
		$MechName = array();
		$Avar_str = array();
		$tmp_rec = array();
		$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
									 ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "select ID, MehName from Massive_MehNames order by ID";
		$result = $db->query($sql);
		$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($tmp_array as $str_num => $rec)
		{
			$MechName[$rec['ID']]['MechName'] = iconv("Windows-1251","UTF-8", trim($rec['MehName']));
		}
		$tmp_array = null;
		$sql = "select * from Err2 where dt >= '$dt_beg' and dt <= '$dt_end' order by dt";
		$result = $db->query($sql);
		$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($tmp_array as $str_num => $rec)
		{
			if ($rec['reason'] > 2 and $rec['reason'] != 10 and $rec['num'] > 200 && $rec['nummeh'] != 0)
			{
				$num = $rec['num']-200;
				$Avar_str[$index] = 'Авария в маршруте №'.$num.' (остановился: '.$MechName[$rec['nummeh']]['MechName'].')';
				$tmp_rec = array(date("d.m.Y H:i:s", strtotime($rec['dt'])), $Avar_str[$index]);
				array_push($data, $tmp_rec);
				$index++;
			}
			if ($rec['num'] < 200 && $rec['num'] != 0 && $rec['reason'] == 0 )
			{
				$Avar_str[$index] = 'Механизм: '.$MechName[$rec['num']]['MechName'].' не запустился';
				$tmp_rec = array(date("d.m.Y H:i:s", strtotime($rec['dt'])), $Avar_str[$index]);
				array_push($data, $tmp_rec);
				$index++;
			}
			elseif ($rec['num'] < 200 && $rec['num'] != 0 && $rec['reason'] != 10 )
			{
				$Avar_str[$index] = 'Механизм: '.$MechName[$rec['num']]['MechName'].' остановился. Причина: '.$MechName[$rec['nummeh']]['MechName'];
				$tmp_rec = array(date("d.m.Y H:i:s", strtotime($rec['dt'])), $Avar_str[$index]);
				array_push($data, $tmp_rec);
				$index++;
			}
		}
	  $result_array['column_titles'] = array('Дата', 'Аварийное сообщение');
		$result_array['data'] = $data;
    $result_array['footer'] = '';
		return $result_array;
	}
}
?>