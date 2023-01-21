	var request;
//------------------------------------------------------------------------------
      function GetElementClass(SubclassName)
      {
        var UnderlinePosition = SubclassName.indexOf("_");
        var CName = SubclassName.slice(0,UnderlinePosition);
        return CName;
      }
//------------------------------------------------------------------------------
      function GetElementName(Element, symbol)
      {
        var EqualityPosition = Element.indexOf(symbol);
        ElementName = Element.slice(0,EqualityPosition);
        return ElementName;
      }
//------------------------------------------------------------------------------
      function GetElementValue(Element, symbol)
      {
        var EqualityPosition = Element.indexOf(symbol);
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
          ElementName = GetElementName(Elements[i], "=");
          ElementValue = GetElementValue(Elements[i], "=");
//          alert('EN='+ElementName+'  EV='+ElementValue);  
          SwitchElement(ElementName, ElementValue, Elements);
        }
      }
//------------------------------------------------------------------------------
      function SwitchElement(element, state, Elements)
      {
 //       var Element = document.getElementById(element);
//		alert('Element='+Element);
        if (element != null)
        {  
//			alert('Опачки');
			if (element == 'RF1_in_a_33' || element == 'RF1_in_a_34' || element == 'RF1_in_a_35' || element == 'RF1_in_a_36' || element == 'RF1_in_a_37' ||
				element == 'RF1_in_a_38' || element == 'RF1_in_a_39' || element == 'RF1_in_a_40' || element == 'RF1_in_a_41' || element == 'RF1_in_a_42' ||
				element == 'RF1_in_a_43' || element == 'RF1_in_a_44' || element == 'RF1_in_a_45' || element == 'RF1_in_a_46' || element == 'RF1_in_a_47' ||
				element == 'RF1_in_a_48' || element == 'RF1_in_a_49' || element == 'RF1_in_a_50' || element == 'RF1_in_a_51' || element == 'RF1_in_a_52' ||
				element == 'RF1_in_a_53' || element == 'RF1_in_a_54')
			{
				var obj = document.getElementById('CSHI_rotary_furn');
				if (obj != null) //Вращающаяся печь №2
				{
					switch (element)
					{
						case 'RF1_in_a_40':
							obj.rows[1].cells[1].innerHTML = state + " Па"; // Разрежение перед дымососом
						break;
						case 'RF1_in_a_38':
							obj.rows[2].cells[1].innerHTML = state + " Па"; // Разрежение перед электрофильтром левый канал
						break;
						case 'RF1_in_a_39':
							obj.rows[3].cells[1].innerHTML = state + " Па"; // Разрежение перед электрофильтром правый канал
						break;
						case 'RF1_in_a_36':
							obj.rows[4].cells[1].innerHTML = state + " Па"; // Разрежение перед циклоном левый канал
						break;
						case 'RF1_in_a_37':
							obj.rows[5].cells[1].innerHTML = state + " Па"; // Разрежение перед циклоном правый канал
						break;
						case 'RF1_in_a_35':
							obj.rows[6].cells[1].innerHTML = state + " Па"; // Разрежение перед скруббером
						break;
						case 'RF1_in_a_34':
							obj.rows[7].cells[1].innerHTML = state + " Па"; // Разрежение в пылевой камере
						break;
						case 'RF1_in_a_47':
							obj.rows[8].cells[1].innerHTML = state + " <sup>o</sup>C"; // Температура перед дымососом
						break;					
						case 'RF1_in_a_45':
							obj.rows[9].cells[1].innerHTML = state + " <sup>o</sup>C"; // Температура перед электрофильтром левый канал
						break;					
						case 'RF1_in_a_46':
							obj.rows[10].cells[1].innerHTML = state + " <sup>o</sup>C"; // Температура перед электрофильтром правый канал
						break;					
						case 'RF1_in_a_43':
							obj.rows[11].cells[1].innerHTML = state + " <sup>o</sup>C"; // Температура перед циклоном левый канал
						break;
						case 'RF1_in_a_44':
							obj.rows[12].cells[1].innerHTML = state + " <sup>o</sup>C"; // Температура перед циклоном правый канал
						break;
						case 'RF1_in_a_41':
							obj.rows[13].cells[1].innerHTML = state + " <sup>o</sup>C"; // Температура в пылевой камере
						break;
						case 'RF1_in_a_42':
							obj.rows[14].cells[1].innerHTML = state + " %"; // Анализ на «СО»
						break;
						case 'RF1_in_a_48':
							obj.rows[15].cells[1].innerHTML = state + " м<sup>3</sup>/ч"; // Расход вентиляционного воздуха
						break;					
						case 'RF1_in_a_49':
							obj.rows[16].cells[1].innerHTML = state + " м<sup>3</sup>/ч"; // Расход газа
						break;					
						case 'RF1_in_a_50':
							obj.rows[17].cells[1].innerHTML = state + " кПа"; // Давление газа
						break;					
						case 'RF1_in_a_51':
							obj.rows[18].cells[1].innerHTML = state + " %"; // Весы
						break;	
						case 'RF1_in_a_52':
							obj.rows[19].cells[1].innerHTML = state + " B"; // Нагрузка питателя
						break;					
/*						case 'RF2_in_a_22':
							obj.rows[22].cells[1].innerHTML = state + " %"; // Уровень в бункере выгрузки пыли
						break;					
						case 'RF2_in_a_23':
							obj.rows[21].cells[1].innerHTML = state + " %"; // ИМ газ
						break;					
						case 'RF2_in_a_24':
							obj.rows[22].cells[1].innerHTML = state + " %"; // ИМ воздух
						break;					 
						case 'RF2_in_a_25':
							obj.rows[21].cells[1].innerHTML = state + " м<sup>3</sup>/ч"; // Расход сжатого воздуха на газоочистку
						break;	*/						
					}
				} 			
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
          request.open("POST", "/ASUTP/php/GetOPCData.php?mnemo=6", false);
//          request.open("POST", "/ASUTP/php/cshi_rf/AddMoreInformation.php", false); 		  
//          request.open("POST", "/ASUTP/php/1.php", false);
          request.send(null);
//          alert('request');          
        }
//        else
//          alert('ошибка при создании xmlrequest!');
        setTimeout('SendRequest()', 5000); 
      }
			
//------------------------------------------------------------------------------------
    function getCookie(name) {
      var cookie = " " + document.cookie;
      var search = " " + name + "=";
      var setStr = null;
      var offset = 0;
      var end = 0;
      if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
          offset += search.length;
          end = cookie.indexOf(";", offset)
          if (end == -1) {
            end = cookie.length;
          }
          setStr = unescape(cookie.substring(offset, end));
        }
      }
      return(setStr);
    }  

    function setCookie (name, value, expires, path, domain, secure) {
          document.cookie = name + "=" + escape(value) +
            ((expires) ? "; expires=" + expires : "") +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            ((secure) ? "; secure" : "");
    }    
    
    function GetElementClass(SubclassName)
    {
      var UnderlinePosition = SubclassName.indexOf("_");
      var CName = SubclassName.slice(0,UnderlinePosition);
      return CName;
    }
		
 			
		
