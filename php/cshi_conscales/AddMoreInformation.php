<?php
		$result_str = "";
		$base_sql = "SELECT *
								 FROM   Currents";								 
		$sql_order_by = " ORDER BY ID_W";
		$sql = sprintf("%s%s", $base_sql, $sql_order_by); //Готовый SQL запрос
		try
		{
			set_time_limit(5);
			$db = new PDO ( 'odbc:DRIVER={SQL Server};SERVER='.'weight-server'.';database='.'Conv_vesy'.';Uid='.'sa'.';Pwd='.'123Oup123'.';' );
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$result = $db->query($sql);
			$ids=array("1"=>"7","2"=>"17","3"=>"29","4"=>"39","5"=>"53");
			while ($row = $result->fetch())
			{
				$id_w = $row[0];
				$dt = date('d-m-Y H:i:s', strtotime($row[1]));
				$z1 = sprintf('%.0f', $row[2]);
				$z2 = sprintf('%.0f', $row[3]);
				$z3 = sprintf('%.0f', $row[4]);
				$v = sprintf('%.2f', $row[7]);
				$i = sprintf('%.0f', $row[8]);
				$q = sprintf('%.1f', $row[6]);
				
				if ($v <= 0 and $q < 0) $q = 0;
				else $q = sprintf('%.1f', $row[6]);
				
				$result_str .= 'S'.$ids[$id_w].'_Z1='.$z1.';';
				$result_str .= 'S'.$ids[$id_w].'_Z2='.$z2.';';
				$result_str .= 'S'.$ids[$id_w].'_Z3='.$z3.';';
				$result_str .= 'S'.$ids[$id_w].'_Q='.$q.';';
				$result_str .= 'S'.$ids[$id_w].'_V='.$v.';';
				$result_str .= 'S'.$ids[$id_w].'_I='.$i.';';
			}
			$db = null;
		}
		catch( PDOException $err )
		{
			return -3; //Ошибка связи с базой данных
		}
    echo $result_str;
?>