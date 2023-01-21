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
          window.location.href='/ASUTP/localmenu/askureports_result/report_type=cshi_analysis1_daily&date1='+document.getElementById('theDate1').value.replace('.','-').replace('.','-');
        });
		$('#submit2').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          window.location.href='/ASUTP/localmenu/askureports_result/report_type=cshi_analysis2_daily&date1='+document.getElementById('theDate1').value.replace('.','-').replace('.','-');
        });
      });
	   window.onunload="$.unblockUI();";
</script>
<div id="domMessage" style="display:none;">
  <h1>Идет загрузка страницы</h1>
  <!--img src="/ASUTP/img/wait.gif"-->
  <?php echo html::image('img/wait.gif',array('border' => '0'))?>
</div>
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
			<input type="submit" value="Коксовый газ" id="submit1">
			<input type="submit" value="Сжатый воздух" id="submit2">
        </td>
	</tr>
</table>