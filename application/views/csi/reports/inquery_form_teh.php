<script>
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
		if (alarm_flag) {
			document.getElementById('submit1').disabled=true;
			document.getElementById('submit2').disabled=true;
		}
		else {
			document.getElementById('submit1').disabled=false;
			document.getElementById('submit2').disabled=false;
		}
	}
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
          window.location.href='/ASUTP/localmenu/csireports_result/report_type=<?php echo $report_type?>&param_type=' + document.getElementById('sel_teh').value + '&date1=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-');
        });
		$('#submit2').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href='/ASUTP/localmenu/trend_result/report_type=<?php echo $report_type?>&param_type=' + document.getElementById('sel_teh').value + '&date1=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-');
        });
      });
</script>
<div id="domMessage" style="display:none;">
  <h1>Идет загрузка страницы</h1>
  <!--img src="/ASUTP/img/wait.gif"-->
  <?php echo html::image('img/wait.gif',array('border' => '0'))?>
</div>
<table align="center" style="margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p>Параметр:</p>
		</td>
		<td colspan="2" align="right">
			<select name="sel_teh" id="sel_teh">
				<option value=PLC-IN_A-01>Камерное сушило 1. Расход природ.газа</option>
				<option value=PLC-IN_A-02>Камерное сушило 1. Расход воздуха</option>
				<option value=PLC-IN_A-03>Камерное сушило 1. Давление природ.газа</option>
				<option value=PLC-IN_A-04>Камерное сушило 1. Давление воздуха</option>
				<option value=PLC-IN_A-05>Камерное сушило 1. Температура 1</option>
				<option value=PLC-IN_A-06>Камерное сушило 1. Температура 2</option>
				<option value=PLC-IN_A-07>Камерное сушило 1. Температура 3</option>
				<option value=PLC-IN_A-14>Камерное сушило 1. Положение ИМ расхода газа</option>
				<option value=PLC-IN_A-15>Камерное сушило 1. Положение ИМ расхода воздуха</option>
				<option value=PLC-IN_A-08>Камерное сушило 2. Расход природ.газа</option>
				<option value=PLC-IN_A-09>Камерное сушило 2. Расход воздуха</option>
				<option value=PLC-IN_A-10>Камерное сушило 2. Давление природ.газа</option>
				<option value=PLC-IN_A-11>Камерное сушило 2. Давление воздуха</option>
				<option value=PLC-IN_A-12>Камерное сушило 2. Температура 1</option>
				<option value=PLC-IN_A-13>Камерное сушило 2. Температура 2</option>
				<option value=PLC-IN_A-19>Камерное сушило 2. Температура 3</option>
				<option value=PLC-IN_A-17>Камерное сушило 2. Положение ИМ расхода газа</option>
				<option value=PLC-IN_A-18>Камерное сушило 2. Положение ИМ расхода воздуха</option>
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
			<input type="text" align="middle" value="<?php $dt=new DateTime(date('d.m.Y H:i')); $dt->sub(new DateInterval('PT12H'));echo $dt->format('d.m.Y H:i');?>" name="theDate1" id="theDate1" maxlength="16" onKeyUp="checkDate()" onChange="checkDate()">
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
		<td align="right" style="width:180px">
			<p><label name="alarm2" id="alarm2" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="middle" value="<?php echo date('d.m.Y H:i');?>" name="theDate2" id="theDate2" maxlength="16" onKeyUp="checkDate()" onChange="checkDate()">
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
			<input type="submit" id="submit1" value="Отчет">
			<input type="submit" id="submit2" value="График">
		</td>
	</tr>
</table>