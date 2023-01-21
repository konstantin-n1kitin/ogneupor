<SCRIPT Language="Javascript">
function GetPDF()
      {
        var newForm = document.createElement("form"); 
        newForm.action = "http://192.168.1.4/fff/post_test.php"; 
        newForm.method = "POST";
        var newInput = document.createElement("input");
        newInput.name = "HInput"; /* @end @*/
        newInput.type = "hidden";
        //newInput.value = window.document.documentElement.innerHTML;
        newInput.value = document.getElementById("report_content").innerHTML;
        //newInput.value = window.document.text;
        newForm.appendChild(newInput);
        document.getElementsByTagName("body")[0].appendChild(newForm);
        newForm.submit();
      }
</script>
<div id="report_content"><p align="center">
	<img src="<?php echo urlencode("test_image.php?par1=text&par2=text")?>">
</p></div>
<img src="/ASUTP/img/pdf.png" onClick="GetPDF()">