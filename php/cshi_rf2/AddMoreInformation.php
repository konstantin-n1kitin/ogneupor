<?php
/* ����������� ���������� ������� ����� ����� ������ ���������� � ���� ������������ � ������� ��������� ��� ������������� ����� */
	$BasicStr="RF2_in_a_1=257.89;";
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
//        $value = GetElementValue($str, $StartPos); 
		if ($name[$i] == 'RF2_in_a_1')  // ����� ����������� ����	
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_2')  // ����� �������� ����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_3')  // ���������� � ������� ������	
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_4')  // ���������� ����� ����������	
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_5')  // ���������� ����� �������� ����� �����
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_6')  // ���������� ����� �������� ������ �����
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_7')  // ���������� ����� ��������������� ����� �����
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_8')  // ���������� ����� ��������������� ������ �����
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_9')  // ���������� ����� ���������
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_10')  // ����������� � ������� ������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			
		if ($name[$i] == 'RF2_in_a_11')  // ������ �� ��λ
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_12')  // ����������� ����� �������� ����� �����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_13')  // ����������� ����� �������� ������ �����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_14')  // ����������� ����� ��������������� ����� �����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_15')  // ����������� ����� ��������������� ������ �����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_16')  // ����������� ����� ���������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_17')  // ������ ��������������� �������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_18')  // ������ ����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_19')  // �������� ����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_20')  // ����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF2_in_a_21')  // �������� ��������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF2_in_a_22')  // ������� � ������� �������� ����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF2_in_a_23')  // �� ���
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF2_in_a_24')  // �� ������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF2_in_a_25')  // ������ ������� ������� �� �����������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			

//        $value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
        $Massive[$name[$i]] = $value;
      } 
      for ($i = 0; $i < $ElementsCount; $i++) //��������� � ������ ����� ��������
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