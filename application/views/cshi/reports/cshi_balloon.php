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
    //alert('1');
    $('#submit').click(function()
    {
      $.blockUI({ message: $('#domMessage') });
      //test();
      //alert('2');
			window.location.href='/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1='+document.getElementById('theDate1').value.replace('.','-').replace('.','-')+'&date2='+ document.getElementById('theDate2').value.replace('.','-').replace('.','-') + '&param_type=' + document.getElementById('MechName').value + '&mech_name=' + document.getElementById('MechName').options[document.getElementById('MechName').selectedIndex].innerHTML
		});
  });
</script>
<div id="domMessage" style="display:none;">
  <h1>Идет загрузка страницы</h1>
  <!--img src="/ASUTP/img/wait.gif"-->
  <?php echo html::image('img/wait.gif',array('border' => '0'))?>
</div>
<table align="center" style="width=450px;margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p>Название механизма:</p>
		</td>
		<td colspan="2" align="right">
      <select name="MechName" id="MechName">
				<option value='IN_D-IN_D_56'>Баллон №1
				<option value='IN_D-IN_D_57'>Баллон №2
				<option value='IN_D-IN_D_58'>Баллон №3
				<option value='IN_D-IN_D_59'>Баллон №4
				<option value='IN_D-IN_D_60'>Баллон №5
				<option value='IN_D-IN_D_61'>Баллон №6
				<option value='IN_D-IN_D_62'>Баллон №7
				<option value='IN_D-IN_D_63'>Баллон №8
				<option value='IN_D-IN_D_64'>Баллон №9
				<option value='IN_D-IN_D_65'>Баллон №10
				<option value='IN_D-IN_D_66'>Баллон №11
				<option value='IN_D-IN_D_67'>Баллон №12
				<option value='IN_D-IN_D_68'>Баллон №13
				<option value='IN_D-IN_D_69'>Баллон №14
				<option value='IN_D-IN_D_70'>Баллон №15
				<option value='IN_D-IN_D_71'>Баллон №16
				<option value='IN_D-IN_D_72'>Баллон №17
				<option value='IN_D-IN_D_73'>Баллон №18
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
			<input type="submit" id="submit" class="demo" value="Отчёт">
		</td>
	</tr>
</table>
