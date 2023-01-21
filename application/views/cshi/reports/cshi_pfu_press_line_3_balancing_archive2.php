<script>
	function checkDate () {
		mask='31.12.2050';
		alarm_flag=false;
		if (isDate(document.getElementById('theDate1').value,mask)) {
			document.getElementById('alarm1').innerHTML="";
			document.getElementById('submit1').disabled=false;
			document.getElementById('submit2').disabled=false;
		}
		else {
			document.getElementById('alarm1').innerHTML="Неверная дата";
			document.getElementById('submit1').disabled=true;
			document.getElementById('submit2').disabled=true;
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
		if (alarm_flag) {
			document.getElementById('submit3').disabled=true;
			document.getElementById('submit4').disabled=true;
		}
		else {
			document.getElementById('submit3').disabled=false;
			document.getElementById('submit4').disabled=false;
		}
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
<script type="text/javascript">
	  $(document).ready(function()
      {
		//alert('1');
		$('#submit1').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href="/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1="+document.getElementById('theDate1').value.replace('.','-').replace('.','-')+"&scale_number="+document.getElementById('scale_number').value;
        });
		$('#submit5').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href="/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1=01-" + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value + ' 00:00:00' + '&date2='+ getMaxDate(document.getElementById('sel_year').value, document.getElementById('sel_month').value-1) + '-' + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value + ' 24:00:00'+"&scale_number="+document.getElementById('scale_number').value;
        });
		$('#submit3').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href="/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1=" + document.getElementById('theDate2').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate3').value.replace('.','-').replace('.','-') +"&scale_number="+document.getElementById('scale_number').value;
        });
      });
	   window.onunload="$.unblockUI();";
</script>
<div id="domMessage" style="display:none;">
  <h1>Идет загрузка страницы</h1>
  <!--img src="/ASUTP/img/wait.gif"-->
  <?php echo html::image('img/wait.gif',array('border' => '0'))?>
</div>
<table align="center" style="width:450px;margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p>Номер весов</p>
		</td>
		<td align="right">
			<select name="scale_number" id="scale_number">
				<option selected value=1>620</option>
				<option value=2>621</option>
				<option value=3>622</option>
				<option value=4>623</option>
				<option value=5>594</option>
			</select>
		</td>
	</tr>
</table>
<p align="center" style="font-size: 12px; font-weight:bold;">Суточный отчёт</p>
<table align="center" style="width:450px;margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p align="middle">Выберите дату:</p>
		</td>
		<td align="right">
			<p><label name="alarm1" id="alarm1" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="middle" value="<?php echo date('d.m.Y');?>" name="theDate1" id="theDate1" maxlength="10" onKeyUp="checkDate()" onChange="checkDate()">
			<button id="trigger1">...</button>
			<script type="text/javascript">
				Calendar.setup(
					{
						inputField : "theDate1", // ID of the input field
						ifFormat : "%d.%m.%Y", // the date format
						//showsTime : true,
						button : "trigger1" // ID of the button
					}
				);
			</script>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="right">
			<input type="submit" value="Генерировать отчёт" id="submit1">
        </td>
	</tr>
</table>
<p align="center" style="font-size: 12px; font-weight:bold;">Месячный отчёт</p>
<table align="center" style="width:450px;margin: 10px auto;font-size: 12px;">
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
			<input type="submit" id="submit5" value="Генерировать отчёт">
       	</td>
	</tr>
</table>
<p align="center" style="font-size: 12px; font-weight:bold;">Пользовательский отчёт</p>
<table align="center" style="width:450px;margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p>Начальная дата:</p>
		</td>
		<td align="right">
			<p><label name="alarm2" id="alarm2" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="middle" value="<?php $dt=new DateTime(date('d.m.Y')); $dt->sub(new DateInterval('P1M'));echo $dt->format('d.m.Y');?>" name="theDate2" id="theDate2" maxlength="10" onKeyUp="checkDate()" onChange="checkDate()">
			<button id="trigger2">...</button>
			<script type="text/javascript">
				Calendar.setup(
					{
						inputField : "theDate2", // ID of the input field
						ifFormat : "%d.%m.%Y", // the date format
						//showsTime : true,
						button : "trigger2" // ID of the button
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
			<p><label name="alarm3" id="alarm3" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="middle" value="<?php echo date('d.m.Y');?>" name="theDate3" id="theDate3" maxlength="10" onKeyUp="checkDate()" onChange="checkDate()">
			<button id="trigger3">...</button>
			<script type="text/javascript">
				Calendar.setup(
					{
						inputField : "theDate3", // ID of the input field
						ifFormat : "%d.%m.%Y", // the date format
						//showsTime : true,
						button : "trigger3" // ID of the button
					}
				);
			</script>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="right">
			<input type="submit" value="Генерировать отчёт" id="submit3">
        </td>
	</tr>
</table>