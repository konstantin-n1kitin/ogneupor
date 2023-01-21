<?php
// Для доменной авторизации
/*	$cred = explode('\\',$_SERVER['REMOTE_USER']);
	if (count($cred) == 1) array_unshift($cred, "(no domain info - perhaps SSPIOmitDomain is On)");
	list($domain, $user) = $cred;

	echo "You appear to be user <B>$user</B><BR/>";
	echo "logged into the Windows NT domain <B>$domain</B>";
*/
?>

<p align="right">
	<?php echo Html::image('img/printer.png',array('onClick'=>'printit()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
	<?php echo Html::image('img/pdf.png',array('onClick'=>'GetPDF()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
</p>
<script>
	function getParameterByName(name) 
	{ 
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]"); 
		var regexS = "[\\?&]" + name + "=([^&#]*)"; 
		var regex = new RegExp(regexS); 
		var results = regex.exec(window.location.search); 
		if(results == null) 
			return ""; 
		else
			return decodeURIComponent(results[1].replace(/\+/g, " ")); 
	}

	function checkDate () {
		mask='31.12.2050 23:59';
		alarm_flag=false;
		if (isDate(document.getElementById('theDate1').value,mask))
			document.getElementById('alarm1').innerHTML="";
		else {
			document.getElementById('alarm1').innerHTML="Неверная дата";
			alarm_flag=true;
		}
		if (isDate(document.getElementById('theDate2').value,mask))
			document.getElementById('alarm2').innerHTML="";
		else {
			document.getElementById('alarm2').innerHTML="Неверная дата";
			alarm_flag=true;
		}
		if (alarm_flag) document.getElementById('submit').disabled=true;
		else document.getElementById('submit').disabled=false;
	}
      $(document).ready(function()
      {
        //alert('1');
        $('#submit1').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href='/ASUTP/localmenu/cshireports/report_type=<?php echo $id?>&product_type=' + document.getElementById('gruz_type').value + '&date1=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-');
        });
		$('#submit2').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href='/ASUTP/localmenu/trend_result/report_type=<?php echo $id?>&product_type=' + document.getElementById('gruz_type').value + '&date1=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-');
        });
      });	
</script>
<div id="report_content">
<table align="center" style="width=450px;margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p>Тип сырья:</p> 
		</td>
		<td colspan=2 align="right">
			<select name="gruz_type" id="gruz_type">
				<option value=0 <?php if ($product_type == 0) echo 'selected' ?> >Все</option>
				<option value=1 <?php if ($product_type == 1) echo 'selected' ?> >Аркалык</option>
				<option value=2 <?php if ($product_type == 2) echo 'selected' ?> >Берлинка</option> 
			</select>		
		</td>
	</tr>
	<tr>
		<td>
			<p>Начальная дата:</p>
		</td>
		<td align="right">
			<p><label name="alarm1" id="alarm1" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="middle" value="<?php echo $dt_beg;?>" name="theDate1" id="theDate1" maxlength="16" onKeyUp="checkDate()" onChange="checkDate()">
			<button id="trigger1">...</button>
			<script type="text/javascript">
				Calendar.setup(
					{
						inputField : "theDate1", // ID of the input field
						ifFormat : "%d.%m.%Y %H:%M", // the date format
						showsTime : true,
						button : "trigger1" // ID of the button
					}
				);
			</script>
		</td>
	</tr>
	<tr>
		<td>
			<p>Конечная дата:</p>
		</td>
		<td align="right">
			<p><label name="alarm2" id="alarm2" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="middle" value="<?php echo $dt_end;?>" name="theDate2" id="theDate2" maxlength="16" onKeyUp="checkDate()" onChange="checkDate()">
			<button id="trigger2">...</button>
			<script type="text/javascript">
				Calendar.setup(
					{
						inputField : "theDate2", // ID of the input field
						ifFormat : "%d.%m.%Y %H:%M", // the date format
						showsTime : true,
						button : "trigger2" // ID of the button
					}
				);
			</script>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="right">
<!--			<input type="submit" name="submit" id="submit" value="Показать список вагонов" onclick="javascript:location.href='/ASUTP/localmenu/cshireports_carts_passport/param_type=' + document.getElementById('gruz_type').value + '&dt_beg=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&dt_end=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-');"> -->
			<input type="submit" name="submit" id="submit" value="Показать список вагонов" onclick="javascript:location.href='/ASUTP/localmenu/cshireports_carts_passport/product_type=' + document.getElementById('gruz_type').value + '&dt_beg=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&dt_end=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-');">
		</td>
	</tr>
</table>
<center>
<?php echo $table?>
</center>
</div>