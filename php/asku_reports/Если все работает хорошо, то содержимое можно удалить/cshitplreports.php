	<?php

class Model_CshiTPLReports
{
	public function CalcStopRunMechTime($data, $mode) //$data - многомерный массив с данными $mode - поиск  единичек/нулей (1/0)
	{
		$dt_sum = 0; // Суммарное время работы/простоя механизма
		$index = 0;  // текущий ндекс
		$dt_1 = ''; $dt2 = ''; $dt3 = '';
/*						echo count($data);
						echo "321<br>";
						print_r ($data);  */
		while ($index < count($data))
		{
			if ($data[$index]['quality'] != 0) //Проверка Quality
			{
				switch ($mode)
				{
					case 0:
						if ($mode == 1 and $data[$index]['state'] != 0) //Ищем время включения механизма
						{
							$dt_1 = strtotime($data[$index]['DT']); //нашли дату работы механихма
							$mode = 0;
						}
						if ($mode == 0 and $data[$index]['state'] == 0) //Ищем время выключения механизма
						{
							$dt_2 = strtotime($data[$index]['DT']); // нашли дату остановки механизма
							$dt_3 = $dt_2 - $dt_1; // вычислили время работы механизма
							$dt_sum = $dt_sum + $dt_3; // Суммируем времена работы механизма
							$mode = 1;
						}
					break;
					case 1:
						if ($mode == 0 and $data[$index]['state'] == 0) //Ищем время включения механизма
						{
							$dt_1 = strtotime($data[$index]['DT']); //нашли дату работы механихма
							$mode = 1;
						}
						if ($mode == 1 and $data[$index]['state'] != 0) //Ищем время выключения механизма
						{
							$dt_2 = strtotime($data[$index]['DT']); // нашли дату остановки механизма
							$dt_3 = $dt_2 - $dt_1; // вычислили время работы механизма
							$dt_sum = $dt_sum + $dt_3; // Суммируем времена работы механизма
							$mode = 0;
						}
					break;

				}
			}
			$index++;
		}
		return $dt_sum;
	}

  public function cshi_tpl_mech_working_time_report($date, $date2, $mechnum)
  {
    $dt_beg = new DateTime($date);
		$dt = $dt_beg->format('Y-m-d H:i:s');
    $dt_end = new DateTime($date2);
   	$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";
		$data = array();
		$dt_sum = 0;
		$state = 1; //поиск 0-лей

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		$sql = "SELECT	state, dt, num, quality
						FROM	in_d
						WHERE	(dt >= (SELECT	MAX(dt) AS Expr1
                          FROM	in_d AS in_d_1
                          WHERE	(dt <= '$dt') AND (state = $state)))
									AND (num = $mechnum)";
		$result = $db->query($sql);
//		$result = $db->prepare($sql);
//		echo "num=$mechnum | dt=$dt_beg_1 | state=$state";
//    $result->execute(array(':mechnum'=>$mechnum, ':dt_beg'=>$dt_beg_1, ':state'=>$state));
    $tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);

		foreach($tmp_array as $str_num => $rec)
    {
      $data[$str_num]['DT'] = date("d-m-Y H:i:s", strtotime($rec['dt']));
      $data[$str_num]['state'] = $rec['state'];
      $data[$str_num]['quality'] = $rec['quality'];
		}
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
		$cmd = 1; //1 - поиск единичек, 0 - поиск нулей
    foreach($data as $str_num => $rec)
    {
/*			if ($data[$str_num]['quality'] != 0) //Проверка Quality
			{
				if ($cmd == 1 and $data[$str_num]['state'] != 0)
				{
					$dt_1 = strtotime($data[$str_num]['DT']);
					$cmd = 0;
				}
				if ($cmd == 0 and $data[$str_num]['state'] == 0)
				{
					$dt_2 = strtotime($data[$str_num]['DT']);
					$dt_3 = $dt_2 - $dt_1;
					$dt_sum = $dt_sum + $dt_3;
					$cmd = 1;
				}
			} */
			if ($data[$str_num]['state'] == 0)
				$data[$str_num]['state'] = "выкл.";
			else
				$data[$str_num]['state'] = "вкл.";

			$tmp_rec = array($data[$str_num]['DT'], $data[$str_num]['state']);
			array_push($tmp_array, $tmp_rec);
    }
		$dt_sum = $this->CalcStopRunMechTime($data, $cmd);
    $result_array['column_titles'] = array('Время', 'Состояние механизма');
    $result_array['data'] = $tmp_array;
    $result_array['footer'] = 'Время работы механизма: '.$dt_sum.' c';
  	return $result_array;
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function cshi_tpl_marsh_state_report($date, $date2, $marsh)
  {
    $dt_beg = new DateTime($date);
    $dt_end = new DateTime($date2);
   	$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";
		$data = array();

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT num, dt, state, quality
						FROM in_d
						WHERE num = :marsh and dt >= :dt_beg and dt <= :dt_end
						ORDER by dt";
   	$result = $db->prepare($sql);
    $result->execute(array(':marsh'=>$marsh, ':dt_beg'=>$dt_beg->format('Y-m-d H:i:s'),':dt_end'=>$dt_end->format('Y-m-d H:i:s')));
    $tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);

		foreach($tmp_array as $str_num => $rec)
    {
      $data[$str_num]['DT'] = date("d-m-Y H:i:s", strtotime($rec['dt']));
      $data[$str_num]['state'] = $rec['state'];
      $data[$str_num]['quality'] = $rec['quality'];
		}
    $tmp_array = array();// промежуточный массив только с данными
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
			}
			$tmp_rec = array($data[$str_num]['DT'], $data[$str_num]['state']);
      array_push($tmp_array, $tmp_rec);
    }

    $result_array['column_titles'] = array('Время', 'Состояние маршрута');
    $result_array['data'] = $tmp_array;
    $result_array['footer'] = '';
  	return $result_array;
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

  public function cshi_tpl_refregirator_stop_time_report($date, $date2 ,$id)
  {
		// 2 - холодильник 071
		// 8 - холодильник 072
    $IDs[1] = 2;
    $IDs[2] = 8;
/*    $dt_beg = new DateTime($date);
    $dt_end = new DateTime($date2); */
    $dt_beg = new DateTime('2011-08-12 08:00:00');
    $dt_end = new DateTime('2011-08-12 20:00:00');
   	$dbhost = "tpl-server";
		$dbname = "tpl";
		$dbuser = "sa";
		$dbpass = "tpl";
		$data = $data2 = array();
		$tmp_rec = null;

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		$tmp_array = array();// промежуточный массив только с данными
		$result_array = array(); //окончательный массив с данными и футером
		for ($i = 1; $i <= 2; $i++)
		{
			$sql = "SELECT num, dt, state, quality
							FROM in_d
							WHERE num = :num and dt >= :dt_beg and dt <= :dt_end
							ORDER by dt";
			$result = $db->prepare($sql);
			$result->execute(array(':num' => $IDs[$i], ':dt_beg'=>$dt_beg->format('Y-m-d H:i:s'),':dt_end'=>$dt_end->format('Y-m-d H:i:s')));
			$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
			foreach($tmp_array as $str_num => $rec)
			{
				$data = null;
				$data[$str_num]['DT'] = date("d-m-Y H:i:s", strtotime($rec['dt']));
				$data[$str_num]['state'] = $rec['state'];
				$data[$str_num]['quality'] = $rec['quality'];
			}
			switch ($i)
			{
				case 1:
						$cmd = 0; //1 - поиск единичек, 0 - поиск нулей
						$dt_1 = $dt_2 = $dt_3 = $dt_sum071 = 0;
						$dt_1_str = $dt_2_str = '';
						foreach($data as $str_num => $rec)
						{
/*							if ($data[$str_num]['quality'] != 0) //Проверка Quality
							{
								if ($cmd == 0 and $data[$str_num]['state'] == 0)
								{
									$dt_1 = strtotime($data[$str_num]['DT']);
									$dt_1_str = $data[$str_num]['DT'];
									$cmd = 1;
								}
								if ($cmd == 1 and $data[$str_num]['state'] != 0)
								{
									$dt_2 = strtotime($data[$str_num]['DT']);
									$dt_2_str = $data[$str_num]['DT'];
									$dt_3 = $dt_2 - $dt_1;

									$dt_sum071 = $dt_sum071 + $dt_3;
									$cmd = 0;
								}
							}
							if ($data[$str_num]['state'] == 0)
								$data[$str_num]['state'] = "выкл.";
							else
								$data[$str_num]['state'] = "вкл.";   */
							$tmp_rec = array($data[$str_num]['DT'], $data[$str_num]['state']);
							array_push($tmp_array, $tmp_rec);
//							print_r ($tmp_array);
						}
//						print_r ($tmp_rec);
						$dt_sum071 = $this->CalcStopRunMechTime($data, 0);
						$result_array['column_titles'] = array('Время', 'Состояние');
						$result_array['data'] = $tmp_array;
						$result_array['footer'] = 'Время простоя холодильника 071: '.$dt_sum071.' c';
//						print_r ($result_array);
				break;
				case 2:
						$data = null;
						$data[$str_num]['DT'] = date("d-m-Y H:i:s", strtotime($rec['dt']));
						$data[$str_num]['state'] = $rec['state'];
						$data[$str_num]['quality'] = $rec['quality'];
						$cmd = 0; //1 - поиск единичек, 0 - поиск нулей
						$dt_1 = $dt_2 = $dt_3 = $dt_sum072 = 0;
						$dt_1_str = $dt_2_str = '';
						foreach($data as $str_num => $rec)
						{
							if ($data[$str_num]['quality'] != 0) //Проверка Quality
							{
								if ($cmd == 0 and $data[$str_num]['state'] == 0)
								{
									$dt_1 = strtotime($data[$str_num]['DT']);
									$dt_1_str = $data[$str_num]['DT'];
									$cmd = 1;
								}
								if ($cmd == 1 and $data[$str_num]['state'] != 0)
								{
									$dt_2 = strtotime($data[$str_num]['DT']);
									$dt_2_str = $data[$str_num]['DT'];
									$dt_3 = $dt_2 - $dt_1;

				//					echo "$dt_2_str - $dt_1_str <br>";
									$dt_sum072 = $dt_sum072 + $dt_3;
									$cmd = 0;
								}
							}
							if ($data[$str_num]['state'] == 0)
								$data[$str_num]['state'] = "выкл.";
							else
								$data[$str_num]['state'] = "вкл.";

							$tmp_rec = array($data[$str_num]['DT'], $data[$str_num]['state']);
							array_push($tmp_array, $tmp_rec);
				//			print_r ($tmp_array);
						}

						$result_array['column_titles2'] = array('Время', 'Состояние');
						$result_array['data2'] = $tmp_array;
						$result_array['footer2'] = 'Время простоя холодильника 072: '.$dt_sum072.' c';
				break;
			}
		}
//		print_r ($result_array['data']);
		return $result_array;
	}
	// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
}
?>