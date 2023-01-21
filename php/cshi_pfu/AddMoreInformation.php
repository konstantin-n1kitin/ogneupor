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
    
    function GruboTochno($Pitatel, $Grubo, $Tochno)
    {
      $result = 0;
      if ($Pitatel == 1)
      {
		if ($Tochno == 1)
		{
		  if ($Grubo == 1)
			$result = 2;
		  if ($Grubo == 0)	
			$result = 1;
		}
		else
		  $result = 0;
      }
      else
        $result = 0;
      return $result;   
    }
    
    function GruboTochno2($Pitatel, $Grubo, $Tochno)
    {
      $result = 0;
      if ($Pitatel == 1)
      {
		if ($Grubo == 1)
		{
		  if ($Tochno == 1)
			$result = 2;
		  if ($Tochno == 0)	
			$result = 1;
		}
		else
		  $result = 0;
      }
      else
        $result = 0;
      return $result;   
    }

    function AddMoreInfo($it_name, $array)
    {
      $return_value = "";                  //Результирующая строка
      switch ($it_name)
      {
        case "Engine_glass_current":         //ток стакана
          $value = trim(sprintf("%.2f", $array[$it_name]*30/4096));
          $return_value = "$value A";
          break;
        case "Engine_vortex_current":        //ток завихрителя
          $value = trim(sprintf("%.2f", $array[$it_name]*150/4096));
          $return_value = "$value A";
          break;
        case "Moment_engine_press":          //Момент двигателя пресса
          $value = trim(sprintf("%.2f", $array[$it_name]*150/4096));
          $return_value = "$value %";
          break;
        case "Engine_press_current_task":    //Задание тока двигателя пресса
          $value = trim(sprintf("%.2f", $array[$it_name]*150/4096));
          $return_value = "$value A";
          break;
        case "Engine_press_current_real":    //Фактический ток двигателя пресса
          $value = trim(sprintf("%.2f", $array[$it_name]*150/4096));
          $return_value = "$value А";
          break;
          
        case "Cycle_state_num":                //состояние цикла
          switch ($array[$it_name])
          {
            case 0:
              $value = "Цикл стоит";
              break;
            case 1:
              $value = "Загрузка весов";
              break;
            case 2:
              $value = "Выгрузка весов №1";
              break;
            case 3:
              $value = "Смешивание компонента №1";
              break;
            case 4:
              $value = "Выгрузка весов №2";
              break;
            case 5:
              $value = "Смешивание компонента №2";
              break;
            case 6:
              $value = "Выгрузка весов №3";
              break;
            case 7:
              $value = "Смешивание компонента №3";
              break;
            case 8:
              $value = "Выгрузка весов №4";
              break;
            case 9:
              $value = "Смешивание компонента №4";
              break;
            case 10:
              $value = "Открытие днища смесителя";
              break;
            case 11:
              $value = "Выгрузка смесителя";
              break;
            case 12:
              $value = "Закрытие днища смесителя";
              break;
            default:
              $value = "Ошибка";
          }
          $return_value = "$array[$it_name];Cycle_state=$value";
          break;
        case "Pitatel_610_1":                  //Грубо/точно
          Switch (GruboTochno2($array[$it_name], $array["Rough_610"], $array["Equal_610"]))
          {
            case 0:
              $value = "Г/Т";
              break;
            case 1:
              $value = "Точно";
              break;
            case 2:
              $value = "Грубо";
              break;
            default:
              $value = "Г/Т";            
          }
//          $return_value = "$array[$it_name];GruboTochno_610=$value";
          $return_value = "$array[$it_name];GruboTochno_610=$value";
          break;   
        case "Pitatel_611_1":                  //Грубо/точно
		  $value = "Г/Т";
		  $val = "Pitatel_611_1";
		  if ($array["RN_611"] == 1)
		  {
		    if ($array["Pitatel_611_rough"] == 1)
		    {
              $value = "Грубо";
			  $return_value = "Pitatel_611_rough=1;";
		    }
			else
			  $return_value = "Pitatel_611_rough=0;";
			
		    if ($array["Pitatel_611_equal"] == 1)
		    {
              $value = "Точно";
			  $return_value = "Pitatel_611_equal=1;";
		    }
			else
			  $return_value = "Pitatel_611_equal=0;";
			  
		    if ($array["Pitatel_611_rough"] == 1 and $array["Pitatel_611_equal"] == 1)
			{
              $value = "Грубо";
			  $return_value = "Pitatel_611_equal=1;Pitatel_611_rough=1;";
			}  
		  }  
		  else
		  {
            $value = "Г/Т";
		    $return_value = "$array[$val];GruboTochno_611=$value;Pitatel_611_equal=0;Pitatel_611_rough=0;";		
		  }	
		  $return_value = "$array[$val];GruboTochno_611=$value;$return_value";
		  break;
        case "Pitatel_612_1":                  //Грубо/точно
          Switch (GruboTochno($array[$it_name], $array["Rough_612"], $array["Equal_612"]))
          {
            case 0:
              $value = "Г/Т";
              break;
            case 1:
              $value = "Точно";
              break;
            case 2:
              $value = "Грубо";
              break;
            default:
              $value = "Г/Т";            
          }
          $return_value = "$array[$it_name];GruboTochno_612=$value";
          break;   
        case "Pitatel_613_1":                  //Грубо/точно
          Switch (GruboTochno($array[$it_name], $array["Rough_613"], $array["Equal_613"]))
          {
            case 0:
              $value = "Г/Т";
              break;
            case 1:
              $value = "Точно";
              break;
            case 2:
              $value = "Грубо";
              break;
            default:
              $value = "Г/Т";            
          }
          $return_value = "$array[$it_name];GruboTochno_613=$value";
          break; 
        case "Engine_glass":
          if ($array[$it_name] == 1 and $array["Mode_glass"] == 1)
            $return_value = "$array[$it_name];Mode_glass=$array[Mode_glass];MD_glass=1";
          if ($array[$it_name] == 1 and $array["Mode_glass"] == 0)
            $return_value = "$array[$it_name];Mode_glass=$array[Mode_glass];MD_glass=0";
//          if (($array[$it_name] != 0 and $array["Mode_glass"] != 1) or ($array[$it_name] != 1 and $array["Mode_glass"] != 1))
          if (($array[$it_name] == 0 and $array["Mode_glass"] == 0) or ($array[$it_name] == 1 and $array["Mode_glass"] == 0))
            $return_value = "$array[$it_name];Mode_glass=$array[Mode_glass];MD_glass=999";
          break;    
        case "Engine_vortex":
          if ($array[$it_name] == 1 and $array["Mode_vortex"] == 1)
            $return_value = "$array[$it_name];Mode_vortex=$array[Mode_vortex];MD_vortex=1";
          if ($array[$it_name] == 1 and $array["Mode_vortex"] == 0)
            $return_value = "$array[$it_name];Mode_vortex=$array[Mode_vortex];MD_vortex=0";
          if (($array[$it_name] != 0 and $array["Mode_vortex"] != 1) or ($array[$it_name] != 1 and $array["Mode_vortex"] != 1))
            $return_value = "$array[$it_name];Mode_vortex=$array[Mode_vortex];MD_vortex=0";
          break;
        case "Cycle_start":
            $return_value = "$array[$it_name];Cycle_stop=$array[$it_name]";
          break;
        case "Virtual_for_press_1":  
          $press = 0;
          $conveyer = 0;
          if ($array['Virtual_for_press_1'] == 1 or $array["Virtual_for_press_2"] == 1 or $array["Virtual_for_press_3"] == 1 or $array["Virtual_for_press_4"] == 1)
            $press = 1;
          else  
            $press = 0;
          switch ($array['Conveyer_619'])
          {
            case 0:
              $conveyer = 0;
              break;
            case 1:
              $conveyer = 0;
              break;
            case 2:
              $conveyer = 1;
              break;
            case 3:
              $conveyer = 1;
              break;
            case 4:
              $conveyer = 1;
              break;
            case 5:
              $conveyer = 1;
              break;
            case 6:
              $conveyer = 0;
              break;
            default:
              $conveyer = 0;
              break;
          }
          if ($press == 1 and $conveyer == 1)
            $return_value = "$array[$it_name];Engine_press=1;Press=2;Conveyer_619_text=1"; /* Press=0 (все стоит), Press=1 (конвейер вкл, пресс стоит), Press=2 (все работает) */
          if ($press == 0 and $conveyer == 1)
            $return_value = "$array[$it_name];Engine_press=0;Press=1;Conveyer_619_text=1"; 
          if (($press == 0 and $conveyer == 0) or ($press == 1 and $conveyer == 0))
            $return_value = "$array[$it_name];Engine_press=0;Press=0;Conveyer_619_text="; 
          break;
        case "RECIPE_1_1":  
          if ($array[$it_name] == 1 or $array["RECIPE_2_1"] == 1 or $array["RECIPE_3_1"] == 1 or $array["RECIPE_4_1"] == 1)
               $scale_1 = 1;
          else $scale_1 = 0;
          if ($array[$it_name] == 2 or $array["RECIPE_2_1"] == 2 or $array["RECIPE_3_1"] == 2 or $array["RECIPE_4_1"] == 2)
               $scale_2 = 1;
          else $scale_2 = 0;
          if ($array[$it_name] == 3 or $array["RECIPE_2_1"] == 3 or $array["RECIPE_3_1"] == 3 or $array["RECIPE_4_1"] == 3)
               $scale_3 = 1;
          else $scale_3 = 0;
          if ($array[$it_name] == 4 or $array["RECIPE_2_1"] == 4 or $array["RECIPE_3_1"] == 4 or $array["RECIPE_4_1"] == 4)
               $scale_4 = 1;
          else $scale_4 = 0;
          $return_value = "$array[$it_name];InRecipe_610=$scale_1;InRecipe_612=$scale_2;InRecipe_613=$scale_3;InRecipe_611=$scale_4";
          break;
        case "CurrentMass_610":
          $current = trim(sprintf("%.1f",$array["MAX_MASS_1"]*$array["CurrentMass_610"]/4096))+";";
          if ($current <= 0 ) $current = "0.0";
          $task = trim(sprintf("%.1f",$array["UNMODIFIED_MASS_1_virtual"]/100))+";";
          $return_value = "$current кг;Assignment_610=$task кг";
          break;  
        case "CurrentMass_612":
          $current = trim(sprintf("%.0f",$array["MAX_MASS_2"]*$array["CurrentMass_612"]/4096))+";";
          if ($current <= 0 ) $current = "0.0";
          $task = trim(sprintf("%.0f",$array["UNMODIFIED_MASS_2_virtual"]/100))+";";
          $return_value = "$current кг;Assignment_612=$task кг";
          break;  
        case "CurrentMass_613":
          $current = trim(sprintf("%.0f",$array["MAX_MASS_3"]*$array["CurrentMass_613"]/4096))+";";
          if ($current <= 0 ) $current = "0.0";
          $task = trim(sprintf("%.0f",$array["UNMODIFIED_MASS_3_virtual"]/100))+";";
          $return_value = "$current кг;Assignment_613=$task кг";
          break;  
        case "CurrentMass_611":
          $current = trim(sprintf("%.1f",$array["MAX_MASS_4"]*$array["CurrentMass_611"]/4096))+";";
          if ($current <= 0 ) $current = "0.0";
          $task = trim(sprintf("%.1f",$array["UNMODIFIED_MASS_4_virtual"]/100))+";";
          $return_value = "$current кг;Assignment_611=$task кг";
          break;
        case "CurrentMass_line_6":
          $current = trim(sprintf("%.2f",50*$array["CurrentMass_line_6"]/4096))+";";
          if ($current <= 0 ) $current = "0.0";
          $return_value = "$current кг";
          break;	  
        case "Ventilyator_274":
					if ($array["Ventilyator_274"] == 1)
						$return_value = "1";
          if (($array["Conveyer_618-1"] == 1 or $array["Conveyer_618-1"] == 3) and $array["Ventilyator_274"] == 1)
            $return_value = "1";
          if ($array["Ventilyator_274"] != 1 and ($array["Conveyer_618-1"] != 1 and $array["Conveyer_618-1"] != 0))  
            $return_value = "0"; 
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
//				print_r($result_str);
      return $result_str;      
    }
//    echo CreateResultString($BaseStr);
?>