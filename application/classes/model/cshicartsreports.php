<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Cshicartsreports extends Kohana_Model
{
	public function GetTruckList($arg_dt_beg, $arg_dt_end, $param_type)
	{
		$dt_beg = new DateTime($arg_dt_beg);
		$dt_end = new DateTime($arg_dt_end);
		$total = 0;
		$dbhost = 'promauto1';
		$dbname = 'vesyterm';
		$dbuser = 'sa';
		$dbpass = 'admintp';

/*		$sql =	"select basa.Basa_Datetime_first, basa.Basa_Brutto, basa.Basa_Tara, basa.Basa_Netto, gruzy.GRUZ_Name, basa.Basa_NumTS FROM basa, gruzy 
				 where basa.BASA_gruz=gruzy.GRUZ_guid
				 ORDER BY basa.Basa_Datetime_first";*/

		 switch ($param_type)
		{
			case 0:
				$sql = "select basa.Basa_Datetime_first, basa.Basa_Brutto, basa.Basa_Tara, basa.Basa_Netto, gruzy.GRUZ_Name, basa.Basa_NumTS FROM basa, gruzy 
					where basa.BASA_gruz=gruzy.GRUZ_guid and basa.Basa_Datetime_first>='".$dt_beg->format('Y-m-d H:i:s')."' and basa.Basa_Datetime_first<='".$dt_end->format('Y-m-d H:i:s')."'
					ORDER BY basa.Basa_Datetime_first";
				break;
			case 1:
				$val = iconv("UTF-8", "Windows-1251", 'Аркалык');
				$sql = "select basa.Basa_Datetime_first, basa.Basa_Brutto, basa.Basa_Tara, basa.Basa_Netto, gruzy.GRUZ_Name, basa.Basa_NumTS FROM basa, gruzy 
					where basa.BASA_gruz=gruzy.GRUZ_guid and gruzy.GRUZ_Name = '$val' and basa.Basa_Datetime_first>='".$dt_beg->format('Y-m-d H:i:s')."' and basa.Basa_Datetime_first<='".$dt_end->format('Y-m-d H:i:s')."'
					ORDER BY basa.Basa_Datetime_first";
				break;
			case 2:
				$val = iconv("UTF-8", "Windows-1251", 'Берлинка');
				$sql = "select basa.Basa_Datetime_first, basa.Basa_Brutto, basa.Basa_Tara, basa.Basa_Netto, gruzy.GRUZ_Name, basa.Basa_NumTS FROM basa, gruzy 
					where basa.BASA_gruz=gruzy.GRUZ_guid and gruzy.GRUZ_Name = '$val' and basa.Basa_Datetime_first>='".$dt_beg->format('Y-m-d H:i:s')."' and basa.Basa_Datetime_first<='".$dt_end->format('Y-m-d H:i:s')."'
					ORDER BY basa.Basa_Datetime_first";
				break;
			default:
				$sql = "select basa.Basa_Datetime_first, basa.Basa_Brutto, basa.Basa_Tara, basa.Basa_Netto, gruzy.GRUZ_Name, basa.Basa_NumTS FROM basa, gruzy 
					where basa.BASA_gruz=gruzy.GRUZ_guid
					ORDER BY basa.Basa_Datetime_first";
				break;
		}
		
		try
		{
			set_time_limit(600);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result = $db->prepare($sql);
//			echo $sql;
			$result->execute();
			$result_array['data'] = $result->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result_array['data'] as &$value) {
				if ($value['Basa_Datetime_first']!="") {
					$Basa_Datetime_first = new DateTime($value['Basa_Datetime_first']);
					$value['Basa_Datetime_first'] = $Basa_Datetime_first->format('d.m.Y H:i:s');
					$value['GRUZ_Name']=iconv("Windows-1251", "UTF-8", $value['GRUZ_Name']);
					$total += round($value['Basa_Netto']);
				}
			}
			$result_array['header'] = array ('Вагон выгрузили', 'Брутто, кг', 'Тара, кг', 'Нетто, кн', 'Тип сырья', 'Номер вагона');
			$result_array['footer']='Суммарная масса: '.$total.' кг';
			
			return $result_array;
		}
		catch( PDOException $err )
		{
			return; //Ошибка связи с базой данных
		}
	}
//-------------------------------------------------------------------
	public function GetPassport($arg_dt)
	{
		$dt = new DateTime($arg_dt);

		$dbhost = 'promauto1';
		$dbname = 'vesyterm';
		$dbuser = 'sa';
		$dbpass = 'admintp';

		
		$sql = "select basa.Basa_Datetime_first, basa.Basa_Brutto, basa.Basa_Tara, basa.Basa_Netto, gruzy.GRUZ_Name, basa.Basa_NumTS FROM basa, gruzy 
			where basa.BASA_gruz=gruzy.GRUZ_guid and basa.Basa_Datetime_first='".$dt->format('Y-m-d H:i:s')."'
			ORDER BY basa.Basa_Datetime_first";
			
		try
		{
			set_time_limit(600);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result = $db->prepare($sql);
			$result->execute();
			$result_array = $result->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result_array as &$value) {
				if ($value['Basa_Datetime_first']!="") {
					$dt_tmp = new DateTime($value['Basa_Datetime_first']);
					$value['Basa_Datetime_first'] = $dt_tmp->format('d.m.Y H:i:s');
				}
				$value['GRUZ_Name']=iconv("Windows-1251", "UTF-8", $value['GRUZ_Name']);
				$value['Basa_Brutto']=round($value['Basa_Brutto'],1);
				$value['Basa_Tara']=round($value['Basa_Tara'],1);
				$value['Basa_Netto']=round($value['Basa_Netto'],1);
				$value['Basa_NumTS']=round($value['Basa_NumTS'],1);
			}
			return $result_array[0];
		}
		catch( PDOException $err )
		{
			return; //Ошибка связи с базой данных
		}
	}
}
?>