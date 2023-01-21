<script>
	function checkDate () {
		mask='31.12.2050';
		alarm_flag1=false;
		if (isDate(document.getElementById('theDate1').value,mask))
			document.getElementById('alarm1').innerHTML="";
		else {
			document.getElementById('alarm1').innerHTML="Неверная дата";
			alarm_flag1=true;
		}
		if (isDate(document.getElementById('theDate2').value,mask))
			document.getElementById('alarm2').innerHTML="";
		else {
			document.getElementById('alarm2').innerHTML="Неверная дата";
			alarm_flag1=true;
		}
		if (alarm_flag1) {
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
		$('#submit1').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href='/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1=01-' + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value + '&date2='+ getMaxDate(document.getElementById('sel_year').value, document.getElementById('sel_month').value-1) + '-' + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value+'&brigade='+document.getElementById('sel_brigade').value+'&furnace='+document.getElementById('sel_furnace').value;
        });
		$('#submit2').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href='/ASUTP/localmenu/brigade_trend_result/report_type=<?php echo $id?>&date1=01-' + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value + '&date2='+ getMaxDate(document.getElementById('sel_year').value, document.getElementById('sel_month').value-1) + '-' + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value+'&brigade='+document.getElementById('sel_brigade').value+'&furnace='+document.getElementById('sel_furnace').value;
        });
		$('#submit3').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href='/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-')+'&brigade='+document.getElementById('sel_brigade').value+'&furnace='+document.getElementById('sel_furnace').value;
        });
		$('#submit4').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href='/ASUTP/localmenu/brigade_trend_result/report_type=<?php echo $id?>&date1=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-')+'&brigade='+document.getElementById('sel_brigade').value+'&furnace='+document.getElementById('sel_furnace').value;
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
			<p>Бригада</p>
		</td>
		<td>
		</td>
		<td align="right">
			<select name="sel_brigade" id="sel_brigade">
				<option selected value=0>Все</option>
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
			</select>
		</td>
	<tr>
	<tr>
		<td>
			<p>Вращающаяся печь</p>
		</td>
		<td>
		</td>
		<td align="right">
			<select name="sel_furnace" id="sel_furnace">
				<option selected value=0>Все</option>
				<option value=1>1</option>
				<option value=2>2</option>
			</select>
		</td>
	<tr>
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
			<input type="submit" id="submit1" value="Отчёт">
			<input type="submit" id="submit2" value="График">
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
			<p><label name="alarm1" id="alarm1" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="middle" value="<?php $dt=new DateTime(date('d.m.Y')); $dt->sub(new DateInterval('P1M'));echo $dt->format('d.m.Y');?>" name="theDate1" id="theDate1" maxlength="10" onKeyUp="checkDate()" onChange="checkDate()">
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
		<td>
			<p>Конечная дата:</p>
		</td>
		<td align="right">
			<p><label name="alarm2" id="alarm2" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="middle" value="<?php echo date('d.m.Y');?>" name="theDate2" id="theDate2" maxlength="10" onKeyUp="checkDate()" onChange="checkDate()">
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
		<td colspan="3" align="right">
			<input type="submit" value="Отчёт" id="submit3">
			<input type="submit" value="График" id="submit4">
        </td>
	</tr>
</table>
