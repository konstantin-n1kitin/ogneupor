<?php
defined('SYSPATH') or die('No direct script access.');
//include ('sql_queries.php');
class Model_Cshiconscalesreports extends Kohana_Model
{
//------------------------------------------------------------------------------
	//Cуточный отчет
  public function cshi_vonveyor_scales_daily_report($date, $mech_id)
  {
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->add(new DateInterval('PT1S'));
    $date_end->add(new DateInterval('PT24H'));
		
/*	$dt = new DateTime(date('Y-m-d H:i:s'));
	$current_dt = $dt->format('Y-m-d H:i:s');
	if (strtotime($dt_end) > strtotime($current_dt))	$dt_end = $current_dt;
	if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;*/
	$dbhost = 'WEIGHT-SERVER'; $dbname = 'Conv_vesy'; $dbuser = 'sa'; $dbpass = '123Oup123';
		
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT  DT, Diff_Z3
            FROM Archive
            WHERE (ID_W = ".$mech_id.") AND (DT >= :dt_begin) AND (DT <= :dt_end)
            ORDER BY DT;";
    $result = $db->prepare($sql);
    $result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    $tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//	echo count($tmp_array); exit;
	if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
	$total=0;
	$data = array();
	foreach ($tmp_array as &$value)
	{
		$date_tmp=new DateTime($value[DT]);
		$date_tmp->add(new DateInterval('PT59M59S'));
		$value[DT]=date_format($date_tmp,'H:00:00');
		$data[$value[DT]]+=$value[Diff_Z3];
//		$data[$value[DT]] = sprintf('%.2f', $data[$value[DT]]);
		$total+= $value[Diff_Z3];
	}
	$total = sprintf('%.2f', $total);
	unset($value);
	$tmp_array=array();
	foreach ($data as $key => $value) {
		array_push($tmp_array,array($key,$value));
	}
    $result_array = array(); //окончательный массив с данными и футером
    $result_array['column_titles']=array('Время','Количество материала, кг');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.' кг.';
  	return $result_array;
  }
  
  
  
	//Месячный отчет
	public function cshi_vonveyor_scales_monthly_report($date1, $date2, $mech_id)
  {
    $date_begin = new DateTime($date1);
    $date_end = new DateTime($date2);
    $date_begin->add(new DateInterval('PT1S'));
    $date_end->add(new DateInterval('PT24H'));
		
/*	$dt = new DateTime(date('Y-m-d H:i:s'));
	$current_dt = $dt->format('Y-m-d H:i:s');
	if (strtotime($dt_end) > strtotime($current_dt))	$dt_end = $current_dt;
	if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;*/
	$dbhost = 'WEIGHT-SERVER'; $dbname = 'Conv_vesy'; $dbuser = 'sa'; $dbpass = '123Oup123';
		
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT  DT, Diff_Z3
            FROM Archive
            WHERE (ID_W = ".$mech_id.") AND (DT >= :dt_begin) AND (DT <= :dt_end)
            ORDER BY DT";
    $result = $db->prepare($sql);
    $result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    $tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
	$total=0;
	$data = array();
	foreach ($tmp_array as &$value)
	{
		$date_tmp=new DateTime($value[DT]);
		$date_tmp->sub(new DateInterval('PT15M'));
		$value[DT]=date_format($date_tmp,'d.m.Y');
		$data[$value[DT]]+=$value[Diff_Z3];
		$total+=$value[Diff_Z3];
	}
	unset($value);
	$tmp_array=array();
	foreach ($data as $key => $value) {
		array_push($tmp_array,array($key,$value));
	}
    $result_array = array(); //окончательный массив с данными и футером
    $result_array['column_titles']=array('Дата','Количество материала, кг');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.' кг.';
  	return $result_array;
  }
  //------------------------------------------------------------------------------
	//Cуточный отчет
  public function cshi_vonveyor_scales2_daily_report($date, $mech_id, $brigade)
  {
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->add(new DateInterval('PT8H'));
    $date_end->add(new DateInterval('PT32H'));
		
/*	$dt = new DateTime(date('Y-m-d H:i:s'));
	$current_dt = $dt->format('Y-m-d H:i:s');
	if (strtotime($dt_end) > strtotime($current_dt))	$dt_end = $current_dt;
	if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;*/	
		
	$dbhost = 'WEIGHT-SERVER'; $dbname = 'Conv_vesy'; $dbuser = 'sa'; $dbpass = '123Oup123';
		
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT  DT, Diff_Z3
            FROM Archive
            WHERE (ID_W = ".$mech_id.") AND (DT > :dt_begin) AND (DT <= :dt_end)
            ORDER BY DT;";
    $result = $db->prepare($sql);
    $result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    $tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
//	echo count($tmp_array); exit;
	if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
	$total=0;
	$data = array();
	$Brig=array(array(2,1,3),array(3,4,1),array(1,2,4),array(4,3,2));
	$month=array(0,31,59,90,120,151,181,212,243,273,304,334);
	foreach ($tmp_array as &$value)
	{
		$date_tmp=new DateTime($value[DT]);
		$date_tmp->sub(new DateInterval('PT1S'));
		if ($date_tmp->format('H')<8)
			$shift=0;
		else if ($date_tmp->format('H')<20)
			$shift=1;
		else
			$shift=2;
        $brigade_number=$Brig[(
            ($date_tmp->format('Y')-2016)*365 +
            (integer)(($date_tmp->format('Y')-2016)/4)+1 +
            $month[$date_tmp->format('m')-1] +
            (integer)$date_tmp->format('d') +
            ($date_tmp->format('m')>2 && ($date_tmp->format('Y') % 4 == 0) ? 0 : -1)) % 4][$shift];
		//echo($date_tmp->format('H:i:s')."-".$shift."-".$brigade_number."<BR>");
		if ($brigade==$brigade_number or $brigade==0) {
			$date_tmp->add(new DateInterval('PT1H'));
			$value[DT]=date_format($date_tmp,'H:00:00');
			$data[$value[DT]]+=$value[Diff_Z3];
			$total+= $value[Diff_Z3];
		}
	}
	$total = sprintf('%.0f', $total);
	unset($value);
	$tmp_array=array();
	foreach ($data as $key => $value) {
		array_push($tmp_array,array($key,$value));
	}
    $result_array = array(); //окончательный массив с данными и футером
    $result_array['column_titles']=array('Время','Количество материала, кг');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.' кг.';
  	return $result_array;
  }
  
  
  
	//Месячный отчет
	public function cshi_vonveyor_scales2_monthly_report($date1, $date2, $mech_id,$brigade)
  {
    $date_begin = new DateTime($date1);
	$date_begin->sub(new DateInterval('PT4H'));
    $date_end = new DateTime($date2);
    $date_end->add(new DateInterval('PT20H'));
		
/*	$dt = new DateTime(date('Y-m-d H:i:s'));
	$current_dt = $dt->format('Y-m-d H:i:s');
	if (strtotime($dt_end) > strtotime($current_dt))	$dt_end = $current_dt;
	if (strtotime($dt_beg) >= strtotime($dt_end)) return -1;*/
	$dbhost = 'WEIGHT-SERVER'; $dbname = 'Conv_vesy'; $dbuser = 'sa'; $dbpass = '123Oup123';
		
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT  DT, Diff_Z3
            FROM Archive
            WHERE (ID_W = ".$mech_id.") AND (DT > :dt_begin) AND (DT <= :dt_end)
            ORDER BY DT";
    $result = $db->prepare($sql);
    $result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),':dt_end'=>$date_end->format('Y-m-d H:i:s')));
    $tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
	if (count($tmp_array) < 1) return -2; //Нет данных в этом диапазоне
	$total=0;
	$data = array();
	$Brig=array(array(1,2,3),array(3,4,2),array(2,1,4),array(4,3,1));
	$month=array(0,31,59,90,120,151,181,212,243,273,304,334);
	foreach ($tmp_array as &$value)
	{
		$date_tmp=new DateTime($value[DT]);
		//$date_tmp->sub(new DateInterval('PT15M'));
		$date_tmp->sub(new DateInterval('PT1S'));
		if ($date_tmp->format('H')<8)
			$shift=0;
		else if ($date_tmp->format('H')<20)
			$shift=1;
		else
			$shift=2;
		$brigade_number=$Brig[(
		    ($date_tmp->format('Y')-2016)*365 +
            (integer)(($date_tmp->format('Y')-2016)/4)+1 +
            $month[$date_tmp->format('m')-1] +
            (integer)$date_tmp->format('d') +
            ($date_tmp->format('m')>2 && ($date_tmp->format('Y') % 4 == 0) ? 0 : -1)) % 4][$shift];
		if ($brigade==$brigade_number or $brigade==0) {
			$date_tmp->add(new DateInterval('PT4H'));
			$value[DT]=date_format($date_tmp,'d.m.Y');
			$data[$value[DT]]+=$value[Diff_Z3];
			$total+=$value[Diff_Z3];
		}
	}
	unset($value);
	$tmp_array=array();
	foreach ($data as $key => $value) {
		array_push($tmp_array,array($key,$value));
	}
    $result_array = array(); //окончательный массив с данными и футером
    $result_array['column_titles']=array('Дата','Количество материала, кг');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.' кг.';
  	return $result_array;
  }
}
?>