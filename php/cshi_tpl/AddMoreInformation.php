<?php
/* ����������� ���������� ������� ����� ����� ������ ���������� � ���� ������������ � ������� ��������� ��� ������������� ����� */
/*		$Line_array = array();	
		$Line_array[0] = array('PlastPitatel1-20', 'GlinaCutMashine1-292', 'line_1-20-1-292');
		$Line_array[1] = array('GlinaCutMashine1-292', 'Conveyer291', 'line_1-292-291_a', 'line_1-292-291_b', 'line_1-292-291_c');
		$Line_array[2] = array('PlastPitatel2-20', 'GlinaCutMashine2-292', 'line_2-20-2-292');
		$Line_array[3] = array('GlinaCutMashine2-292', 'Conveyer291', 'line_2-292-291_a', 'line_2-292-291_b', 'line_2-292-291_c');*/
    function GetElementName($string, &$index)  
    {
      $position = strpos($string, "=", $index);        //����� ������� ��������� ������
      $leigth = $position - $index;                   //����� ����� �����
      $ElementName = substr($string, $index, $leigth); //�������� ��� ��������
      return $ElementName;
    }
    function GetElementValue($string, &$index)
    {
      $position_beg = strpos($string, "=", $index);                //����� ������� ��������� ������
      $position_end = strpos($string, ";", $index);                //����� ������� ��������� ������
      $leigth = $position_end - 1 - $position_beg;                 //����� ����� �����
      $ElementValue = substr($string, $position_beg + 1, $leigth); //�������� �������� ��������
      $index = $position_end + 1;
      return $ElementValue;
    }

    function CreateResultString($str)
    {
      $ElementsCount = substr_count($str, ";");
      $Massive = array();
      $result_str = '';
      for ($i = 0; $i < $ElementsCount; $i++) //��������� ������: ������[��� ��������]=�������� ��������
      {
        $name[$i] = GetElementName($str, $StartPos);
        $value = GetElementValue($str, $StartPos); 
        $Massive[$name[$i]] = $value;
      } 
      for ($i = 0; $i < $ElementsCount; $i++) //��������� � ������ ����� ��������
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

?>