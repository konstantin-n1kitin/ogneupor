<!-----------------------------начало local menu------------------------------->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
	<head style="height:100%,width:100%">
		<meta http-equiv="Content-Language" content="ru">
<!--		<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251"> -->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title><?php echo $title;?></title>
		<meta name="keywords" content="<?php echo $meta_keywords;?>" />
		<meta name="description" content="<?php echo $meta_description;?>" />
		<meta name="copyright" content="<?php echo $meta_copywrite;?>" />
    <script type="text/javascript" src="/ASUTP/js/jquery/jquery-1.6.3.js "></script>
    <script type="text/javascript" src="/ASUTP/js/jquery/jquery.blockUI.js "></script>
	<SCRIPT Language="Javascript">
	function printit()
	{
		if (window.print)
		{
	    window.print() ;
		}
		else
			{
		  var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
		  document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
		  WebBrowser1.ExecWB(6, 2);
		}
	}
	function GetPDF()
      {
        var newForm = document.createElement("form"); 
        newForm.action = "/ASUTP/php/pdf/getpdf.php"; 
        newForm.method = "POST";
        var newInput = document.createElement("input");
        newInput.name = "HInput"; /* @end @*/
        newInput.type = "hidden";
		newInput.value = document.getElementById("report_content").innerHTML;
		var HeaderInput = document.createElement("input");
		HeaderInput.name = "HeaderInput"; /* @end @*/
        HeaderInput.type = "hidden";
        HeaderInput.value = document.title;
        newForm.appendChild(newInput);
		newForm.appendChild(HeaderInput);
        document.getElementsByTagName("body")[0].appendChild(newForm);
        newForm.submit();
      }
</script>
		<?php foreach($styles as $file => $type) { echo HTML::style($file, array('media' => $type)), "\n"; }?>
		<?php foreach($scripts as $file) { echo HTML::script($file, NULL, TRUE), "\n"; }?>
  </head>
	<body style="height:100%,width:100%" onload=<?php echo $on_body_load_js; ?>>
		<?php echo $menu;?>
		<table border="0">
			<tr>
				<td class="localmenu_td" width="auto" valign="top"><?php echo $local_menu;?></td>
				<td width="100%" valign="top"><?php echo $content;?></td>
			<tr>
		</table>
	</body>
</html>
<!----------------------конец local menu--------------------------------------->