<?php
//include('connectdb.php');// ����������� � ������� MySql � ����� ��
include('connectdb.php');// ����������� � ������� MySql � ����� ��
 
if (($_POST['login']!='') || 
    ($_POST['pass1']!='') || 
    ($_POST['pass2']!='') || 
    ($_POST['email']!='')) { // ���� ��� ������ ��� ����������� �������, �� ����������
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
 
    if (strcmp($pass1, $pass2) == 0) {// ���� ������ ���������, �� ����������
      $login = $_POST['login'];
      $email = $_POST['email'];
 
      //��������� ������� � �� ������������ � ������� $login
      $sql='SELECT * FROM users WHERE login='.$login; // ������ ��� ������ �� ������ � ������� users
      if (!($res=mysql_query($sql)) || (mysql_num_rows($res) == 0)) { // ���� ���������� �������� ������� ����, �� ����������
	  // sql-������ ��� ���������� ����� � �������
	  $sql = 'INSERT INTO users(login, pass, email) 
		  VALUES("'.$login.'", "'.$pass1.'", "'.$email.'")';
	  if(mysql_query($sql)) {// ��������� ������
	    echo '������������ '.$_POST['login'].' ������� ���������������! <a href="/ASUTP/test/2/index.php">����� ��� �����.';
	  } else {
	    echo '��� ����������� ��������� ������, <a href="/ASUTP/test/2/register.php">��������� �������</a>.';
	  }
	} else echo '������������ � ����� ������� ��� ���������������!';
    } else echo '��������� ������ �� ���������, <a href="/ASUTP/test/2/register.php">��������� �������</a>.';
} else {
?>
  <form method='post' action='/ASUTP/test/2/register.php'>
  ������� �����: <input type='text' size='30' name='login' /><br />
  ������� e-mail: <input type=text size=30 name='email' /><br />
  ������: <input type='password' name='pass1' size='30' /><br />
  ��������� ������: <input type='password' name='pass2' size='30' /><br />
  <input type='submit' value='�����������' />
<?php  
}
?>
</form>