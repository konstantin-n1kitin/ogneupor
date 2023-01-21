<?php
/* Результатом выполнения данного файла будет строка переданная в него объединенная с данными состояний для подсвечивания линий */
	$BasicStr="RF1_in_a_1=257.89;";
    function GetElementName($string, &$index)  
    {
      $position = strpos($string, "=", $index);        //Нашли позицию включения строки
      $leigth = $position - $index;                   //Нашли длину слова
      $ElementName = substr($string, $index, $leigth); //Получили имя элемента
      return $ElementName;
    }
    function GetElementValue($string, &$index)
    {
      $position_beg = strpos($string, "=", $index);                //Нашли позицию включения строки
      $position_end = strpos($string, ";", $index);                //Нашли позицию включения строки
      $leigth = $position_end - 1 - $position_beg;                 //Нашли длину слова
      $ElementValue = substr($string, $position_beg + 1, $leigth); //Получили значение элемента
      $index = $position_end + 1;
      return $ElementValue;
    }
    function FindParametrType($Name)  /* Выборка типа параметра */
    {
      $pos = strpos($Name, "_", 0);   //Нашли позицию включения строки
      return substr($Name, 0, $pos);
    }

    function Dimosos($value1, $value2)
    {
      $result = 0;
      if ($value1 == 1 or $value2 == 1)
      {
		$result = 1;
      }
      else
        $result = 0;
      return $result;   
    }	
	
	
    function AddMoreInfo($it_name, $array)
    {
      $return_value = "";                  //Результирующая строка
      $value = "";
	  
		if ($it_name == "Dimosos_25_1" or $it_name == "Dimosos_26_1" or $it_name == "Dimosos_27_1" or $it_name == "Dimosos_28_1")
		{
		  switch ($it_name)
		  {
			case "Dimosos_25_1": 
				$value = Dimosos($array["Dimosos_25_1"], $array["Dimosos_25_2"]);
				if ($array["Dimosos_25_1"] == 1)
					$return_value = "$array[$it_name];dimosos_25=$value;MP_dimosos_25=1;CHP_dimosos_25=0";
				if ($array["Dimosos_25_2"] == 1)
					$return_value = "$array[$it_name];dimosos_25=$value;MP_dimosos_25=0;CHP_dimosos_25=1";
				break;
			case "Dimosos_26_1": 
				$value = Dimosos($array["Dimosos_26_1"], $array["Dimosos_26_2"]);
				if ($array["Dimosos_26_1"] == 1)
					$return_value = "$array[$it_name];dimosos_26=$value;MP_dimosos_26=1;CHP_dimosos_26=0";
				if ($array["Dimosos_26_2"] == 1)
					$return_value = "$array[$it_name];dimosos_26=$value;MP_dimosos_26=0;CHP_dimosos_26=1";
				break;
			case "Dimosos_27_1": 
				$value = Dimosos($array["Dimosos_27_1"], $array["Dimosos_27_2"]);
				if ($array["Dimosos_27_1"] == 1)
					$return_value = "$array[$it_name];dimosos_27=$value;MP_dimosos_27=1;CHP_dimosos_27=0";
				if ($array["Dimosos_27_2"] == 1)
					$return_value = "$array[$it_name];dimosos_27=$value;MP_dimosos_27=0;CHP_dimosos_27=1";
				break;
			case "Dimosos_28_1": 
				$value = Dimosos($array["Dimosos_28_1"], $array["Dimosos_28_2"]);
				if ($array["Dimosos_28_1"] == 1)
					$return_value = "$array[$it_name];dimosos_28=$value;MP_dimosos_28=1;CHP_dimosos_28=0";
				if ($array["Dimosos_28_2"] == 1)
					$return_value = "$array[$it_name];dimosos_28=$value;MP_dimosos_28=0;CHP_dimosos_28=1";
				break;
		  }		
		}
		else 
		{
		  switch (FindParametrType($it_name))
		  {
			case "P":         
			  $value = sprintf("%.1f", $array[$it_name]);
			  if($it_name == "P_gas_obsh_value" or $it_name == "P_RF2_gas_value" or $it_name == "P_RF1_gas_value")
				$return_value = "$value кПа";
			  else	
				$return_value = "$value Па";
			  break;
			case "T":         
			  $value = sprintf("%.1f", $array[$it_name]);
			  $return_value = "$value <sup>o</sup>C";
			  break;
			case "CO":         
			  $value = sprintf("%.1f", $array[$it_name]);
			  $return_value = "$value %";
			  break;
			case "Uroven":         
			  $value = sprintf("%.1f", $array[$it_name]);
			  $return_value = "$value %";
			  break;
			case "Vesi":         
			  $value = sprintf("%.1f", $array[$it_name]);
			  $return_value = "$value %";
			  break;
			case "Shiber":         
			  $value = sprintf("%.1f", $array[$it_name]);
			  switch ($it_name)
			  {
			    case "Shiber_RF1_gas_value":
				  if ($value < 1 or $value > 100)
					$return_value = "$value %;Shiber_RF1_gas=0";	
				  else	
					$return_value = "$value %;Shiber_RF1_gas=1";	
				break;
			    case "Shiber_RF2_gas_value":
				  if ($value < 1 or $value > 100)
					$return_value = "$value %;Shiber_RF2_gas=0";	
				  else	
					$return_value = "$value %;Shiber_RF2_gas=1";	
				break;
			    case "Shiber_RF1_vozduh_value":
				  if ($value < 1 or $value > 100)
					$return_value = "$value %;Shiber_RF1_vozduh=0";	
				  else	
					$return_value = "$value %;Shiber_RF1_vozduh=1";	
				break;
			    case "Shiber_RF2_vozduh_value":
				  if ($value < 1 or $value > 100)
					$return_value = "$value %;Shiber_RF2_vozduh=0";	
				  else	
					$return_value = "$value %;Shiber_RF2_vozduh=1";	
				break;
			  }
			  $return_value = $return_value;
			  break;
			case "Nagr":         
			  $value = sprintf("%.1f", $array[$it_name]);
			  $return_value = "$value %";
			  break;
			case "F":         
			  $value = sprintf("%.1f", $array[$it_name]);
			  if ($it_name == 'F_hot_air' or $it_name == 'F_gas_on_furn_current' or $it_name == 'F_gas_on_furn_task')
				$return_value = "$value *10<sup>3</sup> м<sup>3</sup>/ч";
			  else
				$return_value = "$value м<sup>3</sup>/ч";
			  break;
			default:
			  $return_value = "";
			  break;  
		  }
		}
      return $return_value;
    }	
	
    function CreateResultString($str)
    {
      $str = str_replace(",", ".", $str);  //Корректируем строку
      $ElementsCount = substr_count($str, ";");
      $Massive = array();
      $result_str = '';
      for ($i = 0; $i < $ElementsCount; $i++) //Заполняем массив: Массив[Имя элемента]=Значение элемента
      {
        $name[$i] = GetElementName($str, $StartPos);
//        $value = GetElementValue($str, $StartPos); 
		switch($name[$i])
		{
			case "T_gas_obsh_value": // Общая температура газа	
				$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
			break;
			case 'P_gas_obsh_value':  // Общее давление газа	
				$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
			break;	
			case 'P_RF2_pil_cam_value':  // Разрежение в пылевой камере (ВП2)	
				$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
			break;
			case 'P_RF2_pered_skruber_value':  // Разрежение перед скруббером	(ВП2)
				$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
			break;
			case 'P_RF2_pered_ciklon_LK_value':  // Разрежение перед циклоном левый канал (ВП2)
				$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
			break;
			case 'P_RF2_pered_ciklon_PK_value':  // Разрежение перед циклоном правый канал (ВП2)
				$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
			break;
			case 'P_RF2_pered_electr_filtr_LK_value':  // Разрежение перед электрофильтром левый канал (ВП2)
				$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
			break;
			case 'P_RF2_pered_electr_filtr_PK_value':  // Разрежение перед электрофильтром правый канал (ВП2)
				$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
			break;
			case 'P_RF2_pered_dimosos_value':  // Разрежение перед дымососом (ВП2)
				$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
			break;
			case 'T_RF2_pil_cam_value':  // Температура в пылевой камере (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'CO_RF2_value':  // Анализ на «СО» (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'T_RF2_pered_ciklon_LK_value':  // Температура перед циклоном левый канал (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'T_RF2_pered_ciklon_PK_value':  // Температура перед циклоном правый канал (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'T_RF2_pered_electr_filtr_LK_value':  // Температура перед электрофильтром левый канал (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'T_RF2_pered_electr_filtr_PK_value':  // Температура перед электрофильтром правый канал (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'T_RF2_pered_dimosos_value':  // Температура перед дымососом (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'F_RF2_ventilyatornii_vozduh_value':  // Расход вентиляционного воздуха (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'F_RF2_gas_value':  // Расход газа (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'P_RF2_gas_value':  // Давление газа (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'Vesi_RF2_value':  // Весы (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			break;
			case 'Nagr_pitatel_value':  // Нагрузка питателя (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'Uroven_RF2_value':  // Уровень в бункере выгрузки пыли (ПВ2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
/*			case 'Shiber_RF2_gas_value':  // ИМ газ (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
				if ($value < 1 or $value > 100)
					$value = "$value;Shiber_RF2_gas=0";	
				else	
					$value = "$value;Shiber_RF2_gas=1";	
			break;
			case 'Shiber_RF2_vozduh_value':  // ИМ воздух (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
				if ($value < 1 or $value > 100)
					$value = "$value;Shiber_RF2_vozduh=0";	
				else	
					$value = "$value;Shiber_RF2_vozduh=1";	
			break;*/
			case 'F_sjatii_vozduh_gazoochistka_value':  // Расход сжатого воздуха на газоочистку (ВП2)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
				
				
			case 'P_RF1_pil_cam_value':  // Разрежение в пылевой камере (ВП1) 
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'P_RF1_pered_skruber_value':  // Разрежение перед скруббером (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'P_RF1_pered_ciklon_LK_value':  // Разрежение перед циклоном левый канал (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'P_RF1_pered_ciklon_PK_value':  // Разрежение перед циклоном правый канал (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'P_RF1_pered_electr_filtr_LK_value':  // Разрежение перед электрофильтром левый канал (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'P_RF1_pered_electr_filtr_PK_value':  // Разрежение перед электрофильтром правый канал (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'P_RF1_pered_dimosos_value':  // Разрежение перед дымососом (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'T_RF1_pil_cam_value':  // Температура в пылевой камере (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'CO_RF1_value':  // Анализ на СО (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'T_RF1_pered_ciklon_LK_value':  // Температура перед циклоном левый канал (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'T_RF1_pered_ciklon_PK_value':  // Температура перед циклоном правый канал (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'T_RF1_pered_electr_filtr_LK_value':  // Температура перед электрофильтром левый канал (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'T_RF1_pered_electr_filtr_PK_value':  // Температура перед электрофильтром правый канал (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'T_RF1_pered_dimosos_value':  // Температура перед дымососом (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'F_RF1_ventilyatornii_vozduh_value':  // Расход вентиляторного воздуха (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'F_RF1_gas_value':  // Расход газа (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'P_RF1_gas_value':  // Давление газа (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'Vesi_RF1_value':  // Весы (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
			case 'Nagr_pitatel_RF1_value':  // Нагрузка питателя (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
			break;
/*			case 'Shiber_RF1_gas_value':  // Положение ИМ расхода газа (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
				if ($value < 1 or $value > 100)
					$value = "$value;Shiber_RF1_gas=0";	
				else	
					$value = "$value;Shiber_RF1_gas=1";	
			break;
			case 'Shiber_RF1_vozduh_value':  // Положение ИМ расхода воздуха (ВП1)
				$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
				if ($value < 1 or $value > 100)
					$value = "$value;Shiber_RF1_vozduh=0";	
				else	
					$value = "$value;Shiber_RF1_vozduh=1";	
			break;*/


			default:
				$value = GetElementValue($str, $StartPos);
			break;
		}
			
			
//        $value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
        $Massive[$name[$i]] = $value;
      }
  
      for ($i = 0; $i < $ElementsCount; $i++) //Добавляем в строку новые значения
      {
        $newvalue = AddMoreInfo($name[$i], $Massive);
        if ($newvalue != null or $newvalue != "")
          $value = $newvalue;
        else
          $value = $Massive[$name[$i]];  
        $result_str = "$result_str$name[$i]=$value;";
      } 
//				print_r($result_str);
      return $result_str;
    }
//	echo CreateResultString($BasicStr);

?>