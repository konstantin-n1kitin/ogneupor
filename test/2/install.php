<?php
//include('connectdb.php');// ����������� � ������� MySql � ����� ��
include('connectdb.php');// ����������� � ������� MySql � ����� ��
 
// sql-������ ��� �������� �������
$sql='CREATE TABLE users(
  id INT NOT NULL AUTO_INCREMENT,
  login VARCHAR(15),
  pass TEXT,
  email VARCHAR(150),
  PRIMARY KEY(id)
);';
 
//��������� sql-������
if(!mysql_query($sql)){
  echo '������ ��� �������� ������� � ��!';
} else {
  echo '�� ������ �������, ������� �������!';
}
?>