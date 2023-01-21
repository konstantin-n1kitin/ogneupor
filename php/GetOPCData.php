<?php
// Обмен данными между веб сервером и визуализацией TPL
    header('Content-Type: text/plain;');
    error_reporting(E_ALL ^ E_WARNING);
    set_time_limit(0);
    ob_implicit_flush();
//    $address = '192.168.1.4'; 
    $address = '127.0.0.1';
    $port = 5000;
    $mnemo = chr($_GET['mnemo']);  //Получаем номер мнемосхемы
		switch ($_GET['mnemo'])
		{
			case 2:
				include('c:\wamp\www\ASUTP\php\cshi_pfu\AddMoreInformation.php');
				break;
			case 3:
				include("c:\wamp\www\ASUTP\php\cshi_tunnel\AddMoreInformation.php");
				break;
			case 4:
				include("c:\wamp\www\ASUTP\php\csi_chamber\AddMoreInformation.php");
				break;
			case 5:
				include("c:\wamp\www\ASUTP\php\cshi_rf2\AddMoreInformation.php");
				break;
			case 6:
				include("c:\wamp\www\ASUTP\php\cshi_rf1\AddMoreInformation.php");
				break;
			case 7:
				include("c:\wamp\www\ASUTP\php\cshi_RF\AddMoreInformation.php");
				break; 
			case 8:
				include("c:\wamp\www\ASUTP\php\csi_tunneldry\AddMoreInformation.php");
				break; 
			case 9:
				include("c:\wamp\www\ASUTP\php\cshi_pfu5\AddMoreInformation.php");
				break; 
			case 10:
				include("c:\wamp\www\ASUTP\php\cshi_pfu3\AddMoreInformation.php");
				break; 
			default:
				include("c:\wamp\www\ASUTP\php\cshi_pfu\AddMoreInformation.php");
				break;
		}

    if ($mnemo == null or $mnemo == "")
    {}
    else
      $msg = $mnemo;
	
/*	$memo = 6;
	$msg = $memo; */   
		try {
      //создает сокет
      $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      if ($socket < 0) {
          throw new Exception('Ошибка функции socket_create(): '.socket_strerror(socket_last_error())."\n");
      }
      //соединяемся с сокетом на сервере
      $result = socket_connect($socket, $address, $port);
      if ($result == false) {
          throw new Exception('Ошибка функции socket_connect(): '.socket_strerror(socket_last_error())."\n");
      }
      //отправляем сообщение-запрос серверу
      socket_write($socket, $msg, strlen($msg));
      //получаем ответ от сервера
      $out = socket_read($socket, 15000);
//	  echo $out;
		echo CreateResultString($out);
    }
    catch (Exception $e) {
      echo "\n Ошибка: ".$e->getMessage();
    }
    if (isset($socket)) {
      socket_close($socket);
    }
?>