<?php
//include ('sql_queries.php');
class Model_Csidailyreports extends Kohana_Model
{//------------------------------------------------------------------------------
//Кислород суточный отчёт
  public function csi_oxygen_daily_report($date)
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
        $rec_date->add(new DateInterval('PT2H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round(($data[$str_num]['T']+$data[$str_num-1]['T'])/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round(($data[$str_num]['P']+$data[$str_num-1]['P'])/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round(($data[$str_num]['dP']+$data[$str_num-1]['dP'])/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round($data[$str_num]['V']+$data[$str_num-1]['V'],2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2);
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
//Пар суточный отчёт
  public function csi_steam_daily_report($date)
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
                 'T_br'=> '3003',
                 'P'=>    '2430',
                 'P_br'=> '3002',
                 'dP'=>   '2365',
                 'dP_br'=>'3001',
                 'V'=>    '2692',
                 'Q'=>    '2694');
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
        $rec_date->add(new DateInterval('PT2H'));
        $tmp_rec = array(date_format(new DateTime($data[$str_num]['DT']),'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2),
                         round((($data[$str_num]['Q'])+($data[$str_num-1]['Q'])),2));
        $total+=round((($data[$str_num]['Q'])+($data[$str_num-1]['Q'])),2);
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
                                  'Объем, м<sup>3</sup> (V)',
                                  'Тепловая энергия, ккал (Q)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарная тепловая энергия: '.$total.' ккал';
  	return $result_array;
  }
//------------------------------------------------------------------------------
//сжатый воздух суточный отчёт
  public function csi_compressed_air_daily_report($date)
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
        $rec_date->add(new DateInterval('PT2H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2);
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
//------------------------------------------------------------------------------
//теплофикационная вода
  public function csi_thermalclamping_water_daily_report($date)
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
                 'M_s'=>    '2436',   //массовый расход прямой воды
                 'M_s_br'=> '2549',   //обрыв массового расхода прямой воды
                 'T_r'=>    '2439',   //температура обратной воды
                 'T_r_br'=> '2552',   //обрыв температуры обратной воды
                 'M_r'=>    '2437',   //массовый расход обратной воды
                 'M_r_br'=> '2550',   //обрыв массового расхода обратной воды
                 'G'=>      '2568');  //тепловая энергия
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
            WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
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
        $rec_date->add(new DateInterval('PT2H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round(($data[$str_num]['T_s']+$data[$str_num-1]['T_s'])/2,2),
                         round(($data[$str_num]['T_s_br']+$data[$str_num-1]['T_s_br'])/3600,1),
                         round($data[$str_num]['M_s']+$data[$str_num-1]['M_s'],2),
                         round(($data[$str_num]['M_s_br']+$data[$str_num-1]['M_s_br'])/3600,1),
                         round($data[$str_num]['T_r']+$data[$str_num-1]['T_r'],2),
                         round(($data[$str_num]['T_r_br']+$data[$str_num-1]['T_r_br'])/3600,1),
                         round($data[$str_num]['M_r']+$data[$str_num-1]['M_r'],2),
                         round(($data[$str_num]['M_r_br']+$data[$str_num-1]['M_r_br'])/3600,1),
                         round($data[$str_num]['G']+$data[$str_num-1]['G'],2));
        $total+=round((($data[$str_num]['G'])+($data[$str_num-1]['G'])),2);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
                                  'Температура пр., С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Масса пр., кг (М)',
                                  'Обрыв канала, ч',
                                  'Температура обр., С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Масса обр., кг (М)',
                                  'Обрыв канала, ч',
                                  'Тепловая энергия, ккал (G)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарная тепловая энергия: '.$total.', ккал';
    return $result_array;
  }
//------------------------------------------------------------------------------
//коксовый газ на сушильные барабаны суточный отчёт
  public function csi_rotary_driers_coke_gas_daily_report($date)
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
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT2H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2);
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
//природный газ низкая сторона (ЦСИ)
  public function csi_natural_gas_daily_report($date)
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
    foreach($data as $str_num => $rec)
    {
      if ($str_num%2!=0)
      {
        $rec_date = new DateTime($data[$str_num-1]['DT']);
        $rec_date->add(new DateInterval('PT2H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1),
                         round((($data[$str_num]['dP'])+($data[$str_num-1]['dP']))/2,2),
                         round(($data[$str_num]['dP_br']+$data[$str_num-1]['dP_br'])/3600,1),
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2);
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
                                  'Перепад давления, Па (dP)',
                                  'Обрыв канала, ч',
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//коксовый газ на кузнечную печь ЦСИ суточный отчёт
  public function csi_forge_furnaces_coke_gas_daily_report($date)
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
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->sub(new DateInterval('PT1H'));
    $date_end->add(new DateInterval('PT23H30M'));
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
/*     ВНИМАНИЕ!!! Костыль!!! до момента сдачи узла есть битые данные,
           поэтому при запросе даты раньше - дата подменяется*/
    if ($date_begin<new DateTime('2011-10-11'))
    {
      $date_begin=new DateTime('2011-10-11');
    }
    if ($date_end<new DateTime('2011-10-11'))
    {
      $date_end=new DateTime('2011-10-11');
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
        $rec_date->add(new DateInterval('PT2H'));
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
}
?>