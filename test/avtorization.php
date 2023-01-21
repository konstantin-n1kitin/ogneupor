<html>

<head>
  <title></title>
</head>

<body>

<?php
	include('D:\ALEX\WEB\Test avtorisation\config.php');
	//Если прилетели данные пользователя, то проверить его используя LDAP
	if(isset($_POST['username'])&&isset($_POST['password']))
	{
  		$username = $_POST['username'];
		$login = $_POST['login'].$domain;
  		$password = $_POST['password'];
  		//подсоединяемся к LDAP серверу
  		$ldap = ldap_connect($ldaphost,$ldapport) or die ("Cant connect to LDAP Server");
  		//Включаем LDAP протокол версии 3
  		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
  		if($ldap)
  		{
    		// Пытаемся войти в LDAP при помощи введенных логина и пароля
    		$bind = ldap_bind($ldap,$login,$password);
    		if($bind)
    		{
    			//логин и пароль подошли!
    			// Проверим, является ли пользователь членом указанной группы.
      			$result = ldap_search($ldap,$base,"(&(memberOf=".$memberof.")(".$filter.$username."))");
      			// Получаем количество результатов предыдущей проверки
      			$result_ent = ldap_get_entries($ldap,$result);
    		}
    		else
    		{
      			die('Вы ввели неправильный логин или пароль. попробуйте еще раз');
    		}
  		}
  		// Если пользователь найден, т.е. результатов больше 0 (1 должен быть)
  		if($result_ent['count'] != 0)
  		{
    		// тут код для запоминания авторизайии
    		exit;
  		}
  		else
  		{
    		die('К сожалению, вам доступ закрыт');
  		}
	}
?>

</body>

</html>