<?php
//include ('sql_queries.php');
class Model_Csitpreports
{
//------------------------------------------------------------------------------
//отчёт алармов по камерному сушилу №1
  public function csi_chamber_drier1_alarm_report($arg_dt_begin, $arg_dt_end)
  {
    $dt_begin = new DateTime($arg_dt_begin);
    if ($arg_dt_end!='')
      $dt_end = new DateTime($arg_dt_end);
    else
      {      	$dt_end = new DateTime($arg_dt_begin);
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
      $tmp_array[$key]['Value'] = (int)($tmp_rec['Value']);
    }
//    print_r($tmp_array);
    $result_array['column_titles']=array('Время',
                                         'Тревога',
                                         'Состояние тревоги');
    $result_array['data']=$tmp_array;
    $result_array['footer']='';
    return $result_array;
  }
}
?>