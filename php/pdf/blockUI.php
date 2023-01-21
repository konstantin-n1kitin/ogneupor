<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <script type="text/javascript" src="/fff/SmartModal/jquery-1.6.3.js "></script>  
  <script type="text/javascript" src="/fff/jquery.blockUI.js "></script>    
  <script>
    $(document).ready(function() 
    { 
      $('#pageDemo4').click(function() 
      { 
        $.blockUI({ message: $('#domMessage') }); 
        //test(); 
        window.location.href="http://www.google.ru";
      }); 
    }); 
  </script>
  </head>
  <body>
    <div id="domMessage" style="display:none;"> 
        <h1>Идет загрузка страницы</h1>
        <img src="wait.gif">
    </div>   
    <div>
      <input id="pageDemo4" class="demo" type="submit" value="тест" />
    </div>
  </body>
</html>
<?php
?>