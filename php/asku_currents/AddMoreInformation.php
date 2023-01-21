<?php
//  header("Content-type: text/html; charset=windows-1251");
//  require_once("../config/config.php");
//  require_once("C:\wamp\www\kohana\config\config.php");

    // -------- ТЕПЛОФИКАЦИОННАЯ ВОДА ЦШИ --------
    // ID = 2500   V-канал    - расход прямой
    // ID = 2501   V-канал    - расход обратной
    // ID = 2502   V-канал    - температура прямой
    // ID = 2503   V-канал    - температура обратной
    // ID = 2554   V-канал    - тепловая мощность прямой
    // ID = 2555   V-канал    - тепловая мощность обратной
    // ID = 2450   G-канал    - расход прямой
    // ID = 2451   G-канал    - расход обратной
    // ID = 2459   G-канал    - температура прямой
    // ID = 2461   G-канал    - температура обратной
    // -------- ТЕПЛОФИКАЦИОННАЯ ВОДА ЦСИ --------
    // ID = 2506   G-канал    - расход прямой
    // ID = 2507   G-канал    - расход обратной
    // ID = 2515   G-канал    - температура прямой
    // ID = 2517   G-канал    - температура обратной
    // ID = 2543   V-канал    - тепловая мощность прямой
    // ID = 2544   V-канал    - тепловая мощность обратной
    // ID = 2436   V-канал    - расход прямой
    // ID = 2437   V-канал    - расход обратной
    // ID = 2438   V-канал    - температура прямой
    // ID = 2439   V-канал    - температура обратной
    // -------- СЖАТЫЙ ВОЗДУХ ЦШИ --------
    // ID = 2647   V-канал    - расход
    // ID = 327    V-канал    - перепад давления
    // ID = 2391   V-канал    - давление
    // ID = 2446   V-канал    - температура
    // ID = 3      А-канал    - температура
    // ID = 10     А-канал    - давление
    // ID = 11     А-канал    - перепад давления
    // -------- СЖАТЫЙ ВОЗДУХ ЦШИ --------
    // ID = 3615   V-канал    - расход
    // ID = 3613   V-канал    - давление
    // ID = 3612   V-канал    - температура
    // -------- СЖАТЫЙ ВОЗДУХ ЦСИ --------
    // ID = 2616   V-канал    - расход
    // ID = 2225   G-канал    - перепад давления
    // ID = 2226   G-канал    - давление
    // ID = 2234   G-канал    - температура
    // ID = 2424   V-канал    - температура
    // ID = 2434   V-канал    - давление
    // ID = 2364   V-канал    - перепад давления
    // -------- ПРИРОДНЫЙ ГАЗ ЦСИ --------
    // ID = 2649   V-канал    - расход
    // ID = 2344   V-канал    - перепад давления
    // ID = 2427   V-канал    - давление
    // ID = 2417   V-канал    - температура
    // ID = 2175   А-канал    - температура
    // ID = 2162   А-канал    - давление
    // ID = 2161   А-канал    - перепад давления
    // -------- ПРИРОДНЫЙ ГАЗ ЦШИ (высокая сторона) --------
    // ID = 3007   V-канал    - расход
    // ID = 2643   V-канал    - давление
    // ID = 2642   V-канал    - температура
    // -------- КИСЛОРОД ЦШИ --------
    // ID = 2698   V-канал    - расход
    // ID = 326    V-канал    - перепад давления
    // ID = 2390   V-канал    - давление
    // ID = 2447   V-канал    - температура
    // ID = 4      А-канал    - температура
    // ID = 12     А-канал    - давление
    // ID = 13     А-канал    - перепад давления
    // -------- КИСЛОРОД ЦСИ --------
    // ID = 2615   V-канал    - расход
    // ID = 2443   V-канал    - перепад давления
    // ID = 2442   V-канал    - давление
    // ID = 2342   V-канал    - температура
    // ID = 2308   G-канал    - температура
    // ID = 2312   G-канал    - давление
    // ID = 2311   G-канал    - перепад давления
    // -------- ПАР ЦСИ --------
    // ID = 2365   V-канал    - перепад давления
    // ID = 2420   V-канал    - температура
    // ID = 2430   V-канал    - давление
    // ID = 2691   V-канал    - расход
    // ID = 2693   V-канал    - тепловая мощность
    // ID = 2205   G-канал    - перепад давления
    // ID = 2206   G-канал    - давление
    // ID = 2207   G-канал    - температура
    // -------- КОКСОВЫЙ ГАЗ В/П 1 --------
    // ID = 325    V-канал    - перепад давления
    // ID = 2369   V-канал    - температура
    // ID = 2448   V-канал    - давление
    // ID = 3008   V-канал    - расход
    // ID = 7      А-канал    - перепад давления
    // ID = 6      А-канал    - давление
    // ID = 1      А-канал    - температура
    // -------- КОКСОВЫЙ ГАЗ В/П 2 --------
    // ID = 24     V-канал    - перепад давления
    // ID = 2370   V-канал    - температура
    // ID = 2449   V-канал    - давление
    // ID = 3009    V-канал    - расход
    // ID = 9      А-канал    - перепад давления
    // ID = 8      А-канал    - давление
    // ID = 2      А-канал    - температура
    // -------- КОКСОВЫЙ ГАЗ СУШ. БАРАБАН 1 --------
    // ID = 96     V-канал    - перепад давления
    // ID = 94     V-канал    - температура
    // ID = 95     V-канал    - давление
    // ID = 322    V-канал    - расход
    // ID = 88     А-канал    - перепад давления
    // ID = 2413   А-канал    - давление
    // ID = 84     А-канал    - температура
    // -------- КОКСОВЫЙ ГАЗ СУШ. БАРАБАН 2 --------
    // ID = 2384   V-канал    - перепад давления
    // ID = 323    V-канал    - температура
    // ID = 2383   V-канал    - давление
    // ID = 2395   V-канал    - расход
    // ID = 90     А-канал    - перепад давления
    // ID = 89     А-канал    - давление
    // ID = 85     А-канал    - температура
    // -------- КОКСОВЫЙ ГАЗ СУШ. БАРАБАН 3 --------
    // ID = 3183   V-канал    - перепад давления
    // ID = 2396   V-канал    - температура
    // ID = 2397   V-канал    - давление
    // ID = 3185   V-канал    - расход
    // ID = 92     А-канал    - перепад давления
    // ID = 2414   А-канал    - давление
    // ID = 86     А-канал    - температура
	// -------- Коксовый газ на туннел.печи ЦШИ (резервный газопровод) -------- 
    // ID = 3240   V-канал    - температура
    // ID = 67     V-канал    - давление
    // ID = 68     V-канал    - перепад давления
    // ID = 328    V-канал    - расход
    // -------- КОКСОВЫЙ ГАЗ СУШ. БАРАБАН 3 --------
    // ID = 2086   V-канал    - перепад давления
    // ID = 2088   V-канал    - температура
    // ID = 2087   V-канал    - давление
    // ID = 2090   V-канал    - расход
    // -------- КОКСОВЫЙ ГАЗ ДП и ФУ ЦСИ --------
    // ID = 2348   V-канал    - перепад давления
    // ID = 2423   V-канал    - температура
    // ID = 2433   V-канал    - давление
    // ID = 3332   V-канал    - расход
    // -------- КОКСОВЫЙ ГАЗ СУШ. БАРАБАН ОБЩ --------  ?????
    // ID = 1945   V-канал    - перепад давления
    // ID = 2488   V-канал    - температура
    // ID = 2487   V-канал    - давление
    // ID = 1944   V-канал    - расход
    // -------- КОКСОВЫЙ ГАЗ НА НАГРЕВАТЕЛЬНЫЕ ПЕЧИ 1-2 ТЕРМИЧЕСКОГО УЧАСТКА РМУ ЦСИ --------
    // ID = 2091   V-канал    - перепад давления
    // ID = 2441   V-канал    - температура
    // ID = 2440   V-канал    - давление
    // ID = 2445   V-канал    - расход
	

		$result_str = "";
		$base_sql = "SELECT ID_Channel, MeasureDate, Value, State
								 FROM   Currents
								 WHERE  ID_Channel = 2500 or ID_Channel = 2501 or ID_Channel = 2502 or ID_Channel = 2503 or ID_Channel = 2554 or ID_Channel = 2555 or ID_Channel = 2450 or ID_Channel = 2451 or 
										ID_Channel = 2436 or ID_Channel = 2437 or ID_Channel = 2438 or ID_Channel = 2439 or ID_Channel = 2543 or ID_Channel = 2544 or ID_Channel = 2506 or ID_Channel = 2507 or ID_Channel = 2515 or ID_Channel = 2517 or
										ID_Channel = 2364 or ID_Channel = 2424 or ID_Channel = 2434 or ID_Channel = 2616 or ID_Channel = 2225 or ID_Channel = 2226 or ID_Channel = 2234 or
										ID_Channel = 2647 or ID_Channel = 327 or ID_Channel = 2391 or ID_Channel = 2446 or ID_Channel = 3 or ID_Channel = 10 or ID_Channel = 11 or
										ID_Channel = 2344 or ID_Channel = 2417 or ID_Channel = 2427 or ID_Channel = 2649 or ID_Channel = 2161 or ID_Channel = 2162 or ID_Channel = 2175 or
										ID_Channel = 2698 or ID_Channel = 326 or ID_Channel = 2390 or ID_Channel = 2447 or ID_Channel = 4 or ID_Channel = 12 or ID_Channel = 13 or
										ID_Channel = 2615 or ID_Channel = 2342 or ID_Channel = 2442 or ID_Channel = 2443 or ID_Channel = 2308 or ID_Channel = 2311 or ID_Channel = 2312 or
										ID_Channel = 2365 or ID_Channel = 2420 or ID_Channel = 2430 or ID_Channel = 2691 or ID_Channel = 2693 or ID_Channel = 2205 or ID_Channel = 2206 or ID_Channel = 2207 or
										ID_Channel = 2643 or ID_Channel = 2642 or ID_Channel = 3007 or
										ID_Channel = 325 or ID_Channel = 2369 or ID_Channel = 2448 or ID_Channel = 3008 or ID_Channel = 24 or ID_Channel = 2370 or ID_Channel = 2449 or ID_Channel = 3009 or
										ID_Channel = 96 or ID_Channel = 94 or ID_Channel = 95 or ID_Channel = 322 or ID_Channel = 2395 or ID_Channel = 2384 or ID_Channel = 323 or ID_Channel = 2383 or
										ID_Channel = 3183 or ID_Channel = 2396 or ID_Channel = 2397 or ID_Channel = 3185 or ID_Channel = 1945 or ID_Channel = 1944 or ID_Channel = 2487 or ID_Channel = 2488 or ID_Channel = 3240 or ID_Channel = 67 or ID_Channel = 68 or ID_Channel = 328 or
										ID_Channel = 2086 or ID_Channel = 2087 or ID_Channel = 2088 or ID_Channel = 2090 or 
										ID_Channel = 1890 or ID_Channel = 1891 or ID_Channel = 1889 or ID_Channel = 1888 or ID_Channel = 1899 or ID_Channel = 1900 or ID_Channel = 1893 or ID_Channel = 1892 or (ID_Channel >= 3208 and ID_Channel<=3224) or (ID_Channel >= 3174 and ID_Channel<=3182) or ID_Channel = 2348 or ID_Channel = 2423 or ID_Channel = 2433 or ID_Channel = 3332 or
										ID_Channel = 2091 or ID_Channel = 2440 or ID_Channel = 2441 or ID_Channel = 2445 or ID_Channel = 3612 or ID_Channel = 3613 or ID_Channel = 3615 or
										ID_Channel = 2238 or ID_Channel = 2239 or ID_Channel = 2240 or ID_Channel = 2246 or ID_Channel = 3895 or ID_Channel = 3893 or ID_Channel = 3892 or ID_Channel = 3899 or
										(ID_Channel>=181 and ID_Channel<=194) or ID_Channel = 2561 or ID_Channel = 2562 or ID_Channel = 2565 or ID_Channel = 2566";
//										ID_Channel = 3183 or ID_Channel = 2396 or ID_Channel = 2397 or ID_Channel = 3185 or ID_Channel = 1945 or ID_Channel = 1944 or ID_Channel = 2487 or ID_Channel = 2488 or ID_Channel = 52 or ID_Channel = 67 or ID_Channel = 68 or ID_Channel = 328 or

		$sql_order_by = " ORDER BY ID_Channel";
		$sql = sprintf("%s%s", $base_sql, $sql_order_by); //Готовый SQL запрос
		try
		{
			//set_time_limit(10);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.'askuserver2'.';database='.'oup'.';Uid='.'sa'.';Pwd='.'metallurg'.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result = $db->query($sql);
			while ($row = $result->fetch())
			{
				$id_channel = $row[0];                        // ID
				$dt = date('d-m-Y H:i:s', strtotime($row[1]));// DT
				$value = sprintf('%.2f', $row[2]);            // Value
				$state = $row[3];                             //State
				$state_array[$id_channel]=$state;
				$data[$id_channel]=$value;
				if ($state == 0)
					$state = '&nbsp;';
				$result_str = "$result_str$id_channel=$value|$state;";
			}
			$db = null;
			//Расчетные значения
			$result_str .= 'oxygen_total='.($data['2698']+$data['2615']).'|&nbsp;;';
			$result_str .= 'koks_gas_csi='.($data['1944']+$data['2090']+$data['3332']+$data['2445']).'|&nbsp;;';
			$result_str .= 'koks_gas_cshi='.($data['3008']+$data['3009']+$data['322']+$data['2395']+$data['3185']+$data['328']).'|&nbsp;;';
			$result_str .= 'koks_gas_total='.($data['1944']+$data['2090']+$data['3008']+$data['3009']+$data['322']+$data['2395']+$data['3185']+$data['328']+$data['2445']).'|&nbsp;;';
			$result_str .= 'compressed_air_total='.($data['2647']+$data['2616']).'|&nbsp;;';
			$result_str .= 'heating_water_total='.($data['']+$data['']).'|&nbsp;;';
			if (($state_array['2342']>0)&&($state_array['2447']>0))
				$temp_outside='-';
			elseif ($state_array['2342']>0)
				$temp_outside=$data['2447'];
			elseif ($state_array['2447']>0)
				$temp_outside=$data['2342'];
			else
				$temp_outside=$data['2342']<$data['2447']?$data['2342']:$data['2447'];
			$result_str .= 'temp_outside='.$temp_outside.'|&nbsp;;';
		}
		catch( PDOException $err )
		{
			return -3; //Ошибка связи с базой данных
		}
    //Считываем данные для ЦМДО
    // -------- ПРИРОДНЫЙ ГАЗ ЦМДО --------
    // ID = 220   V-канал    - перепад давления
    // ID = 221   V-канал    - давление
    // ID = 222   V-канал    - температура
    // ID = 223   V-канал    - расход
    // ID = 33    А-канал    - перепад давления
    // ID = 34    А-канал    - давление
    // ID = 49    А-канал    - температура
	// ID = 595   A-канал    - расход

    // ------------------------------------------------------------------------------------------------
    /* $base_sql = "SELECT ID_Channel, MeasureDate, Value, State
                 FROM   Currents
                 WHERE  ID_Channel = 595 ";
//                 WHERE  ID_Channel = 33 or ID_Channel = 34 or ID_Channel = 49 or ID_Channel = 220 or ID_Channel = 221 or ID_Channel = 222 or ID_Channel = 223"; 
    $sql_order_by = " ORDER BY ID_Channel";
		try
		{
			//set_time_limit(10);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.'CMDO-ASKU'.';database='.'cmdo'.';Uid='.'sa'.';Pwd='.''.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$sql = sprintf("%s%s", $base_sql, $sql_order_by); //Готовый SQL запрос
			$result = $db->query($sql);
			while ($row = $result->fetch())
			{
				$id_channel = $row[0];                        // ID
				$dt = date('d-m-Y H:i:s', strtotime($row[1]));// DT
				$value = sprintf('%.2f', $row[2]);            // Value
				$state = $row[3];                             //State
				if ($state == 0)
					$state = '&nbsp;';
				$result_str = "$result_str$id_channel=$value|$state;";
				$data[$id_channel]=$value;
			}
			$db = null;
		}	
		catch( PDOException $err )
		{
			//return -3; //Ошибка связи с базой данных
		} */
		//Расчетные значения
			$result_str .= 'natural_gas_total='.($data['2649']+$data['3007']+$data['595']).'|&nbsp;;';
			
    echo $result_str;
?>