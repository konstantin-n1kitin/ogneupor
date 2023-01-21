<?php
  set_time_limit(600);
  include("/mpdf/mpdf.php");
  ini_set("memory_limit","-1");
  $html = $_POST['HInput'];
  $html=str_replace("<CAPTION>",'<TR><TD COLSPAN='.(substr_count($html,"<TR>")!=0?round((substr_count($html,"<TH>")+substr_count($html,"<TD>"))/substr_count($html,"<TR>")):"1").'><STRONG>',$html);
  $html=str_replace("</CAPTION>","</STRONG></TD></TR>",$html);
  //echo $result;
  $stylesheet = file_get_contents('../../css/print.css');
  $mpdf = new mPDF('UTF-8', 'A4');
  $mpdf->charset_in = 'UTF-8';
  $mpdf->SetHeader($_POST['HeaderInput']);
  //$mpdf->shrink_tables_to_fit=1;
  //$mpdf->autoPageBreak=TRUE;
  //$mpdf->hyphenateTables=TRUE;
  //$mpdf->tableMinSizePriority=TRUE;
  //$mpdf->progressBar=TRUE;
  //Оптимизация использования памяти
  //$mpdf->packTableData=TRUE;
  //$mpdf->simpleTables=TRUE;
  //$mpdf->cacheTables = true;
  //---------------
  $mpdf->showImageErrors=true;
  $mpdf->AddPage();
  //$mpdf->StartProgressBarOutput();
  $mpdf->WriteHTML($stylesheet,1);
  $warning='Размер отчета превышает допустимую величину. Попробуйте уменьшить диапазон времени при запросе отчета.';
  if (strlen($html)<80000)
	$mpdf->WriteHTML($html);
  else $mpdf->WriteHTML(iconv('CP1251','UTF-8',$warning));
  $mpdf->output();
?>
