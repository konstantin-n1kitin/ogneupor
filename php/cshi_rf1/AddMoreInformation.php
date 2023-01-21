<?php
/* ����������� ���������� ������� ����� ����� ������ ���������� � ���� ������������ � ������� ��������� ��� ������������� ����� */
	$BasicStr="RF1_in_a_1=257.89;";
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
		if ($name[$i] == 'RF1_in_a_34')  // ���������� � ������� ������	
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_35')  // ���������� ����� ����������	
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_36')  // ���������� ����� �������� ����� �����
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_37')  // ���������� ����� �������� ������ �����
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_38')  // ���������� ����� ��������������� ����� �����
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_39')  // ���������� ����� ��������������� ������ �����
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_40')  // ���������� ����� ���������
			$value = sprintf("%.0f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_41')  // ����������� � ������� ������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
			
		if ($name[$i] == 'RF1_in_a_42')  // ������ �� ��λ
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_43')  // ����������� ����� �������� ����� �����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_44')  // ����������� ����� �������� ������ �����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_45')  // ����������� ����� ��������������� ����� �����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_46')  // ����������� ����� ��������������� ������ �����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_47')  // ����������� ����� ���������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_48')  // ������ ��������������� �������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_49')  // ������ ����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_50')  // �������� ����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_51')  // ����
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 
		if ($name[$i] == 'RF1_in_a_52')  // �������� ��������
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF1_in_a_53')  // �� ���
			$value = sprintf("%.1f", GetElementValue($str, $StartPos)); 			
		if ($name[$i] == 'RF1_in_a_54')  // �� ������
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