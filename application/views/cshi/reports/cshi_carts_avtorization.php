<?php
/*!!!Чтобы не повредить работоспособности скрипта выше этого комментария не размещайте вообще ничего!!!*/
//include('connectdb.php');// подключение к серверу MySql и выбор БД

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


$userinfo='';
$state='0';
if( (isset($_COOKIE['login'])) & (isset($_COOKIE['pass'])) ) {// если в куках лежит логин и зашифрованый пароля
  if (!isset($_GET['exit'])) {// если кнопка выход не была нажата
    $login=$_COOKIE['login'];
    $pass=$_COOKIE['pass'];
 
    // проверяем наличие пользователя в БД и достаём оттуда пароль
    $sql="SELECT id, pass FROM users WHERE login='$login'";
    $res=mysql_query($sql);
    if(mysql_num_rows($res)>0){// если пользователь есть в БД
      $userinfo = mysql_fetch_array($res);// в этой переменной лежит пароль из БД
      if(strcmp($pass,md5($userinfo['pass'])) == 0) { //проверяем схожесть пароля из БД с паролем из куков
 
	// достаём все данные из БД
	$sql="SELECT * FROM users WHERE login='$login'";
	$res=mysql_query($sql);
	$userinfo=mysql_fetch_array($res); // в этой переменной будет лежать вся информация о пользователе из БД
	$time=time();
	// устанавливаем куки для запоминания статуса пользователя
	setcookie("login",$login,$time+1800);
	setcookie("pass",$pass,$time+1800);
	$state = 1;// статус, если 1, тогда пользователь авторизован
      }
    }
  } else {
    //обнуляем куки, если была нажата кнопка выход
    setcookie("login");
    setcookie("pass");
  }
}
if($state != 1) {// если после проверки куков, оказалось, что пользователь не авторизован, то идем дальше
  if( (isset($_POST['login'])) & (isset($_POST['pass'])) ){ // если пользователь ввёл логин и пароль
  $login = $_POST['login'];	
 
  // проверяем наличие пользователя в БД и достаём оттуда пароль
  $sql = "SELECT id, pass FROM users WHERE login='$login'";
  $res = mysql_query($sql);
    if(mysql_num_rows($res)>0) {// если пользователь есть в БД
      $userinfo = mysql_fetch_array($res);// в этой переменной лежит пароль из БД и номер пользователя
      $pass = $_POST['pass'];
      if(strcmp($pass,$userinfo['pass'])==0){
 
	// достаём все данные из БД
	$sql="SELECT * FROM users WHERE login='$login'";
	$res=mysql_query($sql);
	$userinfo=mysql_fetch_array($res);// в этой переменной будет лежать вся информация о пользователе из БД
	$time=time();
	// устанавливаем куки для запоминания статуса пользователя, пароль шифруем
	setcookie("login", $login, $time+1800);
	setcookie("pass", md5($pass), $time+1800);
	$state = 1;// статус, если 1, тогда пользователь авторизован
      }
    }
  }
}
if($state != 1) 
{
?>

<form method="post" action="/ASUTP/localmenu/cshireports/id=cshi_carts_avtorization">
<table align="center" style="width=450px;margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p>Пользователь:</p> 
		</td>
		<td>
			<input type='text' size='30' name='login' />
		</td>
	</tr>
	<tr>
		<td>
			<p>Пароль:</p> 
		</td>
		<td>
			<input type='password' size='30' name='pass' />
		</td>
	</tr>
	<tr>
		<td >
		</td>
		<td align="right">
			<input type="submit" value="Войти"/>
		</td>
	</tr>
</table>
</form>
<?php
} 
else 
{
	print "<script>window.location.href='/ASUTP/localmenu/cshireports_carts_passport'</script>"; 
}
?>
