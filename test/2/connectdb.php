<?php
//������ � �����, ������������ � ���� ������
$host = 'localhost';
$user = 'root'; 
$pass = ''; 
$dbname = 'webdb';
 
// ������������ � �������� ��, ������� ������� ����
if(!mysql_connect($host,$user,$pass))
  die('�� ������� ������������ � ������� MySql!');
elseif(!mysql_select_db($dbname))
  die('�� ������� ������� ��!');
?>