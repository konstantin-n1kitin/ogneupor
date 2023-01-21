<?php
defined('SYSPATH') or die('No direct script access.');
//include ('sql_queries.php');
class Model_Cmdomonthlyreports extends Kohana_Model
{
//------------------------------------------------------------------------------
//природный газ ЦМДО
  public function cmdo_natural_gas_monthly_report($arg_dt_begin, $arg_dt_end)
  {
//природный газ
//595  2  Расход Природ.газ ЦМДО А
//689  2  Расход Природ.газ ЦМДО S
//    //задаем константы IDшек
    $IDs = array('T'=>    '0',
                 'T_br'=> '0',
                 'P'=>    '0',
                 'P_br'=> '0',
                 'F'=>   '595',
                 'F_br'=> '0',
                 'V'=>    '689');
    $data = array();
    $date_begin = new DateTime($arg_dt_begin);
    $date_end = new DateTime($arg_dt_end);
    $date_begin->sub(new DateInterval('PT1H30M'));
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
/*                           'T'=>'',
                           'T_br'=>'',
                           'P'=>'',
                           'P_br'=>'',
                           'F'=>'',
                           'F_br'=>'',*/
                           'V'=>'');
    $interval_points_count = 0;//количество записей за один суточный интервал
    //для вычисления средних значений
    while (isset($data[$input_array_ind])) //пробегаемся по массиву значений
    {
      $rec_date = new DateTime($data[$input_array_ind]['DT']);
      if ($rec_date<=$interval_end)
      {
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
/*        $tmp_rec_array['T']+=$data[$input_array_ind]['T'];
        $tmp_rec_array['T_br']+=$data[$input_array_ind]['T_br'];
        $tmp_rec_array['P']+=$data[$input_array_ind]['P'];
        $tmp_rec_array['P_br']+=$data[$input_array_ind]['P_br'];
        $tmp_rec_array['F']+=$data[$input_array_ind]['F'];
        $tmp_rec_array['F_br']+=$data[$input_array_ind]['F_br']; */
        $tmp_rec_array['V']+=$data[$input_array_ind]['V'];
        $interval_points_count++;
        $input_array_ind++;
      }
      else
      {
       //вычисляем средние значения там где нужно
        $tmp_rec_array['DT'] = $rec_date->format('d.m.Y');
/*        $tmp_rec_array['T'] = round($tmp_rec_array['T']/$interval_points_count,2);
        $tmp_rec_array['T_br'] = round($tmp_rec_array['T_br']/3600,2);
        $tmp_rec_array['P'] = round($tmp_rec_array['P']/$interval_points_count,2);
        $tmp_rec_array['P_br'] = round($tmp_rec_array['P_br']/3600,2); 
        $tmp_rec_array['F'] = round($tmp_rec_array['F']/$interval_points_count,2);
        $tmp_rec_array['F_br'] = round($tmp_rec_array['F_br']/3600,2);*/
        $tmp_rec_array['V'] = round($tmp_rec_array['V'],2);
        $total+=round($tmp_rec_array['V'],2);
        array_push($tmp_array, $tmp_rec_array);
        unset($tmp_rec_array);
        $tmp_rec_array = array('DT'=>'',
/*                               'T'=>'',
                               'T_br'=>'',
                               'P'=>'',
                               'P_br'=>'',
                               'F'=>'',
                               'F_br'=>'',*/
                               'V'=>'');
        $interval_points_count = 0;
        $interval_begin = $interval_end;
        $interval_begin->add(new DateInterval('PT30M'));
        $interval_end->add(new DateInterval('PT23H30M'));
        $output_array_ind++;
      }
    }
    $result_array['column_titles']=array('Число',
/*                                  'Температура, С<sup>o</sup> (T)',
                                  'Обрыв канала, ч',
                                  'Давление, МПа (P)',
                                  'Обрыв канала, ч', 
                                  'Расход, м3 (F)',
                                  'Обрыв канала, ч',*/
                                  'Объем, м<sup>3</sup> (V)');
    $result_array['data'] = $tmp_array;
    $result_array['footer']='Суммарный объем: '.$total.'м<sup>3</sup>';
  	return $result_array;
  }
}
?>