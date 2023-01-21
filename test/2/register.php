<?php
//include('connectdb.php');// подключение к серверу MySql и выбор БД
include('connectdb.php');// подключение к серверу MySql и выбор БД
 
if (($_POST['login']!='') || 
    ($_POST['pass1']!='') || 
    ($_POST['pass2']!='') || 
    ($_POST['email']!='')) { // если все данные для регистрации введены, то продолжаем
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
 
    if (strcmp($pass1, $pass2) == 0) {// если пароли совпадают, то продолжаем
      $login = $_POST['login'];
      $email = $_POST['email'];
 
      //проверяем наличие в БД пользователя с логином $login
      $sql='SELECT * FROM users WHERE login='.$login; // скрипт для поиска по логину в таблице users
      if (!($res=mysql_query($sql)) || (mysql_num_rows($res) == 0)) { // если количество найденых записей ноль, то продолжаем
	  // sql-скрипт для добавления даных в таблицу
	  $sql = 'INSERT INTO users(login, pass, email) 
		  VALUES("'.$login.'", "'.$pass1.'", "'.$email.'")';
	  if(mysql_query($sql)) {// выполняем скрипт
	    echo 'Пользователь '.$_POST['login'].' успешно зарегистрирован! <a href="/ASUTP/test/2/index.php">Форма для входа.';
	  } else {
	    echo 'При регистрации произошла ошибка, <a href="/ASUTP/test/2/register.php">повторите попытку</a>.';
	  }
	} else echo 'Пользователь с таким логином уже зарегистрирован!';
    } else echo 'Введенные пароли не совпадают, <a href="/ASUTP/test/2/register.php">повторите попытку</a>.';
} else {
?>
  <form method='post' action='/ASUTP/test/2/register.php'>
  Введите Логин: <input type='text' size='30' name='login' /><br />
  Введите e-mail: <input type=text size=30 name='email' /><br />
  Пароль: <input type='password' name='pass1' size='30' /><br />
  Повторите пароль: <input type='password' name='pass2' size='30' /><br />
  <input type='submit' value='Регистрация' />
<?php  
}
?>
</form>