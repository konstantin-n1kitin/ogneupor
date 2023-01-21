<?php
defined('SYSPATH') or die('No direct script access.');
//include ('sql_queries.php');
class Model_Cmdodailyreports extends Kohana_Model
{//------------------------------------------------------------------------------
//природный газ ЦМДО
  public function cmdo_natural_gas_daily_report($date)
  {
//природный газ
//595  2  Расход Природ.газ ЦМДО А
//689  2  Расход Природ.газ ЦМДО S
//    //задаем константы IDшек
    $IDs = array(
/*				 'T'=>    '0',
                 'T_br'=> '0',
                 'P'=>    '0',
                 'P_br'=> '0',
                 'F'=>   '595',
                 'F_br'=> '0', */
                 'V'=>    '689');
    $data = array();
    $date_begin = new DateTime($date);
    $date_end = new DateTime($date);
    $date_begin->sub(new DateInterval('PT1H'));
    $date_end->add(new DateInterval('PT23H30M'));
   	$dbhost = "CMDO-ASKU";
		$dbname = "cmdo";
		$dbuser = "sa";
		$dbpass = "";
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
        $rec_date->add(new DateInterval('PT1H'));
        $tmp_rec = array(date_format($rec_date,'H:i:s'),
/*                         round((($data[$str_num]['T'])+($data[$str_num-1]['T']))/2,2),
                         round(($data[$str_num]['T_br']+$data[$str_num-1]['T_br'])/3600,1),
                         round((($data[$str_num]['P'])+($data[$str_num-1]['P']))/2,2),
                         round(($data[$str_num]['P_br']+$data[$str_num-1]['P_br'])/3600,1), */
/*                         round((($data[$str_num]['F'])+($data[$str_num-1]['F']))/2,2),
                         round(($data[$str_num]['F_br']+$data[$str_num-1]['F_br'])/3600,1),*/
                         round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2));
        $total+=round((($data[$str_num]['V'])+($data[$str_num-1]['V'])),2);
        array_push($tmp_array, $tmp_rec);
      }
      else
      {

      }

    }
    $result_array['column_titles']=array('Время',
/*                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч',  */
/*                                  'Расход, м3 (F)',
                                  'Обрыв канала, ч',*/
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data']=$tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
//------------------------------------------------------------------------------
}
?>