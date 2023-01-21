<?php
defined('SYSPATH') or die('No direct script access.');
class Model_askutotalreports extends Kohana_Model
{
//------------------------------------------------------------------------------
//Кислород суточный
  public function total_oxygen_daily_report($date)
  {
	$IDs = array(25,2101);
    $data = array();
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->sub(new DateInterval('PT1H'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
	$dbname = "oup";
	$dbuser = "sa";
	$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['V'] = $rec['ExpValue'];
	}
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
    foreach($data as $str_num => $rec)
    {
		if ($str_num%2!=0)
		{
			$rec_date = new DateTime($data[$str_num-1]['DT']);
			$rec_date->add(new DateInterval('PT1H'));
			$tmp_volume = $data[$str_num]['V']+$data[$str_num-1]['V'];
			$tmp_rec = array(date_format($rec_date,'H:i:s'),
							 round($tmp_volume,2),round(0.0002651877*$tmp_volume,2),round(0.00777*$tmp_volume,2),round(0.00185441527446*$tmp_volume,2));
			$total+=($data[$str_num]['V'])+($data[$str_num-1]['V']);
			array_push($tmp_array, $tmp_rec);
		}
    }
    $result_array['column_titles']=array('Время','Расход, м<sup>3</sup> (V)','Расход, т.у.т.','Расход, ГДж','Расход, Гкал');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный расход: '.round($total,2).'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//Кислород месячный  
  public function total_oxygen_monthly_report($arg_dt_begin, $arg_dt_end)
  {
    //задаем константы IDшек
    $IDs = array(25,2101);
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H00M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
						   ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['V'] = $rec['ExpValue'];
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

    $tmp_rec_array = array('DT'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
		$tmp_rec_array['V1'] = round(0.0002651877*$tmp_rec_array['V'],2);
		$tmp_rec_array['V2'] = round(0.00777*$tmp_rec_array['V'],2);
		$tmp_rec_array['V3'] = round(0.00185441527*$tmp_rec_array['V'],2);
        $total+=$tmp_rec_array['V'];
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Время','Расход, м<sup>3</sup> (V)','Расход, т.у.т.','Расход, ГДж','Расход, Гкал');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.round($total,2).'м<sup>3</sup>';
  	return $result_array;
  }
 //------------------------------------------------------------------------------
//Коксовый газ суточный
  public function total_coke_gas_daily_report($date)
  {
    $IDs = array(26,27,97,98,99,122,1949,2094,2092);
    $data = array();
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->sub(new DateInterval('PT1H'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
	$dbname = "oup";
	$dbuser = "sa";
	$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['V'] = $rec['ExpValue'];
	}
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
    foreach($data as $str_num => $rec)
    {
		if ($str_num%2!=0)
		{
			$rec_date = new DateTime($data[$str_num-1]['DT']);
			$rec_date->add(new DateInterval('PT1H'));
			$tmp_volume = $data[$str_num]['V']+$data[$str_num-1]['V'];
			$tmp_rec = array(date_format($rec_date,'H:i:s'),
							 round($tmp_volume,2),round(0.000571*$tmp_volume,2),round(0.01675*$tmp_volume,2),round(0.0039976*$tmp_volume,2));
			$total+=($data[$str_num]['V'])+($data[$str_num-1]['V']);
			array_push($tmp_array, $tmp_rec);
		}
    }
    $result_array['column_titles']=array('Время','Расход, м<sup>3</sup> (V)','Расход, т.у.т.','Расход, ГДж','Расход, Гкал');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.round($total,2).'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//Коксовый газ месячный  
  public function total_coke_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
    //задаем константы IDшек
	$IDs = array(26,27,97,98,99,122,1949,2094,2092);
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H00M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
						   ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['V'] = $rec['ExpValue'];
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

    $tmp_rec_array = array('DT'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $total+=$tmp_rec_array['V'];
		$tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
		$tmp_rec_array['V1'] = round(0.0005710*$tmp_rec_array['V'],2);
		$tmp_rec_array['V2'] = round(0.0167500*$tmp_rec_array['V'],2);
		$tmp_rec_array['V3'] = round(0.0039976*$tmp_rec_array['V'],2);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Время','Расход, м<sup>3</sup> (V)','Расход, т.у.т.','Расход, ГДж','Расход, Гкал');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.round($total,2).'м<sup>3</sup>';
  	return $result_array;
  }
  //------------------------------------------------------------------------------
//Пар суточный
  public function total_steam_daily_report($date)
  {
    $IDs = array(2694);
    $data = array();
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->sub(new DateInterval('PT1H'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
	$dbname = "oup";
	$dbuser = "sa";
	$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['Q'] = $rec['ExpValue'];
	}
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
    foreach($data as $str_num => $rec)
    {
		if ($str_num%2!=0)
		{
			$rec_date = new DateTime($data[$str_num-1]['DT']);
			$rec_date->add(new DateInterval('PT1H'));
			$tmp_rec = array(date_format($rec_date,'H:i:s'),
							 round((($data[$str_num]['Q'])+($data[$str_num-1]['Q'])),2));
			$total+=($data[$str_num]['Q'])+($data[$str_num-1]['Q']);
			array_push($tmp_array, $tmp_rec);
		}
    }
    $result_array['column_titles']=array('Время','Тепловая энергия, Гкал(Q)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарная тепловая энергия: '.round($total,2).' Гкал';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//Пар месячный  
  public function total_steam_monthly_report($arg_dt_begin, $arg_dt_end)
  {
    //задаем константы IDшек
	$IDs = array(2694);
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H00M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
						   ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['Q'] = $rec['ExpValue'];
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

    $tmp_rec_array = array('DT'=>'','Q'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['Q']+=$data[$input_array_ind]['Q'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $total+=$tmp_rec_array['Q'];
		$tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['Q'] = round($tmp_rec_array['Q'],2);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','Q'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Время','Тепловая энергия, Гкал(Q)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарная тепловая энергия: '.round($total,2).' Гкал';
  	return $result_array;
  }
  //------------------------------------------------------------------------------
//Природный газ суточный
  public function total_natural_gas_daily_report($date)
  {
    $IDs = array(33,2280);
    $data = array();
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->sub(new DateInterval('PT1H'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
	$dbname = "oup";
	$dbuser = "sa";
	$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['V'] = $rec['ExpValue'];
	}
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
    foreach($data as $str_num => $rec)
    {
		if ($str_num%2!=0)
		{
			$rec_date = new DateTime($data[$str_num-1]['DT']);
			$rec_date->add(new DateInterval('PT1H'));
			$tmp_volume = $data[$str_num]['V']+$data[$str_num-1]['V'];
			$tmp_rec = array(date_format($rec_date,'H:i:s'),
							 round($tmp_volume,2),round(0.001146*$tmp_volume,2),round(0.0336*$tmp_volume,2),round(0.008019093*$tmp_volume,2));
			$total+=($data[$str_num]['V'])+($data[$str_num-1]['V']);
			array_push($tmp_array, $tmp_rec);
		}
    }
    $result_array['column_titles']=array('Время','Расход, м<sup>3</sup> (V)','Расход, т.у.т.','Расход, ГДж','Расход, Гкал');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.round($total,2).'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//Природный газ месячный  
  public function total_natural_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
    //задаем константы IDшек
	$IDs = array(33,2280);
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H00M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
						   ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['V'] = $rec['ExpValue'];
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

    $tmp_rec_array = array('DT'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $total+=$tmp_rec_array['V'];
		$tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
		$tmp_rec_array['V1'] = round(0.001146*$tmp_rec_array['V'],2);
		$tmp_rec_array['V2'] = round(0.0336*$tmp_rec_array['V'],2);
		$tmp_rec_array['V3'] = round(0.008019093*$tmp_rec_array['V'],2);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Время','Расход, м<sup>3</sup> (V)','Расход, т.у.т.','Расход, ГДж','Расход, Гкал');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.round($total,2).'м<sup>3</sup>';
  	return $result_array;
  }
  //------------------------------------------------------------------------------
//Сжатый воздух суточный
  public function total_compressed_air_daily_report($date)
  {
    $IDs = array(28,2368);
    $data = array();
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->sub(new DateInterval('PT1H'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
	$dbname = "oup";
	$dbuser = "sa";
	$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['V'] = $rec['ExpValue'];
	}
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
    foreach($data as $str_num => $rec)
    {
		if ($str_num%2!=0)
		{
			$rec_date = new DateTime($data[$str_num-1]['DT']);
			$rec_date->add(new DateInterval('PT1H'));
			$tmp_volume = $data[$str_num]['V']+$data[$str_num-1]['V'];
			$tmp_rec = array(date_format($rec_date,'H:i:s'),
							 round($tmp_volume,2), round(0.0000457338*$tmp_volume,2),round(0.00134*$tmp_volume,2),round(0.00032*$tmp_volume,2));
			$total+=($data[$str_num]['V'])+($data[$str_num-1]['V']);
			array_push($tmp_array, $tmp_rec);
		}
    }
    $result_array['column_titles']=array('Время','Расход, м<sup>3</sup> (V)','Расход, т.у.т.','Расход, ГДж','Расход, Гкал');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.round($total,2).'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//Сжатый воздух месячный  
  public function total_compressed_air_monthly_report($arg_dt_begin, $arg_dt_end)
  {
    //задаем константы IDшек
	$IDs = array(28,2368);
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H00M'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    for($i=0;$i<=count($IDs)-2;$i++)
	{
		$sql_condition.='ID_Channel = '.$IDs[$i].' OR ';
	}
	$sql_condition.='ID_Channel = '.$IDs[$i];
	$sql = 'SELECT MeasureDate, SUM(Value) AS ExpValue
            FROM Mains
            WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
			GROUP BY MeasureDate
            ORDER BY MeasureDate;';
	$result = $db->prepare($sql);
	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
						   ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	foreach($tmp_array as $str_num => $rec)
	{
		$data[$str_num]['DT'] = $rec['MeasureDate'];
		$data[$str_num]['V'] = $rec['ExpValue'];
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

    $tmp_rec_array = array('DT'=>'','V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $total+=$tmp_rec_array['V'];
		$tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
		$tmp_rec_array['V1'] = round(0.0000457338*$tmp_rec_array['V'],2);
		$tmp_rec_array['V2'] = round(0.00134*$tmp_rec_array['V'],2);
		$tmp_rec_array['V3'] = round(0.00032*$tmp_rec_array['V'],2);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'','V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
   $result_array['column_titles']=array('Время','Расход, м<sup>3</sup> (V)','Расход, т.у.т.','Расход, ГДж','Расход, Гкал');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.round($total,2).'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
public function cshi_analysis1_report($date)
  {
	//Анализ энергоресурсов ЦШИ
    //задаем константы IDшек
    $IDs = array('F_vp_1'=>	'3008',		//расход коксового газа ВП №1
                 'F_vp_2'=>	'3009',		//расход коксового газа ВП №2
				 'F_sb_1'=>	'322',		//расход коксового газа СБ №1
				 'F_sb_2'=>	'2395',		//расход коксового газа СБ №2
				 'F_sb_3'=>	'3185');	//расход коксового газа СБ №3
    $data = array();
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->add(new DateInterval('PT8H'));
    $date_end->add(new DateInterval('PT32H'));
   	$dbhost = "ASKUSERVER2";
	$dbname = "oup";
	$dbuser = "sa";
	$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();
    $tmp_rec1 = array();
	$tmp_rec2 = array();

    //print_r($data);
	foreach($data as $str_num => $rec) {
		foreach($rec as $k => $v) {
			if ($str_num<24)
				$tmp_rec1[$k]+=$v;
			else
				$tmp_rec2[$k]+=$v;
		}
    }
	$tmp_array1=array(
		array('Вращающаяся печь №1', round($tmp_rec1[F_vp_1]/2,1), round($tmp_rec1[F_vp_1]/24,1)),
		array('Вращающаяся печь №2', round($tmp_rec1[F_vp_2]/2,1), round($tmp_rec1[F_vp_2]/24,1)),
		array('Итого', round(($tmp_rec1[F_vp_1]+$tmp_rec1[F_vp_2])/2,1), round(($tmp_rec1[F_vp_1]+$tmp_rec1[F_vp_2])/24,1)),
		array('Сушильный барабан №1', round($tmp_rec1[F_sb_1]/2,1), round($tmp_rec1[F_sb_1]/24,1)),
		array('Сушильный барабан №2', round($tmp_rec1[F_sb_2]/2,1), round($tmp_rec1[F_sb_2]/24,1)),
		array('Сушильный барабан №3', round($tmp_rec1[F_sb_3]/2,1), round($tmp_rec1[F_sb_3]/24,1)),
		array('Итого', round(($tmp_rec1[F_sb_1]+$tmp_rec1[F_sb_2]+$tmp_rec1[F_sb_3])/2,1), round(($tmp_rec1[F_sb_1]+$tmp_rec1[F_sb_2]+$tmp_rec1[F_sb_3])/24,1)),
		array('Всего', round(($tmp_rec1[F_vp_1]+$tmp_rec1[F_vp_2]+$tmp_rec1[F_sb_1]+$tmp_rec1[F_sb_2]+$tmp_rec1[F_sb_3])/2,1), round(($tmp_rec1[F_vp_1]+$tmp_rec1[F_vp_2]+$tmp_rec1[F_sb_1]+$tmp_rec1[F_sb_2]+$tmp_rec1[F_sb_3])/24,1))
	);
	$tmp_array2=array(
		array('Вращающаяся печь №1', round($tmp_rec2[F_vp_1]/2,1),	round($tmp_rec2[F_vp_1]/24,1)),
		array('Вращающаяся печь №2', round($tmp_rec2[F_vp_2]/2,1), round($tmp_rec2[F_vp_2]/24,1)),
		array('Итого', round(($tmp_rec2[F_vp_1]+$tmp_rec2[F_vp_2])/2,1), round(($tmp_rec2[F_vp_1]+$tmp_rec2[F_vp_2])/24,1)),
		array('Сушильный барабан №1', round($tmp_rec2[F_sb_1]/2,1), round($tmp_rec2[F_sb_1]/24,1)),
		array('Сушильный барабан №2', round($tmp_rec2[F_sb_2]/2,1), round($tmp_rec2[F_sb_2]/24,1)),
		array('Сушильный барабан №3', round($tmp_rec2[F_sb_3]/2,1), round($tmp_rec2[F_sb_3]/24,1)),
		array('Итого', round(($tmp_rec2[F_sb_1]+$tmp_rec2[F_sb_2]+$tmp_rec2[F_sb_3])/2,1), round(($tmp_rec2[F_sb_1]+$tmp_rec2[F_sb_2]+$tmp_rec2[F_sb_3])/24,1)),
		array('Всего', round(($tmp_rec2[F_vp_1]+$tmp_rec2[F_vp_2]+$tmp_rec2[F_sb_1]+$tmp_rec2[F_sb_2]+$tmp_rec2[F_sb_3])/2,1), round(($tmp_rec2[F_vp_1]+$tmp_rec2[F_vp_2]+$tmp_rec2[F_sb_1]+$tmp_rec2[F_sb_2]+$tmp_rec2[F_sb_3])/24,1 ))
	);
	
    $result_array['column_titles']=array('Параметр',
                                  'Общее, м<sup>3</sup>',
                                  'Среднее, м<sup>3</sup>');							  
    $result_array['data']=array($tmp_array1,$tmp_array2);
  	return $result_array;
  }
  
//------------------------------------------------------------------------------
public function cshi_analysis2_report($date)
  {
	//Анализ энергоресурсов ЦШИ
    //задаем константы IDшек
    $IDs = array('F_1'=>	'28',		//расход сжатого воздуха ЦШИ
                 'F_2'=>	'2368');	//расход сжатого воздуха ЦСИ
    $data = array();
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->add(new DateInterval('PT8H'));
    $date_end->add(new DateInterval('PT32H'));
   	$dbhost = "ASKUSERVER2";
	$dbname = "oup";
	$dbuser = "sa";
	$dbpass = "metallurg";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
	//print_r($data);
    $result_array = array();
    $tmp_rec1 = array();
	$tmp_rec2 = array();

    //print_r($data);
	foreach($data as $str_num => $rec) {
		foreach($rec as $k => $v) {
			if ($str_num<24)
				$tmp_rec1[$k]+=$v;
			else
				$tmp_rec2[$k]+=$v;
		}
    }
	$tmp_array1=array(
		array('ЦШИ', round($tmp_rec1[F_1],1), round($tmp_rec1[F_1]/12,1)),
		array('ЦСИ', round($tmp_rec1[F_2],1), round($tmp_rec1[F_2]/12,1)),
		array('Всего', round(($tmp_rec1[F_1]+$tmp_rec1[F_2]),1), round(($tmp_rec1[F_1]+$tmp_rec1[F_2])/12,1))
	);
	$tmp_array2=array(
		array('ЦШИ', round($tmp_rec2[F_1],1), round($tmp_rec2[F_1]/12,1)),
		array('ЦСИ', round($tmp_rec2[F_2],1), round($tmp_rec2[F_2]/12,1)),
		array('Всего', round(($tmp_rec2[F_1]+$tmp_rec2[F_2]),1), round(($tmp_rec2[F_1]+$tmp_rec2[F_2])/12,1 ))
	);
	
    $result_array['column_titles']=array('Параметр',
                                  'Общее, м<sup>3</sup>',
                                  'Среднее, м<sup>3</sup>');							  
    $result_array['data']=array($tmp_array1,$tmp_array2);
  	return $result_array;
  }
}