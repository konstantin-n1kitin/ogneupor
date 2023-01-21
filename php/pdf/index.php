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
		<script>
			function checkDate () {
				mask='31.12.2050';
				alarm_flag=false;
				if (isDate(document.getElementById('theDate1').value,mask)) {
					document.getElementById('alarm1').innerHTML="";
					document.getElementById('submit1').disabled=false;
				}
				else {
					document.getElementById('alarm1').innerHTML="Неверная дата";
					document.getElementById('submit1').disabled=true;
				}
				if (isDate(document.getElementById('theDate2').value,mask))
					document.getElementById('alarm2').innerHTML="";
				else {
					document.getElementById('alarm2').innerHTML="Неверная дата";
					alarm_flag=true;
				}
				if (isDate(document.getElementById('theDate3').value,mask))
					document.getElementById('alarm3').innerHTML="";
				else {
					document.getElementById('alarm3').innerHTML="Неверная дата";
					alarm_flag=true;
				}
				if (alarm_flag) document.getElementById('submit2').disabled=true;
				else document.getElementById('submit2').disabled=false;
			}
		</script>
		<script type="text/javascript">
			getMaxDate = function(y, m) {
				if (m == 1) {
					return y%4 || (!(y%100) && y%400 ) ? 28 : 29;
				};
				return m===3 || m===5 || m===8 || m===10 ? 30 : 31;
			};
		 </script>
		<table align="center" style="margin: 10px auto; font-size: 16px;">
			<tr>
				<td>
					<p>Выберите дату:</p>
				</td>
				<td align="right">
					<select name="sel_month" id="sel_month">
						<option <?php if (date('m')=='01') echo "selected"?> value=01>Январь</option>
						<option <?php if (date('m')=='02') echo "selected"?> value=02>Февраль</option>
						<option <?php if (date('m')=='03') echo "selected"?> value=03>Март</option>
						<option <?php if (date('m')=='04') echo "selected"?> value=04>Апрель</option>
						<option <?php if (date('m')=='05') echo "selected"?> value=05>Май</option>
						<option <?php if (date('m')=='06') echo "selected"?> value=06>Июнь</option>
						<option <?php if (date('m')=='07') echo "selected"?> value=07>Июль</option>
						<option <?php if (date('m')=='08') echo "selected"?> value=08>Август</option>
						<option <?php if (date('m')=='09') echo "selected"?> value=09>Сентябрь</option>
						<option <?php if (date('m')=='10') echo "selected"?> value=10>Октябрь</option>
						<option <?php if (date('m')=='11') echo "selected"?> value=11>Ноябрь</option>
						<option <?php if (date('m')=='12') echo "selected"?> value=12>Декабрь</option>
					</select>
					<select name="sel_year" id="sel_year">
						<option value=<?php echo date('Y');?>><?php echo date('Y');?></option>
						<option value=<?php echo date('Y')-1;?>><?php echo date('Y')-1;?></option>
						<option value=<?php echo date('Y')-2;?>><?php echo date('Y')-2;?></option>
						<option value=<?php echo date('Y')-3;?>><?php echo date('Y')-3;?></option>
						<option value=<?php echo date('Y')-4;?>><?php echo date('Y')-4;?></option>
						<option value=<?php echo date('Y')-5;?>><?php echo date('Y')-5;?></option>
						<option value=<?php echo date('Y')-6;?>><?php echo date('Y')-6;?></option>
						<option value=<?php echo date('Y')-7;?>><?php echo date('Y')-7;?></option>
						<option value=<?php echo date('Y')-8;?>><?php echo date('Y')-8;?></option>
						<option value=<?php echo date('Y')-9;?>><?php echo date('Y')-9;?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="submit" value="Генерировать отчёт" onclick="php:location.href='report.php?date1=01-'+document.getElementById('sel_month').value+'-'+document.getElementById('sel_year').value+'&date2='+getMaxDate(document.getElementById('sel_year').value,document.getElementById('sel_month').value-1)+'-'+document.getElementById('sel_month').value+'-'+document.getElementById('sel_year').value"><br>
				</td>
			</tr>
		</table>
	</body>
</html>