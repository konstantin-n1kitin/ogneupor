<p align="right">
	<?php echo Html::image('img/printer.png',array('onClick'=>'printit()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
	<?php echo Html::image('img/pdf.png',array('onClick'=>'GetPDF()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
</p>
<div id="report_content">
<table id="passport_table">
	<thead>
		<tr>
			<th colspan="4">Паспорт вагона № <?php echo $CartNum?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th colspan="4">Данные по сушилу</th>
		</tr>
		<tr>
			<td>Технолог оператор</td>
			<td class = "passport_value"><?php echo $Dry_FIO?></td>
		</tr>
		<tr>
			<td>Бригада №</td>
			<td class = "passport_value"><?php echo $Dry_Brigade?></td>
			<td>Смена №</td>
			<td class = "passport_value"><?php echo $Dry_Shift?></td>
		</tr>
		<tr>
			<td>Вагон поставили в сушило (факт.)</td>
			<td class = "passport_value"><?php echo $DT_dry_add_progr?></td>
			<td>Время выхода вагона из сушила</td>
			<td class = "passport_value"><?php echo $DT_dry_end?></td>
		</tr>
		<tr>
			<td>Вагон поставили в сушило</td>
			<td class = "passport_value"><?php echo $DT_dry_beg?></td>
		</tr>
		<tr>
			<td>Количество вагонов в сушиле на момент загрузки</td>
			<td class = "passport_value"><?php echo $CartCountOnDry?></td>
			<td>Количество вагонов в сушиле на момент выгрузки</td>
			<td class = "passport_value"><?php echo $CartCountOffDry?></td>
		</tr>
		<tr>
			<td>Температура воздуха в сушиле на поз. 3 (левая сторона)</td>
			<td class = "passport_value"><?php echo $T1?> <sup>o</sup>С</td>
			<td>Расход горячего воздуха в сушило</td>
			<td class = "passport_value"><?php echo $F2*1000?> м<sup>3</sup>/ч</td>
		</tr>
		<tr>
			<td>Температура воздуха в сушиле на поз. 3 (правая сторона)</td>
			<td class = "passport_value"><?php echo $T8?> <sup>o</sup>С</td>
			<td>Разрежение в сушиле на поз. 3</td>
			<td class = "passport_value"><?php echo $P4?> кПа</td>
		</tr>
		<tr>
			<td>Температура воздуха в сушиле на поз. 10 (левая сторона)</td>
			<td class = "passport_value"><?php echo $T2?> <sup>o</sup>С</td>
			<td>Давление в сушиле на поз. 10</td>
			<td class = "passport_value"><?php echo $P5?> кПа</td>
		</tr>
		<tr>
			<td>Температура воздуха в сушиле на поз. 10 (правая сторона)</td>
			<td class = "passport_value"><?php echo $T9?> <sup>o</sup>С</td>
			<td>Температура подаваемого воздуха в сушило</td>
			<td class = "passport_value"><?php echo $T3?> <sup>o</sup>С</td>
		</tr>
		<tr>
			<th colspan="4">Данные по печи</th>
		</tr>
		<tr>
			<td>Технолог оператор</td>
			<td class = "passport_value"><?php echo $Furn_FIO?></td>
		</tr>
		<tr>
			<td>Бригада №</td>
			<td class = "passport_value"><?php echo $Furn_Brigade?></td>
			<td>Смена №</td>
			<td class = "passport_value"><?php echo $Furn_Shift?></td>
		</tr>
		<tr>
			<td>Вагон поставили в печь (факт.)</td>
			<td class = "passport_value"><?php echo $DT_furn_add_progr?></td>
			<td>Время выхода вагона из печи</td>
			<td class = "passport_value"><?php echo $DT_furn_end?></td>
		</tr>
		<tr>
			<td>Вагон поставили в печь</td>
			<td class = "passport_value"><?php echo $DT_furn_beg?></td>
		</tr>
		<tr>
			<td>Температура под сводом печи на поз. 15 <?php $dT=array("Аркалык"=>"(650 - 850) <sup>o</sup>С", "Берлинка"=>"(600 - 700) <sup>o</sup>С", "Смесь"=>"(650 - 850) <sup>o</sup>С", "Осыпь"=>"", "Шлак"=>""); echo $dT[$Material];?></td>
			<td class = "passport_value"><?php echo $T4?> <sup>o</sup>С</td>
			<td>Средний расход газа</td>
			<td class = "passport_value"><?php echo $F1*100?> м<sup>3</sup>/ч</td>
		</tr>
		<tr>
			<td>Температура под сводом печи на поз. 20 <?php $dT=array("Аркалык"=>"(1245 - 1345) <sup>o</sup>С", "Берлинка"=>"(1215 - 1315) <sup>o</sup>С", "Смесь"=>"(1175 - 1285) <sup>o</sup>С", "Осыпь"=>"", "Шлак"=>""); echo $dT[$Material];?></td>
			<td class = "passport_value"><?php echo $T5?> <sup>o</sup>С</td>
			<td>Расход воздуха распределенной подачи</td>
			<td class = "passport_value"><?php echo $F3*1000?> м<sup>3</sup>/ч</td>
		</tr>
		<tr>
			<td>Температура под сводом печи на поз. 23 <?php
				if ($Cart_Turns<=13) 		$dT=array("Аркалык"=>"1320", "Берлинка"=>"1250", "Смесь"=>"1290", "Осыпь"=>"1290", "Шлак"=>"1290");
				else if ($Cart_Turns<=16) 	$dT=array("Аркалык"=>"1330", "Берлинка"=>"1270", "Смесь"=>"1300", "Осыпь"=>"1300", "Шлак"=>"1300");
				else if ($Cart_Turns<=19) 	$dT=array("Аркалык"=>"1350", "Берлинка"=>"1290", "Смесь"=>"1320", "Осыпь"=>"1320", "Шлак"=>"1320");
				else if ($Cart_Turns<=22) 	$dT=array("Аркалык"=>"1360", "Берлинка"=>"1300", "Смесь"=>"1330", "Осыпь"=>"1330", "Шлак"=>"1330");
				else if ($Cart_Turns<=25) 	$dT=array("Аркалык"=>"1380", "Берлинка"=>"1310", "Смесь"=>"1350", "Осыпь"=>"1350", "Шлак"=>"1350");
				else if ($Cart_Turns<=28) 	$dT=array("Аркалык"=>"", "Берлинка"=>"1320", "Смесь"=>"", "Осыпь"=>"", "Шлак"=>"");
				else 						$dT=array("Аркалык"=>"", "Берлинка"=>"1330", "Смесь"=>"", "Осыпь"=>"", "Шлак"=>"");
				echo "(".$dT[$Material]."&plusmn; 15 <sup>o</sup>С)";?></td>
			<td class = "passport_value"><?php echo $T6?> <sup>o</sup>С</td>
			<td>Средний расход воздуха на горение</td>
			<td class = "passport_value"><?php echo $F4*1000?> м<sup>3</sup>/ч</td>
		</tr>
		<tr>
			<td>Температура отходящих газов на поз. 30-34</td>
			<td class = "passport_value"><?php echo $T7?> <sup>o</sup>С</td>
			<td>Среднее давление газа</td>
			<td class = "passport_value"><?php echo $P1?> кгс/м<sup>2</sup></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Давление на поз. 28</td>
			<td class = "passport_value"><?php echo $P2?> кгс/м<sup>2</sup></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Разрежение на поз. 10</td>
			<td class = "passport_value"><?php echo $P3?> кгс/м<sup>2</sup></td>
		</tr>
		<tr>
			<th colspan="4">Общие данные</th>
		</tr>
		<tr>
			<td>Количество прогонок</td>
			<td class = "passport_value"><?php echo $Cart_Turns?></td>
			<td>Повторный обжиг</td>
			<td class = "passport_value"><?php echo $ReturnFiring?></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Кажущаяся плотность сырца</td>
			<td class = "passport_value"><?php echo $Dencity?></td>
		</tr>
		<tr>
			<td>Тип вагона</td>
			<td class = "passport_value"><?php echo $CartType?></td>
			<td>Количество елок</td>
			<td class = "passport_value"><?php echo $QuantityFir?></td>
		</tr>
		<tr>
			<td>Вид сырья</td>
			<td class = "passport_value"><?php echo $Material?></td>
			<td>Количество брикетов</td>
			<td class = "passport_value"><?php echo $QuantityBriquette?></td>
		</tr>
		<tr>
			<td>Марка кирпича</td>
			<td class = "passport_value"><?php echo $BrickMark?></td>
			<td>На подсаде</td>
			<td class = "passport_value"><?php echo $OnPodsad?></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>Паспорт сгенерирован</td>
			<td class = "passport_value"><?php echo date("d.m.Y H:i:s");?></td>
		</tr>
	</tbody>
</table>
</div>