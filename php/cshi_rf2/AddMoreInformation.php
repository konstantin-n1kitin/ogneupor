<?php
/* Результатом выполнения данного файла будет строка переданная в него объединенная с данными состояний для подсвечивания линий */
	$BasicStr="RF2_in_a_1=257.89;";
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
		if ($name[$i] == 'RF2_in_a_1')  // Общая температура газа	
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_2')  // Общее давление газа
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_3')  // Разрежение в пылевой камере	
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_4')  // Разрежение перед скруббером	
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_5')  // Разрежение перед циклоном левый канал
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_6')  // Разрежение перед циклоном правый канал
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_7')  // Разрежение перед электрофильтром левый канал
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_8')  // Разрежение перед электрофильтром правый канал
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_9')  // Разрежение перед дымососом
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_10')  // Температура в пылевой камере
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			
		if ($name[$i] == 'RF2_in_a_11')  // Анализ на «СО»
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_12')  // Температура перед циклоном левый канал
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_13')  // Температура перед циклоном правый канал
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_14')  // Температура перед электрофильтром левый канал
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_15')  // Температура перед электрофильтром правый канал
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_16')  // Температура перед дымососом
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_17')  // Расход вентиляционного воздуха
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_18')  // Расход газа
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_19')  // Давление газа
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_20')  // Весы
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_21')  // Нагрузка питателя
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF2_in_a_22')  // Уровень в бункере выгрузки пыли
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF2_in_a_23')  // ИМ газ
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF2_in_a_24')  // ИМ воздух
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF2_in_a_25')  // Расход сжатого воздуха на газоочистку
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