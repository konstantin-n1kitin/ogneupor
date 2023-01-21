<?php
defined('SYSPATH') or die('No direct script access.');
//include ('sql_queries.php');
class Model_Csitpreports extends Kohana_Model
{
//------------------------------------------------------------------------------
//отчёт алармов по камерному сушилу №1
  public function csi_chamber_drier1_alarm_report($arg_dt_begin, $arg_dt_end)
  {
    $dt_begin = new DateTime($arg_dt_begin);
    if ($arg_dt_end!='')
      $dt_end = new DateTime($arg_dt_end);
    else
      {
      	$dt_end = new DateTime($arg_dt_begin);
      	$dt_end->add(new DateInterval('PT24H'));
      }
   	$dbhost = "CSI-SERVER";
		$dbname = "CSI_11_1";
		$dbuser = "sa";
		$dbpass = "oup";
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $sql = "SELECT TS, Description , Value
            FROM trendtable_alarm JOIN alarm_description ON
            TRENDTABLE_ALARM.Name = alarm_description.Name
            WHERE (TS > :dt_begin) AND (TS <= :dt_end) AND (Quality=192)
            ORDER BY TS;";

   	$result = $db->prepare($sql);
    $result->execute(array(':dt_begin'=>$dt_begin->format('Y-m-d H:i:s'),':dt_end'=>$dt_end->format('Y-m-d H:i:s')));
    $tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach($tmp_array as $key => $tmp_rec)
    {
      $tmp_date = new DateTime($tmp_rec['TS']);
      $tmp_array[$key]['TS'] = $tmp_date->format('d.m.Y H:i:s');
      $tmp_array[$key]['Description'] = iconv("Windows-1251","UTF-8", $tmp_rec['Description']);
      $tmp_array[$key]['Value'] = ($tmp_rec['Value']=="1")?'Тревога!':'Норма.';
    }
//    print_r($tmp_array);
    $result_array['column_titles']=array('Время',
                                         'Тревога',
                                         'Состояние тревоги');
    $result_array['data']=$tmp_array;
    $result_array['footer']='';
    return $result_array;
  }
 //------------------------------------------------------------------------------

	public function csi_desiccators($date1,$date2,$id,$title)
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

	public function drier_teh($date1,$date2,$id,$title)
	{
		define ("SQLCHARSET", "utf8");
		
		$date_begin = new DateTime($date1);
		$date_end = new DateTime($date2);
		$date1=$date_begin->format('Y-m-d H:i:s');
		$date2=$date_end->format('Y-m-d H:i:s');

		$dbhost = 'csi-server'; $dbname = 'CSI_11_1'; $dbuser = 'sa'; $dbpass = 'oup';
		$sql = "SELECT DATEADD(hh, 5, TS) AS Date, Value
				FROM trendtable_in_a
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