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
				<option value=T-T_SIGNAL_01>Температура сушила на позиции 3С(Л)</option>
				<option value=T-T_SIGNAL_02>Температура сушила на позиции 3С(П)</option>
				<option value=T-T_SIGNAL_03>Температура печи на позиции 5(Л)</option>
				<option value=T-T_SIGNAL_04>Температура печи на позиции 5(П)</option>
				<option value=T-T_SIGNAL_05>Температура печи на позиции 9(Л)</option>
				<option value=T-T_SIGNAL_06>Температура печи на позиции 13(Л)</option>
				<option value=T-T_SIGNAL_07>Температура печи на позиции 15(П)</option>
				<option value=T-T_SIGNAL_08>Температура печи на позиции 15(Л)</option>
				<option value=T-T_SIGNAL_09>Температура печи на позиции 18(П)</option>
				<option value=T-T_SIGNAL_10>Температура печи на позиции 19(Л)</option>
				<option value=T-T_SIGNAL_11>Температура печи на позиции 21(Л)</option>
				<option value=T-T_SIGNAL_12>Температура печи на позиции 22(Л)</option>
				<option value=T-T_SIGNAL_13>Температура печи на позиции 24(П)</option>
				<option value=T-T_SIGNAL_14>Температура печи на позиции 29(Л)</option>
				<option value=T-T_SIGNAL_15>Температура печи на позиции 35(П)</option>
				<option value=T-T_SIGNAL_17>Температура печи на позиции 40(Л)</option>
				<option value=T-T_SIGNAL_18>Температура отходящего воздуха поз 30-31</option>
				<option value=T-T_SIGNAL_19>Температура отходящего воздуха поз 34-35</option>
				<option value=T-T_SIGNAL_24>Температура рециркуляционного воздуха</option>
				<option value=T-T_SIGNAL_25>Температура отходящих газов</option>
				<option value=T-T_SIGNAL_26>Температура сушила на позиции 10(Л)</option>
				<option value=T-T_SIGNAL_27>Температура сушила на позиции 10(П)</option>
				<option value=T-T_SIGNAL_33>Температура свода печи на позиции 15(СВ)</option>
				<option value=T-T_SIGNAL_34>Температура свода печи на позиции 20(СВ)</option>
				<option value=T-T_SIGNAL_35>Температура свода печи на позиции 23(СВ)</option>
				<option value=F-F_SIGNAL_01>Расход газа зона 1 Левая</option>
				<option value=F-F_SIGNAL_02>Расход газа зона 1 Правая</option>
				<option value=F-F_SIGNAL_03>Расход газа зона 2 Левая</option>
				<option value=F-F_SIGNAL_04>Расход газа зона 2 Правая</option>
				<option value=F-F_SIGNAL_05>Расход газа зона 3 Левая</option>
				<option value=F-F_SIGNAL_06>Расход газа зона 3 Правая</option>
				<option value=F-F_SIGNAL_07>Расход воздуха зона 1 Левая</option>
				<option value=F-F_SIGNAL_08>Расход воздуха зона 1 Правая</option>
				<option value=F-F_SIGNAL_09>Расход воздуха зона 2 Левая</option>
				<option value=F-F_SIGNAL_10>Расход воздуха зона 2 Правая</option>
				<option value=F-F_SIGNAL_11>Расход воздуха зона 3 Левая</option>
				<option value=F-F_SIGNAL_12>Расход воздуха зона 3 Правая</option>
				<option value=F-F_SIGNAL_13>Расход воздуха (Распределенная подача)</option>
				<option value=F-F_SIGNAL_14>Расход воздуха (Сосредоточенная подача)</option>
				<option value=F-F_SIGNAL_15>Расход воздуха (Рециркуляционный)</option>
				<option value=F-F_SIGNAL_16>Расход коксового газа</option>
				<option value=F-F_SIGNAL_17>Расход воздуха</option>
				<option value=P-P_SIGNAL_01>Давление в сушиле на позиции 3С</option>
				<option value=P-P_SIGNAL_02>Давление в сушиле на позиции 10С</option>
				<option value=P-P_SIGNAL_04>Давление в печи на позиции 5</option>
				<option value=P-P_SIGNAL_05>Давление в печи на позиции 15</option>
				<option value=P-P_SIGNAL_08>Давление в печи на позиции 33</option>
				<option value=P-P_SIGNAL_09>Давление в печи на позиции 38</option>
				<option value=P-P_SIGNAL_10>Давление газа в печь</option>
				<option value=P-P_SIGNAL_11>Давление отходящих газов</option>
				<option value=P-P_SIGNAL_12>Давление воздуха на горение</option>
				<option value=P-P_SIGNAL_13>Давление в зоне охлаждения</option>
				<option value=P-P_SIGNAL_14>Разряжение в зоне нагрева</option>
				<option value=T-T_SIGNAL_41>Температура ТП1 23 левая</option>
				<option value=T-T_SIGNAL_42>Температура ТП1 22 правая</option>
				<option value=T-T_SIGNAL_43>Температура ТП3 23 левая</option>
				<option value=T-T_SIGNAL_44>Температура ТП3 22 правая</option>
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