<?php
//данные о хосте, пользователе и базе данных
$host = 'localhost';
$user = 'root'; 
$pass = ''; 
$dbname = 'webdb';
 
// подключаемся и выбираем бд, которую указали выше
if(!mysql_connect($host,$user,$pass))
  die('Не удалось подключиться к серверу MySql!');
elseif(!mysql_select_db($dbname))
  die('Не удалось выбрать БД!');
?>