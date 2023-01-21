    var marsh = new Array(); //многомерный массив массив маршрутов с включающих его механизмами
    var temp_array = new Array();  //выбранный маршрут массив
    marsh[1] = new Array('PlastPitatel2-30', 'GlinaCutMashine4-392');
    marsh[2] = new Array('PlastPitatel50', 'GlinaCutMashine5-536', 'Conveyer534');
    marsh[3] = new Array('PlastPitatel50', 'GlinaCutMashine5-536', 'Conveyer534', 'Conveyer434');
    marsh[4] = new Array('PlastPitatel40', 'GlinaCutMashine6-436', 'Conveyer535', 'Conveyer434');
    marsh[5] = new Array('SushBaraban533', 'Elevator532', 'Conveyer531', 'StoneOtdelitVal27', 'Ventilator53');
    marsh[6] = new Array('SushBaraban433', 'Elevator432', 'Conveyer431', 'StoneOtdelitVal26', 'Ventilator43');
    marsh[7] = new Array('Conveyer333-1', 'SushBaraban333', 'Elevator332', 'Conveyer331', 'StoneOtdelitVal25', 'Ventilator33');
    marsh[8] = new Array('LotPitatel52', 'Conveyer514', 'Dezintegrator513', 'Elevator512', 'Ventilator322');
    marsh[9] = new Array('LotPitatel42', 'Conveyer414', 'Dezintegrator413', 'Elevator412', 'Ventilator325');
    marsh[10] = new Array('LotPitatel32', 'Conveyer314', 'Dezintegrator313', 'Elevator312', 'Ventilator329');
    marsh[11] = new Array('Conveyer481', 'Elevator49', 'Conveyer47', 'Conveyer53', 'Conveyer50-2', 'Ventilator204', 'Ventilator336', 'Ventilator272', 'Ventilator329');
    marsh[12] = new Array('Conveyer491', 'Elevator48', 'Conveyer47', 'Conveyer53', 'Conveyer50-2', 'Ventilator204', 'Ventilator336', 'Ventilator272', 'Ventilator329');
    marsh[13] = new Array('PlastPitatel1-20', 'GlinaCutMashine1-292', 'Conveyer291');
    marsh[14] = new Array('PlastPitatel2-20', 'GlinaCutMashine2-292', 'Conveyer291');
    marsh[15] = new Array('PlastPitatel1-30', 'GlinaCutMashine3-392');
    marsh[16] = new Array('');
    marsh[17] = new Array('Conveyer29', 'Conveyer00', 'VrashPech073');
    marsh[18] = new Array('Conveyer39', 'Conveyer00', 'VrashPech073');
    marsh[19] = new Array('Conveyer39', 'Conveyer10', 'VrashPech074');
    marsh[20] = new Array('Conveyer29', 'Conveyer10', 'VrashPech074');
    marsh[21] = new Array('Refregerator072', 'Conveyer07', 'Elevator041', 'Ventilator316', 'Conveyer01');
    marsh[22] = new Array('Refregerator072', 'Conveyer07', 'Elevator041', 'Ventilator316', 'Conveyer11');
    marsh[23] = new Array('Refregerator072', 'Conveyer07', 'Elevator241', 'Ventilator316', 'Conveyer01');
    marsh[24] = new Array('Refregerator072', 'Conveyer07', 'Elevator241', 'Ventilator316', 'Conveyer11');
    marsh[25] = new Array('Refregerator072', 'Conveyer17', 'Elevator241', 'Ventilator316', 'Conveyer01');
    marsh[26] = new Array('Refregerator072', 'Conveyer17', 'Elevator241', 'Ventilator316', 'Conveyer11');
    marsh[27] = new Array('Refregerator072', 'Conveyer17', 'Elevator141', 'Ventilator203', 'Conveyer01');
    marsh[28] = new Array('Refregerator072', 'Conveyer17', 'Elevator141', 'Ventilator203', 'Conveyer11');
    marsh[29] = new Array('Refregerator071', 'Conveyer07', 'Elevator041', 'Ventilator316', 'Conveyer01');
    marsh[30] = new Array('Refregerator071', 'Conveyer07', 'Elevator041', 'Ventilator316', 'Conveyer11');
    marsh[31] = new Array('Refregerator071', 'Conveyer07', 'Elevator241', 'Ventilator316', 'Conveyer01');
    marsh[32] = new Array('Refregerator071', 'Conveyer07', 'Elevator241', 'Ventilator316', 'Conveyer11');
    marsh[33] = new Array('Refregerator071', 'Conveyer17', 'Elevator241', 'Ventilator316', 'Conveyer01');
    marsh[34] = new Array('Refregerator071', 'Conveyer17', 'Elevator241', 'Ventilator316', 'Conveyer11');
    marsh[35] = new Array('Refregerator071', 'Conveyer17', 'Elevator141', 'Ventilator203', 'Conveyer01');
    marsh[36] = new Array('Refregerator071', 'Conveyer17', 'Elevator141', 'Ventilator203', 'Conveyer11');
    marsh[37] = new Array('Conveyer793', 'BallsMill792', 'Elevator791', 'Ventilator198');
    marsh[38] = new Array('Conveyer783', 'BallsMill782', 'Elevator781', 'Ventilator199');
    marsh[39] = new Array('Conveyer773', 'BallsMill772', 'Elevator771', 'Ventilator201');
    marsh[40] = new Array('Conveyer763', 'BallsMill762', 'Elevator761', 'Ventilator202');
    marsh[41] = new Array('Conveyer542', 'Elevator541', 'Conveyer55', 'Conveyer52', 'Ventilator315', 'Ventilator57');
    marsh[42] = new Array('Conveyer552', 'Elevator551', 'Conveyer54', 'Conveyer51', 'Ventilator315', 'Ventilator57');
    marsh[43] = new Array('Conveyer481', 'Elevator49', 'Conveyer47', 'Conveyer18', 'Ventilator204', 'Ventilator336', 'Ventilator206');
    marsh[44] = new Array('Conveyer481', 'Elevator49', 'Conveyer47', 'Bunker', 'Ventilator204', 'Ventilator336');
    marsh[45] = new Array('SushBaraban433', 'Elevator432', 'Conveyer46', 'Conveyer47', 'Conveyer18', 'Ventilator43');
    marsh[46] = new Array('SushBaraban433', 'Elevator432', 'Conveyer46', 'Conveyer47', 'Bunker', 'Ventilator43');
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
          SwitchElement(ElementName, ElementValue);
        }
      }
//------------------------------------------------------------------------------
      function SwitchElement(element, state)
      {
        var Element = document.getElementById(element);
				var flag = getCookie('MarshSelectFlag'); //считываем кукис флага выделения маршрута
				var marsh_tmp = GetElementClass(element); //выделяем слово 'marsh' для фильтрации данных
//				var tmp = 'element='+element+' state='+state;
//				alert(tmp);
        if (Element != null)
        {  
					var ClassName = GetElementClass(Element.className);
					if (marsh_tmp == 'marsh') ClassName = marsh_tmp;
					switch (ClassName)
					{
						case ('Marsh'):
							switch(state) //для индикации маршрутов
							{
								case ('0'):
									if (Element.className != ClassName+'_Off') //стоит
										Element.className = ClassName+'_Off';
									break;
								case ('1'):
									if (Element.className != ClassName+'_Assemble')  //идет сборка
										Element.className = ClassName+'_Assemble';
									break;
								case ('2'):
									if (Element.className != ClassName+'_On') //запущен
										Element.className = ClassName+'_On';           
									break;
								case ('3'):
									if (Element.className != ClassName+'_Disassemble') //нормально разбирается
										Element.className = ClassName+'_Disassemble';           
									break;
								case ('4'):
									if (Element.className != ClassName+'_Avar') //Аварийно работает
										Element.className = ClassName+'_Avar';           
									break;
								default:
									Element.className = ClassName+'_Off';
									break;
							}
						break;
/*						case ('marsh'):
							var MarshNum = GetElementValue(element, "_");
							var i = 0;
							if (marsh[MarshNum].length == 0)
							{ 
								arert('Неверный номер маршрута');
								return 0;
							}  
							while (i < marsh[MarshNum].length)
							{
								var Element = document.getElementById(marsh[MarshNum][i]);
								if (Element != null)
								{  
									var ClassName = GetElementClass(Element.className);
									switch (state)
									{
										case ('0'):
											if (Element.className != ClassName+'_Off') //стоит
												Element.className = ClassName+'_Off';
										break;  
										case ('1'):
											if (Element.className != ClassName+'_marsh_Assembled') //собирается
												Element.className = ClassName+'_marsh_Assembled';
										break;  
										case ('2'):
											if (Element.className != ClassName+'_On') //работает
												Element.className = ClassName+'_On';
										break;  
										case ('3'):
											if (Element.className != ClassName+'_marsh_DisAssembled') //разбирается
												Element.className = ClassName+'_marsh_DisAssembled';
										break;  
										case ('4'):
										break;  
									}  
								} 
								i++; 
							}
						break;*/
						default:
							switch(state) 
							{
								case ('0'):
									if (Element.className != ClassName+'_Off' && (flag == 0 || flag == null))
										Element.className = ClassName+'_Off';
									break;
								case ('1'):
									if (Element.className != ClassName+'_On' && (flag == 0 || flag == null))
										Element.className = ClassName+'_On';
									break;
								case ('2'):
									if (Element.className != ClassName+'_mech_Assembled' && (flag == 0 || flag == null))
										Element.className = ClassName+'_mech_Assembled';
								break;  
								case ('3'):
									if (Element.className != ClassName+'_Manual' && (flag == 0 || flag == null))
										Element.className = ClassName+'_Manual';           
									break;
								default:
									Element.className = ClassName+'_Off';
									break;
							}
						break;
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
          request.open("POST", "/ASUTP/php/GetOPCData.php?mnemo=1", false);
          request.send(null);
//          alert('request');          
        }
        else
          alert('ошибка при создании xmlrequest!');
        setTimeout('SendRequest()', 10000); 
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
		
    function State_marsh(state, MarshNum)
    {
      var i = 0;
      var flag = getCookie('MarshSelectFlag');
      if (marsh[MarshNum].length == 0)
      { 
        arert('Неверный номер маршрута');
        return 0;
      }  
      while (i < marsh[MarshNum].length)
      {
        var Element = document.getElementById(marsh[MarshNum][i]);
        if (Element != null)
        {  
          var ClassName = GetElementClass(Element.className);
          switch (state)
          {
            case ('on'):
              if (flag == 0)
                Element.className = ClassName+'_On';
            break;  
            case ('off'):
              if (flag == 0)
                Element.className = ClassName+'_Off';
            break;  
            case ('select'):
              Element.className = ClassName+'_Selected';
            break;  
          }  
        } 
        i++; 
      }  
    }   			