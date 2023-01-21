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
		if (alarm_flag) document.getElementById('submit').disabled=true;
		else document.getElementById('submit').disabled=false;
	}
</script>
<script type="text/javascript">
  $(document).ready(function()
  {
    $('#submit').click(function()
    {
      $.blockUI({ message: $('#domMessage') });
			window.location.href='/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1='+document.getElementById('theDate1').value.replace('.','-').replace('.','-')+'&date2='+ document.getElementById('theDate2').value.replace('.','-').replace('.','-') + '&mech_id=' + document.getElementById('MechName').value + '&mech_name=' + document.getElementById('MechName').options[document.getElementById('MechName').selectedIndex].innerHTML + ''
		});
  });
</script>
<div id="domMessage" style="display:none;">
  <h1>Идет загрузка страницы</h1>
  <!--img src="/ASUTP/img/wait.gif"-->
  <?php echo html::image('img/wait.gif',array('border' => '0'))?>
</div>
<table align="center" style="width=550px;margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p>Название механизма:</p>
		</td>
<!--		<td></td> -->
		<td colspan="2" align="right">
      <select name="MechName" id="MechName">
<!--				<option value=166>Вентилятор 274</option> -->
				<option value=89>Вентилятор 274</option>
				<option value=168>Конвейер 616</option>
				<option value=169>Конвейер 616А</option>
				<option value=165>Конвейер 619</option>
				<option value=167>Элеватор 618-2</option>
				<option value=166>Конвейер ПГ 618-1</option>
				<option value=78>Питатель смесителя</option>
				<option value=81>Двигатель стакана</option>
				<option value=79>Двигатель завихрителя</option>
				<option value=82>Двигатель насоса</option>
				<option value=130>Двигатель пресса</option>
				<option value=132>Муфта</option>
				<option value=148>Аварийный стоп смесителя (инверсия)</option>
				<option value=149>Аварийный стоп пресса (инверсия)</option>
<!--				<option value=128>Старт цикла</option>
				<option value=129>Стоп цикла</option> -->

				<option value=20>Питатель (Весы 610 загрузка)</option>
				<option value=16>Днище (Весы 610)</option>
				<option value=25>Шибер (Весы 610)</option>
				<option value=18>Вибратор (Весы 610)</option>

				<option value=33>Питатель (Весы 612 загрузка)</option>
				<option value=29>Днище (Весы 612)</option>
				<option value=32>Вибратор (Весы 612)</option>

				<option value=39>Питатель (Весы 613 загрузка)</option>
				<option value=35>Днище (Весы 613)</option>
				<option value=38>Вибратор (Весы 613)</option>

				<option value=45>Клапан (Весы 611 загрузка)</option>
				<option value=43>Клапан "Грубо" (Весы 611 выгрузка)</option>
				<option value=44>Клапан "Точно" (Весы 611 выгрузка)</option>
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
		<td align="right">
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
			<input type="submit" id="submit" value="Отчёт">
		</td>
	</tr>
</table>
