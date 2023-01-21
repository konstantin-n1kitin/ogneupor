<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height:100%;margin:0px;padding:0px">

  <head>
  <!--link rel="stylesheet" href="/fff/SmartModal/resets.css" type="text/css" media="screen" charset="utf-8" /-->	
	<link rel="stylesheet" href="/fff/SmartModal/styles.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/fff/SmartModal/modal.css" type="text/css" media="screen" charset="utf-8" />
  
  <script type="text/javascript" src="/fff/SmartModal/jquery-1.6.3.js "></script>
  <script type="text/javascript" src="/fff/SmartModal/jquery.smartmodal.js "></script>
  
  </head>
  <body style="height:100%;margin:0px;padding:0px">
    <a href="#" rel="rel_modal_content" class="modal3">тынц </a>
    <div class="on_click" style="display:none; width:800px">
      ыбля
    </div>
    <div class="container">
      <div id="rel_modal_content" class="hidden">
				<p>You can put anything in me from full on forms to just a simple bit of text.</p>
				<p><a href="#">Here is a link</a></p>
				<ul>
					<li>An unordered list</li>
				</ul>
			</div>      
    </div>
    <script type="text/javascript">
      jQuery(document).ready(function() 
      {
        jQuery('.modal3').smart_modal();
        //jQuery('.on_click_modal').smart_modal_show();
        //alert('п');
        window.location.href="http://www.google.ru";
      });
    </script>
  </body>
</html>
<?php
?>
