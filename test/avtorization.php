<html>

<head>
  <title></title>
</head>

<body>

<?php
	include('D:\ALEX\WEB\Test avtorisation\config.php');
	//���� ��������� ������ ������������, �� ��������� ��� ��������� LDAP
	if(isset($_POST['username'])&&isset($_POST['password']))
	{
  		$username = $_POST['username'];
		$login = $_POST['login'].$domain;
  		$password = $_POST['password'];
  		//�������������� � LDAP �������
  		$ldap = ldap_connect($ldaphost,$ldapport) or die ("Cant connect to LDAP Server");
  		//�������� LDAP �������� ������ 3
  		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
  		if($ldap)
  		{
    		// �������� ����� � LDAP ��� ������ ��������� ������ � ������
    		$bind = ldap_bind($ldap,$login,$password);
    		if($bind)
    		{
    			//����� � ������ �������!
    			// ��������, �������� �� ������������ ������ ��������� ������.
      			$result = ldap_search($ldap,$base,"(&(memberOf=".$memberof.")(".$filter.$username."))");
      			// �������� ���������� ����������� ���������� ��������
      			$result_ent = ldap_get_entries($ldap,$result);
    		}
    		else
    		{
      			die('�� ����� ������������ ����� ��� ������. ���������� ��� ���');
    		}
  		}
  		// ���� ������������ ������, �.�. ����������� ������ 0 (1 ������ ����)
  		if($result_ent['count'] != 0)
  		{
    		// ��� ��� ��� ����������� �����������
    		exit;
  		}
  		else
  		{
    		die('� ���������, ��� ������ ������');
  		}
	}
?>

</body>

</html>