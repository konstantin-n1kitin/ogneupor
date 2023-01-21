<?php
//defined('SYSPATH') or die('No direct script access.');
//include ('sql_queries.php');
class Model_Askueshorts //extends Kohana_Model
{
  public function get_askue_short($arg_dt_begin, $arg_dt_end, $arg_report_type, $arg_param_id)
  {
		$channels_ids = array('cshi_oxygen'=>array('1'=>'2447',
											   '3'=>'2390',
											   '5'=>'326',
											   '7'=>'2698'),
    'cshi_rotating_oven_1_coke_gas'=>array('1'=>'2369',
										   '3'=>'2448',
										   '5'=>'325',
										   '7'=>'3008'),
    'cshi_rotating_oven_2_coke_gas'=>array('1'=>'2370',
										   '3'=>'2449',
										   '5'=>'24',
										   '7'=>'3009'),
    'cshi_rotary_drier1_coke_gas'=>array('1'=>'94',
      									 '3'=> '95',
      									 '5'=> '96',
      									 '7'=> '322'),
    'cshi_rotary_drier2_coke_gas'=>array('1'=>'323',
      									 '3'=> '2383',
      									 '5'=> '2384',
      									 '7'=> '2395'),
    'cshi_rotary_drier3_coke_gas'=>array('1'=>'2396',
      									 '3'=> '2397',
      									 '5'=> '3183',
      									 '7'=> '3185'),
    'cshi_natural_gas'=>array('1'=>'2642',
   							  '2'=>'2643',
							  '3'=>'3007',),
    'cshi_compressed_air'=>array('1'=>'2446',
   								 '3'=>'2391',
								 '5'=>'327',
								 '7'=>'2647'),
    'cshi_compressed_air_gas_cleaning'=>array('1'=>'3612',
   								 '3'=>'3613',
								 '5'=>'3615'),
//								 '7'=>'3616'),
    'cshi_thermalclamping_water'=>array('1'=>'2502',  
										'3'=>'2561',  
										'5'=>'2503', 
										'7'=>'2562',
										'9'=>'2572',
										'91'=>'2447',
										'95'=>'2447'),
    'cshi_tunnel_furnaces_coke_gas'=>array('1'=>'3240',
             							   '3'=>'67',
					          			   '5'=>'68',
									       '7'=>'328'),
    'csi_oxygen'=>array('1'=>'2342',
				        '3'=>'2442',
    				    '5'=>'2443',
                        '7'=>'2615'),
    'csi_steam'=>array('1'=>'2420',
					   '2'=>'2430',
					   //'5'=>'2365',
					   '3'=>'2691',
					   '4'=>'2693'),
		'csi_steam_teh'=>array('1'=>'2239',
					   '2'=>'2238',
					   //'5'=>'2365',
					   '3'=>'2240',
					   '4'=>'2246'),
    'csi_compressed_air'=>array('1'=>'2424',
								'3'=>'2434',
				    			'5'=>'2364',
				                '7'=>'2616'),
    'csi_thermalclamping_water'=>array('1'=>'2438',
									   '3'=>'2436',
									   '5'=>'2439',
									   '7'=>'2437',
									   '9'=>'2568',
									   '91'=>'2342',
									   '95'=>'2342'),
    'csi_rotary_driers_coke_gas_DPU'=>array('1'=>'2488',
											'3'=>'2487',
								    		'5'=>'1945',
								            '7'=>'1944'),
	'csi_rotary_driers_coke_gas_DPandFU'=>array('1'=>'2423',
												'3'=>'2433',
								    			'5'=>'2348',
								                '7'=>'3332'),														  
    'csi_natural_gas'=>array('1'=>'2417',
						     '3'=>'2427',
		    				 '5'=>'2344',
		                     '7'=>'2649'),

    'cmdo_natural_gas'=>array('1'=>'595'),
									   
	'csi_forge_furnaces_coke_gas'=>array('1'=>'2088',
						                 '3'=>'2087',
		    				             '5'=>'2086',
		                                 '7'=>'2090'),
   
   'csi_coke_gas_heating_fu_1_2'=>array('1'=>'2441',
						                '3'=>'2440',
		    				            '5'=>'2091',
		                                '7'=>'2445'),
	'total_oxygen'=>array('1'=>2698,'2'=>2615),
	'total_coke_gas'=>array('1'=>3008,'2'=>3009,'3'=>322,'4'=>2395,'5'=>3185,'6'=>328,'7'=>1944,'8'=>2090, '9'=>2445),
	'total_steam'=>array('1'=>2691),
	'total_natural_gas'=>array('1'=>2641,'2'=>2649, '3'=>689),
	'total_compressed_air'=>array('1'=>2647,'2'=>2616),
	'electro'=>array('1'=>181,'2'=>183,'3'=>184,'4'=>185,'5'=>186,'6'=>187,'7'=>188,'8'=>189,'9'=>190,'10'=>191,'11'=>192,'12'=>193,'13'=>194,'14'=>0),
	'cshi_compressed_air_formovka'=>array('1'=>3892,'2'=>3893,'3'=>3895),
            'drinking_water_1'=>array('1'=>2502,'3'=>2502),
            'drinking_water_2'=>array('1'=>2502,'3'=>2502),
            'drinking_water_3'=>array('1'=>2502,'3'=>2502),
            'drinking_water_4'=>array('1'=>2502,'3'=>2502),
            'drinking_water_5'=>array('1'=>2502,'3'=>2502),
            'drinking_water_6'=>array('1'=>2502,'3'=>2502),

    );
    $data = array();//массив для данных отчета
    if (array_key_exists($arg_report_type, $channels_ids) AND
        array_key_exists($arg_param_id, $channels_ids[$arg_report_type]))
    {
	    $date_begin = new DateTime($arg_dt_begin);
	    $date_end = new DateTime($arg_dt_end);

		//$date_begin->sub(new DateInterval('PT2H'));
		//$date_end->sub(new DateInterval('PT2H'));
	   	if ($arg_report_type=='cmdo_natural_gas')
		{
			$dbhost = "CMDO-ASKU";
			$dbname = "cmdo";
			$dbuser = "sa";
			$dbpass = "";
		}
		else
		{
			$dbhost = "ASKUSERVER2";
			$dbname = "oup";
			$dbuser = "sa";
			$dbpass = "metallurg";
		}
		
	    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
	                   ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
	    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		if (substr($arg_report_type,0,strpos($arg_report_type,"_"))!="total")
		{
			if ($arg_param_id==14 and $arg_report_type=='electro') {
				$sql_order='';
				foreach($channels_ids[$arg_report_type] as $value) {
					$sql_order.='ID_Channel = '.$value.' OR ';
				}
				$sql = "SELECT SUM(Value) AS Value,  DATEADD(hh, 0, MeasureDate) AS Date
						FROM Shorts
						WHERE (".substr($sql_order,0,-4).") AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
						GROUP BY MeasureDate
						ORDER BY Date;";
				$result = $db->prepare($sql);
				$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
										 ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
			}
			else {
				$sql = "SELECT Value,  DATEADD(hh, 0, MeasureDate) AS Date
						FROM Shorts
						WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
						ORDER BY Date;";
				$result = $db->prepare($sql);
				$result->execute(array(':id_channel'=>$channels_ids[$arg_report_type][$arg_param_id],
										 ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
										 ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
			}
			if ($result->rowCount()==0) {
				$sql = "SELECT Value,  DATEADD(hh, 0, MeasureDate) AS Date
					FROM Mains
					WHERE (ID_Channel = :id_channel) AND (MeasureDate > :dt_begin) AND (MeasureDate <= :dt_end)
					ORDER BY Date;";
				$result = $db->prepare($sql);
				$result->execute(array(':id_channel'=>$channels_ids[$arg_report_type][$arg_param_id],
								   ':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
								   ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
			}
			
		}
		else
		{
			$sql_condition="";
			for($i=1;$i<=count($channels_ids[$arg_report_type])-1;$i++)
			{
				$sql_condition.='ID_Channel = '.$channels_ids[$arg_report_type][$i].' OR ';
			}
			$sql_condition.='ID_Channel = '.$channels_ids[$arg_report_type][$i];
			$sql = 'SELECT SUM(Value) AS Value, MeasureDate AS Date
				FROM Shorts
				WHERE (MeasureDate >= :dt_begin) AND (MeasureDate < :dt_end) AND ('.$sql_condition.')
				GROUP BY MeasureDate
				ORDER BY MeasureDate;';
			$result = $db->prepare($sql);
			$result->execute(array(':dt_begin'=>$date_begin->format('Y-m-d H:i:s'),
								   ':dt_end'=>$date_end->format('Y-m-d H:i:s')));
		}
	   	$data = $result->fetchAll(PDO::FETCH_ASSOC);
		
		// Температура теплофикационной воды
		if ($arg_param_id==91) {
			foreach ($data as $i => &$point) {
				$normal_temp=array('-33'=>93.82,'-32'=>92.63,'-31'=>91.44,'-30'=>90.24,'-29'=>89.04,'-28'=>87.84,'-27'=>86.63,'-26'=>85.41,'-25'=>84.19,'-24'=>82.96,'-23'=>81.73,'-22'=>80.49);
				if (round($point['Value'])<=-34) {
					$point['Value']=95;
				}
				else if (round($point['Value'])>=-21) {
					$point['Value']=80;
				}
				else {
					$point['Value']=$normal_temp[round($point['Value'])];
				}
			}
		}
		if ($arg_param_id==95) {
			$normal_temp=array('-33'=>69.30,'-32'=>68.59,'-31'=>67.88,'-30'=>67.17,'-29'=>66.45,'-28'=>65.72,'-27'=>64.99,'-26'=>64.26,'-25'=>63.51,'-24'=>62.77,'-23'=>62.02,'-22'=>61.26,'-21'=>61.09,'-20'=>61.30,'-19'=>61.52,'-18'=>61.73,'-17'=>61.94,'-16'=>62.16,'-15'=>62.37,'-14'=>62.58,'-13'=>62.79,'-12'=>63.00,'-11'=>63.21,'-10'=>63.43,'-9'=>63.64,'-8'=>63.85,'-7'=>64.06,'-6'=>64.27,'-5'=>64.48,'-4'=>64.69,'-3'=>64.89,'-2'=>65.10,'-1'=>65.31,'0'=>65.52,'1'=>65.73,'2'=>65.93,'3'=>66.14,'4'=>66.35,'5'=>66.55,'6'=>66.76,'7'=>66.97);
			foreach ($data as $i => &$point) {
				if (round($point['Value'])<=-34) {
					$point['Value']=70;
				}
				else if (round($point['Value'])>=8) {
					$point['Value']=67.17;
				}
				else {
					$point['Value']=$normal_temp[round($point['Value'])];
				}
			}
		}
		if ($arg_report_type=='electro') {
			foreach ($data as $i => &$point) {
				$point['Value']=$point['Value']*20;
			}
		}
	  }
    $result_array['data']=$data;
   	return $result_array;
  }
}
?>