      var request;
//------------------------------------------------------------------------------
      function GetElementClass(SubclassName)
      {
        var UnderlinePosition = SubclassName.indexOf("_");
        var CName = SubclassName.slice(0,UnderlinePosition);
        return CName;
      }
//------------------------------------------------------------------------------
      function GetElementState(SubclassName)
      {
        var UnderlinePosition = SubclassName.indexOf("_");
        var Length = SubclassName.length;
//        alert(SubclassName + " = " + Length);
        var CName = SubclassName.slice(UnderlinePosition, Length);
        return CName;
      }
//------------------------------------------------------------------------------
      function GetElementName(Element)
      {
        var EqualityPosition = Element.indexOf("=");
        ElementName = Element.slice(0,EqualityPosition);
        return ElementName;
      }
//------------------------------------------------------------------------------
      function GetElementValue(Element)
      {
        var EqualityPosition = Element.indexOf("=");
        ElementValue = Element.slice(EqualityPosition+1,Element.length);
        return ElementValue;
      }
//------------------------------------------------------------------------------
      function UpdateElements(ServerAnswer)
      {
        var Elements = new Array();
        var ElementName;
        var ElementValue;
        Elements = ServerAnswer.split(";");
        for (var i=0; i<Elements.length; i++)
        {
          ElementName = GetElementName(Elements[i]);
          ElementValue = GetElementValue(Elements[i]);
//          alert('EN='+ElementName+'  EV='+ElementValue);      
          SwitchElement(ElementName, ElementValue);
        }
      }
//------------------------------------------------------------------------------
       function SwitchElement(ID, value)
      {
        var Element = document.getElementById(ID);
        if (Element != null)
        {}
        else
        {
          var obj = document.getElementById('CSI_curingroom1');
          if (obj != null && ID == "T_propar_kamera_1") // ПРОПАРОЧНАЯ КАМЕРА №1
          {
            obj.rows[2].cells[1].innerHTML = value; // Температура
          }
          var obj = document.getElementById('CSI_curingroom2');
          if (obj != null && ID == "T_propar_kamera_2") // ПРОПАРОЧНАЯ КАМЕРА №2
          {
            obj.rows[2].cells[1].innerHTML = value; // Температура
          }
          var obj = document.getElementById('CSI_curingroom3');
          if (obj != null && ID == "T_propar_kamera_3") // ПРОПАРОЧНАЯ КАМЕРА №3
          {
            obj.rows[2].cells[1].innerHTML = value; // Температура
          }
          var obj = document.getElementById('CSI_curingroom4');
          if (obj != null && ID == "T_propar_kamera_4") // ПРОПАРОЧНАЯ КАМЕРА №4
          {
            obj.rows[2].cells[1].innerHTML = value; // Температура
          }
		}
	}	
//------------------------------------------------------------------------------
      function processRequestChange()
      {
        if (request.readyState == 4)
        {
//          alert('ответ:'+request.responseText);
          UpdateElements(request.responseText)
        }
      }
//------------------------------------------------------------------------------
      function SendRequest()
      {       
        if (!window.XMLHttpRequest)
          request = new ActiveXObject("Msxml2.XMLHTTP");
        else
          request = new XMLHttpRequest();
        if (request!=null)
        {
          request.onreadystatechange = processRequestChange;
//          request.open("POST", "AddMoreInformation.php", false); 
//          request.open("POST", "/ASUTP/php/csi_curingroom/AddMoreInformation.php", false); 
          request.open("POST", "/ASUTP/php/GetOPCData.php?mnemo=8", false); 
          request.send(null);
//          alert('request');          
        }
        else
          alert('ошибка при создании xmlrequest!')
        setTimeout('SendRequest()', 2000) 
      }