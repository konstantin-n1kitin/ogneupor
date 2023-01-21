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
			window.location.href='/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1='+document.getElementById('theDate1').value.replace('.','-').replace('.','-')+'&date2='+ document.getElementById('theDate2').value.replace('.','-').replace('.','-') + '&route_number=' + document.getElementById('MarchNum').value
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
			<p>Маршрут №</p>
		</td>
		<td></td>
		<td align="right">
          <select name="MarchNum" size="1" id="MarchNum">
					<?php
						$count = 1;
						$MarshCount = 0;
						$sql = "select MAX(marsh) as Max_Marsh from fl_marsh";
						$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.'tpl-server'.';database='.'tpl'.';Uid='.'sa'.';Pwd='.'tpl'.';');
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
						$result = $db->query($sql);
						$data = $result->fetchAll();
						foreach($data as $rec)
						{
							$MarshCount = trim($rec[0]); //Кол-во маршрутов
						}
						$db = null;
						while ($count <= $MarshCount)
						{
							echo "<option value=$count>&nbsp;$count&nbsp;";
							$count++;
						}
					?>
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
		<td align="right" colspan=3>
			<input type="submit" name="submit" id="submit" class="demo" value="Отчёт">
		</td>
	</tr>
</table>