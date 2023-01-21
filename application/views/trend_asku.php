<style type="text/css">
.hidden { display:none; }
.visible { display:block; }
</style>
<p align="right">
	<?php echo Html::image('img/printer.png',array('onClick'=>'printit()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
	<?php echo Html::image('img/pdf.png',array('onClick'=>'GetPDF()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
</p>
<font size="2"><span class="noprint"><?php $tmp_date=new DateTime($end_time); $tmp_date->sub(new DateInterval('P1D')); foreach ($parameters as $number => $value) if	($value!='Обрыв канала, ч' and $value!='Время' and $value!='Расход, т.у.т.' and $value!='Расход, ГДж' and $value!='Расход, Гкал') echo '<a href="/ASUTP/localmenu/askureports_trend/report_type='.$report_type.'&date1='.$beg_time.'&date2='.$tmp_date->format('d-m-Y').'&parameter='.$number.'">'.str_replace('Объем','Расход',substr($value,0,strpos($value,','))).'</a> | ';?></span></font>
<div id="loading" class="visible"><h2 align="center">Загрузка графика</h2><h2 align="center"><?php echo html::image('img/wait.gif',array('border' => '0'))?></h2></div>
<div id="error" class="hidden"><p align="center">Превышение времени выполнения сценария. Попробуйте сократить временной интервал.</p></div>
<div id="abort" class="hidden"><p align="center">Загрузка графика отменена пользователем</p></div>
<div id="report_content">
<div id="note" class="hidden"><p style="font-size: 12px;"><?php echo $note ?></p></div>
<p align="center"><img onload="javascript:document.getElementById('loading').className = 'hidden';document.getElementById('note').className = 'visible';" onerror="javascript:document.getElementById('loading').className = 'hidden';document.getElementById('error').className = 'visible';" onabort="javascript:document.getElementById('loading').className = 'hidden';document.getElementById('abort').className = 'visible';" src="<?php echo $img ?>"></p>
</div>