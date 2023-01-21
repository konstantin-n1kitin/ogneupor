<?php
defined('SYSPATH') or die('No direct script access.');
//include ('sql_queries.php');
class Model_Cshidailyreports extends Kohana_Model
{
//------------------------------------------------------------------------------
//Кислород суточный отчёт
  public function cshi_oxygen_daily_report($date)
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
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->sub(new DateInterval('PT1H'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "ASKUSERVER2";
		$dbname = "oup";
		$dbuser = "sa";
		$dbpass = "metallurg";
//    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
//                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
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
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на вращающуюся печь №1 суточный отчёт
  public function cshi_rotating_oven_1_coke_gas_daily_report($date)
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на вращающуюся печь №2 суточный отчёт
  public function cshi_rotating_oven_2_coke_gas_daily_report($date)
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на сушильный барабан №1 суточный отчёт
  public function cshi_rotary_drier1_coke_gas_daily_report($date)
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на сушильный барабан №2 суточный отчёт
  public function cshi_rotary_drier2_coke_gas_daily_report($date)
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на сушильный барабан №3 суточный отчёт
  public function cshi_rotary_drier3_coke_gas_daily_report($date)
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//природный газ высокая сторона (ЦШИ)
  public function cshi_natural_gas_daily_report($date)
  {
//природный газ
//33	3	Vпр.газ Высок.
//2641	3	Fпр.газ Высок исх.
//2642	3	Тпр.газ Высок
//2643	3	Рпр.газ Высок.
//3007	3	Fпр.газ Высок.
    //задаем константы IDшек
    $IDs = array('T'=>    '2642',
//                 'T_br'=> '0',
                 'P'=>    '2643',
//                 'P_br'=> '0',
                 'dP'=>   '2641',
//                 'F_br'=>'0',
                 'V'=>    '33');
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
//                         (($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
//                         (($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
//                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
//                         (($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
//                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
//                                  'Обрыв канала, ч',
//                                  'Перепад давления, Па (dP)',
//                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//сжатый воздух ЦШИ
//------------------------------------------------------------------------------
  public function cshi_compressed_air_daily_report($date)
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
//    print_r ($result_array['data']);
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//сжатый воздух ЦШИ формовка
//------------------------------------------------------------------------------
  public function cshi_compressed_air_formovka_daily_report($date)
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Давление, МПа (P)',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
//    print_r ($result_array['data']);
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//----------------------------------------------------------------------------------------------  
  public function cshi_compressed_air_gas_cleaning_daily_report($date)
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['F'])+($data[$str_num-1]['F']))/2,2),
                         round(($data[$str_num]['F_br']+$data[$str_num-1]['F_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Расход, м<sup>3</sup>/ч (F)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
//    print_r ($result_array['data']);
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }  
  
//теплофикационная вода
//------------------------------------------------------------------------------
  public function cshi_thermalclamping_water_daily_report($date)
  {
//теплофикационная вода
    //задаем константы IDшек
    $IDs = array('T_s'=>    '2502',   //температура прямой воды
                 'T_s_br'=> '2732',   //обрыв температуры прямой воды
                 'M_s'=>    '1971',   //массовый расход прямой воды
                 'M_s_br'=> '2734',   //обрыв массового расхода прямой воды
                 'T_r'=>    '2503',   //температура обратной воды
                 'T_r_br'=> '2733',   //обрыв температуры обратной воды
                 'M_r'=>    '1972',   //массовый расход обратной воды
                 'M_r_br'=> '2735',   //обрыв массового расхода обратной воды
                 'G'=>      '2572');  //тепловая энергия
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();
    $tmp_array = array();
    $total = 0;
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T_s'])+($data[$str_num-1]['T_s']))/2,2),
                         round(($data[$str_num]['T_s_br']+$data[$str_num-1]['T_s_br'])/3600,1),
                         round((($data[$str_num]['M_s'])+($data[$str_num-1]['M_s'])),2),
                         round(($data[$str_num]['M_s_br']+$data[$str_num-1]['M_s_br'])/3600,1),
                         round((($data[$str_num]['T_r'])+($data[$str_num-1]['T_r']))/2,2),
                         round(($data[$str_num]['T_r_br']+$data[$str_num-1]['T_r_br'])/3600,1),
                         round((($data[$str_num]['M_r'])+($data[$str_num-1]['M_r'])),2),
                         round(($data[$str_num]['M_r_br']+$data[$str_num-1]['M_r_br'])/3600,1),
                         round((($data[$str_num]['G'])+($data[$str_num-1]['G'])),2));
        $total+=round((($data[$str_num]['G'])+($data[$str_num-1]['G'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура пр., С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Массовый расход пр., т (М)',
                                  'Обрыв канала, ч',
                                  'Температура обр., С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Массовый расход обр., т (М)',
                                  'Обрыв канала, ч',
                                  'Тепловая энергия, Гкал (G)');
    $result_array['data']=$tmp_array;
//    print_r ($result_array['data']);
    $result_array['footer']='Суммарная тепловая энергия: '.$total.', Гкал';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на туннельные печи ЦШИ  резервный трубопровод суточный отчёт
  public function cshi_tunnel_furnaces_coke_gas_daily_report($date)
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
    $IDs = array('T'=>    '3240',
                 'T_br'=> '139',
                 'P'=>    '67',
                 'P_br'=> '140',
                 'dP'=>   '68',
                 'dP_br'=>'141',
                 'V'=>    '122');
    $data = array();
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->sub(new DateInterval('PT1H'));
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
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
    foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value,
    	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
    	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $tmp_array = array();// промежуточный массив только с данными
    $result_array = array(); //окончательный массив с данными и футером
    $total = 0;
//    print_r($data);
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),1);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',
                                  'Перепад давления, кПа (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
	public function electro_daily_report($date)
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
    $sql = "SELECT ID_Channel, MeasureDate, Value, State
            FROM Mains
            WHERE (ID_Channel = :id_channel) AND (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end)
            ORDER BY MeasureDate;";
		foreach($IDs as $param_name => $value)
    {
   		$result = $db->prepare($sql);
    	$result->execute(array(':id_channel'=>$value, ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//    	$result_arrays[$key]=$result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $data[$str_num]['DT'] = $rec['MeasureDate'];
        $data[$str_num][$param_name] = $rec['Value'];
      }
    }
    $result_array = array();
    $tmp_array = array();
    $total = 0;
		$total_rec[0]='';
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T1'])+($data[$str_num-1]['T1'])),2),
												 round((($data[$str_num]['T2'])+($data[$str_num-1]['T2'])),2),
												 round((($data[$str_num]['T3'])+($data[$str_num-1]['T3'])),2),
												 round((($data[$str_num]['T4'])+($data[$str_num-1]['T4'])),2),
												 round((($data[$str_num]['T5'])+($data[$str_num-1]['T5'])),2),
												 round((($data[$str_num]['T14'])+($data[$str_num-1]['T14'])),2),
												 round((($data[$str_num]['T16'])+($data[$str_num-1]['T16'])),2),
												 round((($data[$str_num]['T6'])+($data[$str_num-1]['T6'])),2),
												 round((($data[$str_num]['T7'])+($data[$str_num-1]['T7'])),2),
												 round((($data[$str_num]['T9'])+($data[$str_num-1]['T9'])),2),
												 round((($data[$str_num]['T8'])+($data[$str_num-1]['T8'])),2),
												 round((($data[$str_num]['T10'])+($data[$str_num-1]['T10'])),2),
												 round((($data[$str_num]['T13'])+($data[$str_num-1]['T13'])),2),
												 round((($data[$str_num]['T1'])+($data[$str_num-1]['T1']))+
												 (($data[$str_num]['T2'])+($data[$str_num-1]['T2']))+
												 (($data[$str_num]['T3'])+($data[$str_num-1]['T3']))+
												 (($data[$str_num]['T4'])+($data[$str_num-1]['T4']))+
												 (($data[$str_num]['T5'])+($data[$str_num-1]['T5']))+
												 (($data[$str_num]['T14'])+($data[$str_num-1]['T14']))+
												 (($data[$str_num]['T16'])+($data[$str_num-1]['T16']))+
												 (($data[$str_num]['T6'])+($data[$str_num-1]['T6']))+
												 (($data[$str_num]['T7'])+($data[$str_num-1]['T7']))+
												 (($data[$str_num]['T9'])+($data[$str_num-1]['T9']))+
												 (($data[$str_num]['T8'])+($data[$str_num-1]['T8']))+
												 (($data[$str_num]['T10'])+($data[$str_num-1]['T10']))+
												 (($data[$str_num]['T13'])+($data[$str_num-1]['T13'])),2));
				$total_rec[1]+=$data[$str_num]['T1']+$data[$str_num-1]['T1'];
				$total_rec[2]+=$data[$str_num]['T2']+$data[$str_num-1]['T2'];
				$total_rec[3]+=$data[$str_num]['T3']+$data[$str_num-1]['T3'];
				$total_rec[4]+=$data[$str_num]['T4']+$data[$str_num-1]['T4'];
				$total_rec[5]+=$data[$str_num]['T5']+$data[$str_num-1]['T5'];
				$total_rec[6]+=$data[$str_num]['T14']+$data[$str_num-1]['T14'];
				$total_rec[7]+=$data[$str_num]['T16']+$data[$str_num-1]['T16'];
				$total_rec[8]+=$data[$str_num]['T6']+$data[$str_num-1]['T6'];
				$total_rec[9]+=$data[$str_num]['T7']+$data[$str_num-1]['T7'];
				$total_rec[10]+=$data[$str_num]['T9']+$data[$str_num-1]['T9'];
				$total_rec[11]+=$data[$str_num]['T8']+$data[$str_num-1]['T8'];
				$total_rec[12]+=$data[$str_num]['T10']+$data[$str_num-1]['T10'];
				$total_rec[13]+=$data[$str_num]['T13']+$data[$str_num-1]['T13'];
        $total+=$data[$str_num]['T1']+$data[$str_num-1]['T1']+$data[$str_num]['T2']+$data[$str_num-1]['T2']+$data[$str_num]['T3']+$data[$str_num-1]['T3']+$data[$str_num]['T4']+$data[$str_num-1]['T4']+$data[$str_num]['T5']+$data[$str_num-1]['T5']+$data[$str_num]['T14']+$data[$str_num-1]['T14']+$data[$str_num]['T16']+$data[$str_num-1]['T16']+$data[$str_num]['T6']+$data[$str_num-1]['T6']+$data[$str_num]['T7']+$data[$str_num-1]['T7']+$data[$str_num]['T9']+$data[$str_num-1]['T9']+$data[$str_num]['T8']+$data[$str_num-1]['T8']+$data[$str_num]['T10']+$data[$str_num-1]['T10']+$data[$str_num]['T13']+$data[$str_num-1]['T13'];
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }
    }
		$sum=0;
		foreach ($total_rec as $value) {
			$sum+=$value;
		}
		$total_rec[14]=$sum;
		foreach ($total_rec as &$value) {
			$value=round($value,1);
		}
		$total_rec[0]='Всего';
		array_push($tmp_array, $total_rec);
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
    $result_array['data']=$tmp_array;
//    print_r ($result_array['data']);
    $result_array['footer']='Суммарная электроэнергия: '.round($total,2).', кВт*ч';
  	return $result_array;
  }
//Пожарно-питьевая вода
//------------------------------------------------------------------------------
    public function drinking_water_daily_report($date, $IDs)
    {
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
        $tmp_array = array();
        $total = 0;
        foreach($data as $str_num => $rec)
        {
            if ($str_num%2!=0)
            {
                $rec_date = new DateTime($data[$str_num-1]['DT']);
                $rec_date->add(new DateInterval('PT1H'));
                $tmp_rec = array(date_format($rec_date,'H:i:s'),
                    round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                    round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                    round((($data[$str_num]['Q'])+($data[$str_num-1]['Q'])),2),
                    round(($data[$str_num]['Q_br']+$data[$str_num-1]['Q_br'])/3600,1));
                $total+=round((($data[$str_num]['Q'])+($data[$str_num-1]['Q'])),1);
                array_push($tmp_array, $tmp_rec);
            }
        }
        $result_array['column_titles']=array('Время',
            'Давление, Па',
            'Обрыв канала, ч',
            'Расход, м<sup>3</sup>/ч',
            'Обрыв канала, ч');
        $result_array['data']=$tmp_array;
        $result_array['footer']='Суммарная расход: '.$total.', м<sup>3</sup>';
        return $result_array;
    }
    //Пожарно-питьевая вода. Быт. ввод №2 (бойлер) ЦШИ
    public function drinking_water_1_daily_report($date)
    {
        //задаем константы IDшек
        $IDs = array(
            'P'=>    '2502',   //давление
            'P_br'=> '2732',   //обрыв давления
            'Q'=>    '1971',   //расход
            'Q_br'=> '2734'   //обрыв расхода
        );
        return Model_Cshidailyreports::drinking_water_daily_report($date, $IDs);
    }
    //Пожарно-питьевая вода. Быт. ввод №1 (зап. вых.) ЦШИ
    public function drinking_water_2_daily_report($date)
    {
        //задаем константы IDшек
        $IDs = array(
            'P'=>    '2502',   //давление
            'P_br'=> '2732',   //обрыв давления
            'Q'=>    '1971',   //расход
            'Q_br'=> '2734'   //обрыв расхода
        );
        return Model_Cshidailyreports::drinking_water_daily_report($date, $IDs);
    }
    //Пожарно-питьевая вода. Маст. энергослужбы ЦШИ
    public function drinking_water_3_daily_report($date)
    {
        //задаем константы IDшек
        $IDs = array(
            'P'=>    '2502',   //давление
            'P_br'=> '2732',   //обрыв давления
            'Q'=>    '1971',   //расход
            'Q_br'=> '2734'   //обрыв расхода
        );
        return Model_Cshidailyreports::drinking_water_daily_report($date, $IDs);
    }
    //Пожарно-питьевая вода. ЦШИ АБК столовая
    public function drinking_water_4_daily_report($date)
    {
        //задаем константы IDшек
        $IDs = array(
            'P'=>    '2502',   //давление
            'P_br'=> '2732',   //обрыв давления
            'Q'=>    '1971',   //расход
            'Q_br'=> '2734'   //обрыв расхода
        );
        return Model_Cshidailyreports::drinking_water_daily_report($date, $IDs);
    }
    //Пожарно-питьевая вода. Бытовые ЦСИ
    public function drinking_water_5_daily_report($date)
    {
        //задаем константы IDшек
        $IDs = array(
            'P'=>    '2502',   //давление
            'P_br'=> '2732',   //обрыв давления
            'Q'=>    '1971',   //расход
            'Q_br'=> '2734'   //обрыв расхода
        );
        return Model_Cshidailyreports::drinking_water_daily_report($date, $IDs);
    }
    //Пожарно-питьевая вода. Мех. мастерская
    public function drinking_water_6_daily_report($date)
    {
        //задаем константы IDшек
        $IDs = array(
            'P'=>    '2502',   //давление
            'P_br'=> '2732',   //обрыв давления
            'Q'=>    '1971',   //расход
            'Q_br'=> '2734'   //обрыв расхода
        );
        return Model_Cshidailyreports::drinking_water_daily_report($date, $IDs);
    }
}
