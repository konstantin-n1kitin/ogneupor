<?php
defined('SYSPATH') or die('No direct script access.');
//include ('sql_queries.php');
class Model_Cshimonthlyreports extends Kohana_Model
{
//------------------------------------------------------------------------------
//Кислород месячный отчёт
  public function cshi_oxygen_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//кислород
//21	3	Fисх.кисл. ЦШИ
//25	3	Vкисл. ЦШИ
//163	3	обрыв Ткисл. ЦШИ
//326	3	dP кисл.
//2390	3	P кисл.
//2447	3	T кисл.
//2698	3	Fкисл.ЦШИ
//2699	3	обрыв Pкисл. ЦШИ
//2700	3	обрыв dPкисл. ЦШИ
//2704	3	обрыв Pкисл. ЦШИ_2
//2708	3	обрыв dPкисл. ЦШИ_2
//2709	3	обрв Ткисл. ЦШИ_2
    //задаем константы IDшек
    $IDs = array('T'=>    '2447',
                 'T_br'=> '2709',
                 'P'=>    '2390',
                 'P_br'=> '2699',
                 'dP'=>   '326',
                 'dP_br'=>'2700',
                 'V'=>    '25');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
        $days_count++;
				$interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>; Средний объем: '.round($total/$days_count,1).'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//Кислород месячный отчёт
  public function test_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//кислород
//21	3	Fисх.кисл. ЦШИ
//25	3	Vкисл. ЦШИ
//163	3	обрыв Ткисл. ЦШИ
//326	3	dP кисл.
//2390	3	P кисл.
//2447	3	T кисл.
//2698	3	Fкисл.ЦШИ
//2699	3	обрыв Pкисл. ЦШИ
//2700	3	обрыв dPкисл. ЦШИ
//2704	3	обрыв Pкисл. ЦШИ_2
//2708	3	обрыв dPкисл. ЦШИ_2
//2709	3	обрв Ткисл. ЦШИ_2
    //задаем константы IDшек
    $IDs = array('T'=>    '2447',
                 'T_br'=> '2709',
                 'P'=>    '2390',
                 'P_br'=> '2699',
                 'dP'=>   '326',
                 'dP_br'=>'2700',
                 'V'=>    '25');
    $brig=array(array(1,4),array(3,1),array(2,3),array(4,2)); //График бригад с 21.11.2017
		$value_brig=array();
		$count_brig=array();
		$data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
		//print_r($data);
		
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime("2013-01-01 8:00:00");
			$reaper_date=new DateTime("2010-01-02 8:00:00");
			$diff=$rec_date->diff($reaper_date);
			//$days_count=array(0,31,59,90,120,151,181,212,243,273,304,334,365);
			//$days=$diff->y*365+floor(($rec_date->format('Y')-2009)/4)+ ($rec_date->format('Y')%4==0 AND ;
			$days=$rec_date-$reaper_date;
			print_r($days);
			//print_r($rec_date);
			//print_r($reaper_date);
			
			//print_r($diff);
			$value_brig[]+=$data[$input_array_ind]['V'];
			$count_brig[]++;
			$rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
        $days_count++;
				$interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>; Средний объем: '.round($total/$days_count,1).'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на вращающуюся печь №1 суточный отчёт
  public function cshi_rotating_oven_1_coke_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//коксовый газ
//20	3	Fкок.газ исх. В/П1
//26	3	Vкок.газ В/П1
//30	3	Vт.в. В/П1
//325	3	dP В/П1
//2369	3	T В/П1
//2448	3	P В/П1
//2710	3	обрыв Т В/П1
//3008	3	Fкокс.газ В/П1
//3146	3	обрыв Р В/П1
//3147	3	обрыв dР В/П1
    //задаем константы IDшек
    $IDs = array('T'=>    '2369',
                 'T_br'=> '2710',
                 'P'=>    '2448',
                 'P_br'=> '3146',
                 'dP'=>   '325',
                 'dP_br'=>'3146',
                 'V'=>    '26');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'', 'T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на вращающуюся печь №2 суточный отчёт
  public function cshi_rotating_oven_2_coke_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//коксовый газ
//27	3	Vкок.газ В/П2
//24	3	dP В/П 2
//324	3	Fкок.газ исх. В/П2
//2370	3	T В/П2
//2449	3	P В/П2
//3009	3	Fкокс.газ В/П2
//3186	3	обрыв Т В/П2
//3187	3	обрыв Р В/П2
//3188	3	обрыв dР В/П2
    //задаем константы IDшек
    $IDs = array('T'=>    '2370',
                 'T_br'=> '3186',
                 'P'=>    '2449',
                 'P_br'=> '3187',
                 'dP'=>   '24',
                 'dP_br'=>'3188',
                 'V'=>    '27');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'', 'T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на сушильный барабан №1 суточный отчёт
  public function cshi_rotary_drier1_coke_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//коксовый газ
//94	6	T С/Б1
//95	6	P С/Б1
//96	6	dP С/Б1
//142	6	Обрыв Tкок.газ С/Б1 (id=142)
//143	6	Обрыв Pкок.газ С/Б1 (id=143)
//144	6	Обрыв dPкок.газ С/Б1 (id=144)
//321	6	Fисх.кок.газ С/Б1
//322	6	Fкок.газ С/Б1 (id=322)
    //задаем константы IDшек
    $IDs = array('T'=>    '94',
                 'T_br'=> '142',
                 'P'=>    '95',
                 'P_br'=> '143',
                 'dP'=>   '96',
                 'dP_br'=>'144',
                 'V'=>    '97');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'', 'T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на сушильный барабан №2 суточный отчёт
  public function cshi_rotary_drier2_coke_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//коксовый газ
//145	6	Обрыв Tкок.газ С/Б2 (id=145)
//146	6	Обрыв Pкок.газ С/Б2 (id=146)
//147	6	Обрыв dPкок.газ С/Б2 (id=147)
//323	6	T С/Б2
//2383	6	P С/Б2
//2384	6	dP С/Б2
//2385	6	Fисх.кок.газ С/Б2
//2395	6	Fкок.газ С/Б2 (id=2395)
    //задаем константы IDшек
    $IDs = array('T'=>    '323',
                 'T_br'=> '145',
                 'P'=>    '2383',
                 'P_br'=> '146',
                 'dP'=>   '2384',
                 'dP_br'=>'147',
                 'V'=>    '98');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на сушильный барабан №3 суточный отчёт
  public function cshi_rotary_drier3_coke_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//коксовый газ
//148	6	Обрыв Tкок.газ С/Б3 (id=148)
//149	6	Обрыв Pкок.газ С/Б3 (id=149)
//150	6	Обрыв dPкок.газ С/Б3 (id=150)
//2396	6	T С/Б3
//2397	6	P С/Б3
//3183	6	dP С/Б3
//3184	6	Fисх.кок.газ С/Б3
//3185	6	Fкок.газ С/Б3
    //задаем константы IDшек
    $IDs = array('T'=>    '2396',
                 'T_br'=> '148',
                 'P'=>    '2397',
                 'P_br'=> '149',
                 'dP'=>   '3183',
                 'dP_br'=>'150',
                 'V'=>    '99');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//природный газ высокая сторона (ЦШИ)
  public function cshi_natural_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//природный газ
//33	3	Vпр.газ Высок.
//2641	3	Fпр.газ Высок исх.
//2642	3	Тпр.газ Высок
//2643	3	Рпр.газ Высок.
//3007	3	Fпр.газ Высок.
    $IDs = array('T'=>    '2642',
//                 'T_br'=> '0',
                 'P'=>    '2643',
//                 'P_br'=> '0',
                 'dP'=>   '2641',
//                 'F_br'=>'0',
                 'V'=>    '33');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'',
                           'T'=>'',
//                           'T_br'=>'',
                           'P'=>'',
//                           'P_br'=>'',
//                           'dP'=>'',
//                           'dP_br'=>'',
                           'V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
//        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
//        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
//        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
//        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
//        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
//        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
//        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
//        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'',
                                'T'=>'',
//                               'T_br'=>'',
                               'P'=>'',
//                               'P_br'=>'',
//                               'dP'=>'',
//                               'dP_br'=>'',
                               'V'=>''
							   );
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
//                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
//                                  'Обрыв канала, ч',
//                                  'Перепад давления, кПа (dP)',
//                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//сжатый воздух ЦШИ
  public function cshi_compressed_air_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//сжатый воздух
//22	3	Fисх.сж.воз. ЦШИ	V
//28	3	Vсж.воз. ЦШИ	S
//327	3	dP сж.воз.	V
//2391	3	P сж.воз.	V
//2446	3	T сж.воз.	V
//2647	3	Fсж.возд.	V
//2701	3	обрыв Тсж.возд. ЦШИ	S
//2702	3	обрыв Pсж.возд ЦШИ	S
//2703	3	обрыв dPсж.возд. ЦШИ	S
//3143	3	dP сж.воз.-низкая	V
//3144	3	dPСж.воз.	V
    //задаем константы IDшек
    $IDs = array('T'=>    '2446',
                 'T_br'=> '2701',
                 'P'=>    '2391',
                 'P_br'=> '2702',
                 'dP'=>   '327',
                 'dP_br'=>'2703',
                 'V'=>    '28');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'',
                           'T'=>'',
                           'T_br'=>'',
                           'P'=>'',
                           'P_br'=>'',
                           'dP'=>'',
                           'dP_br'=>'',
                           'V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'',
                               'T'=>'',
                               'T_br'=>'',
                               'P'=>'',
                               'P_br'=>'',
                               'dP'=>'',
                               'dP_br'=>'',
                               'V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//сжатый воздух ЦШИ
  public function cshi_compressed_air_formovka_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//сжатый воздух
//22	3	Fисх.сж.воз. ЦШИ	V
//28	3	Vсж.воз. ЦШИ	S
//327	3	dP сж.воз.	V
//2391	3	P сж.воз.	V
//2446	3	T сж.воз.	V
//2647	3	Fсж.возд.	V
//2701	3	обрыв Тсж.возд. ЦШИ	S
//2702	3	обрыв Pсж.возд ЦШИ	S
//2703	3	обрыв dPсж.возд. ЦШИ	S
//3143	3	dP сж.воз.-низкая	V
//3144	3	dPСж.воз.	V
    //задаем константы IDшек
    $IDs = array('T'=>    '3892',
                 'P'=>    '3893',
                 'V'=>    '3899');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'',
                           'T'=>'',
                           'P'=>'',
                           'V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'',
                               'T'=>'',
                               'P'=>'',
                               'V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Давление, МПа (P)',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//----------------------------------------------------------------------------------------------  
  public function cshi_compressed_air_gas_cleaning_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//сжатый воздух Газоочистка
    //задаем константы IDшек
    $IDs = array('T'=>    '3612',
                 'T_br'=> '0',
                 'P'=>    '3613',
                 'P_br'=> '0',
                 'F'=>   '3615',
                 'F_br'=>'0',
                 'V'=>    '3616');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'',
                           'T'=>'',
                           'T_br'=>'',
                           'P'=>'',
                           'P_br'=>'',
                           'F'=>'',
                           'F_br'=>'',
                           'V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['F']+=$data[$input_array_ind]['F'];
        $tmp_rec_array['F_br']+=$data[$input_array_ind]['F_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['F'] = round($tmp_rec_array['F']/$interval_points_count,1);
        $tmp_rec_array['F_br'] = round($tmp_rec_array['F_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'',
                               'T'=>'',
                               'T_br'=>'',
                               'P'=>'',
                               'P_br'=>'',
                               'F'=>'',
                               'F_br'=>'',
                               'V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Расход, м<sup>3</sup>/ч (F)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
  
//теплофикационная вода
//------------------------------------------------------------------------------
  public function cshi_thermalclamping_water_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//теплофикационная вода
    //задаем константы IDшек
    $IDs = array('T_s'=>    '2502',   //температура прямой воды
                 'T_s_br'=> '2732',   //обрыв температуры прмой воды
                 'M_s'=>    '1971',   //массовый расход прямой воды
                 'M_s_br'=> '2734',   //обрыв массового расхода прямой воды
                 'T_r'=>    '2503',   //температура обратной воды
                 'T_r_br'=> '2733',   //обрыв температуры обратной воды
                 'M_r'=>    '1972',   //массовый расход обратной воды
                 'M_r_br'=> '2735',   //обрыв массового расхода обратной воды
                 'Q'=>      '2572');  //тепловая энергия
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'',
                           'T_s'=>'',
                           'T_s_br'=>'',
                           'M_s'=>'',
                           'M_s_br'=>'',
                           'T_r'=>'',
                           'T_r_br'=>'',
                           'M_r'=>'',
                           'M_r_br'=>'',
                           'Q'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T_s']+=$data[$input_array_ind]['T_s'];
        $tmp_rec_array['T_s_br']+=$data[$input_array_ind]['T_s_br'];
        $tmp_rec_array['M_s']+=$data[$input_array_ind]['M_s'];
        $tmp_rec_array['M_s_br']+=$data[$input_array_ind]['M_s_br'];
        $tmp_rec_array['T_r']+=$data[$input_array_ind]['T_r'];
        $tmp_rec_array['T_r_br']+=$data[$input_array_ind]['T_r_br'];
        $tmp_rec_array['M_r']+=$data[$input_array_ind]['M_r'];
        $tmp_rec_array['M_r_br']+=$data[$input_array_ind]['M_r_br'];
        $tmp_rec_array['Q']+=$data[$input_array_ind]['Q'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T_s'] = round($tmp_rec_array['T_s']/$interval_points_count,1);
        $tmp_rec_array['T_s_br'] = round($tmp_rec_array['T_s_br']/3600,1);
        $tmp_rec_array['M_s'] = round($tmp_rec_array['M_s'],1);
        $tmp_rec_array['M_s_br'] = round($tmp_rec_array['M_s_br']/3600,1);
        $tmp_rec_array['T_r'] = round($tmp_rec_array['T_r']/$interval_points_count,1);
        $tmp_rec_array['T_r_br'] = round($tmp_rec_array['T_r_br']/3600,1);
        $tmp_rec_array['M_r'] = round($tmp_rec_array['M_r'],1);
        $tmp_rec_array['M_r_br'] = round($tmp_rec_array['M_r_br']/3600,1);
        $tmp_rec_array['Q'] = round($tmp_rec_array['Q'],1);
        $total+=round($tmp_rec_array['Q'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'',
                               'T_s'=>'',
                               'T_s_br'=>'',
                               'M_s'=>'',
                               'M_s_br'=>'',
                               'T_r'=>'',
                               'T_r_br'=>'',
                               'M_r'=>'',
                               'M_r_br'=>'',
                               'Q'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Время',
                                  'Температура пр., С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Масса пр., т (М)',
                                  'Обрыв канала, ч',
                                  'Температура обр., С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Масса обр., т (М)',
                                  'Обрыв канала, ч',
                                  'Тепловая энергия, Гкал (Q)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарная тепловая энергия: '.$total. 'Гкал';
  	return $result_array;
  }
	  public function electro_monthly_report($arg_dt_begin, $arg_dt_end)
  {
    //задаем константы IDшек
    $IDs = array('T1'	=>	'181',
								'T2'	=>	'183',
								'T3'	=>	'184',
								'T4'	=>	'185',
								'T5'	=>	'186',
								'T14'	=>	'187',
								'T16'	=>	'188',
								'T6'	=>	'189',
								'T7'	=>	'190',
								'T9'	=>	'191',
								'T8'	=>	'192',
								'T10'	=>	'193',
								'T13'	=>	'194');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'',
                           'T1'=>'',
                           'T2'=>'',
													 'T3'=>'',
													 'T4'=>'',
													 'T5'=>'',
													 'T14'=>'',
													 'T16'=>'',
													 'T6'=>'',
													 'T7'=>'',
													 'T9'=>'',
													 'T8'=>'',
													 'T10'=>'',
													 'T13'=>'');
		$total_rec_array['DT']='Всего';
    $interval_points_count = 0;//количество записей за один суточный интервал
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T1']+=$data[$input_array_ind]['T1'];
        $tmp_rec_array['T2']+=$data[$input_array_ind]['T2'];
				$tmp_rec_array['T3']+=$data[$input_array_ind]['T3'];
				$tmp_rec_array['T4']+=$data[$input_array_ind]['T4'];
				$tmp_rec_array['T5']+=$data[$input_array_ind]['T5'];
				$tmp_rec_array['T14']+=$data[$input_array_ind]['T14'];
				$tmp_rec_array['T16']+=$data[$input_array_ind]['T16'];
				$tmp_rec_array['T6']+=$data[$input_array_ind]['T6'];
				$tmp_rec_array['T7']+=$data[$input_array_ind]['T7'];
				$tmp_rec_array['T9']+=$data[$input_array_ind]['T9'];
				$tmp_rec_array['T8']+=$data[$input_array_ind]['T8'];
				$tmp_rec_array['T10']+=$data[$input_array_ind]['T10'];
				$tmp_rec_array['T13']+=$data[$input_array_ind]['T13'];
				$tmp_rec_array['Sum']+=$data[$input_array_ind]['T1']+
				                       $data[$input_array_ind]['T2']+
				                       $data[$input_array_ind]['T3']+
				                       $data[$input_array_ind]['T4']+
				                       $data[$input_array_ind]['T5']+
				                       $data[$input_array_ind]['T14']+
				                       $data[$input_array_ind]['T16']+
				                       $data[$input_array_ind]['T6']+
				                       $data[$input_array_ind]['T7']+
				                       $data[$input_array_ind]['T9']+
				                       $data[$input_array_ind]['T8']+
				                       $data[$input_array_ind]['T10']+
				                       $data[$input_array_ind]['T13'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
        $total+=$tmp_rec_array['T1']+$tmp_rec_array['T2']+$tmp_rec_array['T3']+$tmp_rec_array['T4']+$tmp_rec_array['T5']+$tmp_rec_array['T14']+$tmp_rec_array['T16']+$tmp_rec_array['T6']+$tmp_rec_array['T7']+$tmp_rec_array['T9']+$tmp_rec_array['T8']+$tmp_rec_array['T10']+$tmp_rec_array['T13'];
				$tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
				$tmp_rec_array['T1']=round($tmp_rec_array['T1'],1);
        $tmp_rec_array['T2']=round($tmp_rec_array['T2'],1);
				$tmp_rec_array['T3']=round($tmp_rec_array['T3'],1);
				$tmp_rec_array['T4']=round($tmp_rec_array['T4'],1);
				$tmp_rec_array['T5']=round($tmp_rec_array['T5'],1);
				$tmp_rec_array['T14']=round($tmp_rec_array['T14'],1);
				$tmp_rec_array['T16']=round($tmp_rec_array['T16'],1);
				$tmp_rec_array['T6']=round($tmp_rec_array['T6'],1);
				$tmp_rec_array['T7']=round($tmp_rec_array['T7'],1);
				$tmp_rec_array['T9']=round($tmp_rec_array['T9'],1);
				$tmp_rec_array['T8']=round($tmp_rec_array['T8'],1);
				$tmp_rec_array['T10']=round($tmp_rec_array['T10'],1);
				$tmp_rec_array['T13']=round($tmp_rec_array['T13'],1);
				$tmp_rec_array['Sum']=round($tmp_rec_array['Sum'],1);
				
				$total_rec_array['T1']+=$tmp_rec_array['T1'];
        $total_rec_array['T2']+=$tmp_rec_array['T2'];
				$total_rec_array['T3']+=$tmp_rec_array['T3'];
				$total_rec_array['T4']+=$tmp_rec_array['T4'];
				$total_rec_array['T5']+=$tmp_rec_array['T5'];
				$total_rec_array['T14']+=$tmp_rec_array['T14'];
				$total_rec_array['T16']+=$tmp_rec_array['T16'];
				$total_rec_array['T6']+=$tmp_rec_array['T6'];
				$total_rec_array['T7']+=$tmp_rec_array['T7'];
				$total_rec_array['T9']+=$tmp_rec_array['T9'];
				$total_rec_array['T8']+=$tmp_rec_array['T8'];
				$total_rec_array['T10']+=$tmp_rec_array['T10'];
				$total_rec_array['T13']+=$tmp_rec_array['T13'];
				$total_rec_array['Sum']+=$tmp_rec_array['Sum'];
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'',
                           'T1'=>'',
                           'T2'=>'',
													 'T3'=>'',
													 'T4'=>'',
													 'T5'=>'',
													 'T14'=>'',
													 'T16'=>'',
													 'T6'=>'',
													 'T7'=>'',
													 'T9'=>'',
													 'T8'=>'',
													 'T10'=>'',
													 'T13'=>'',
													 'Sum'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    array_push($tmp_array, $total_rec_array);
		$result_array['column_titles']=array('Время',
                                  'Электроэнергия ТП-1.Т1, кВт*ч',
																	'Электроэнергия ТП-2.Т2, кВт*ч',
																	'Электроэнергия ТП-2.Т3, кВт*ч',
																	'Электроэнергия ТП-3.Т4, кВт*ч',
																	'Электроэнергия ТП-4.Т5, кВт*ч',
																	'Электроэнергия ТП-4А.Т14, кВт*ч',
																	'Электроэнергия ТП-4Б.Т16, кВт*ч',
																	'Электроэнергия ТП-5.Т6, кВт*ч',
																	'Электроэнергия ТП-5.Т7, кВт*ч',
																	'Электроэнергия ТП-6.Т9, кВт*ч',
																	'Электроэнергия ТП-6.Т8, кВт*ч',
																	'Электроэнергия ТП-7.Т10, кВт*ч',
																	'Электроэнергия ТП-10.Т13, кВт*ч',
																	'Сумма, кВт*ч');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарная электроэнергия: '.round($total,1). 'кВт*ч';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на туннельные печи ЦШИ месячный отчёт
  public function cshi_tunnel_furnaces_coke_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//коксовый газ
//67	8	Ркокс.газ Т/П(резер.газопровод)
//68	8	dPкокс.газ Т/П(резер.газопровод)
//69	8	Fисх.кокс.газ Т/П(резер.газопровод)
//122	8	Vкокс.газ Т/П(резер.газопровод)
//139	8	Обрыв Tкокс.газ Т/П(резер.газопровод)
//140	8	Обрыв Ркокс.газ Т/П(резер.газопровод)
//141	8	Обрыв dPкокс.газ Т/П(резер.газопровод)
//328	8	Fкокс.газ Т/П(резер.газопровод)
//3240	8	Ткокс.газ Т/П(резер.газопровод)
    //задаем константы IDшек
    $IDs = array('T'=>    '52',
                 'T_br'=> '139',
                 'P'=>    '67',
                 'P_br'=> '140',
                 'dP'=>   '68',
                 'dP_br'=>'141',
                 'V'=>    '122');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
    $date_end->add(new DateInterval('PT23H30M'));
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
/*     ВНИМАНИЕ!!! Костыль!!! до момента сдачи узла есть битые данные,
           поэтому при запросе даты раньше - дата подменяется*/
    if ($date_begin<new DateTime('2011-08-11'))
    {
      $date_begin=new DateTime('2011-08-11');
    }
    if ($date_end<new DateTime('2011-08-11'))
    {
      $date_end=new DateTime('2011-08-11');
    }
/*КОНЕЦ КОСТЫЛЯ*/
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
    $data = array();
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
//    print_r($data);
    $result_array = array();//заводим массив под результаты
    $tmp_array = array();
    if (count($data)>0)
    {
      $interval_begin = new DateTime($data[0]['DT']);
      $interval_end = new DateTime($data[0]['DT']);
    }
    else
    {
      return 0;
    }
    $interval_end->add(new DateInterval('PT23H30M'));
    $input_array_ind = 0;
    $output_array_ind = 0;
    $total = 0;
    $rec_date = $interval_begin;

    $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        $tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,1);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,1);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],1);
        $total+=round($tmp_rec_array['V'],1);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','T'=>'','T_br'=>'','P'=>'','P_br'=>'','dP'=>'','dP_br'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//Пожарно-питьевая вода
//------------------------------------------------------------------------------
    public function drinking_water_monthly_report($arg_dt_begin, $arg_dt_end, $IDs)
    {
        $date_begin = new DateTime($arg_dt_begin);
        $date_end = new DateTime($arg_dt_end);
        $date_begin->sub(new DateInterval('PT1H30M'));
        $date_end->add(new DateInterval('PT23H30M'));
        $dbhost = "ASKUSERVER2";
        $dbname = "oup";
        $dbuser = "sa";
        $dbpass = "metallurg";
        $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
            ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
            ORDER BY MeasureDate;";
        $data = array();
        foreach($IDs as $param_name => $value)
        {
            $result = $db->prepare($sql);
            $result->execute(array(':id_channel'=>$value,
                ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
                ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
            $tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach($tmp_array as $str_num => $rec)
            {
                $data[$str_num]['DT'] = $rec['MeasureDate'];
                $data[$str_num][$param_name] = $rec['Value'];
            }
        }
        $result_array = array();//заводим массив под результаты
        $tmp_array = array();
        if (count($data)>0)
        {
            $interval_begin = new DateTime($data[0]['DT']);
            $interval_end = new DateTime($data[0]['DT']);
        }
        else
        {
            return 0;
        }
        $interval_end->add(new DateInterval('PT23H30M'));
        $input_array_ind = 0;
        $output_array_ind = 0;
        $total = 0;
        $rec_date = $interval_begin;

        $tmp_rec_array = array('DT'=>'',
            'P'=>'',
            'P_br'=>'',
            'Q'=>'',
            'Q_br'=>'');
        $interval_points_count = 0;//количество записей за один суточный интервал
        //для вычисления средних значений
        while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
        {
            $rec_date = new DateTime($data[$input_array_ind]['DT']);
            if ($rec_date<=$interval_end)
            {
                $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
                $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
                $tmp_rec_array['Q']+=$data[$input_array_ind]['Q'];
                $tmp_rec_array['Q_br']+=$data[$input_array_ind]['Q_br'];
                $interval_points_count++;
                $input_array_ind++;
            }
            else
            {
                //вычисляем средние значения там где нужно
                $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
                $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,1);
                $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
                $tmp_rec_array['Q'] = round($tmp_rec_array['Q'],1);
                $tmp_rec_array['Q_br'] = round($tmp_rec_array['Q_br']/3600,1);
                $total+=round($tmp_rec_array['Q'],1);
                array_push($tmp_array, $tmp_rec_array);
                unset($tmp_rec_array);
                $tmp_rec_array = array('DT'=>'',
                    'P'=>'',
                    'P_br'=>'',
                    'Q'=>'',
                    'Q_br'=>'');
                $interval_points_count = 0;
                $interval_begin = $interval_end;
                $interval_begin->add(new DateInterval('PT30M'));
                $interval_end->add(new DateInterval('PT23H30M'));
                $output_array_ind++;
            }
        }
        $result_array['column_titles']=array('Время',
            'Давление, Па',
            'Обрыв канала, ч',
            'Расход, м<sup>3</sup>/ч',
            'Обрыв канала, ч');
        $result_array['data'] = $tmp_array;
        $result_array['footer']='Суммарная расход: '.$total.', м<sup>3</sup>';
        return $result_array;
    }
    public function drinking_water_1_monthly_report($arg_dt_begin, $arg_dt_end)
    {
        $IDs = array(
            'P' => '2502',   //давление
            'P_br' => '2732',   //обрыв давления
            'Q' => '1971',   //расход
            'Q_br' => '2734'   //обрыв расхода
        );
        return Model_Cshimonthlyreports::drinking_water_monthly_report($arg_dt_begin, $arg_dt_end, $IDs);
    }
    public function drinking_water_2_monthly_report($arg_dt_begin, $arg_dt_end)
    {
        $IDs = array(
            'P' => '2502',   //давление
            'P_br' => '2732',   //обрыв давления
            'Q' => '1971',   //расход
            'Q_br' => '2734'   //обрыв расхода
        );
        return Model_Cshimonthlyreports::drinking_water_monthly_report($arg_dt_begin, $arg_dt_end, $IDs);
    }
    public function drinking_water_3_monthly_report($arg_dt_begin, $arg_dt_end)
    {
        $IDs = array(
            'P' => '2502',   //давление
            'P_br' => '2732',   //обрыв давления
            'Q' => '1971',   //расход
            'Q_br' => '2734'   //обрыв расхода
        );
        return Model_Cshimonthlyreports::drinking_water_monthly_report($arg_dt_begin, $arg_dt_end, $IDs);
    }
    public function drinking_water_4_monthly_report($arg_dt_begin, $arg_dt_end)
    {
        $IDs = array(
            'P' => '2502',   //давление
            'P_br' => '2732',   //обрыв давления
            'Q' => '1971',   //расход
            'Q_br' => '2734'   //обрыв расхода
        );
        return Model_Cshimonthlyreports::drinking_water_monthly_report($arg_dt_begin, $arg_dt_end, $IDs);
    }
    public function drinking_water_5_monthly_report($arg_dt_begin, $arg_dt_end)
    {
        $IDs = array(
            'P' => '2502',   //давление
            'P_br' => '2732',   //обрыв давления
            'Q' => '1971',   //расход
            'Q_br' => '2734'   //обрыв расхода
        );
        return Model_Cshimonthlyreports::drinking_water_monthly_report($arg_dt_begin, $arg_dt_end, $IDs);
    }
    public function drinking_water_6_monthly_report($arg_dt_begin, $arg_dt_end)
    {
        $IDs = array(
            'P' => '2502',   //давление
            'P_br' => '2732',   //обрыв давления
            'Q' => '1971',   //расход
            'Q_br' => '2734'   //обрыв расхода
        );
        return Model_Cshimonthlyreports::drinking_water_monthly_report($arg_dt_begin, $arg_dt_end, $IDs);
    }
}