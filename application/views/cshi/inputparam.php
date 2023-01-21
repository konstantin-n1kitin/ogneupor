<script>
	function checkDate () {
		mask='31.12.2050';
		if (isDate(document.getElementById('theDate1').value,mask)){
			document.getElementById('alarm1').innerHTML="";
			alarm1=false;
		}
		else {
			document.getElementById('alarm1').innerHTML="Неверная дата";
			alarm1=true;
		}
		if(parseFloat(document.getElementById('water').value) >= 0 || parseFloat(document.getElementById('water').value) <= 0){
			document.getElementById('alarm2').innerHTML="";
			alarm2=false;
		}
		else {
			document.getElementById('alarm2').innerHTML="Неверное значение";
			alarm2=true;
		}
		if(parseFloat(document.getElementById('density').value) >= 0 || parseFloat(document.getElementById('density').value) <= 0){
			document.getElementById('alarm3').innerHTML="";
			alarm3=false;
		}
		else {
			document.getElementById('alarm3').innerHTML="Неверное значение";
			alarm3=true;
		}
		if (alarm1||alarm2||alarm3) {
			document.getElementById('savebutton').disabled=true;
		}
		else {
				document.getElementById('savebutton').disabled=false;
			}
	}
</script>
<script type="text/javascript">
	$(document).ready(function()
      {
		$('#savebutton').click(function() {
			window.location.href='/ASUTP/basic/cshiinputparam/dt='+document.getElementById('theDate1').value.replace('.','-').replace('.','-')+"&shift="+document.getElementById('sel_shift').value+"&water="+document.getElementById('water').value.replace('.','-').replace(',','-')+"&density="+document.getElementById('density').value.replace('.','-').replace(',','-');
		});
	  });
</script>
<table align="center" style="width:600px;margin: 10px auto;font-size: 12px;">
	<tr>
		<td>
			<p>Дата:</p>
		</td>
		<td align="right">
			<p><label name="alarm1" id="alarm1" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input type="text" align="middle" value="<?php $dt_now=new DateTime(date('d.m.Y')); $dt_now->sub(new DateInterval('P1M'));echo (isset($dt))?$dt:$dt_now->format('d.m.Y');?>" name="theDate1" id="theDate1" maxlength="10" onKeyUp="checkDate()" onChange="checkDate()">
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
			<p>Смена</p>
		</td>
		<td>
		</td>
		<td align="right">
			<select name="sel_shift" id="sel_shift">
				<option selected value=1>1</option>
				<option value=2>2</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<p>Водопоглощение</p>
		</td>
		<td align="right">
			<p><label name="alarm2" id="alarm2" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input id="water" type="number" size="10" value="<?php echo (isset($water))?$water:0;?>" onKeyUp="checkDate()" onChange="checkDate()">
		</td>
	</tr>
	<tr>
		<td>
			<p>Кажущаяся плотность</p>
		</td>
		<td align="right">
			<p><label name="alarm3" id="alarm3" style="color:red;vertical-align:center;"></label></p>
		</td>
		<td align="right">
			<input id="density" type="number" size="10" value="<?php echo (isset($density))?$density:0;?>" onKeyUp="checkDate()" onChange="checkDate()">
		</td>
	</tr>
	<tr>
		<td colspan="3" align="right">
			<input type="submit" value="Сохранить" id="savebutton">
        </td>
	</tr>
</table>
<table align="center" style="font-size:12px;width:auto;">
	<thead>
		<tr style="background-color:#69C;color:white;">
			<th>
				Дата
			</th>
			<th>
				Смена
			</th>
			<th>
				Водопоглощение
			</th>
			<th>
				Кажущаяся плотность
			</th>
		</tr>
	</thead>
	<tbody align="center">
		<?php
			$dbhost = "TPL-server";$dbname = "tpl";$dbuser = "sa";$dbpass = "tpl";
			$db = new PDO ('odbc:DRIVER={SQL Server};SERVER='.$dbhost.';database='.$dbname.';Uid='.$dbuser.';Pwd='.$dbpass.';');
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			$sql = "SELECT top(30) dt,shift,water,density
					FROM teh_params
					ORDER BY dt,shift;";
			$result = $db->prepare($sql);
			$result->execute();
			$tmp_array = $result->fetchAll(PDO::FETCH_ASSOC);
			foreach ($tmp_array as $key=>$record) {
				$dt=new DateTime($record['dt']);
				echo "<tr><td>".$dt->format('d.m.Y')."</td><td>".$record['shift']."</td><td>".round($record['water'],1)."</td><td>".round($record['density'],1)."</td></tr>";
			}
		?>
	</tbody>
</table>
