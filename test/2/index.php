<?php
/*!!!����� �� ��������� ����������������� ������� ���� ����� ����������� �� ���������� ������ ������!!!*/
//include('connectdb.php');// ����������� � ������� MySql � ����� ��
include('connectdb.php');// ����������� � ������� MySql � ����� ��
$userinfo='';
$state='0';
if( (isset($_COOKIE['login'])) & (isset($_COOKIE['pass'])) ) {// ���� � ����� ����� ����� � ������������ ������
  if (!isset($_GET['exit'])) {// ���� ������ ����� �� ���� ������
    $login=$_COOKIE['login'];
    $pass=$_COOKIE['pass'];
 
    // ��������� ������� ������������ � �� � ������ ������ ������
    $sql="SELECT id, pass FROM users WHERE login='$login'";
    $res=mysql_query($sql);
    if(mysql_num_rows($res)>0){// ���� ������������ ���� � ��
      $userinfo = mysql_fetch_array($res);// � ���� ���������� ����� ������ �� ��
      if(strcmp($pass,md5($userinfo['pass'])) == 0) { //��������� �������� ������ �� �� � ������� �� �����
 
	// ������ ��� ������ �� ��
	$sql="SELECT * FROM users WHERE login='$login'";
	$res=mysql_query($sql);
	$userinfo=mysql_fetch_array($res); // � ���� ���������� ����� ������ ��� ���������� � ������������ �� ��
	$time=time();
	// ������������� ���� ��� ����������� ������� ������������
	setcookie("login",$login,$time+1800);
	setcookie("pass",$pass,$time+1800);
	$state = 1;// ������, ���� 1, ����� ������������ �����������
      }
    }
  } else {
    //�������� ����, ���� ���� ������ ������ �����
    setcookie("login");
    setcookie("pass");
  }
}
if($state != 1) {// ���� ����� �������� �����, ���������, ��� ������������ �� �����������, �� ���� ������
  if( (isset($_POST['login'])) & (isset($_POST['pass'])) ){ // ���� ������������ ��� ����� � ������
  $login = $_POST['login'];	
 
  // ��������� ������� ������������ � �� � ������ ������ ������
  $sql = "SELECT id, pass FROM users WHERE login='$login'";
  $res = mysql_query($sql);
    if(mysql_num_rows($res)>0) {// ���� ������������ ���� � ��
      $userinfo = mysql_fetch_array($res);// � ���� ���������� ����� ������ �� �� � ����� ������������
      $pass = $_POST['pass'];
      if(strcmp($pass,$userinfo['pass'])==0){
 
	// ������ ��� ������ �� ��
	$sql="SELECT * FROM users WHERE login='$login'";
	$res=mysql_query($sql);
	$userinfo=mysql_fetch_array($res);// � ���� ���������� ����� ������ ��� ���������� � ������������ �� ��
	$time=time();
	// ������������� ���� ��� ����������� ������� ������������, ������ �������
	setcookie("login", $login, $time+1800);
	setcookie("pass", md5($pass), $time+1800);
	$state = 1;// ������, ���� 1, ����� ������������ �����������
      }
    }
  }
}
if($state != 1) 
{
?>
	<form method="post" action="/ASUTP/test/2/index.php">
	�����: <input type="text" size="30" name="login"/><br />
	������: <input type="password" name="pass" size="30"/><br />
	<input type="submit" value="�����"/>
	</form>
	<!--<br /><a href="/register.php">�����������</a> -->
	<br /><a href="/ASUTP/test/2/register.php">�����������</a>
<?php
} 
else 
{
	echo '�� ����� �� ����!<br /> ��� �����: '.$userinfo["login"].'<br />��� E-mail: '.$userinfo["email"].'<br /> <a href="/ASUTP/test/2/index.php?exit=y">�����</a>';
}
?>