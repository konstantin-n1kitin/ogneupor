<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
  <head>
<!--		<meta http-equiv="Content-Language" content="ru"> -->
<!--		<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251"> -->
		<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Language" content="en-us" />
    <title>Отчёт об ошибках ПФУ</title>
  </head>
	  <body>
		<?php
		  $data = array();
		 	$dbhost = "TPL-SERVER";
		  $dbname = "pfu";
			$dbuser = "sa";
			$dbpass = "tpl";
		  $db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.
		                 ';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
		  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		  $dt_begin=new DateTime($_GET['date1']);
		  $dt_end=new DateTime($_GET['date2']);
		  $one_day=new DateInterval('PT24H');
		  $dt_end->add($one_day);
		  $dt_tmp = $dt_begin;
		  $dt_begin_str = $dt_tmp->format('Y-m-d H:i:s');
		  $dt_tmp->add($one_day);
		  $dt_end_str = $dt_tmp->format('Y-m-d H:i:s');
		  $i=0;
		  while(($dt_tmp<=$dt_end)and($i<100))
		  {		    $sql = "SELECT COUNT(*) AS Expr1
		            FROM Error
		            WHERE (DT >= :dt_begin) AND (DT< :dt_end);";
		 		$result = $db->prepare($sql);
		   	$result->execute(array(':dt_begin'=>$dt_begin_str,':dt_end'=>$dt_end_str));
		   	$data = $result->fetch(PDO::FETCH_ASSOC);
		    $result->closeCursor();
		    $color = ($data['Expr1']>2000)?"red":"green";
		    print_r(str_replace(' 00:00:00','',$dt_begin_str).' - '.'<font color='.$color.'>'.$data['Expr1'].'</font> ошибок<br>');
		    $dt_begin_str = $dt_tmp->format('Y-m-d H:i:s');
		    $dt_tmp->add($one_day);
		    $dt_end_str = $dt_tmp->format('Y-m-d H:i:s');
		    $i++;
		  }
		//получаем массивы названий механизмов
		//  return($result_array);
		?>
  	<a href="javascript:history.back();">Выбрать другой месяц</a>
	</body>
</html>