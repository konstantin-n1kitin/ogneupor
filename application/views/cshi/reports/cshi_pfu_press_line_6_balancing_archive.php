<script>
	function checkDate () {
		mask='31.12.2050 23:59';
		alarm_flag=false;
		/*if (isDate(document.getElementById('theDate1').value,mask)) {
			document.getElementById('alarm1').innerHTML="";
			document.getElementById('submit1').disabled=false;
		}
		else {
			document.getElementById('alarm1').innerHTML="Неверная дата";
			document.getElementById('submit1').disabled=true;
		}*/
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
		if (alarm_flag) document.getElementById('submit3').disabled=true;
		else document.getElementById('submit3').disabled=false;
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
//          window.location.href="/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1="+document.getElementById('theDate1').value.replace('.','-').replace('.','-')+"&scale_number="+document.getElementById('scale_number').value+"&zone="+document.getElementById('zone').value.replace('.','_').replace(',','_');
          window.location.href="/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1="+document.getElementById('theDate1').value.replace('.','-').replace('.','-')+"&scale_number="+document.getElementById('scale_number').value;
        });
        $('#submit2').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
//          window.location.href="/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1=01-" + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value + ' 00:00:00' + '&date2='+ getMaxDate(document.getElementById('sel_year').value, document.getElementById('sel_month').value-1) + '-' + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value + ' 24:00:00'+"&scale_number="+document.getElementById('scale_number').value+"&zone="+document.getElementById('zone').value.replace('.','_').replace(',','_');
          window.location.href="/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1=01-" + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value + ' 00:00:00' + '&date2='+ getMaxDate(document.getElementById('sel_year').value, document.getElementById('sel_month').value-1) + '-' + document.getElementById('sel_month').value + '-' + document.getElementById('sel_year').value + ' 24:00:00'+"&scale_number="+document.getElementById('scale_number').value;
        });
        $('#submit3').click(function()
        {
          $.blockUI({ message: $('#domMessage') });
          //test();
          //alert('2');
//          window.location.href="/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1=" + document.getElementById('theDate2').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate3').value.replace('.','-').replace('.','-') +"&scale_number="+document.getElementById('scale_number').value+"&zone="+document.getElementById('zone').value.replace('.','_').replace(',','_');
          window.location.href="/ASUTP/localmenu/cshireports_result/report_type=<?php echo $id?>&date1=" + document.getElementById('theDate2').value.replace('.','-').replace('.','-') + '&date2=' + document.getElementById('theDate3').value.replace('.','-').replace('.','-') +"&scale_number="+document.getElementById('scale_number').value;
        });
      });
   </script>
<script type="text/javascript">
function testKey(e)
{
	// Make sure to use event.charCode if available
	var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);

	//alert(key);
  
	//Ignore special keys
	if (e.ctrlKey || e.altKey || key < 41)
		return true;

	//if (document.getElementById('zone').value=='0' && key==48)
		//return false;
	
	key = String.fromCharCode(key);
	if (document.getElementById('zone').value.indexOf('.')==-1)
		return /[\d\.]/.test(key);
	else return /[\d]/.test(key);
}
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
<!--				<option selected value="ALL">Все</option> -->
				<option selected value="650">650</option>
<!--				<option value=650>650</option> -->
			</select>
		</td>
	</tr>
<!--<tr>
		<td>
			<p>Зона допустимой погрешности, кг</p>
		</td>
		<td align="right">
			<input align="right" type="text" value="0.5" name="zone" id="zone" onkeypress="return testKey(event)" onblur="if(this.value!='') this.value=parseFloat(this.value); else this.value=0;">
		</td>
	</tr> -->
</table>

<table align="center" style="width:450px;margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p>Начальная дата:</p>
		</td>
		<td align="right">
			<p><label name="alarm2" id="alarm2" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="right" value="<?php $dt=new DateTime(date('')); $dt->sub(new DateInterval('P1D'));echo $dt->format('d.m.Y H:i');?>" name="theDate2" id="theDate2" maxlength="16" onKeyUp="checkDate()" onChange="checkDate()">
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
		<td>
			<p>Конечная дата:</p>
		</td>
		<td align="right">
			<p><label name="alarm3" id="alarm3" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="right" value="<?php echo date('d.m.Y H:i');?>" name="theDate3" id="theDate3" maxlength="16" onKeyUp="checkDate()" onChange="checkDate()">
			<button id="trigger3">...</button>
			<script type="text/javascript">
				Calendar.setup(
					{
						inputField : "theDate3", // ID of the input field
						ifFormat : "%d.%m.%Y %H:%M", // the date format
						showsTime : true,
						button : "trigger3" // ID of the button
					}
				);
			</script>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="right">
			<input type="submit" id="submit3" value="Генерировать отчёт"><br>
		</td>
	</tr>
</table>