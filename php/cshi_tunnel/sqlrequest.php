<?php
  header("Content-type: text/html; charset=windows-1251");
//  print_r($_COOKIE);    // Вывести все печеньки
//  include("C:\wamp\www\kohana\php\cshi_tunnel\config.php");
  $CartPos = $_GET['CartPos']; ////позиция вагона в печи
  $Type = $_GET['Type']; //1-вагон в сушиле 2-вагон в печи
  //Запрос определения нужного нам вагона по номеру позиции вагона в печи
  //1я запись будет соответствовать вагону находящемуся на 1й позиции в печи
  $sql = "select DT_dry_beg, DT_dry_end, DT_furn_beg, DT_furn_end, CartNum, BrickMark, BrickMark_D, Dencity, Material, ModeDispelCart, F1, F2, F3, F4, P1, P2,
                      P3, T1, T2, T3, T4, T5, T6, T7, ReturnFiring, CartType, QuantityFir, QuantityBriquette, OnPodsad, OnPodsad_D, Furn_Brigade, Dry_Brigade, Furn_Shift,
                      Dry_Shift, CartPos, ID, Furn_FIO, Dry_FIO, CartCountOnFurn, CartCountOnDry, CartCountOffDry, Cart_Turns, DT_Turns, P4, P5, DT_dry_add_progr,
                      DT_furn_add_progr, T8, T9
					from Passport_short where CartPos=$Type order by ID DESC"; //Готовый SQL запрос
  $index_i = 0;   //Номер текущей записи
  $index_j = 0;   //Номер текущего столбца
  $result = ""; //результирующая строка с данными
	$str = "";
  // ------------------------------------------------------------------------------------------------
//	$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.tunnel_sql_server.';database='.$tunnel_sql_db.';Uid='.$tunnel_sql_user.';Pwd='.$tunnel_sql_password.';' );
	$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.'tunnel-server'.';database='.'tun_furn'.';Uid='.'sa'.';Pwd='.'ogneupor'.';' );
							$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
							$result = $db->query("select COUNT(*) from passport_short");
							$RowCount = $result->fetchColumn();
							$result = $db->query($sql);
							$ColCount = $result->columnCount();
							$db = null;
							$i=1;
							while ($row = $result->fetch())
							{
								if ($i == $CartPos)
								{
									for ($j = 0; $j < $ColCount; $j++)
									{
//										if ((float)$row[$j]) $a = trim(sprintf("%.2f", $row[$j]));
//  									if (!(float)$row[$j]) $a = $row[$j];
										if (($j==0 or $j==1 or $j==2 or $j==3 or $j==41 or $j==45 or $j==46))
										{
											if (strtotime($row[$j]) == null) 
												$a = '-';
											else
												$a=date("d.m.Y H:i:s", strtotime($row[$j]));
										}
										else
											$a=$row[$j];
										if ($a == null or $a == "" or $a == "&nbsp;") $a = "";
										$str = "$str$j=$a;";
									}
								}
								$i++;
							}
							echo $str;
?>