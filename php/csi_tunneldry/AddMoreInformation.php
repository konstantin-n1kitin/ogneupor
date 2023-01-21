<?php
//  header("Content-type: text/html; charset=windows-1251");
//  $BaseStr = "P_3C=555;T_left_3C=777;T_3C_right=676;T_10C_left=133;";
//  $BaseStr = "T_left_3C=0.0;T_right_3C=0.0;T_left_5=0.0;T_right_5=0.0;T_left_9=0.0;T_left_13=0.0;T_left_15=0.0;T_right_15=0.0;T_right_18=0.0;T_left_19=0.0;T_left_21=0.0;T_left_22=0.0;T_right_24=0.0;T_left_29=0.0;T_right_35=0.0;T_left_40=0.0;T_30-31=0.0;T_34-35=0.0;T_hot_air=0.0;T_out_gas=0.0;T_left_10C=0.0;T_right_10C=0.0;T_15sw=0.0;T_20sw=0.0;T_23sw=0.0;F_gas_left_zone1=0.0;F_gas_right_zone1=0.0;F_gas_left_zone2=0.0;F_air_right_zone2=0.0;F_gas_left_zone3=0.0;F_air_right_zone3=0.0;F_air_left_zone1=0.0;F_air_right_zone1=0.0;F_air_left_zone2=0.0;F_gas_right_zone2=0.0;F_air_left_zone3=0.0;F_gas_right_zone3=0.0;F_air_raspred=0.0;F_air_38=0.0;F_hot_air=0.0;F_gas_on_furn_current=0.0;F_air_on_burn=0.0;Ventilator_1=1;";
  
  $StartPos = 0;    //Начальная позиция поиска
  $result_str = ""; //Результирующая строка вывода
  
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
    function AddMoreInfo($it_name, $array)
    {
      $return_value = "";                  //Результирующая строка
      $value = "";
      switch (FindParametrType($it_name))
      {
        case "IM": 
          $value = sprintf("%.0f", $array[$it_name]);
          $return_value = "$value %";
          break;
        case "P":         
          $value = sprintf("%.1f", $array[$it_name]);
          $return_value = "$value Па";
          break;
        case "dP": 
          $value = sprintf("%.1f", $array[$it_name]);
          $return_value = "P=$value кгс/м<sup>2</sup>";
          break;
        case "T":         
          $value = sprintf("%.1f", $array[$it_name]);
          $return_value = "$value <sup>o</sup>C";
          break;
        case "F":         
          $value = sprintf("%.1f", $array[$it_name]);
          if ($it_name == 'F_tunnel_dry_2')
			$return_value = "$value *10 м<sup>3</sup>/ч";
          if ($it_name == 'F_tunnel_dry_3')
			$return_value = "$value *10 м<sup>3</sup>/ч";
          if ($it_name == 'F_air_hot_generator_1')
			if ($value > 100)
				$return_value = "$value м<sup>3</sup>/ч;Ventilator_1=1";
			else	
				$return_value = "$value м<sup>3</sup>/ч;Ventilator_1=0";
          if ($it_name == 'F_air_hot_generator_2')
			if ($value > 100)
				$return_value = "$value м<sup>3</sup>/ч;Ventilator_2=1";
			else	
				$return_value = "$value м<sup>3</sup>/ч;Ventilator_2=0";
		  if ($it_name != 'F_air_hot_generator_1' and $it_name != 'F_air_hot_generator_2' and $it_name != 'F_tunnel_dry_2' and $it_name != 'F_tunnel_dry_3')
            $return_value = "$value м<sup>3</sup>/ч";
          break;
        default:
          $return_value = "$value";
          break;  
      } 
      return $return_value;
    }  

    function CreateResultString($str)
    {
      $str = str_replace(",", ".", $str);  //Корректируем строку
      $ElementsCount = substr_count($str, ";");
      $Massive = "";
      $result_str = "";
      for ($i = 0; $i < $ElementsCount; $i++) //Заполняем массив: Массив[Имя элемента]=Значение элемента
      {
        $name[$i] = GetElementName($str, $StartPos);
        $value = GetElementValue($str, $StartPos); 
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
//			echo $result_str;
      return $result_str;      
    }
//    echo CreateResultString($BaseStr);
?>