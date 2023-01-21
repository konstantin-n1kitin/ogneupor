<link rel="stylesheet" href="/ASUTP/css/table.css" type="text/css">
<link href="/ASUTP/css/menu_style.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="/ASUTP/css/ThemeRibbon/theme.css" type="text/css">
<center>
<table id="report_table">
<tr>
	<td style="border:0px">
		<table style="font-size:12px;width:auto;" id="CSHI_conscales29">
			<thead>
				<tr>
					<th colspan="2" style="background-color:#69C;">Конвейерные весы №29</th>
				</tr>
			</thead>
			<tbody>
				<tr class="hid">
					<td style="text-align:left">Счетчик 1</td>
					<td>-</td>
				</tr>
				<tr class="hid">
					<td style="text-align:left">Счетчик 2</td>
					<td>-</td>
				</tr>
				<tr class="hid">
					<td style="text-align:left">Счетчик 3</td>
					<td>-</td>
				</tr>
				<tr>
					<td style="text-align:left">Текущая нагрузка на ленту</td>
					<td>-</td>
				</tr>
				<tr>
					<td style="text-align:left">Скорость ленты</td>
					<td>-</td>
				</tr>
				<tr>
					<td style="text-align:left">Текущая производительность</td>
					<td>-</td>
				</tr>
			</tbody>
		</table>
	</td>
	<td style="border:0px">
		<table style="font-size:12px;width:auto;" id="CSHI_conscales39">
			<thead>
				<tr>
					<th colspan="2" style="background-color:#69C;">Конвейерные весы №39</th>
				</tr>
			</thead>
			<tbody>
				<tr class="hid">
					<td style="text-align:left">Счетчик 1</td>
					<td>-</td>
				</tr>
				<tr class="hid">
					<td style="text-align:left">Счетчик 2</td>
					<td>-</td>
				</tr>
				<tr class="hid">
					<td style="text-align:left">Счетчик 3</td>
					<td>-</td>
				</tr>
				<tr>
					<td style="text-align:left">Текущая нагрузка на ленту</td>
					<td>-</td>
				</tr>
				<tr>
					<td style="text-align:left">Скорость ленты</td>
					<td>-</td>
				</tr>
				<tr>
					<td style="text-align:left">Текущая производительность</td>
					<td>-</td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>
</table>
<p><?php $date=new DateTime();for ($i=1;$i<=$skip;$i++) $date->sub(new DateInterval('PT12H'));echo $date->format('d.m.Y');$date8=clone $date;$date8->setTime(8,0,0);if ($date>$date8) echo " Смена 2"; else echo " Смена 1";?></p>
<p align="center"><img src="/ASUTP/php/trend_img_con29.php?skip=<?php echo $skip?>"></p>
<p align="center"><img src="/ASUTP/php/trend_img_con39.php?skip=<?php echo $skip?>"></p>
<input type="button" onclick='location.href="http://oup-doc1/ASUTP/basic/cshiconscales2/<?php echo $skip+1?>"' value="Предыдущая смена"/>
<input type="button" onclick='location.href="http://oup-doc1/ASUTP/basic/cshiconscales2/0"' value="Текущая смена"/>
<input type="button" <?php if ($skip==0) echo "disabled";?> onclick='location.href="http://oup-doc1/ASUTP/basic/cshiconscales2/<?php echo $skip-1?>"' value="Следующая смена"/>