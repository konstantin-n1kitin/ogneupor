<?php
      include("C:/wamp/www/ASUTP/modules/MPDF/vendor/mpdf/mpdf.php");
      $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html>
                  <body>
       <table border="1"><caption>Monthly savings</caption><tr><td>Пример 1</td><td>Пример 2</td><td>Пример 3</td><td>Пример 4</td></tr>
      <tr><td>Пример 5</td><td>Пример 6</td><td>Пример 7</td><td><a href="http://mpdf.bpm1.com/" title="mPDF">mPDF</a></td></tr></table>
      </body></html>';

      $mpdf = new mPDF('UTF-8', 'A4');
      $mpdf->charset_in = 'UTF-8';
  		$mpdf->WriteHTML($html);
		  $pdf = $mpdf->output();
?>