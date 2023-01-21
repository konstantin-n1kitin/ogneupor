<?php
//  header("Content-type: text/html; charset=windows-1251");
//  $BaseStr = "F_gas_dry1=-0,0195324420928955;P_gas_dry1=1,60532259941101;ElectricMotor_gas_dry1=0,0244155526161194;F_air_dry1=0,568394064903259;P_air_dry1=-0,000732466578483582;ElectricMotor_air_dry1=2,40493202209473;P_camera_dry1=0;P_camera_dry2=0;T_left_dry1=37,209300994873;T_right_dry1=39,077091217041;T_swod_dry1=29,6282730102539;T_left_dry2=310,309478759766;T_right_dry2=315,363494873047;T_swod_dry2=334,773864746094;ManualAuto_dry1=False;Mode_dry1=True;ManualAuto_dry2=False;Mode_dry2=True;F_gas_dry2=43,8600997924805;P_gas_dry2=7,96435308456421;ElectricMotor_gas_dry2=0,903375446796417;F_air_dry2=3,46798515319824;P_air_dry2=0,193737417459488;ElectricMotor_air_dry2=23,3046455383301;StepsCount_dry1=19;StepsIndex_dry1=5;StepCurrentTime_dry1=78;StepTime_dry1=119;ChartCurrentTime_dry1=359;ChartTime_dry1=492;StepsCount_dry2=16;StepsIndex_dry2=3;StepCurrentTime_dry2=163;StepTime_dry2=600;ChartCurrentTime_dry2=241;ChartTime_dry2=345;MinPresureGas_dry1=True;MinPresureAir_dry1=True;MinPresureInDry_dry1=False;ExistFire_dry1_Tag1=True;ExistFire_dry1_Tag2=False;STATE_1=1;STATE_2=0;";  
  $StartPos = 0;    //Начальная позиция поиска
  $result_str = ""; //Результирующая строка вывода
  
  function GetElementName($string, &$index)  
  {
    $position = strpos($string, "=", $index);        //Нашли позицию включения строки
    $leigth = $position - $index;                    //Нашли длину слова
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
  function FindParametrType($Name)   /* Выборка типа параметра */
  {
    $pos = strpos($Name, "_", 0);         //Нашли позицию включения строки
    return substr($Name, 0, $pos);
  }
  function FindLongParametrType($Name)   /* Выборка типа параметра */
  {
    $pos = strpos($Name, "_", 0);         //Нашли 1ю позицию включения строки
    $pos = strpos($Name, "_", $pos+1);    //Нашли 2ю позицию включения строки
    return substr($Name, 0, $pos);
  }
  function AddMoreInfo($it_name, $array)
  {
    $return_value = "";                   //Результирующая строка
    $value = "";
    switch (FindParametrType($it_name))
    {
      case "ElectricMotor": 
        $value = sprintf("%.1f", $array[$it_name]);
        $return_value = "Исп. мех. = $value %";
        break;
      case "P":         
        $value = sprintf("%.1f", $array[$it_name]);
        $return_value = "Давление = $value кПа";
        break;
      case "T": 
        $value = sprintf("%.1f", $array[$it_name]);
        switch (FindLongParametrType($it_name))
        {
          case "T_left":
            $return_value = "Tлев = $value <sup>o</sup>C";
            break;
          case "T_right":
            $return_value = "Tправ = $value <sup>o</sup>C";
            break;
          case "T_swod":
            $return_value = "Tсвод = $value <sup>o</sup>C";
            break;
          default :
            $return_value = "T = $value <sup>o</sup>C";
            break;  
        }         
        break;
      case "F":         
        $value = sprintf("%.1f", $array[$it_name]);
        $return_value = "Расход = $value м<sup>3</sup>/ч";
        break;
      case "ExistFire":
        switch ($it_name)
        {
          case 'ExistFire_dry1_Tag1':
            if ($array['ExistFire_dry1_Tag1'] == 'True' and $array['ExistFire_dry1_Tag2'] == 'True')
              $return_value = "$array[$it_name];ExistFire_dry1=True";
            else
              $return_value = "$array[$it_name];ExistFire_dry1=False";
            break;
          case 'ExistFire_dry2_Tag1':  
            if ($array['ExistFire_dry2_Tag1'] == 'True' and $array['ExistFire_dry2_Tag2'] == 'True')
              $return_value = "$array[$it_name];ExistFire_dry2=True";
            else
              $return_value = "$array[$it_name];ExistFire_dry2=False";
            break;  
        }
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
		echo $result_str;
    return $result_str;      
  }
//    echo CreateResultString($BaseStr);
?>