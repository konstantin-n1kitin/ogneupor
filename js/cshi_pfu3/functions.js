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
        //alert(ServerAnswer);
		var Elements = new Array();
        var ElementName;
        var ElementValue;
        Elements = ServerAnswer.split(";");
        for (var i=0; i<Elements.length; i++)
        {
          ElementName = GetElementName(Elements[i]);
          ElementValue = GetElementValue(Elements[i]);
          SwitchElement(ElementName, ElementValue);
        }
		//Сумма веса в рецепте
		document.getElementById('pfu3_sum').innerHTML=parseInt(document.getElementById('pfu3_r11').innerHTML)+parseInt(document.getElementById('pfu3_r21').innerHTML)+parseInt(document.getElementById('pfu3_r31').innerHTML)+parseInt(document.getElementById('pfu3_r41').innerHTML)+parseInt(document.getElementById('pfu3_r51').innerHTML);
      }
//------------------------------------------------------------------------------
      function SwitchElement(element, state)
      {
        var Element = document.getElementById(element);
        if (Element != null)
        {
          var ClassState = GetElementState(Element.className);
		  var ClassName = GetElementClass(Element.className);
          if (ClassState == "_Off" || ClassState == "_On" || ClassState == "_Manual" || ClassState == "_UnCertain" || ClassState == "_Avar" || 
              ClassState == "_off" || ClassState == "_on" || ClassState == "_manual" || ClassState == "_Local" || ClassState == "_Off2" || ClassState == "_on2")
          {
			  switch(state)
              {
                case ('True'):
                  {
                    switch (ClassName) // Делаем выборку 
                    {
                      case ('rdy'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Весы готовы";
                      break;
					  case ('mode'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "АВТ";
                      break;
                      default:
                        if (Element.className != ClassName+'_on')
                          Element.className = ClassName+'_on';
                      break; 
                    }
                  }  
                  break;
                case ('False'):
                {
                    switch (ClassName) // Делаем выборку 
                    {
                      case ('rdy'):
						if (Element.className != ClassName+'_off') Element.className = ClassName+'_off';
						document.getElementById(element+"_text").innerHTML = "Весы не готовы";
					  break;
					  case ('mode'):
						if (Element.className != ClassName+'_off') Element.className = ClassName+'_off';
						document.getElementById(element+"_text").innerHTML = "РУЧ";
					  break;
                      default:
                        if (Element.className != ClassName+'_off')
                          Element.className = ClassName+'_off';
                      break; 
                    }
                  }  
                  break;
                case ('0'):
                    switch (ClassName)
                    {
                      case ('load'):
						if (Element.className != ClassName+'_off') Element.className = ClassName+'_off';
						document.getElementById(element+"_text").innerHTML = "Ожидание";
                      break;
					  case ('mixer'):
						if (Element.className != ClassName+'_off') Element.className = ClassName+'_off';
						document.getElementById(element+"_text").innerHTML = "Режим ожидания";
                      break;
					  case ('state'):
						if (Element.className != ClassName+'_off') Element.className = ClassName+'_off';
						document.getElementById(element+"_text").innerHTML = "Загрузка отключена";
                      break;
                      default:
                        Element.className = ClassName+'_off';
                      break;  
                    }
                break;
				case ('1'):
                    switch (ClassName)
                    {
                      case ('load'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Загрузка";
                      break;
					  case ('mixer'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Закрытие днища";
                      break;
					  case ('state'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Запуск аспирационной системы";
                      break;
                      default:
                        Element.className = ClassName+'_off';
                      break;  
                    }
                break;
				case ('2'):
                    switch (ClassName)
                    {
                      case ('load'):
						if (Element.className != ClassName+'_on2') Element.className = ClassName+'_on2';
						document.getElementById(element+"_text").innerHTML = "Вес набран";
                      break;                  
					  case ('mixer'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Запуск аспирационной системы";
                      break;
					  case ('state'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Запуск ППС";
                      break;
                      default:
                        Element.className = ClassName+'_off';
                      break;  
                    }
                break;
				case ('3'):
                    switch (ClassName)
                    {
                      case ('load'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Выгрузка";
                      break;                  
					  case ('mixer'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Пуск смесителя";
                      break;
					  case ('state'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Запуск питателя-мешалки";
                      break;
                      default:
                        Element.className = ClassName+'_off';
                      break;  
                    }
                break;
				case ('4'):
                    switch (ClassName)
                    {
					  case ('mixer'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Загрузка весов";
                      break;
					  case ('state'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Запуск конвейера";
                      break;
                      default:
                        Element.className = ClassName+'_off';
                      break;  
                    }
                break;
				case ('5'):
                    switch (ClassName)
                    {
					  case ('mixer'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Смешивание";
                      break;
					  case ('state'):
						if (Element.className != ClassName+'_on2') Element.className = ClassName+'_on2';
						document.getElementById(element+"_text").innerHTML = "Загрузка работает";
                      break;
                      default:
                        Element.className = ClassName+'_off';
                      break;  
                    }
                break;
				case ('6'):
                    switch (ClassName)
                    {
					  case ('mixer'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Открытие днища";
                      break;
					  case ('state'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Остановка конвейера";
                      break;
                      default:
                        Element.className = ClassName+'_off';
                      break;  
                    }
                break;
				case ('7'):
                    switch (ClassName)
                    {
					  case ('mixer'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Выгрузка смесителя";
                      break;
					  case ('state'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Остановка питателя-мешалки";
                      break;
                      default:
                        Element.className = ClassName+'_off';
                      break;  
                    }
                break;
				case ('8'):
                    switch (ClassName)
                    {
					  case ('mixer'):
						if (Element.className != ClassName+'_on') Element.className = ClassName+'_on';
						document.getElementById(element+"_text").innerHTML = "Закрытие днища";
                      break;
                      default:
                        Element.className = ClassName+'_off';
                      break;  
                    }
                break;
                default:
                    Element.className = ClassName+'_off';
                break;  
              }
          }
          else
          {
            Element.innerHTML = state;
          }  
        }  
      }
//------------------------------------------------------------------------------
      function processRequestChange()
      {
        if (request.readyState == 4)
        {
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
          request.open("POST", "/ASUTP/php/GetOPCData.php?mnemo=10", false); 
          request.send(null);
        }
        else
          alert('ошибка при создании xmlrequest!');
        setTimeout('SendRequest()', 1000); 
      }	