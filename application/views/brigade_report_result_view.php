<!----------------------------------------------------------------------------->
<!--начало report_result_view-->
<SCRIPT Language="Javascript">
	function GetXLS() {
		window.location.href+="&media=xls";
	}
</SCRIPT>
<p align="right">
	<?php echo Html::image('img/printer.png',array('onClick'=>'printit()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
	<?php echo Html::image('img/pdf.png',array('onClick'=>'GetPDF()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>&nbsp;
	<?php echo Html::image('img/excel.png',array('onClick'=>'GetXLS()','style'=>'cursor:hand','class'=>'NonPrintableImage'));?>
</p>
<div id="report_content" align="center">
	<?php echo $report_content?>
</div>
<!--конец report_result_view-->
<!----------------------------------------------------------------------------->
