<p align="right">
	<?php echo Html::image('img/printer.png',array('onClick'=>'printit()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
	<?php echo Html::image('img/pdf.png',array('onClick'=>'GetPDF()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
</p>
<div id="report_content">
	<table id="cart_passport_table" border=0>
		<tr>
			<td colspan="6" style="vertical-align:middle;text-align:center;">ПАСПОРТ ВАГОНА №<?php echo $Basa_NumTS?></td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<tr>
			<td style="background-color:transparent;border:0px #C0C0C0 solid;text-align:left;vertical-align:middle;width:124px;height:18px;">
				<div><span style="color:#000000;font-family:Arial;font-size:11px;">Дата составления:</span></div>
			</td>
<!--			<td colspan="5" style="text-align:left; font-family:Arial;font-size:13px;"><?php echo $Basa_Datetime_first?></td> -->
			<td colspan="5" style="text-align:left; font-family:Arial;font-size:13px;"><?php $dt = date("d.m.Y H:i:s"); echo $dt ?></td>
		</tr>
		<tr>
			<td border=6>&nbsp;</td>
		</tr>
		<tr>
			<td style="border:1px #000000 solid;text-align:center;vertical-align:middle;width:124px;height:18px;color:#000000;font-family:Arial;font-size:13px;">Время</td>
			<td style="border:1px #000000 solid;text-align:center;vertical-align:middle;width:124px;height:18px;color:#000000;font-family:Arial;font-size:13px;">Номер вагона</td>
			<td style="border:1px #000000 solid;text-align:center;vertical-align:middle;width:124px;height:18px;color:#000000;font-family:Arial;font-size:13px;">Брутто, кг</td>
			<td style="border:1px #000000 solid;text-align:center;vertical-align:middle;width:86px;height:18px;color:#000000;font-family:Arial;font-size:13px;">Тара, кг</td>
			<td style="border:1px #000000 solid;text-align:center;vertical-align:middle;width:124px;height:18px;color:#000000;font-family:Arial;font-size:13px;">Нетто, кг</td>
			<td style="border:1px #000000 solid;text-align:center;vertical-align:middle;height:18px;color:#000000;font-family:Arial;font-size:13px;">Вид сырья</td>
		</tr>
		<tr style="font-family:Arial;font-size:14px;">
			<td style="border:1px #000000 solid;text-align:center;"><?php echo $Basa_Datetime_first?>&nbsp;</td>
			<td style="border:1px #000000 solid;text-align:center;"><?php echo $Basa_NumTS?>&nbsp;</td>
			<td style="border:1px #000000 solid;text-align:center;"><?php echo $Basa_Brutto?>&nbsp;</td>
			<td style="border:1px #000000 solid;text-align:center;"><?php echo $Basa_Tara?>&nbsp;</td>
			<td style="border:1px #000000 solid;text-align:center;"><?php echo $Basa_Netto?>&nbsp;</td>
			<td style="border:1px #000000 solid;text-align:center;"><?php echo $GRUZ_Name?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6" style="background-color:transparent;border:0px #C0C0C0 solid;text-align:left;vertical-align:top;height:18px;">&nbsp;</td>
		</tr>
		<tr>
			<td style="background-color:transparent;border:0px #C0C0C0 solid;text-align:left;vertical-align:top;width:124px;height:20px;">
				<div><span style="color:#000000;font-family:Arial;font-size:13px;">Оператор:</span></div>
			</td>
			<td colspan="2" style="background-color:transparent;border:0px #C0C0C0 solid;text-align:left;vertical-align:top;height:20px;">
				<div><span style="color:#000000;font-family:Arial;font-size:16px;">___________________________</span></div>
			</td>
			<td colspan="3" style="background-color:transparent;border:0px #C0C0C0 solid;text-align:left;vertical-align:top;height:20px;">
				<div><span style="color:#000000;font-family:Arial;font-size:13px;">________________________________________________</span></div>
			</td>
		</tr>
		<tr>
			<td style="background-color:transparent;border:0px #C0C0C0 solid;text-align:left;vertical-align:top;width:124px;height:18px;">&nbsp;</td>
			<td colspan="2" style="background-color:transparent;border:0px #C0C0C0 solid;text-align:center;vertical-align:top;height:18px;">
				<div><span style="color:#000000;font-family:Arial;font-size:13px;"><sup>подпись</sup></span></div>
			</td>
			<td colspan="3" style="background-color:transparent;border:0px #C0C0C0 solid;text-align:center;vertical-align:top;height:18px;">
				<div><span style="color:#000000;font-family:Arial;font-size:13px;"><sup>фамилия</sup></span></div>
			</td>
		</tr>		
	</table>
</div>