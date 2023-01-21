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

    function CreateResultString($str)
    {
      $ElementsCount = substr_count($str, ";");
      $Massive = array();
      $result_str = '';
      for ($i = 0; $i < $ElementsCount; $i++) //Заполняем массив: Массив[Имя элемента]=Значение элемента
      {
        $name[$i] = GetElementName($str, $StartPos);
//        $value = GetElementValue($str, $StartPos); 
		if ($name[$i] == 'RF1_in_a_34')  // Разрежение в пылевой камере	
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_35')  // Разрежение перед скруббером	
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_36')  // Разрежение перед циклоном левый канал
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_37')  // Разрежение перед циклоном правый канал
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_38')  // Разрежение перед электрофильтром левый канал
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_39')  // Разрежение перед электрофильтром правый канал
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_40')  // Разрежение перед дымососом
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_41')  // Температура в пылевой камере
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			
		if ($name[$i] == 'RF1_in_a_42')  // Анализ на «СО»
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_43')  // Температура перед циклоном левый канал
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_44')  // Температура перед циклоном правый канал
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_45')  // Температура перед электрофильтром левый канал
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_46')  // Температура перед электрофильтром правый канал
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_47')  // Температура перед дымососом
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_48')  // Расход вентиляционного воздуха
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_49')  // Расход газа
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_50')  // Давление газа
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_51')  // Весы
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_52')  // Нагрузка питателя
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF1_in_a_53')  // ИМ газ
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF1_in_a_54')  // ИМ воздух
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			

//        $value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
        $Massive[$name[$i]] = $value;
      } 
      for ($i = 0; $i < $ElementsCount; $i++) //Добавляем в строку новые значения
      {
/*        $newvalue = AddMoreInfo($name[$i], $Massive);
        if ($newvalue != null or $newvalue != "")
          $value = $newvalue;
        else*/
          $value = $Massive[$name[$i]];  
        $result_str = "$result_str$name[$i]=$value;";
      } 
//				print_r($result_str);
      return $result_str;
    }
//	echo CreateResultString($BasicStr);

?>