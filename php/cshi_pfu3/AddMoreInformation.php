<?php
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
    
    function AddMoreInfo($it_name, $array)
    {
      $return_value = "";                  //Результирующая строка
      switch ($it_name)
      {
        case "pfu3_ma101":
				case "pfu3_ma102":
				case "pfu3_ma121":
				case "pfu3_ma122":
				case "pfu3_ma141":
				case "pfu3_ma142":
				case "pfu3_ma161":
				case "pfu3_ma162":
				case "pfu3_ma181":
				case "pfu3_ma182":
          $value = trim(sprintf("%.1f", $array[$it_name]));
          $return_value = "$value кг";
        break;
		case "pfu5_a1_5":
			$value = round(str_replace(",",".",$array[$it_name]),2);
			if ($value<0.4 || $value>0.7)
				$alarm="True";
			else
				$alarm="False";
			$return_value = "$value МПа;alarm1=$alarm";
        break;
		case "pfu5_a2_5":
			$value = round(str_replace(",",".",$array[$it_name]),2);
			if ($value<0.4 || $value>1.0)
				$alarm="True";
			else
				$alarm="False";
			$return_value = "$value МПа;alarm2=$alarm";
        break;
				case "pfu3_a5":
					$value = trim(sprintf("%.1f", $array[$it_name]));
          $return_value = "$value А";
        break;
				case "pfu3_d123":
				case "pfu3_di148":
					if ($array[$it_name]=="True")
						$value="False";
					else
						$value="True";
          $return_value = "$value";
        break;
      }
      return $return_value;
    }

    function CreateResultString($str)
    {
      $ElementsCount = substr_count($str, ";");
      $Massive = array();
      $result_str = '';
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
			return $result_str;      
    }
?>