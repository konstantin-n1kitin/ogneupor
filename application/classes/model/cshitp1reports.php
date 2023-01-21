<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Cshitp1reports extends Kohana_Model
{
	public function GetTruckList($arg_dt_beg, $arg_dt_end)
	{
		$dt_beg = new DateTime($arg_dt_beg);
		$dt_end = new DateTime($arg_dt_end);

		$dbhost = 'tunnel-server';
		$dbname = 'tun_furn';
		$dbuser = 'sa';
		$dbpass = 'ogneupor';
		$sql = "SELECT     DT_dry_beg, DT_dry_end, DT_furn_beg, DT_furn_end, CartNum
				FROM         Passport
				WHERE     (DT_dry_beg <= '".$dt_end->format('Y-m-d H:i:s')."') and (DT_dry_beg >= '".$dt_beg->format('Y-m-d H:i:s')."')
				UNION
				SELECT     DT_dry_beg, DT_dry_end, DT_furn_beg, DT_furn_end, CartNum
				FROM         Passport_short
				WHERE     (DT_dry_beg <= '".$dt_end->format('Y-m-d H:i:s')."') and (DT_dry_beg >= '".$dt_beg->format('Y-m-d H:i:s')."')
				ORDER BY DT_dry_beg";
		try
		{
			set_time_limit(600);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result = $db->prepare($sql);
			$result->execute();
			$result_array['data'] = $result->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result_array['data'] as &$value) {
				if ($value['DT_dry_beg']!="") {
					$DT_dry_beg = new DateTime($value['DT_dry_beg']);
					$value['DT_dry_beg'] = $DT_dry_beg->format('d.m.Y H:i:s');
				}
				if ($value['DT_dry_end']!="") {
					$DT_dry_end = new DateTime($value['DT_dry_end']);
					$value['DT_dry_end'] = $DT_dry_end->format('d.m.Y H:i:s');
				}
				if ($value['DT_furn_beg']!="") {
					$DT_furn_beg = new DateTime($value['DT_furn_beg']);
					$value['DT_furn_beg'] = $DT_furn_beg->format('d.m.Y H:i:s');
				}
				if ($value['DT_furn_end']!="") {
					$DT_furn_end = new DateTime($value['DT_furn_end']);
					$value['DT_furn_end'] = $DT_furn_end->format('d.m.Y H:i:s');
				}
			}
			$result_array['header'] = array ('Поставили в сушило','Вышел из сушила','Поставили в печь','Вышел из печи','Номер вагона');
			return $result_array;
		}
		catch( PDOException $err )
		{
			return; //Ошибка связи с базой данных
		}
	}
//-------------------------------------------------------------------
	public function GetPassport($arg_dt)
	{
		$dt = new DateTime($arg_dt);

		$dbhost = 'tunnel-server';
		$dbname = 'tun_furn';
		$dbuser = 'sa';
		$dbpass = 'ogneupor';

		$sql1 = "SELECT DT_dry_beg, DT_dry_end, DT_furn_beg, DT_furn_end, CartNum, BrickMark, BrickMark_D, Dencity, Material,
					ModeDispelCart,	F1, F2, F3, F4, P1, P2, P3, T1, T2, T3, T4, T5, T6, T7, ReturnFiring, CartType, QuantityFir, QuantityBriquette, OnPodsad, OnPodsad_D, Furn_Brigade, Dry_Brigade, Furn_Shift, Dry_Shift, CartPos, ID, Furn_FIO, Dry_FIO, CartCountOnFurn, CartCountOnDry, CartCountOffDry, Cart_Turns, P4, P5, DT_dry_add_progr, DT_furn_add_progr, T8, T9
				FROM   Passport
				WHERE  DT_dry_beg = '".$dt->format('Y-m-d H:i:s')."'";
		$sql2 = "SELECT DT_dry_beg, DT_dry_end, DT_furn_beg, DT_furn_end, CartNum, BrickMark, BrickMark_D, Dencity, Material,
					ModeDispelCart,	F1, F2, F3, F4, P1, P2, P3, T1, T2, T3, T4, T5, T6, T7, ReturnFiring, CartType, QuantityFir, QuantityBriquette, OnPodsad, OnPodsad_D, Furn_Brigade, Dry_Brigade, Furn_Shift, Dry_Shift, CartPos, ID, Furn_FIO, Dry_FIO, CartCountOnFurn, CartCountOnDry, CartCountOffDry, Cart_Turns, P4, P5, DT_dry_add_progr, DT_furn_add_progr, T8, T9
				FROM   Passport_short
				WHERE  DT_dry_beg = '".$dt->format('Y-m-d H:i:s')."'";
		try
		{
			set_time_limit(600);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result1 = $db->prepare($sql1);
			$result2 = $db->prepare($sql2);
			$result1->execute();
			$result2->execute();
			$result_array = $result1->fetchAll(PDO::FETCH_ASSOC);
			if (count($result_array)==0) $result_array = $result2->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result_array as &$value) {
				if ($value['DT_dry_beg']!="") {
					$dt_tmp = new DateTime($value['DT_dry_beg']);
					$value['DT_dry_beg'] = $dt_tmp->format('d.m.Y H:i:s');
				}
				if ($value['DT_dry_end']!="") {
					$dt_tmp = new DateTime($value['DT_dry_end']);
					$value['DT_dry_end'] = $dt_tmp->format('d.m.Y H:i:s');
				}
				if ($value['DT_furn_beg']!="") {
					$dt_tmp = new DateTime($value['DT_furn_beg']);
					$value['DT_furn_beg'] = $dt_tmp->format('d.m.Y H:i:s');
				}
				if ($value['DT_furn_end']!="") {
					$dt_tmp = new DateTime($value['DT_furn_end']);
					$value['DT_furn_end'] = $dt_tmp->format('d.m.Y H:i:s');
				}
				if ($value['DT_dry_add_progr']!="") {
					$DT_dry_add_progr = new DateTime($value['DT_dry_add_progr']);
					$value['DT_dry_add_progr'] = $DT_dry_add_progr->format('d.m.Y H:i:s');
				}
				if ($value['DT_furn_add_progr']!="") {
					$dt_tmp = new DateTime($value['DT_furn_add_progr']);
					$value['DT_furn_add_progr'] = $dt_tmp->format('d.m.Y H:i:s');
				}
				$value['Dry_FIO']=iconv("Windows-1251", "UTF-8", $value['Dry_FIO']);
				$value['Furn_FIO']=iconv("Windows-1251", "UTF-8", $value['Furn_FIO']);
				$value['CartType']=iconv("Windows-1251", "UTF-8", $value['CartType']);
				$value['Material']=iconv("Windows-1251", "UTF-8", $value['Material']);
				$value['BrickMark']=iconv("Windows-1251", "UTF-8", $value['BrickMark']);
				$value['OnPodsad']=iconv("Windows-1251", "UTF-8", $value['OnPodsad']);
				$value['F1']=round($value['F1'],1);
				$value['F2']=round($value['F2'],1);
				$value['F3']=round($value['F3'],1);
				$value['F4']=round($value['F4'],1);
				$value['P1']=round($value['P1'],1);
				$value['P2']=round($value['P2'],1);
				$value['P3']=round($value['P3'],1);
				$value['P4']=round($value['P4'],1);
				$value['P5']=round($value['P5'],1);
				$value['T1']=round($value['T1'],1);
				$value['T2']=round($value['T2'],1);
				$value['T3']=round($value['T3'],1);
				$value['T4']=round($value['T4'],1);
				$value['T5']=round($value['T5'],1);
				$value['T6']=round($value['T6'],1);
				$value['T7']=round($value['T7'],1);
				$value['T8']=round($value['T8'],1);
				$value['T9']=round($value['T9'],1);
				foreach ($value as &$Tag) {
					if ($Tag=="") $Tag="-";
					$Tag=trim($Tag);
				}
			}
			return $result_array[0];
		}
		catch( PDOException $err )
		{
			return; //Ошибка связи с базой данных
		}
	}
//-------------------------------------------------------------------
	public function CalcStopRunMechTime($data, $mode, $dt_end)  //$data - многомерный массив с данными $mode - поиск  единичек/нулей (1/0)
	{
		$cmd = $mode;
		$last_dt = ''; //последняя дата в массиве
		$first_dt = ''; //первая дата в массиве
		$dt_sum = 0; // Суммарное время работы/простоя механизма
		$index = 0;  // текущий ндекс
		$dt_1 = ''; $dt_2 = ''; $dt_3 = '';
		while ($index < count($data))
		{
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
//-------------------------------------------------------------------

	public function cshi_tp1_mech_working_time_report($date, $date2, $mechname)
	{
    $dt = new DateTime($date);
		$dt_beg = $dt->format('Y-m-d H:i:s');
    $dt = new DateTime($date2);
    $dt->add(new DateInterval('PT1S'));
		$dt_end = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime(date('Y-m-d H:i:s'));
		$current_dt = $dt->format('Y-m-d H:i:s');
		if (strtotime($dt_end) > strtotime($current_dt)) $dt_end = $current_dt;
		if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;
   	$dbhost = "tunnel-server";
		$dbname = "Tun_furn";
		$dbuser = "sa";
		$dbpass = "ogneupor";
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
                         FROM TREND_IN_D AS TREND_IN_D_1
                         WHERE (TS <= '$dt_beg') AND (Name = '$name'))) AND (TS < '$dt_end') AND (Name = '$name')
					  ORDER BY TS";		
//		echo $sql;
		try
		{
			set_time_limit(600);
			$result = $db->query($sql);
			$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
			//print_r($tmp_array);
			$count=$result->fetch(PDO::FETCH_NUM);
			$index=0;
			$flag = false;
			if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
			foreach($tmp_array as $str_num => $rec)
			{
				if (count($tmp_array) == 1) //Если запись всего одна то время этой записи должно быть равно $date1 а не времени которое мы нашли, когда искали дополнительную запись в SQK запросе
				{
  				$data[$index]['DT'] = $date;
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
					}
					else	
					{
						if ($flag == true)
						{
							$index++;
							$flag = false;
						}	
						$data[$index]['DT'] = date("d.m.Y H:i:s", strtotime($rec['TS']));
						$data[$index]['state'] = $rec['Value'];
						$data[$index]['quality'] = $rec['Quality'];
						$index++;
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
					$tmp_rec = array($data[$index]['DT'], $data[$index]['state']);
					array_push($tmp_array, $tmp_rec);
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
	//------------------------------------------------------------------------------

	public function cshi_tp1_teh($date1,$date2,$id,$title)
	{
		define ("SQLCHARSET", "utf8");
		
		$date_begin = new DateTime($date1);
		$date_end = new DateTime($date2);
		$date1=$date_begin->format('Y-m-d H:i:s');
		$date2=$date_end->format('Y-m-d H:i:s');
		
		$dbhost = 'tunnel-server'; $dbname = 'tun_furn'; $dbuser = 'sa'; $dbpass = 'ogneupor';
		$sql = "SELECT DATEADD(hh, 5, TS) AS Date, Value
				FROM trend_in_a
				WHERE (Name = '".$id."') AND (DATEADD(hh, 5, TS) >= '".$date1."') AND (DATEADD(hh, 5, TS) <= '".$date2."') AND (Quality=192)
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
}
?>