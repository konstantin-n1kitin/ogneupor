<?php
defined('SYSPATH') or die('No direct script access.');
//include ('sql_queries.php');
class Model_Csimonthlyreports extends Kohana_Model
{
//------------------------------------------------------------------------------
//Кислород суточный отчёт
  public function csi_oxygen_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//кислород
//2101	38	Vкислорода РМУ
//2342	38	T кислорода
//2343	38	Fисх.кисл. (РМУ)
//2442	38	P кислорода
//2443	38	dP кислорода
//2615	38	Fкисл.
//3004	38	Обрыв Tкисл
//3005	38	Обрыв dPкисл
//3006	38	Обрыв Pкисл
    //задаем константы IDшек
    $IDs = array('T'=>    '2342',
                 'T_br'=> '3004',
                 'P'=>    '2442',
                 'P_br'=> '3006',
                 'dP'=>   '2443',
                 'dP_br'=>'3005',
                 'V'=>    '2101');
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
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,2);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,2);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,2);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
        $total+=round($tmp_rec_array['V'],2);
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
//Пар суточный отчёт
  public function csi_steam_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//пар
//2245	39	Fисх.пар
//2276	39	Зашкал dРпара
//2365	39	dPпара
//2420	39	Tпара
//2430	39	Pпара
//2692	39	Fпара
//2694	39	Qпара
//3001	39	Обрыв dРпара
//3002	39	Обрыв Рпара
//3003	39	Обрыв Tпара
    //задаем константы IDшек
    $IDs = array('T'=>    '2420',
                 //'T_br'=> '3003',
                 'P'=>    '2430',
                 //'P_br'=> '3002',
                 //'dP'=>   '2365',
                 //'dP_br'=>'3001',
                 'V'=>    '2692',
                 'Q'=>    '2694');
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
                           //'T_br'=>'',
                           'P'=>'',
                           //'P_br'=>'',
                           //'dP'=>'',
                           //'dP_br'=>'',
                           'V'=>'',
                           'Q'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        //$tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        //$tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        //$tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        //$tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $tmp_rec_array['Q']+=$data[$input_array_ind]['Q'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,2);
        //$tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,2);
        //$tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        //$tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,2);
        //$tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
        $tmp_rec_array['Q'] = round($tmp_rec_array['Q'],2);
        $total+=round($tmp_rec_array['Q'],2);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'',
                               'T'=>'',
                               //'T_br'=>'',
                               'P'=>'',
                               //'P_br'=>'',
                               //'dP'=>'',
                               //'dP_br'=>'',
                               'V'=>'',
                               'Q'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  //'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  //'Обрыв канала, ч',
                                  //'Перепад давления, кПа (dP)',
                                  //'Обрыв канала, ч',
                                  'Объем, т (V)',
                                  'Тепловая энергия, Гкал (Q)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарная тепловая энергия: '.$total. ' Гкал';
  	return $result_array;
  }
//Пар технологический
  public function csi_steam_teh_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//пар
//2245	39	Fисх.пар
//2276	39	Зашкал dРпара
//2365	39	dPпара
//2420	39	Tпара
//2430	39	Pпара
//2692	39	Fпара
//2694	39	Qпара
//3001	39	Обрыв dРпара
//3002	39	Обрыв Рпара
//3003	39	Обрыв Tпара
    //задаем константы IDшек
    $IDs = array('T'=>    '2239',
                 //'T_br'=> '3003',
                 'P'=>    '2238',
                 //'P_br'=> '3002',
                 //'dP'=>   '2365',
                 //'dP_br'=>'3001',
                 'V'=>    '3889',
                 'Q'=>    '3890');
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
                           //'T_br'=>'',
                           'P'=>'',
                           //'P_br'=>'',
                           //'dP'=>'',
                           //'dP_br'=>'',
                           'V'=>'',
                           'Q'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        //$tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        //$tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        //$tmp_rec_array['dP']+=$data[$input_array_ind]['dP'];
        //$tmp_rec_array['dP_br']+=$data[$input_array_ind]['dP_br'];
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $tmp_rec_array['Q']+=$data[$input_array_ind]['Q'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,2);
        //$tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,2);
        //$tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        //$tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,2);
        //$tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
        $tmp_rec_array['Q'] = round($tmp_rec_array['Q'],2);
        $total+=round($tmp_rec_array['Q'],2);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'',
                               'T'=>'',
                               //'T_br'=>'',
                               'P'=>'',
                               //'P_br'=>'',
                               //'dP'=>'',
                               //'dP_br'=>'',
                               'V'=>'',
                               'Q'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  //'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  //'Обрыв канала, ч',
                                  //'Перепад давления, кПа (dP)',
                                  //'Обрыв канала, ч',
                                  'Объем, т (V)',
                                  'Тепловая энергия, Гкал (Q)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарная тепловая энергия: '.$total. ' Гкал';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//сжатый воздух суточный отчёт
  public function csi_compressed_air_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//сжатый воздух
//2244	39	Fисх.сж.возд.
//2364	39	G21(dPсж.воздуха)
//2368	39	Fсж.воздуха
//2424	39	G30 (Tсж.воздуха)
//2434	39	G22 (Pcж.воздуха)
//2616	39	Fсж.возд.
//2736	39	Обрыв Рсж.возд.
//2737	39	Обрыв dPсж.возд.
//2738	39	Обрыв Тсж.возд.
    //задаем константы IDшек
    $IDs = array('T'=>    '2424',
                 'T_br'=> '2738',
                 'P'=>    '2434',
                 'P_br'=> '2736',
                 'dP'=>   '2364',
                 'dP_br'=>'2737',
                 'V'=>    '2368');
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
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,2);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,1);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,2);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,1);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,2);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,1);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
        $total+=round($tmp_rec_array['V'],2);
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
//теплофикационная вода
  public function csi_thermalclamping_water_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//теплофикационная вода
//2283	39	F п/п воды быт.1
//2436	39	ТП1 F воды прям.
//2437	39	ТП1 F воды обрат
//2547	39	Vпрямая вода
//2548	39	Vобратная вода
//2549	39	Обрыв ТП1 F воды прям
//2550	39	Обрыв ТП1 F воды обрат
//2551	39	Обрыв ТП1 Т воды прям
//2552	39	Обрыв ТП1 Т воды обрат
//2438  41  Т воды прям
//2439  42  Т воды обр.
//2568	39	Тепловая энергия
    //задаем константы IDшек
    $IDs = array('T_s'=>    '2438',   //температура прямой воды
                 'T_s_br'=> '2551',   //обрыв температуры прмой воды
                 'M_s'=>    '2547',   //массовый расход прямой воды
                 'M_s_br'=> '2549',   //обрыв массового расхода прямой воды
                 'T_r'=>    '2439',   //температура обратной воды
                 'T_r_br'=> '2552',   //обрыв температуры обратной воды
                 'M_r'=>    '2548',   //массовый расход обратной воды
                 'M_r_br'=> '2550',   //обрыв массового расхода обратной воды
                 'Q'=>      '2568');  //тепловая энергия
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
        $total+=round($tmp_rec_array['Q'],2);
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
//------------------------------------------------------------------------------
//коксовый газ на сушильный барабан месячный отчёт
  public function csi_rotary_driers_coke_gas_DPU_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//коксовый газ
//1944	37	Fисх.кок.газа С/Бобщ
//1945	37	dPкокс.газа C/Бобщ
//1946	37	Обрыв dPкок.газа С/Бобщ
//1947	37	Обрыв Pкок.газа С/Бобщ
//1948	37	Обрыв Tкок.газ С/Бобщ
//1949	37	Vкок.газа С/Бобщ
//2487	37	Pкокс.газа С/Бобщ
//2488	37	Tкокс.газа С/Бобщ
//2489	37	Fкок.газа С/Бобщ
    //задаем константы IDшек
    $IDs = array('T'=>    '2488',
                 'T_br'=> '1948',
                 'P'=>    '2487',
                 'P_br'=> '1947',
                 'dP'=>   '1945',
                 'dP_br'=>'1946',
                 'V'=>    '1949');
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
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,2);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,2);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,2);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,2);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,2);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,2);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
        $total+=round($tmp_rec_array['V'],2);
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
//коксовый газ на ДП и ФУ месячный отчёт
  public function csi_rotary_driers_coke_gas_DPandFU_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//коксовый газ
//3335	39	обрыв dPкок.газа
//3334	39	обрыв Pкокс.газа
//3333	39	обрыв Tкокс.газа 
//2348	39	dPкок.газа 
//2433	39	Pкокс.газа 
//2423	39	Tкокс.газа 
//2367	39	Fкок.газа общ
    //задаем константы IDшек
    $IDs = array('T'=>    '2423',
                 'T_br'=> '3335',
                 'P'=>    '2433',
                 'P_br'=> '3334',
                 'dP'=>   '2348',
                 'dP_br'=>'3333',
                 'V'=>    '2367');
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
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,2);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,2);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,2);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,2);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,2);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,2);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
        $total+=round($tmp_rec_array['V'],2);
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
  public function csi_natural_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//природный газ
//2241	39	Fисх.пр.газТГ1..5 (id=2241)	V
//2280	39	Fпр.газа ТГ1..5 (id=2280)	S
//2344	39	dPпр.газа ТГ1..5 (id=2344)	V
//2417	39	Tпр.газа ТГ 1..5 (id=2417)	V
//2427	39	Pпр.газа ТГ1..5 (id=2427)	V
//2695	39	Обрыв Рприр.газа (id=2695)	S
//2696	39	Обрыв dPприр.газа (id=2696)	S
//2697	39	Обрыв Тприр.газ (id=2697)	S
//    //задаем константы IDшек
    $IDs = array('T'=>    '2417',
                 'T_br'=> '2697',
                 'P'=>    '2427',
                 'P_br'=> '2695',
                 'dP'=>   '2344',
                 'dP_br'=> '2696',
                 'V'=>    '2280');
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
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
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
        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,2);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,2);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,2);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,2);
        $tmp_rec_array['dP'] = round($tmp_rec_array['dP']/$interval_points_count,2);
        $tmp_rec_array['dP_br'] = round($tmp_rec_array['dP_br']/3600,2);
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
        $total+=round($tmp_rec_array['V'],2);
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
//коксовый газ на кузнечную печь ЦСИ месячный отчёт
  public function csi_forge_furnaces_coke_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
    //задаем константы IDшек
    $IDs = array('T'=>    '2088',  //температура
                 'T_br'=> '3329',  //обрыв температуры
                 'P'=>    '2087',  //давление
                 'P_br'=> '3331',  //обрыв давления
                 'dP'=>   '2086',  //перепад давления
                 'dP_br'=>'3330',  //обрыв по перепаду давления
                 'V'=>    '2094'); //объем
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
//коксовый газ на нагревательные печи 1 и 2 термического участка ЦСИ месячный отчёт
  public function csi_coke_gas_heating_fu_1_2_monthly_report($arg_dt_begin, $arg_dt_end)
  {
    //задаем константы IDшек
    $IDs = array('T'=>    '2441',  //температура
                 'T_br'=> '3611',  //обрыв температуры
                 'P'=>    '2440',  //давление
                 'P_br'=> '3610',  //обрыв давления
                 'dP'=>   '2091',  //перепад давления
                 'dP_br'=>'3609',  //обрыв по перепаду давления
                 'V'=>    '2092'); //объем
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
}
?>