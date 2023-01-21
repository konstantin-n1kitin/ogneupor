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
          var obj = document.getElementById('CSHI_conscales7'); // Конвейерные весы №7
          if (obj != null && ID == "S7_Z1")
          {
            obj.rows[1].cells[1].innerHTML = value+" кг"; // Счетчик 1
          }
		  if (obj != null && ID == "S7_Z2")
          {
            obj.rows[2].cells[1].innerHTML = value+" кг"; // Счетчик 2
          }
		  if (obj != null && ID == "S7_Z3")
          {
            obj.rows[3].cells[1].innerHTML = value+" кг"; // Счетчик 3
          }
		  if (obj != null && ID == "S7_Q")
          {
//			if (value < 0) 
//				obj.rows[4].cells[1].innerHTML = "0.0 кг/м";
//			else
				obj.rows[4].cells[1].innerHTML = value+" кг/м"; // Текущая нагрузка на ленту
          }
		  if (obj != null && ID == "S7_V")
          {
            obj.rows[5].cells[1].innerHTML = value+" м/с"; // Скорость ленты
          }
		  if (obj != null && ID == "S7_I")
          {
            obj.rows[6].cells[1].innerHTML = value+" кг/ч"; // Текущая производительность
          }
		  var obj = document.getElementById('CSHI_conscales17'); // Конвейерные весы №17
          if (obj != null && ID == "S17_Z1")
          {
            obj.rows[1].cells[1].innerHTML = value+" кг"; // Счетчик 1
          }
		  if (obj != null && ID == "S17_Z2")
          {
            obj.rows[2].cells[1].innerHTML = value+" кг"; // Счетчик 2
          }
		  if (obj != null && ID == "S17_Z3")
          {
            obj.rows[3].cells[1].innerHTML = value+" кг"; // Счетчик 3
          }
		  if (obj != null && ID == "S17_Q")
          {
//			if (value < 0) 
//				obj.rows[4].cells[1].innerHTML = "0.0 кг/м";
//			else
				obj.rows[4].cells[1].innerHTML = value+" кг/м"; // Текущая нагрузка на ленту
          }
		  if (obj != null && ID == "S17_V")
          {
            obj.rows[5].cells[1].innerHTML = value+" м/с"; // Скорость ленты
          }
		  if (obj != null && ID == "S17_I")
          {
            obj.rows[6].cells[1].innerHTML = value+" кг/ч"; // Текущая производительность
          }
		  var obj = document.getElementById('CSHI_conscales29'); // Конвейерные весы №29
          if (obj != null && ID == "S29_Z1")
          {
            obj.rows[1].cells[1].innerHTML = value+" кг"; // Счетчик 1
          }
		  if (obj != null && ID == "S29_Z2")
          {
            obj.rows[2].cells[1].innerHTML = value+" кг"; // Счетчик 2
          }
		  if (obj != null && ID == "S29_Z3")
          {
            obj.rows[3].cells[1].innerHTML = value+" кг"; // Счетчик 3
          }
		  if (obj != null && ID == "S29_Q")
          {
//			if (value < 0) 
//				obj.rows[4].cells[1].innerHTML = "0.0 кг/м";
//			else
				obj.rows[4].cells[1].innerHTML = value+" кг/м"; // Текущая нагрузка на ленту
          }
		  if (obj != null && ID == "S29_V")
          {
            obj.rows[5].cells[1].innerHTML = value+" м/с"; // Скорость ленты
          }
		  if (obj != null && ID == "S29_I")
          {
            obj.rows[6].cells[1].innerHTML = value+" кг/ч"; // Текущая производительность
          }
		  var obj = document.getElementById('CSHI_conscales39'); // Конвейерные весы №39
          if (obj != null && ID == "S39_Z1")
          {
            obj.rows[1].cells[1].innerHTML = value+" кг"; // Счетчик 1
          }
		  if (obj != null && ID == "S39_Z2")
          {
            obj.rows[2].cells[1].innerHTML = value+" кг"; // Счетчик 2
          }
		  if (obj != null && ID == "S39_Z3")
          {
            obj.rows[3].cells[1].innerHTML = value+" кг"; // Счетчик 3
          }
		  if (obj != null && ID == "S39_Q")
          {
//			if (value < 0) 
//				obj.rows[4].cells[1].innerHTML = "0.0 кг/м";
//			else
				obj.rows[4].cells[1].innerHTML = value+" кг/м"; // Текущая нагрузка на ленту
          }
		  if (obj != null && ID == "S39_V")
          {
            obj.rows[5].cells[1].innerHTML = value+" м/с"; // Скорость ленты
          }
		  if (obj != null && ID == "S39_I")
          {
            obj.rows[6].cells[1].innerHTML = value+" кг/ч"; // Текущая производительность
          }
		  var obj = document.getElementById('CSHI_conscales53'); // Конвейерные весы №53
          if (obj != null && ID == "S53_Z1")
          {
            obj.rows[1].cells[1].innerHTML = value+" кг"; // Счетчик 1
          }
		  if (obj != null && ID == "S53_Z2")
          {
            obj.rows[2].cells[1].innerHTML = value+" кг"; // Счетчик 2
          }
		  if (obj != null && ID == "S53_Z3")
          {
            obj.rows[3].cells[1].innerHTML = value+" кг"; // Счетчик 3
          }
		  if (obj != null && ID == "S53_Q")
          {
//			if (value < 0) 
//				obj.rows[4].cells[1].innerHTML = "0.0 кг/м";
//			else
				obj.rows[4].cells[1].innerHTML = value+" кг/м"; // Текущая нагрузка на ленту
          }
		  if (obj != null && ID == "S53_V")
          {
            obj.rows[5].cells[1].innerHTML = value+" м/с"; // Скорость ленты
          }
		  if (obj != null && ID == "S53_I")
          {
            obj.rows[6].cells[1].innerHTML = value+" кг/ч"; // Текущая производительность
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
          request.open("POST", "/ASUTP/php/cshi_conscales/AddMoreInformation.php", false);
//          request.open("POST", "/ASUTP/php/GetOPCData.php?mnemo=8", false); 
          request.send(null);
//          alert('request');          
        }
        else
          alert('ошибка при создании xmlrequest!')
        setTimeout('SendRequest()', 2000) 
      }