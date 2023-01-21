<?php
//include('connectdb.php');// подключение к серверу MySql и выбор БД
include('connectdb.php');// подключение к серверу MySql и выбор БД
 
// sql-скрипт для создания таблицы
$sql='CREATE TABLE users(
  id INT NOT NULL AUTO_INCREMENT,
  login VARCHAR(15),
  pass TEXT,
  email VARCHAR(150),
  PRIMARY KEY(id)
);';
 
//выполняем sql-запрос
if(!mysql_query($sql)){
  echo 'Ошибка при создании таблицы в БД!';
} else {
  echo 'Всё прошло отлично, таблица создана!';
}
?>