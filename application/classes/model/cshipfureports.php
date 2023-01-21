<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Cshipfureports extends Kohana_Model
{
  public function cshi_pfu_alarm_report($arg_dt_begin, $arg_dt_end)
  {
    $tmp_array = array();
    $mechs_array = array();
    $data = array();
    $tables_names = array('1'=>'PULT_MehNames','2'=>'PSU14_MehNames','3'=>'PSU20_MehNames');
    $date_begin = new DateTime($arg_dt_begin);
    if ($arg_dt_end!='')
    {    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "TPL-SERVER";
		$dbname = "pfu";
		$dbuser = "sa";
		$dbpass = "tpl";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
//получаем массив сообщений joinенный с таблицей причин
    $sql = "SELECT Error.ID, Error.DT, Error.ErrorStatus, Error.ControllerNum,
                   General_Reason.Reason AS Res
            FROM Error JOIN General_Reason ON Error.Reason = General_Reason.ID
            WHERE (DT > :dt_begin) AND (DT <= :dt_end)
            ORDER BY DT;";
 		$result = $db->prepare($sql);
   	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
   	$data = $result->fetchAll(PDO::FETCH_ASSOC);
    $result->closeCursor();
//получаем массивы названий механизмов
    $sql = "SELECT *
            FROM :table_name;";
    foreach($tables_names as $param_name => $value)
    {
      $tmp_sql = str_replace(':table_name',$value,$sql);
   		$result = $db->prepare($tmp_sql);
    	$result->execute();
    	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $str_num => $rec)
      {
        $mechs_array[$str_num]['ID'] = $rec['ID'];
        $mechs_array[$str_num][$param_name] = iconv("Windows-1251","UTF-8", $rec['MehName']);
      }
    }
    $result_array = array('data', 'column_titles', 'footer');
    $result_array['column_titles'] = array('Время', 'Авария', 'Механизм','Причина');
    $result_array['footer'] = '';
    foreach($data as $str_num=>$key)
    {
      $tmp_rec = $key;
      $tmp_date = new DateTime($tmp_rec['DT']);
      $result_array['data'][$str_num]['DT'] = $tmp_date->format('d.m.Y H:i:s');
      $result_array['data'][$str_num]['Reason'] = iconv("Windows-1251", "UTF-8", $tmp_rec['Res']);
      if ($tmp_rec['ID']=='99')
      {
      	$result_array['data'][$str_num]['Mech'] = 'нет';
      }
      else
      {
        $result_array['data'][$str_num]['Mech'] = $mechs_array[$key['ID']-1][$key['ControllerNum']];
      }
	  if ($tmp_rec['ErrorStatus']=='99')
      {
      	$result_array['data'][$str_num]['Status'] = 'нет';
      }
      else
      {
        $result_array['data'][$str_num]['Status'] = $mechs_array[$key['ErrorStatus']-1][$key['ControllerNum']];
      }
    }
  return($result_array);
  }
 //------------------------------------------------------------------------------

 
  public function cshi_pfu_press5_alarm_report($arg_dt_begin, $arg_dt_end)
  {
    $data = array();
	$s_array = array();
    $date_begin = new DateTime($arg_dt_begin);
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
		$date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "press5-server";
	$dbname = "PRESS_5";
	$dbuser = "sa";
	$dbpass = "admintp";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	//получаем массив сообщений
    $sql = "SELECT Alarm.TS, Alarm.Description, Alarm.TextAttr03 
            FROM Alarm 
            WHERE (TS > :dt_begin) AND (TS <= :dt_end) and (Quality = 192) and (Value = 1)
            ORDER BY TS;";
 	$result = $db->prepare($sql);
   	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
   	$data = $result->fetchAll(PDO::FETCH_ASSOC);
    $result->closeCursor();
 
	$result_array = array('data', 'column_titles', 'footer');
    $result_array['column_titles'] = array('Время', 'Авария');
    $result_array['footer'] = '';
	$step=0;
    foreach($data as $str_num=>$key)
    {
		$tmp_rec = $key;
		if ($tmp_rec['Description'] != NULL) 
		{
			$tmp_date = new DateTime($tmp_rec['TS']);
			$tmp_date->add(new DateInterval('PT5H'));
			$result_array['data'][$step]['TS'] = $tmp_date->format('d.m.Y H:i:s');
			$result_array['data'][$step]['Alarm'] = iconv("Windows-1251", "UTF-8", $tmp_rec['Description'] . ' ' . $tmp_rec['TextAttr03']);
			$step++;
		}
    }
//  print_r($sql);
  return($result_array);
  } 
	//------------------------------------------------------------------------------

 
  public function cshi_pfu_press3_alarm_report($arg_dt_begin, $arg_dt_end)
  {
    $data = array();
	$s_array = array();
    $date_begin = new DateTime($arg_dt_begin);
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
		$date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "press5-server";
	$dbname = "PRESS_3";
	$dbuser = "sa";
	$dbpass = "admintp";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	//получаем массив сообщений
    $sql = "SELECT Alarm.TS, Alarm.Description, Alarm.TextAttr03 
            FROM Alarm 
            WHERE (TS > :dt_begin) AND (TS <= :dt_end) and (Quality = 192) and (Value = 1)
            ORDER BY TS;";
 	$result = $db->prepare($sql);
   	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
   	$data = $result->fetchAll(PDO::FETCH_ASSOC);
    $result->closeCursor();
 
	$result_array = array('data', 'column_titles', 'footer');
    $result_array['column_titles'] = array('Время', 'Авария');
    $result_array['footer'] = '';
	$step=0;
    foreach($data as $str_num=>$key)
    {
		$tmp_rec = $key;
		if ($tmp_rec['Description'] != NULL) 
		{
			$tmp_date = new DateTime($tmp_rec['TS']);
			$tmp_date->add(new DateInterval('PT5H'));
			$result_array['data'][$step]['TS'] = $tmp_date->format('d.m.Y H:i:s');
			$result_array['data'][$step]['Alarm'] = iconv("Windows-1251", "UTF-8", $tmp_rec['Description'] . ' ' . $tmp_rec['TextAttr03']);
			$step++;
		}
    }
//  print_r($sql);
  return($result_array);
  } 
//------------------------------------------------------------------------------
  public function cshi_pfu_press2_recipe_archive($arg_dt_begin, $arg_dt_end)
  {
		//196	PSU-14.Global.PSU14_RECIPE_1_1
		//197	PSU-14.Global.PSU14_RECIPE_1_2
		//198	PSU-14.Global.PSU14_RECIPE_1_3
		//199	PSU-14.Global.PSU14_RECIPE_1_4
		//200	PSU-14.Global.PSU14_RECIPE_2_1
		//201	PSU-14.Global.PSU14_RECIPE_2_2
		//202	PSU-14.Global.PSU14_RECIPE_2_3
		//203	PSU-14.Global.PSU14_RECIPE_2_4
		//204	PSU-14.Global.PSU14_RECIPE_3_1
		//205	PSU-14.Global.PSU14_RECIPE_3_2
		//206	PSU-14.Global.PSU14_RECIPE_3_3
		//207	PSU-14.Global.PSU14_RECIPE_3_4
		//208	PSU-14.Global.PSU14_RECIPE_4_1
		//209	PSU-14.Global.PSU14_RECIPE_4_2
		//210	PSU-14.Global.PSU14_RECIPE_4_3
		//211	PSU-14.Global.PSU14_RECIPE_4_4
		//212	PSU-14.Global.PSU14_RECIPE_5_1
		//213	PSU-14.Global.PSU14_RECIPE_5_2
		//214	PSU-14.Global.PSU14_RECIPE_5_3
		//215	PSU-14.Global.PSU14_RECIPE_5_4
		//216	PSU-14.Global.MAX_MASS_1
		//217	PSU-14.Global.MAX_MASS_2
		//218	PSU-14.Global.MAX_MASS_3
		//219	PSU-14.Global.MAX_MASS_4
		//220	PSU-14.Global.MAX_MASS_5
		//221	PSU-14.Global.FREE_MASS_1
		//222	PSU-14.Global.FREE_MASS_2
		//223	PSU-14.Global.FREE_MASS_3
		//224	PSU-14.Global.FREE_MASS_4
		//225	PSU-14.Global.FREE_MASS_5
		//230	PSU-14.Global.PSU14_UNMODIFIED_MASS_1
		//231	PSU-14.Global.PSU14_UNMODIFIED_MASS_2
		//232	PSU-14.Global.PSU14_UNMODIFIED_MASS_3
		//233	PSU-14.Global.PSU14_UNMODIFIED_MASS_4
    $scales_array = array('ScalesNumber'=>array('1'=>'610','2'=>'612','3'=>'613','4'=>'611'),
                          'MaxMass'=>array('1'=>'50','2'=>'1000','3'=>'1000','4'=>'50')); //массив с человеческими именами тегов
    $changes_dates = array(); //массив с датами изменения рецепта
    $tmp_array = array(); //временный массив
    $recipe_record = array(); //одна строка рецепта
    $recipe_data = array();//один рецепт
    $data = array('caption'=>'', 'column_titles'=>'','data'=>'','footer'=>'');//массив рецептов
    $result_array = array();
    $date_begin = new DateTime($arg_dt_begin);
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "TPL-SERVER";
		$dbname = "pfu";
		$dbuser = "sa";
		$dbpass = "tpl";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
//находим даты изменения рецептов за указанный период
    $sql = "SELECT DT
            FROM RecipeOtchet
            WHERE (DT > :dt_begin) AND (DT < :dt_end) AND (Quality = 192)
            GROUP BY DT
            ORDER BY DT;";
 		$result = $db->prepare($sql);
   	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
   	$changes_dates = $result->fetchAll(PDO::FETCH_ASSOC);
//для каждой даты прогоняем запрос и формируем наборы значений
    foreach ($changes_dates as $str_num=>$str_date)
    {
//      print_r($str_date);
      $sql = "SELECT *
              FROM RecipeOtchet
              WHERE (DT = :dt_change) AND (Quality = 192)
              ORDER BY ID;";
   		$result = $db->prepare($sql);
     	$result->execute(array(':dt_change'=>$str_date['DT']));
     	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//      print_r($tmp_array);
      $tmp_dt = new DateTime($str_date['DT']);
     	$recipe_record['DT'] = $tmp_dt->format('d.m.Y H:i:s');
      for($i = 0; $i<=15; $i+=4)
      {
        if ($tmp_array[$i]['TagValue']<90)
        {
         	$recipe_record['ScalesNumber'] = $scales_array['ScalesNumber'][$tmp_array[$i]['TagValue']];
          $max_mass = $scales_array['MaxMass'][$tmp_array[$i]['TagValue']];
         	$recipe_record['MixingTime'] = $tmp_array[$i+1]['TagValue'];
         	$recipe_record['FreeMass'] = round(($max_mass*$tmp_array[$i+3]['TagValue']/4096)-($max_mass*$tmp_array[$i+2]['TagValue']/4096), 1);
       	  $recipe_record['FullMass'] = round($max_mass*$tmp_array[$i+3]['TagValue']/4096, 1);
          array_push($recipe_data, $recipe_record);
        }
      }
      if (count($recipe_data)>0)//проверка на пустой рецепт
      {
        $data['caption'] = $tmp_dt->format('d.m.Y H:i:s');
        $data['column_titles'] = array('Время',
                                           '№ весов',
                                           'Время смешивания, с',
                                           'Свободный столб, кг',
                                           'Масса, кг');
        $data['data'] = $recipe_data;
        $data['footer'] = '';
        array_push($result_array, $data);
      }
      $recipe_data = array();//обнуляем массив рецепта
    }
//    print_r(count($data));
    return($result_array);
  }
//------------------------------------------------------------------------------
  public function cshi_pfu_press5_recipe_archive($arg_dt_begin, $arg_dt_end)
  {
    $scales_array = array('ScalesNumber'=>array('1'=>'610','2'=>'612','3'=>'613','4'=>'611'),
                          'MaxMass'=>array('1'=>'50','2'=>'1000','3'=>'1000','4'=>'50')); //массив с человеческими именами тегов
    $changes_dates = array(); //массив с датами изменения рецепта
    $tmp_array = array(); //временный массив
    $recipe_record = array(); //одна строка рецепта
    $recipe_data = array();//один рецепт
    $data = array('caption'=>'', 'column_titles'=>'','data'=>'','footer'=>'');//массив рецептов
    $result_array = array();
    $date_begin = new DateTime($arg_dt_begin);
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "press5-server";
		$dbname = "press_5";
		$dbuser = "sa";
		$dbpass = "admintp";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT *
            FROM recipe
            WHERE (DT > :dt_begin) AND (DT < :dt_end)
            ORDER BY DT;";
 		$result = $db->prepare($sql);
   	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
   	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
		//для каждой даты прогоняем запрос и формируем наборы значений
    foreach ($tmp_array as $str_num=>$str_data)
    {
      $tmp_dt = new DateTime($str_data['DT']);

//		$scales_num = array(array('642', 'Шамот'), array('643', 'Шамот'), array('640', 'Песок и пыль'), array('641', 'Глина'), array('596', 'Шликер'));
      
			$recipe_record['ScalesNumber'] = "642";
			$recipe_record['Materiall'] = "Шамот";
			$recipe_record['Time'] = round($str_data['Time_1']);
			$recipe_record['Mass'] = round($str_data['Weight_1'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "643";
			$recipe_record['Materiall'] = "Шамот";
			$recipe_record['Time'] = round($str_data['Time_2']);
			$recipe_record['Mass'] = round($str_data['Weight_2'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "640";
			$recipe_record['Materiall'] = "Песок и пыль";
			$recipe_record['Time'] = round($str_data['Time_3']);
			$recipe_record['Mass'] = round($str_data['Weight_3'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "641";
			$recipe_record['Materiall'] = "Глина";
			$recipe_record['Time'] = round($str_data['Time_4']);
			$recipe_record['Mass'] = round($str_data['Weight_4'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "596";
			$recipe_record['Materiall'] = "Шликер";
			$recipe_record['Time'] = round($str_data['Time_5']);
			$recipe_record['Mass'] = round($str_data['Weight_5'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "Смеситель";
			$recipe_record['Time'] = round($str_data['Mixer_time']);
			$recipe_record['Materiall'] = NULL;
			$recipe_record['Mass'] = NULL;
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "Имя";
			$recipe_record['Time'] = iconv("Windows-1251", "UTF-8", $str_data['Name']);
			$recipe_record['Materiall'] = NULL;
			$recipe_record['Mass'] = NULL;
			array_push($recipe_data, $recipe_record);

      if (count($recipe_data)>0)//проверка на пустой рецепт
      {
        $data['caption'] = $tmp_dt->format('d.m.Y H:i:s');
        $data['column_titles'] = array('№ весов', 'Материалл',
																			 'Время выгрузки, с',
                                       'Масса, кг');
        $data['data'] = $recipe_data;
        $data['footer'] = '';
        array_push($result_array, $data);
      }
      $recipe_data = array();//обнуляем массив рецепта
    }
   // print_r($result_array);
    return($result_array);
  }
//------------------------------------------------------------------------------
  public function cshi_pfu_press3_recipe_archive($arg_dt_begin, $arg_dt_end)
  {
    $scales_array = array('ScalesNumber'=>array('1'=>'620','2'=>'621','3'=>'622','4'=>'623'),
                          'MaxMass'=>array('1'=>'50','2'=>'1000','3'=>'1000','4'=>'50')); //массив с человеческими именами тегов
    $changes_dates = array(); //массив с датами изменения рецепта
    $tmp_array = array(); //временный массив
    $recipe_record = array(); //одна строка рецепта
    $recipe_data = array();//один рецепт
    $data = array('caption'=>'', 'column_titles'=>'','data'=>'','footer'=>'');//массив рецептов
    $result_array = array();
    $date_begin = new DateTime($arg_dt_begin);
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "press5-server";
		$dbname = "press_3";
		$dbuser = "sa";
		$dbpass = "admintp";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT *
            FROM recipe
            WHERE (DT > :dt_begin) AND (DT < :dt_end)
            ORDER BY DT;";
 		$result = $db->prepare($sql);
   	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
   	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
		//для каждой даты прогоняем запрос и формируем наборы значений
    foreach ($tmp_array as $str_num=>$str_data)
    {
      $tmp_dt = new DateTime($str_data['DT']);

//		$scales_num = array(array('642', 'Шамот'), array('643', 'Шамот'), array('640', 'Песок и пыль'), array('641', 'Глина'), array('596', 'Шликер'));
      
			$recipe_record['ScalesNumber'] = "620";
			$recipe_record['Materiall'] = "Шамот";
			$recipe_record['Time'] = round($str_data['Time_1']);
			$recipe_record['Mass'] = round($str_data['Weight_1'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "621";
			$recipe_record['Materiall'] = "Шамот";
			$recipe_record['Time'] = round($str_data['Time_2']);
			$recipe_record['Mass'] = round($str_data['Weight_2'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "622";
			$recipe_record['Materiall'] = "Глина";
			$recipe_record['Time'] = round($str_data['Time_3']);
			$recipe_record['Mass'] = round($str_data['Weight_3'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "623";
			$recipe_record['Materiall'] = "Добавки";
			$recipe_record['Time'] = round($str_data['Time_4']);
			$recipe_record['Mass'] = round($str_data['Weight_4'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "594";
			$recipe_record['Materiall'] = "Шликер";
			$recipe_record['Time'] = round($str_data['Time_5']);
			$recipe_record['Mass'] = round($str_data['Weight_5'],1);
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "Смеситель";
			$recipe_record['Time'] = round($str_data['Mixer_time']);
			$recipe_record['Materiall'] = NULL;
			$recipe_record['Mass'] = NULL;
			array_push($recipe_data, $recipe_record);
			$recipe_record['ScalesNumber'] = "Имя";
			$recipe_record['Time'] = iconv("Windows-1251", "UTF-8", $str_data['Name']);
			$recipe_record['Materiall'] = NULL;
			$recipe_record['Mass'] = NULL;
			array_push($recipe_data, $recipe_record);

      if (count($recipe_data)>0)//проверка на пустой рецепт
      {
        $data['caption'] = $tmp_dt->format('d.m.Y H:i:s');
        $data['column_titles'] = array('№ весов', 'Материалл',
																			 'Время выгрузки, с',
                                       'Масса, кг');
        $data['data'] = $recipe_data;
        $data['footer'] = '';
        array_push($result_array, $data);
      }
      $recipe_data = array();//обнуляем массив рецепта
    }
    //print_r($result_array);
    return($result_array);
  }
//------------------------------------------------------------------------------
public function cshi_pfu_press_line_2_balancing_archive($arg_dt_begin, $arg_dt_end, $arg_scales_number, $arg_zone, $arg_deviation)
{    set_time_limit(600);
	$max_mass = array('610'=>'50','611'=>'50','612'=>'1000','613'=>'1000'); //массив с максимальными значениями масс
    $batch_numbers = array(); //массив с номерами рецептов
    $tmp_array = array(); //временный массив
    $batch_record = array(); //одна строка замеса
    $batch_data = array();//один замес
	$distribution = array();
	$outside_zone_count=0;
	$inside_zone_count=0;
    $report_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');//массив рецептов
	$analysis_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');
    $data = array('caption'=>'', 'column_titles'=>'','table_data'=>'','footer'=>'');//массив рецептов
    $date_begin = new DateTime($arg_dt_begin);
	$outside_zone_count=0;
	$inside_zone_count=0;
	$total_zone_count=0;
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "TPL-SERVER";
		$dbname = "pfu";
		$dbuser = "sa";
		$dbpass = "tpl";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
//находим даты изменения рецептов за указанный период
    $sql = "SELECT zames
            FROM BalanceReport
            WHERE (begin_dosage > :dt_begin) AND (begin_dosage < :dt_end)
            GROUP BY zames
            ORDER BY zames;";
//	print_r($sql);echo('<BR>');
/*	echo($arg_dt_begin);echo('<BR>');
	echo($arg_dt_end);echo('<BR>');
	echo($arg_scales_number);echo('<BR>');
	echo($arg_zone);echo('<BR>');
	echo($arg_deviation);echo('<BR>');*/
 	$result = $db->prepare($sql);
   	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
   	$batch_numbers = $result->fetchAll(PDO::FETCH_ASSOC);
   	$scales_condition = ($arg_scales_number=='ALL')?'':'AND (vesi = '.$arg_scales_number.')';//дополнительное условие в запросе для фильтра весов
//	$scales_condition = ($arg_deviation=='ALL')?'':' AND (round(ves - ves_z,2) = '.$arg_deviation.')';
//для каждого замеса прогоняем запрос и формируем наборы значений

    foreach ($batch_numbers as $str_num=>$batch_str)
    {
      $sql = "SELECT zames, vesi, material, ves_z, ves, begin_dosage, end_dosage
              FROM BalanceReport
              WHERE (zames = :batch_number) AND (begin_dosage > :dt_begin) AND (begin_dosage < :dt_end) ".$scales_condition.";";
		//print_r($sql);echo('<BR>');
		$result = $db->prepare($sql);
     	$result->execute(array(':batch_number'=>$batch_str['zames'],
     	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                         ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
     	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $key=>$value)
      {
        //Заполнение таблицы
		if ($value['vesi'] == '610' || $value['vesi'] == '611')
		{
			$tmp_date = new DateTime($value['begin_dosage']);
			$tmp_date_str = $tmp_date->format('d.m.Y H:i:s');
			$zames_number = $value['zames'];
			$batch_data[$key]['zames'] = $zames_number;
			$batch_data[$key]['begin_dosage'] = $tmp_date_str;
			$batch_data[$key]['vesi'] = $value['vesi'];
			$batch_data[$key]['material'] = iconv("Windows-1251", "UTF-8", $value['material']);
			$batch_data[$key]['ves_z'] = round($value['ves_z'],2);
			$batch_data[$key]['ves'] = round($value['ves'],2);
			$batch_data[$key]['deviation_absolute'] = round($value['ves'] - $value['ves_z'],2);
			if ($value['ves_z']!=0)
				$batch_data[$key]['deviation_relative'] = round(100*($value['ves'] - $value['ves_z'])/$value['ves_z'],2);
			//Анализ отклонений
			if ($batch_data[$key]['deviation_absolute']>$arg_zone)
				$outside_zone_count++;
			else if ($batch_data[$key]['deviation_absolute']<-$arg_zone)
				$inside_zone_count++;
			$total_zone_count++;
			$distribution[$batch_data[$key]['vesi']][sprintf("%.2f",$value['ves'] - $value['ves_z'])]++;
			//Генерация ссылки на суботчет
			//$batch_data[$key]['deviation_absolute']='<a href="/ASUTP/localmenu/cshireports_result/report_type=balancing_archive&date1='.str_replace('.','-',$arg_dt_begin).'&date2='.str_replace('.','-',$arg_dt_end).'&scale_number='.$arg_scales_number.'&zone='.str_replace('.','_',$arg_zone).'&deviation='.round($batch_data[$key]['deviation_absolute']*4096/$max_mass[$value['vesi']]).'">'.$batch_data[$key]['deviation_absolute'].'</a>';
			array_push($report_array['data'], $batch_data[$key]);
		}
		if ($value['vesi'] == '612' || $value['vesi'] == '613')
		{
			$tmp_date = new DateTime($value['begin_dosage']);
			$tmp_date_str = $tmp_date->format('d.m.Y H:i:s');
			$zames_number = $value['zames'];
			$batch_data[$key]['zames'] = $zames_number;
			$batch_data[$key]['begin_dosage'] = $tmp_date_str;
			$batch_data[$key]['vesi'] = $value['vesi'];
			$batch_data[$key]['material'] = iconv("Windows-1251", "UTF-8", $value['material']);
			$batch_data[$key]['ves_z'] = round($value['ves_z'],0);
			$batch_data[$key]['ves'] = round($value['ves'],0);
			$batch_data[$key]['deviation_absolute'] = round($value['ves'] - $value['ves_z'],0);
			if ($value['ves_z']!=0)
				$batch_data[$key]['deviation_relative'] = round(100*($value['ves'] - $value['ves_z'])/$value['ves_z'],2);
			//Анализ отклонений
			if ($batch_data[$key]['deviation_absolute']>$arg_zone)
				$outside_zone_count++;
			else if ($batch_data[$key]['deviation_absolute']<-$arg_zone)
				$inside_zone_count++;
			$total_zone_count++;
			$distribution[$batch_data[$key]['vesi']][sprintf("%.2f",$value['ves'] - $value['ves_z'])]++;
			//Генерация ссылки на суботчет
			//$batch_data[$key]['deviation_absolute']='<a href="/ASUTP/localmenu/cshireports_result/report_type=balancing_archive&date1='.str_replace('.','-',$arg_dt_begin).'&date2='.str_replace('.','-',$arg_dt_end).'&scale_number='.$arg_scales_number.'&zone='.str_replace('.','_',$arg_zone).'&deviation='.round($batch_data[$key]['deviation_absolute']*4096/$max_mass[$value['vesi']]).'">'.$batch_data[$key]['deviation_absolute'].'</a>';
			array_push($report_array['data'], $batch_data[$key]);
		}		
      }
      if (count($tmp_array)>0)//проверка на пустой рецепт
      {
//        $data['table_data'] = $batch_data;
      }
      $data = array();//обнуляем массив рецепта
      $batch_data = array();
    }
	//print_r($distribution);
//    print_r($data);
//    $result_array['data'] = $data;
//    $result_array['caption'] = 'Отчёт ';
    $report_array['column_titles'] = array('№ замеса',
										   'Начало замеса',
                                           '№ весов',
                                           'Материал',
                                           'Заданая масса, кг',
                                           'Фактическая масса, кг',
                                           'Отклонение кг',
                                           'Отклонение %',);
	$analysis_array['data']=array(array('0'=>$outside_zone_count, '1'=>$inside_zone_count));
	$analysis_array['column_titles']=array('Количество отклонений > '.$arg_zone.' кг.','Количество отклонений < -'.$arg_zone.' кг.');
	$analysis_array['footer']='Всего записей - '.($total_zone_count);
	if ($arg_scales_number!='ALL')
	{
		$result_array[]=$analysis_array;
	}	
	arsort($distribution);
	foreach($distribution as $scales_num=>$distribution_table)
	{
		ksort($distribution_table);
		foreach($distribution_table as $key=>$value)
		{
			$key='<a href="/ASUTP/localmenu/cshireports_result/report_type=line_2_balancing_archive&date1='.str_replace('.','-',$arg_dt_begin).'&date2='.str_replace('.','-',$arg_dt_end).'&scale_number='.$scales_num.'&zone='.str_replace('.','_',$arg_zone).'&deviation='.str_replace('.','_',round($key,2)).'">'.round($key,2).'</a>';
			$distribution_array['data'][]=array($key,$value);
		}
		$distribution_array['column_titles']=array('Отклонение, кг','Количество вхождений');
		$distribution_array['caption']='Весы № '.$scales_num;
		if ($arg_scales_number!='ALL')
		{
			$result_array[]=$distribution_array;
		}
		$distribution_array=array();
	}
	if (count($report_array[data])!=0)
	{
		$result_array[]=$report_array;
	}
    return($result_array);
}
//------------------------------------------------------------------------------
public function cshi_pfu_press_line_5_balancing_archive($arg_dt_begin, $arg_dt_end, $arg_scales_number, $arg_zone, $arg_deviation)
{
    set_time_limit(600);
		$report=array();
		$distribution=array();
    $report_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');//массив рецептов
		//$analysis_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');
    $data = array('caption'=>'', 'column_titles'=>'','table_data'=>'','footer'=>'');//массив рецептов
    $date_begin = new DateTime($arg_dt_begin);
		$outside_zone_count=0;
		$inside_zone_count=0;
		$total_zone_count=0;
		$total_task=0;
		$total_weight=0;
		$recipe_name = "";
		$scales_num = array(array('642', 'Шамот'), array('643', 'Шамот'), array('641', 'Песок и пыль'), array('640', 'Глина'), array('596', 'Шликер'));
		$scales_material = 0;
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "press5-server";
		$dbname = "press_5";
		$dbuser = "sa";
		$dbpass = "admintp";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT *
						FROM dosage
						WHERE (TS > :dt_begin) AND (TS < :dt_end)
						ORDER BY TS;";
		$result = $db->prepare($sql);
		$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
													 ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
		$batches = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach ($batches as $num=>$batch) {
			$tsstring=new DateTime($batch["ts"]);
			for ($i=1;$i<=5;$i++) {
				if (($arg_scales_number==$i OR $arg_scales_number=='ALL') AND $batch["task_mass_".$i]>0) {
					$task_weight = $batch["task_mass_".$i];
					$current_weight = $batch["fact_mass_".$i];
					//Формирование записи отчета
					$record["id"]=$batch["id"];
					$record["Time"]=$tsstring->format('d.m.Y H:i:s');
					$record["Scales"]=$scales_num[$i-1][0];
					$record["Material"]=$scales_num[$i-1][1];
					$record["Task"]=round($task_weight,1);
					$record["Weight"]=round($current_weight,1);
					$record["Devabs"]=round($current_weight-$task_weight,1);
					$record["Devrel"]=$task_weight!=0 ? sprintf("%.1f",$record["Devabs"]*100/$task_weight) : "&nbsp";
					$record["name"]=iconv("Windows-1251","UTF-8", $batch["recipe_name"]);
					$total_task+=$task_weight;
					$total_weight+=$current_weight;
					//Отклонения
					$total_zone_count++;
					if ($record["Devabs"]>$arg_zone)
						$outside_zone_count++;
					else if ($record["Devabs"]<-$arg_zone)
						$inside_zone_count++;
					$distribution[$i][sprintf("%.2f",$record["Devabs"])]++;
					//Добавление записи в отчет
					if ($arg_deviation=="ALL" or $arg_deviation==$record["Devabs"])
						array_push($report, $record);
				}
			}
		}
		//Сортируем отчет по дате
		// function compare ($v1, $v2) {
			// if ($v1["Time"] == $v2["Time"]) return 0;
			// return ($v1["Time"] < $v2["Time"])? -1: 1;
		// }
		// usort($report, "compare");
		
		//Формируем массив отчета
		$report_array['data']=$report;
		$report_array['column_titles'] = array('№ замеса','Время выгрузки смесителя',
                                           '№ весов',
																					 'Материал',
                                           'Заданая масса, кг',
                                           'Фактическая масса, кг',
                                           'Отклонение кг',
                                           'Отклонение %',
										   'Имя рецепта');
		
	$analysis_array['data']=array(array('0'=>$outside_zone_count, '1'=>$inside_zone_count));
	$analysis_array['column_titles']=array('Количество отклонений > '.$arg_zone.' кг.','Количество отклонений < -'.$arg_zone.' кг.');
	$analysis_array['footer']='Всего записей - '.($total_zone_count);
	if ($arg_scales_number!='ALL')
	{
		$result_array[]=$analysis_array;
	}	
	arsort($distribution);
	foreach($distribution as $scales_num=>$distribution_table)
	{
		ksort($distribution_table);
		foreach($distribution_table as $key=>$value)
		{
			$key='<a href="/ASUTP/localmenu/cshireports_result/report_type=line_5_balancing_archive&date1='.str_replace('.','-',$arg_dt_begin).'&date2='.str_replace('.','-',$arg_dt_end).'&scale_number='.$scales_num.'&zone='.str_replace('.','_',$arg_zone).'&deviation='.str_replace('.','_',round($key,2)).'">'.round($key,2).'</a>';
			$distribution_array['data'][]=array($key,$value);
		}
		$distribution_array['column_titles']=array('Отклонение, кг','Количество вхождений');
		$distribution_array['caption']='Весы № '.$scales_num;
		if ($arg_scales_number!='ALL')
		{
			$result_array[]=$distribution_array;
		}
		$distribution_array=array();
	}
	if (count($report_array[data])!=0)
	{
		$result_array[]=$report_array;
	}
	return($result_array);
}
//------------------------------------------------------------------------------
public function cshi_pfu_press_line_5_balancing_archive2($arg_dt_begin, $arg_dt_end, $arg_scales_number)
{
    set_time_limit(600);
		$report=array();
		$batches = array();
    $date_begin = new DateTime($arg_dt_begin);
		$total_weight=0;
		$recipe_name = "";
		$scales_num = array(array('642', 'Шамот','PRESS_5.MDB_IN_A.MDB_IN_A_09'), array('643', 'Шамот','PRESS_5.MDB_IN_A.MDB_IN_A_29'), array('640', 'Песок и пыль','PRESS_5.MDB_IN_A.MDB_IN_A_49'), array('641', 'Глина','PRESS_5.MDB_IN_A.MDB_IN_A_69'), array('596', 'Шликер','PRESS_5.MDB_IN_A.MDB_IN_A_89'));
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "press5-server";
		$dbname = "press_5";
		$dbuser = "sa";
		$dbpass = "admintp";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//Определение предыдущего значения
		$sql = "SELECT TOP(1) [Value]
						FROM mbus_in_a
						WHERE DATEADD(hh,5,TS) <= :dt_begin and (quality=192 or quality=86) and name='".$scales_num[$arg_scales_number-1][2]."'
						ORDER BY TS DESC;";
		$result = $db->prepare($sql);
		$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s')));
		$batches = $result->fetchAll(PDO::FETCH_ASSOC);
		$prev_value=$batches[0]["Value"];
		//Основной отчет
		$sql = "SELECT [TS],[Value]
						FROM mbus_in_a
						WHERE (DATEADD(hh,5,TS) > :dt_begin) AND (DATEADD(hh,5,TS) <= :dt_end) and (quality=192 or quality=86) and name='".$scales_num[$arg_scales_number-1][2]."'
						ORDER BY TS;";
		$result = $db->prepare($sql);
		$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
													 ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
		$batches = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach ($batches as $num=>$batch) {
			$tsstring=new DateTime($batch["TS"]);
			$tsstring->add(new DateInterval('PT5H'));
			if ($prev_value>0 and $prev_value<>$batch["Value"]) {
				//Формирование записи отчета
				$record["Time"]=$tsstring->format('d.m.Y H:i:s');
				$record["Scales"]=$scales_num[$arg_scales_number-1][0];
				$record["Material"]=$scales_num[$arg_scales_number-1][1];
				$record["Value"]=round($batch["Value"]-$prev_value,1);
				$total_weight+=$batch["Value"]-$prev_value;
				//Добавление записи в отчет
				array_push($report, $record);
			}
			$prev_value=$batch["Value"];
		}
		//Формируем массив отчета
		$report_array['data']=$report;
		$report_array['column_titles'] = array('Время','Весы','Материал','Масса, кг');
		$report_array['footer'] = 'Сумма: '.round($total_weight,1).' кг';
		return($report_array);
}
//------------------------------------------------------------------------------
public function cshi_pfu_press_line_3_balancing_archive($arg_dt_begin, $arg_dt_end, $arg_scales_number, $arg_zone, $arg_deviation)
{
    set_time_limit(600);
		$report=array();
		$distribution=array();
    $report_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');//массив рецептов
		//$analysis_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');
    $data = array('caption'=>'', 'column_titles'=>'','table_data'=>'','footer'=>'');//массив рецептов
    $date_begin = new DateTime($arg_dt_begin);
		$outside_zone_count=0;
		$inside_zone_count=0;
		$total_zone_count=0;
		$total_task=0;
		$total_weight=0;
		$recipe_name = "";
		$scales_num = array(array('620', 'Добавки'), array('621', 'Глина'), array('622', 'Шамот А'), array('623', 'Шамот Б'), array('594', 'Шликер'));
		$scales_material = 0;
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "press5-server";
		$dbname = "press_3";
		$dbuser = "sa";
		$dbpass = "admintp";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT *
						FROM dosage
						WHERE (TS > :dt_begin) AND (TS < :dt_end)
						ORDER BY TS;";
		$result = $db->prepare($sql);
		$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
													 ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
		$batches = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach ($batches as $num=>$batch) {
			$tsstring=new DateTime($batch["ts"]);
			for ($i=1;$i<=5;$i++) {
				if (($arg_scales_number==$i OR $arg_scales_number=='ALL') AND $batch["task_mass_".$i]>0) {
					$task_weight = $batch["task_mass_".$i];
					$current_weight = $batch["fact_mass_".$i];
					//Формирование записи отчета
					$record["id"]=$batch["id"];
					$record["Time"]=$tsstring->format('d.m.Y H:i:s');
					$record["Scales"]=$scales_num[$i-1][0];
					$record["Material"]=$scales_num[$i-1][1];
					$record["Task"]=round($task_weight,1);
					$record["Weight"]=round($current_weight,1);
					$record["Devabs"]=round($current_weight-$task_weight,1);
					$record["Devrel"]=$task_weight!=0 ? sprintf("%.1f",$record["Devabs"]*100/$task_weight) : "&nbsp";
					$record["name"]=iconv("Windows-1251","UTF-8", $batch["recipe_name"]);
					$total_task+=$task_weight;
					$total_weight+=$current_weight;
					//Отклонения
					$total_zone_count++;
					if ($record["Devabs"]>$arg_zone)
						$outside_zone_count++;
					else if ($record["Devabs"]<-$arg_zone)
						$inside_zone_count++;
					$distribution[$i][sprintf("%.2f",$record["Devabs"])]++;
					//Добавление записи в отчет
					if ($arg_deviation=="ALL" or $arg_deviation==$record["Devabs"])
						array_push($report, $record);
				}
			}
		}
		//Сортируем отчет по дате
		// function compare ($v1, $v2) {
			// if ($v1["Time"] == $v2["Time"]) return 0;
			// return ($v1["Time"] < $v2["Time"])? -1: 1;
		// }
		// usort($report, "compare");
		
		//Формируем массив отчета
		$report_array['data']=$report;
		$report_array['column_titles'] = array('№ замеса','Время выгрузки смесителя',
                                           '№ весов',
																					 'Материал',
                                           'Заданая масса, кг',
                                           'Фактическая масса, кг',
                                           'Отклонение кг',
                                           'Отклонение %',
										   'Имя рецепта');
		
	$analysis_array['data']=array(array('0'=>$outside_zone_count, '1'=>$inside_zone_count));
	$analysis_array['column_titles']=array('Количество отклонений > '.$arg_zone.' кг.','Количество отклонений < -'.$arg_zone.' кг.');
	$analysis_array['footer']='Всего записей - '.($total_zone_count);
	if ($arg_scales_number!='ALL')
	{
		$result_array[]=$analysis_array;
	}	
	arsort($distribution);
	foreach($distribution as $scales_num=>$distribution_table)
	{
		ksort($distribution_table);
		foreach($distribution_table as $key=>$value)
		{
			$key='<a href="/ASUTP/localmenu/cshireports_result/report_type=line_3_balancing_archive&date1='.str_replace('.','-',$arg_dt_begin).'&date2='.str_replace('.','-',$arg_dt_end).'&scale_number='.$scales_num.'&zone='.str_replace('.','_',$arg_zone).'&deviation='.str_replace('.','_',round($key,2)).'">'.round($key,2).'</a>';
			$distribution_array['data'][]=array($key,$value);
		}
		$distribution_array['column_titles']=array('Отклонение, кг','Количество вхождений');
		$distribution_array['caption']='Весы № '.$scales_num;
		if ($arg_scales_number!='ALL')
		{
			$result_array[]=$distribution_array;
		}
		$distribution_array=array();
	}
	if (count($report_array[data])!=0)
	{
		$result_array[]=$report_array;
	}
	return($result_array);
}
//------------------------------------------------------------------------------
public function cshi_pfu_press_line_3_balancing_archive2($arg_dt_begin, $arg_dt_end, $arg_scales_number)
{
    set_time_limit(600);
		$report=array();
		$batches = array();
    $date_begin = new DateTime($arg_dt_begin);
		$total_weight=0;
		$recipe_name = "";
		$scales_num = array(array('620', 'Шамот','PRESS_3.MDB_IN_A.MDB_IN_A_09'), array('621', 'Шамот','PRESS_3.MDB_IN_A.MDB_IN_A_29'), array('622', 'Глина','PRESS_3.MDB_IN_A.MDB_IN_A_49'), array('623', 'Добавки','PRESS_3.MDB_IN_A.MDB_IN_A_69'), array('594', 'Шликер','PRESS_3.MDB_IN_A.MDB_IN_A_89'));
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "press5-server";
		$dbname = "press_3";
		$dbuser = "sa";
		$dbpass = "admintp";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//Определение предыдущего значения
		$sql = "SELECT TOP(1) [Value]
						FROM mbus_in_a
						WHERE DATEADD(hh,5,TS) <= :dt_begin and (quality=192 or quality=86) and name='".$scales_num[$arg_scales_number-1][2]."'
						ORDER BY TS DESC;";
		$result = $db->prepare($sql);
		$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s')));
		$batches = $result->fetchAll(PDO::FETCH_ASSOC);
		$prev_value=$batches[0]["Value"];
		//Основной отчет
		$sql = "SELECT [TS],[Value]
						FROM mbus_in_a
						WHERE (DATEADD(hh,5,TS) > :dt_begin) AND (DATEADD(hh,5,TS) <= :dt_end) and (quality=192 or quality=86) and name='".$scales_num[$arg_scales_number-1][2]."'
						ORDER BY TS;";
		$result = $db->prepare($sql);
		$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
													 ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
		$batches = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach ($batches as $num=>$batch) {
			$tsstring=new DateTime($batch["TS"]);
			$tsstring->add(new DateInterval('PT5H'));
			if ($prev_value>0 and $prev_value<>$batch["Value"]) {
				//Формирование записи отчета
				$record["Time"]=$tsstring->format('d.m.Y H:i:s');
				$record["Scales"]=$scales_num[$arg_scales_number-1][0];
				$record["Material"]=$scales_num[$arg_scales_number-1][1];
				$record["Value"]=round($batch["Value"]-$prev_value,1);
				$total_weight+=$batch["Value"]-$prev_value;
				//Добавление записи в отчет
				array_push($report, $record);
			}
			$prev_value=$batch["Value"];
		}
		//Формируем массив отчета
		$report_array['data']=$report;
		$report_array['column_titles'] = array('Время','Весы','Материал','Масса, кг');
		$report_array['footer'] = 'Сумма: '.round($total_weight,1).' кг';
		return($report_array);
}
//------------------------------------------------------------------------------
	public function cshi_pfu_press_line_5_balancing_archive_old($arg_dt_begin, $arg_dt_end, $arg_scales_number, $arg_zone, $arg_deviation)
  {
    set_time_limit(600);
		$scales_state = array('1'=>'PRESS_5.SCALES.SCALES_STATE_1','2'=>'PRESS_5.SCALES.SCALES_STATE_2','3'=>'PRESS_5.SCALES.SCALES_STATE_3','4'=>'PRESS_5.SCALES.SCALES_STATE_4','5'=>'PRESS_5.SCALES.SCALES_STATE_5');
    $batch_numbers = array(); //массив с номерами рецептов
    $tmp_array = array(); //временный массив
    $batch_record = array(); //одна строка замеса
    $batch_data = array();//один замес
		$distribution = array();
		$outside_zone_count=0;
		$inside_zone_count=0;
    $report_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');//массив рецептов
		$analysis_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');
    $data = array('caption'=>'', 'column_titles'=>'','table_data'=>'','footer'=>'');//массив рецептов
    $date_begin = new DateTime($arg_dt_begin);
		$outside_zone_count=0;
		$inside_zone_count=0;
		$total_zone_count=0;
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
      $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "TUNNEL-SERVER";
		$dbname = "press_5_press_5";
		$dbuser = "sa";
		$dbpass = "ogneupor";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//Весы 1
		if ($arg_scales_number=1 OR $arg_scales_number='ALL')
		{
			$sql = "SELECT TS, Value
							FROM analog
							WHERE (begin_dosage > :dt_begin) AND (begin_dosage < :dt_end) AND Name='".$scales_state[$arg_scales_number]."' AND quality=192 AND Value=2
							ORDER BY TS;";
			$result = $db->prepare($sql);
			$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
														 ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
			$batch_numbers = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		
		
		
   	$scales_condition = ($arg_scales_number=='ALL')?'':'AND (vesi = '.$arg_scales_number.')';//дополнительное условие в запросе для фильтра весов
	$scales_condition .= ($arg_deviation=='ALL')?'':' AND (ves - ves_z = '.$arg_deviation.')';
//для каждого замеса прогоняем запрос и формируем наборы значений

    foreach ($batch_numbers as $str_num=>$batch_str)
    {
      $sql = "SELECT zames, vesi, material, ves_z, ves, begin_dosage, end_dosage
              FROM BalanceReport
              WHERE (zames = :batch_number) AND (begin_dosage > :dt_begin) AND (begin_dosage < :dt_end) ".$scales_condition.";";
		//print_r($sql);echo('<BR>');
		$result = $db->prepare($sql);
     	$result->execute(array(':batch_number'=>$batch_str['zames'],
     	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                         ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
     	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $key=>$value)
      {
        //Заполнение таблицы
		$tmp_date = new DateTime($value['end_dosage']);
        $tmp_date_str = $tmp_date->format('d.m.Y H:i:s');
        $zames_number = $value['zames'];
        $batch_data[$key]['zames'] = $zames_number;
		$batch_data[$key]['end_dosage'] = $tmp_date_str;
        $batch_data[$key]['vesi'] = $value['vesi'];
        $batch_data[$key]['material'] = iconv("Windows-1251", "UTF-8", $value['material']);
        $batch_data[$key]['ves_z'] = round($max_mass[$value['vesi']]*$value['ves_z']/4096,2);
        $batch_data[$key]['ves'] = round($max_mass[$value['vesi']]*$value['ves']/4096,2);
        $batch_data[$key]['deviation_absolute'] = round($batch_data[$key]['ves'] - $batch_data[$key]['ves_z'],2);
        $batch_data[$key]['deviation_relative'] = round(100*$batch_data[$key]['deviation_absolute']/$batch_data[$key]['ves_z'],2);
		//Анализ отклонений
		if ($batch_data[$key]['deviation_absolute']>$arg_zone)
			$outside_zone_count++;
		else if ($batch_data[$key]['deviation_absolute']<-$arg_zone)
			$inside_zone_count++;
		$total_zone_count++;
		$distribution[$batch_data[$key]['vesi']][sprintf("%.2f",$batch_data[$key]['deviation_absolute'])]++;
		//Генерация ссылки на суботчет
		//$batch_data[$key]['deviation_absolute']='<a href="/ASUTP/localmenu/cshireports_result/report_type=balancing_archive&date1='.str_replace('.','-',$arg_dt_begin).'&date2='.str_replace('.','-',$arg_dt_end).'&scale_number='.$arg_scales_number.'&zone='.str_replace('.','_',$arg_zone).'&deviation='.round($batch_data[$key]['deviation_absolute']*4096/$max_mass[$value['vesi']]).'">'.$batch_data[$key]['deviation_absolute'].'</a>';
        array_push($report_array['data'], $batch_data[$key]);
      }
      if (count($tmp_array)>0)//проверка на пустой рецепт
      {
//        $data['table_data'] = $batch_data;
      }
      $data = array();//обнуляем массив рецепта
      $batch_data = array();
    }
	//print_r($distribution);
//    print_r($data);
//    $result_array['data'] = $data;
//    $result_array['caption'] = 'Отчёт ';
    $report_array['column_titles'] = array('№ замеса',
										   'Время выгрузки',
                                           '№ весов',
                                           'Материал',
                                           'Заданая масса, кг',
                                           'Фактическая масса, кг',
                                           'Отклонение кг',
                                           'Отклонение %',);
	$analysis_array['data']=array(array('0'=>$outside_zone_count, '1'=>$inside_zone_count));
	$analysis_array['column_titles']=array('Количество отклонений > '.$arg_zone.' кг.','Количество отклонений < -'.$arg_zone.' кг.');
	$analysis_array['footer']='Всего записей - '.($total_zone_count);
	$result_array[]=$analysis_array;
	arsort($distribution);
	foreach($distribution as $scales_num=>$distribution_table)
	{
		ksort($distribution_table);
		foreach($distribution_table as $key=>$value)
		{
			$key='<a href="/ASUTP/localmenu/cshireports_result/report_type=line_2_balancing_archive&date1='.str_replace('.','-',$arg_dt_begin).'&date2='.str_replace('.','-',$arg_dt_end).'&scale_number='.$scales_num.'&zone='.str_replace('.','_',$arg_zone).'&deviation='.round($key*4096/$max_mass[$scales_num]).'">'.$key.'</a>';
			$distribution_array['data'][]=array($key,$value);
		}
		$distribution_array['column_titles']=array('Отклонение, кг','Количество вхождений');
		$distribution_array['caption']='Весы № '.$scales_num;
		$result_array[]=$distribution_array;
		$distribution_array=array();
	}
	if (count($report_array[data])!=0)
	{
		$result_array[]=$report_array;
	}
    return($result_array);
  }
//------------------------------------------------------------------------------

	public function CalcStopRunMechTime($data, $mode, $dt_end, $mechID)  //$data - многомерный массив с данными $mode - поиск  единичек/нулей (1/0)
	{
		$cmd = $mode;
		$last_dt = ''; //последняя дата в массиве
		$first_dt = ''; //первая дата в массиве
		$dt_sum = 0; // Суммарное время работы/простоя механизма
		$index = 0;  // текущий ндекс
		$dt_1 = ''; $dt_2 = ''; $dt_3 = '';
//		$array_1 = array(168, 169, 167, 165, 166);
//		$array_2 = array(20, 16, 25, 78, 18, 33, 29, 32, 39, 35, 38, 45, 43, 44);
		while ($index < count($data))
		{
			if ($data[$index]['quality'] != 0) //Проверка Quality
			{
				$state = 0;
				if ($index == 0) $first_dt = strtotime($data[$index]['DT']);
				switch ($mode)
				{
					case 0:
/*						if ($cmd == 0 and $data[$index]['state'] == 0) //Ищем время включения механизма
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
						}*/
					break;
					case 1:
						if ($mechID == 168 or $mechID == 169 or $mechID == 167 or $mechID == 165 or $mechID == 166)
						{
							if ($data[$index]['state'] == 2 or $data[$index]['state'] == 3 or $data[$index]['state'] == 4 or $data[$index]['state'] == 5)
								$state = 1;
							else
								$state = 0;
						}
						else
						{
							if ($data[$index]['state'] == 1)
								$state = 1;
							else
								$state = 0;
						}
						if ($cmd == 1 and $state != 0) //Ищем время включения механизма
						{
							$dt_1 = strtotime($data[$index]['DT']); //нашли дату работы механихма
							$cmd = 0;
							$last_dt = $dt_1;
						}
						if ($cmd == 0 and $state == 0) //Ищем время выключения механизма
						{
							$dt_2 = strtotime($data[$index]['DT']); // нашли дату остановки механизма
							$dt_3 = $dt_2 - $dt_1; // вычислили время работы механизма
							$dt_sum = $dt_sum + $dt_3; // Суммируем времена работы механизма
							$cmd = 1;
							$last_dt = $dt_1;
						}
						if ($index == count($data)-1)
						{
							if ($state == 1)
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
//------------------------------------------------------------------------------

	public function cshi_pfu_mech_working_time_report($date, $date2, $mechID)
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
		$dbhost = "tpl-server";
		$dbname = "pfu";
		$dbuser = "sa";
		$dbpass = "tpl";
		$data = $data2 = array(); //data2 массив для передачи в функцию подсчета времени работы механизма; data - массив для передачи в отчёт (дана расшифровка состояний механизма)
		$dt_sum = 0;
		$state = 1; //поиск 0-лей

		$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT *
					  FROM in_d
					  WHERE (dt >= (SELECT MAX(dt) AS Expr1
                         FROM in_d AS in_d_1
                         WHERE (dt <= '$dt_beg') AND (ID = '$mechID') AND (TagValue = 0))) AND (dt < '$dt_end') AND (ID = '$mechID')
					  ORDER BY dt";
		try
		{
			set_time_limit(600);
			$result = $db->query($sql);
			$index=0;
			$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
			if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
			foreach($tmp_array as $str_num => $rec)
			{
				if (count($tmp_array) == 1) //Если запись всего одна то время этой записи должно быть равно $date1 а не времени которое мы нашли, когда искали дополнительную запись в SQK запросе
				{
  				$data[$index]['DT'] = $date;
					$data[$index]['state'] = $rec['TagValue'];
					$data[$index]['quality'] = $rec['Quality'];
					$index++;
				}
				else
				{
					if (strtotime($rec['DT']) <= strtotime($dt_beg))
					{
						$data[0]['DT'] = date("d.m.Y H:i:s", strtotime($dt_beg));
						$data[0]['state'] = $rec['TagValue'];
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
						if ($data[$index-1]['state']<>$rec['TagValue']) //Фильтр от повторяющихся сигналов
						{	
							$data[$index]['DT'] = date("d.m.Y H:i:s", strtotime($rec['DT']));
							$data[$index]['state'] = $rec['TagValue'];
							$data[$index]['quality'] = $rec['Quality'];
							$index++;
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
					$tmp_rec = array($data[$index]['DT'], $data[$index]['state']);
					array_push($tmp_array, $tmp_rec);
				}
				$index++;
			}
			$dt_sum = $this->CalcStopRunMechTime($data2, $cmd, $dt_end, $mechID);
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
			$dt_sum = "$hour ч. $minute мин. $second с.";
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
	
	public function cshi_pfu_currents($date1,$date2,$id,$ratio)
	{
		define ("SQLCHARSET", "utf8");
		
		$date_begin = new DateTime($date1);
		$date_end = new DateTime($date2);
		$date1=$date_begin->format('Y-m-d H:i:s');
		$date2=$date_end->format('Y-m-d H:i:s');
		
		$dbhost = 'tpl-server'; $dbname = 'pfu'; $dbuser = 'sa'; $dbpass = 'tpl';
		$sql = "SELECT DT, TagValue*".$ratio." AS Value
				FROM in_a
				WHERE (ID = '".$id."') AND (DT >= '".$date1."') AND (DT <= '".$date2."') AND (Quality=192)
				ORDER BY DT";
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
				$tmp_date=new DateTime($value['DT']);
				$result_array['data'][$rec]['DT']=$tmp_date->format('d.m.Y H:i:s');
				$result_array['data'][$rec]['Value']=round($value['Value'],1);
			}
			$result_array['column_titles']=array("Дата", "Ток, А");
			$result_array['footer']="";
			return $result_array;
		}
		catch( PDOException $err )
		{
			return -3; //Ошибка связи с базой данных
		}
	}
//------------------------------------------------------------------------------

public function cshi_pfu_analog($date1,$date2,$id)
	{
		define ("SQLCHARSET", "utf8");
		
		$date_begin = new DateTime($date1);
		$date_end = new DateTime($date2);
		$date1=$date_begin->format('Y-m-d H:i:s');
		$date2=$date_end->format('Y-m-d H:i:s');
		
		$dbhost = 'press5-server'; $dbname = 'PRESS_5'; $dbuser = 'sa'; $dbpass = 'admintp';
		$sql = "SELECT DATEADD(hh, 5, TS) AS Date, Value
				FROM in_a
				WHERE (Name = 'PRESS_5.IN_A.IN_A_".str_pad($id, 2, '0', STR_PAD_LEFT)."') AND (DATEADD(hh, 5, TS) >= '".$date1."') AND (DATEADD(hh, 5, TS) <= '".$date2."') AND (Quality=192)
				ORDER BY Date";
		//print_r($sql);
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
			$result_array['column_titles']=array("Дата", "Ток, А");
			$result_array['footer']="";
			return $result_array;
		}
		catch( PDOException $err )
		{
			return -3; //Ошибка связи с базой данных
		}
	}
//------------------------------------------------------------------------------
	public function cshi_desiccators($date1,$date2,$id,$title)
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
	
//public function cshi_pfu_press_line_6_balancing_archive($arg_dt_begin, $arg_dt_end, $arg_scales_number, $arg_zone, $arg_deviation)
public function cshi_pfu_press_line_6_balancing_archive($arg_dt_begin, $arg_dt_end, $arg_scales_number)
{
	$arg_zone = 0;
	$arg_deviation = 0;
    set_time_limit(600);
	$max_mass = array('650'=>'50'); //массив с максимальными значениями масс
    $batch_numbers = array(); //массив с номерами рецептов
    $tmp_array = array(); //временный массив
    $batch_record = array(); //одна строка замеса
    $batch_data = array();//один замес
	$distribution = array();
	$outside_zone_count=0;
	$inside_zone_count=0;
    $report_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');//массив рецептов
	$analysis_array = array('caption'=>'', 'column_titles'=>'','data'=>array(),'footer'=>'');
    $data = array('caption'=>'', 'column_titles'=>'','table_data'=>'','footer'=>'');//массив рецептов
    $date_begin = new DateTime($arg_dt_begin);
	$outside_zone_count=0;
	$inside_zone_count=0;
	$total_zone_count=0;
    if ($arg_dt_end!='')
    {
    	$date_end = new DateTime($arg_dt_end);
    }
    else
    {
    	$date_end = new DateTime($arg_dt_begin);
        $date_end->add(new DateInterval('PT24H'));
    }
   	$dbhost = "TPL-SERVER";
		$dbname = "pfu";
		$dbuser = "sa";
		$dbpass = "tpl";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
//находим даты изменения рецептов за указанный период
    $sql = "SELECT zames
            FROM BalanceReport_line_6
            WHERE (begin_dosage > :dt_begin) AND (begin_dosage < :dt_end)
            GROUP BY zames
            ORDER BY zames;";
//	print_r($sql);echo('<BR>');
/*	echo($arg_dt_begin);echo('<BR>');
	echo($arg_dt_end);echo('<BR>');
	echo($arg_scales_number);echo('<BR>');
	echo($arg_zone);echo('<BR>');
	echo($arg_deviation);echo('<BR>');*/
 	$result = $db->prepare($sql);
   	$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                       ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
   	$batch_numbers = $result->fetchAll(PDO::FETCH_ASSOC);
   	$scales_condition = ($arg_scales_number=='ALL')?'':'AND (vesi = '.$arg_scales_number.')';//дополнительное условие в запросе для фильтра весов
//	$scales_condition = ($arg_deviation=='ALL')?'':' AND (round(ves - ves_z,2) = '.$arg_deviation.')';
//для каждого замеса прогоняем запрос и формируем наборы значений

	$total=0;
    foreach ($batch_numbers as $str_num=>$batch_str)
    {
      $sql = "SELECT zames, vesi, material, ves_z, ves, begin_dosage, end_dosage
              FROM BalanceReport_line_6
              WHERE (zames = :batch_number) AND (begin_dosage > :dt_begin) AND (begin_dosage < :dt_end) ".$scales_condition.";";
//		print_r($sql);echo('<BR>');
		$result = $db->prepare($sql);
     	$result->execute(array(':batch_number'=>$batch_str['zames'],
     	                       ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
   	                         ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
     	$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
      foreach($tmp_array as $key=>$value)
      {
        //Заполнение таблицы
		$tmp_date = new DateTime($value['end_dosage']);
        $tmp_date_str = $tmp_date->format('d.m.Y H:i:s');
        $zames_number = $value['zames'];
        $batch_data[$key]['zames'] = $zames_number;
		$batch_data[$key]['end_dosage'] = $tmp_date_str;
        $batch_data[$key]['vesi'] = $value['vesi'];
//        $batch_data[$key]['material'] = iconv("Windows-1251", "UTF-8", $value['material']);
//        $batch_data[$key]['ves_z'] = round($value['ves_z'],2);
        $batch_data[$key]['ves'] = round($value['ves'],2);
//        $batch_data[$key]['deviation_absolute'] = round($value['ves'] - $value['ves_z'],2);
/*        if ($value['ves_z']!=0)
			$batch_data[$key]['deviation_relative'] = round(100*($value['ves'] - $value['ves_z'])/$value['ves_z'],2);*/
		//Анализ отклонений
		if ($batch_data[$key]['deviation_absolute']>$arg_zone)
			$outside_zone_count++;
		else if ($batch_data[$key]['deviation_absolute']<-$arg_zone)
			$inside_zone_count++;
		$total_zone_count++;
		$total += ($batch_data[$key]['ves'])+($batch_data[$key-1]['ves']);
		$distribution[$batch_data[$key]['vesi']][sprintf("%.2f",$value['ves'] - $value['ves_z'])]++;
        array_push($report_array['data'], $batch_data[$key]);
      }
      if (count($tmp_array)>0)//проверка на пустой рецепт
      {
//        $data['table_data'] = $batch_data;
      }
      $data = array();//обнуляем массив рецепта
      $batch_data = array();
    }
//	print_r($total_zone_count);
	//print_r($distribution);
//    print_r($data);
//    $result_array['data'] = $data;
//    $result_array['caption'] = 'Отчёт ';
    $report_array['column_titles'] = array('№ замеса',
										   'Время выгрузки',
                                           '№ весов',
//                                           'Материал',
//                                           'Заданая масса, кг',
                                           'Фактическая масса, кг',);
//                                           'Отклонение кг',
//                                           'Отклонение %',);
	$analysis_array['data']=array(array('0'=>$outside_zone_count, '1'=>$inside_zone_count));
	$analysis_array['column_titles']=array('Количество отклонений > '.$arg_zone.' кг.','Количество отклонений < -'.$arg_zone.' кг.');
//	$analysis_array['footer']='Всего записей - '.($total_zone_count);
	$analysis_array['footer']='Суммарная масса: '  .($total) .' кг';
	if ($arg_scales_number!='ALL')
	{
		$result_array[]=$analysis_array;
	}	
	arsort($distribution);
	foreach($distribution as $scales_num=>$distribution_table)
	{
		ksort($distribution_table);
		foreach($distribution_table as $key=>$value)
		{
			$key='<a href="/ASUTP/localmenu/cshireports_result/report_type=line_6_balancing_archive&date1='.str_replace('.','-',$arg_dt_begin).'&date2='.str_replace('.','-',$arg_dt_end).'&scale_number='.$scales_num.'&zone='.str_replace('.','_',$arg_zone).'&deviation='.str_replace('.','_',round($key,2)).'">'.round($key,2).'</a>';
			$distribution_array['data'][]=array($key,$value);
		}
		$distribution_array['column_titles']=array('Отклонение, кг','Количество вхождений');
		$distribution_array['caption']='Весы № '.$scales_num;
		if ($arg_scales_number!='ALL')
		{
			$result_array[]=$distribution_array;
		}
		$distribution_array=array();
	}
	if (count($report_array[data])!=0)
	{
		$result_array[]=$report_array;
	}
    return($result_array);
}	
	
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
//-------------------------------------------------------------------

	public function cshi_line_5_mech_working_time_report($date, $date2, $mechname)
	{
		$dt = new DateTime($date);
		$dt->sub(new DateInterval('PT5H'));
		$dt_beg = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime($date2);
//		$dt->add(new DateInterval('PT1S'));
		$dt->sub(new DateInterval('PT5H'));
		$dt_end = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime(date('Y-m-d H:i:s'));
		$current_dt = $dt->format('Y-m-d H:i:s');
		if (strtotime($dt_end) > strtotime($current_dt)) $dt_end = $current_dt;
		if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;
		$dbhost = "press5-server";
		$dbname = "PRESS_5";
		$dbuser = "sa";
		$dbpass = "admintp";
		$data = $data2 = array(); //data2 массив для передачи в функцию подсчета времени работы механизма; data - массив для передачи в отчёт (дана расшифровка состояний механизма)
		$dt_sum = 0;
		$state = 1; //поиск 0-лей
		$name = str_replace("-", ".", $mechname);

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT *
					  FROM IN_D
					  WHERE (TS >= (SELECT MAX(TS) AS Expr1
                         FROM IN_D AS IN_D_1
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
						if ($OldVal == 0 and $rec['Value'] == 0)
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
	//-------------------------------------------------------------------

	public function cshi_line_3_mech_working_time_report($date, $date2, $mechname)
	{
		$dt = new DateTime($date);
		$dt->sub(new DateInterval('PT5H'));
		$dt_beg = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime($date2);
//		$dt->add(new DateInterval('PT1S'));
		$dt->sub(new DateInterval('PT5H'));
		$dt_end = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime(date('Y-m-d H:i:s'));
		$current_dt = $dt->format('Y-m-d H:i:s');
		if (strtotime($dt_end) > strtotime($current_dt)) $dt_end = $current_dt;
		if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;
		$dbhost = "press5-server";
		$dbname = "PRESS_3";
		$dbuser = "sa";
		$dbpass = "admintp";
		$data = $data2 = array(); //data2 массив для передачи в функцию подсчета времени работы механизма; data - массив для передачи в отчёт (дана расшифровка состояний механизма)
		$dt_sum = 0;
		$state = 1; //поиск 0-лей
		$name = str_replace("-", ".", $mechname);

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT *
					  FROM IN_D
					  WHERE (TS >= (SELECT MAX(TS) AS Expr1
                         FROM IN_D AS IN_D_1
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
						if ($OldVal == 0 and $rec['Value'] == 0)
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
	
	//-------------------------------------------------------------------

	public function pfu_press_current_report($date, $date2, $press_number, $limit_current)
	{
		$dt = new DateTime($date);
		$dt->sub(new DateInterval('PT5H'));
		$dt_beg = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime($date2);
		$dt->sub(new DateInterval('PT5H'));
		$dt_end = $dt->format('Y-m-d H:i:s');
		$dbhost = "press5-server";
		$dbname = "PRESS_5";
		$dbuser = "sa";
		$dbpass = "admintp";
		$data = array();
		$result_array = array();

    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT TS,Value
					  FROM Press_current
					  WHERE TS>='$dt_beg' and TS<='$dt_end' and name='PRESS_5.press_current.$press_number'
					  ORDER BY TS";
		//echo $sql;
		try
		{
			set_time_limit(600);
			$result = $db->query($sql);
			$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
			//print_r($tmp_array);
			if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
			$last_value=999;
			foreach($tmp_array as $str_num => $rec)
			{
				if ($last_value>1 and $rec['Value']>$limit_current) {
					$tmp_date = new DateTime($rec['TS']);
					$tmp_date->add(new DateInterval('PT5H'));
					$data[$tmp_date->format('d.m.Y')]++;
					$dt_sum++;
					//print_r('dt="'.$rec['TS'].';');
				}
				$last_value=$rec['Value'];
			}
			//print_r($data);
			$result_array['data'] = array();
			foreach($data as $date_str => $rec)
			{
				$tmp_rec=array($date_str, $rec);
				array_push($result_array['data'],$tmp_rec);
			}
			$result_array['column_titles'] = array('Дата', 'Количество превышений');
			$result_array['footer'] = 'Сумма: '.$dt_sum;
			//print_r($result_array);
			return $result_array; 
		}
		catch (PDOException $err)
		{
			return -3; //Ошибка связи с базой данных
		}		
	}	
	//-------------------------------------------------------------------

	public function pfu_press_current_overload($date, $date2)
	{
		$dt = new DateTime($date);
		$dt->sub(new DateInterval('PT5H'));
		$dt_beg = $dt->format('Y-m-d H:i:s');
		$dt = new DateTime($date2);
		$dt->sub(new DateInterval('PT5H'));
		$dt_end = $dt->format('Y-m-d H:i:s');
		$dbhost = "press5-server";
		$dbname = "PRESS_5";
		$dbuser = "sa";
		$dbpass = "admintp";
		$data = array();
		$result_array = array();
		$result_array['data'] = array();
		$press=array(array(9,69),array(10,70),array(11,71),array(3,72),array(4,73),array(6,74),array(8,75),array(7,76));
		foreach ($press as $value) $sql_exp.=" or name='PRESS_5.IN_D.IN_D_".$value[1]."'";
		//print_r ($sql_exp);
		$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
					   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$sql = "SELECT TS, Name, Value
					  FROM in_d
					  WHERE Quality=192 and TS>='$dt_beg' and TS<='$dt_end' and (1=0$sql_exp)
					  ORDER BY TS";
		//print_r($sql);
		try
		{
			set_time_limit(600);
			$result = $db->query($sql);
			$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
			//print_r($tmp_array);
			if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
			$prev_value=0;
			foreach($tmp_array as $str_num => $rec)
			{
				if ($rec['Value']==1 and $prev_value==0)
				{
					$tmp_date = new DateTime($rec['TS']);
					$tmp_date->add(new DateInterval('PT5H'));
					$dt_sum+=$rec['Value'];
					foreach ($press as $value)
						if ('PRESS_5.IN_D.IN_D_'.$value[1]==$rec['Name'])
							$press_number=$value[0];
					//print_r('PRESS_5.IN_D.IN_D_'.$value[1].' == '.$rec['Name']);
					$tmp_rec=array($tmp_date->format('d.m.Y H:i:s'), $press_number);
					array_push($result_array['data'],$tmp_rec);
				}
				$prev_value=$rec['Value'];
			}
			//print_r($result_array['data']);
			$result_array['column_titles'] = array('Дата', 'Номер пресса');
			$result_array['footer'] = 'Всего: '.$dt_sum;
			//print_r($result_array);
			return $result_array; 
		}
		catch (PDOException $err)
		{
			return -3; //Ошибка связи с базой данных
		}		
	}	
}
?>