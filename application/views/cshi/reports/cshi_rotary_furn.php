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
          window.location.href='/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&param_type=' + document.getElementById('sel_teh').value + '&date1=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-');
        });
		$('#submit2').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
          window.location.href='/ASUTP/localmenu/trend_result/report_type=<?php echo $id?>&param_type=' + document.getElementById('sel_teh').value + '&date1=' + document.getElementById('theDate1').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate2').value.replace('.','-').replace('.','-');
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
				<option value="IN_A-IN_A_34">Разрежение впылевой камере</option>
				<option value="IN_A-IN_A_35">Разрежение перед скруббером</option>
				<option value="IN_A-IN_A_36">Разрежение перед циклоном левый канал</option>
				<option value="IN_A-IN_A_37">Разрежение перед циклоном правый канал</option>
				<option value="IN_A-IN_A_38">Разрежение перед электрофильтром левый канал</option>
				<option value="IN_A-IN_A_39">Разрежение перед электрофильтром правый канал</option>
				<option value="IN_A-IN_A_40">Разрежение перед дымососом</option>
				<option value="IN_A-IN_A_41">Температура в пылевой камере</option>
				<option value="IN_A-IN_A_42">Анализ на СО</option>
				<option value="IN_A-IN_A_43">Температура перед циклоном левый канал</option>
				<option value="IN_A-IN_A_44">Температура перед циклоном правый канал</option>
				<option value="IN_A-IN_A_45">Температура перед электрофильтром левый канал</option>
				<option value="IN_A-IN_A_46">Температура перед электрофильтром правый канал</option>
				<option value="IN_A-IN_A_47">Температура перед дымососом</option>
				<option value="IN_A-IN_A_48">Расход вентиляторного воздуха</option>
				<option value="IN_A-IN_A_49">Расход газа</option>
				<option value="IN_A-IN_A_50">Давление газа</option>
				<option value="IN_A-IN_A_51">Весы</option>
				<option value="IN_A-IN_A_52">Нагрузка питателя</option>
				<option value="IN_A-IN_A_53">Положение ИМ расхода газа</option>
				<option value="IN_A-IN_A_54">Положение ИМ расхода воздуха</option>
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