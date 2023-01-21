    var marsh = new Array(); //многомерный массив массив маршрутов с включающих его механизмами
    var temp_array = new Array();  //выбранный маршрут массив
    marsh[1] = new Array('PlastPitatel2-30', 'GlinaCutMashine4-392', 'line_2-30', 'line_4-292-39_a');
	marsh[2] = new Array('PlastPitatel50', 'GlinaCutMashine5-536', 'Conveyer534', 'line_50-5_536', 'line_5_536', 'line_5_536-534_a', 'line_5_536-534_b', 'line_5_536-534_c');
    marsh[3] = new Array('PlastPitatel50', 'GlinaCutMashine5-536', 'Conveyer535', 'Conveyer434', 'line_50-5_536', 'line_5_536', 'line_5_536-535_a', 'line_5_536-535_b', 'line_6-436-535_b', 'line_535-434');
    marsh[4] = new Array('PlastPitatel40', 'GlinaCutMashine6-436', 'Conveyer535', 'Conveyer434', 'line_40-6-436', 'line_6-436-535_a', 'line_6-436-535_b', 'line_535-434');
    marsh[5] = new Array('SushBaraban533', 'Elevator532', 'Conveyer531', 'StoneOtdelitVal27', 'Ventilator53', 'line_533-532', 'line_532-531', 'line_531-27');
    marsh[6] = new Array('SushBaraban433', 'Elevator432', 'Conveyer431', 'StoneOtdelitVal26', 'Ventilator43', 'line_433-432', 'line_432-431_a', 'line_432-431_b', 'line_432-431_c', 'line_431-26');
    marsh[7] = new Array('Conveyer333-1', 'SushBaraban333', 'Elevator332', 'Conveyer331', 'StoneOtdelitVal25', 'Ventilator33', 'line_333-1-333', 'line_333-332', 'line_332-331', 'line_331-25');
    marsh[8] = new Array('LotPitatel52', 'Conveyer514', 'Dezintegrator513', 'Elevator512', 'Ventilator322', 'line_52-514', 'line_514-513', 'line_513-512', 'line_512');
    marsh[9] = new Array('LotPitatel42', 'Conveyer414', 'Dezintegrator413', 'Elevator412', 'Ventilator325', 'line_42-414', 'line_414-413', 'line_413-412', 'line_412');
    marsh[10] = new Array('LotPitatel32', 'Conveyer314', 'Dezintegrator313', 'Elevator312', 'Ventilator329', 'line_32-314', 'line_314-313', 'line_313-312', 'line_312');
    marsh[11] = new Array('Conveyer481', 'Elevator49', 'Conveyer47', 'Conveyer53', 'Conveyer50-2', 'Ventilator204', 'Ventilator336', 'Ventilator272', 'Ventilator329', 'line_481-49', 'line_49-47_a', 'line_49-47_b', 'line_49-47_c', 'line_49-47_d', 'line_47-53', 'line_53-50-2');
    marsh[12] = new Array('Conveyer491', 'Elevator48', 'Conveyer47', 'Conveyer53', 'Conveyer50-2', 'Ventilator204', 'Ventilator336', 'Ventilator272', 'Ventilator329', 'line_491-48', 'line_48-47_a', 'line_48-47_b', 'line_48-47_c', 'line_49-47_d', 'line_47-53', 'line_53-50-2');
    marsh[13] = new Array('PlastPitatel1-20', 'GlinaCutMashine1-292', 'Conveyer291', 'line_1-20-1-292', 'line_1-292-291_a', 'line_1-292-291_b', 'line_1-292-291_c');
    marsh[14] = new Array('PlastPitatel2-20', 'GlinaCutMashine2-292', 'Conveyer291', 'line_2-20-2-292', 'line_2-292-291_a', 'line_2-292-291_b', 'line_1-292-291_c');
    marsh[15] = new Array('PlastPitatel1-30', 'GlinaCutMashine3-392', 'line_1-30-3-292', 'line_3-292-29_a', 'line_3-292-29_b', 'line_3-292-29_c');
    marsh[16] = new Array('');
    marsh[17] = new Array('Conveyer29', 'Conveyer00', 'VrashPech073', 'line_29', 'line_29-00_a', 'line_29-00_b', 'line_00-73');
    marsh[18] = new Array('Conveyer39', 'Conveyer00', 'VrashPech073', 'line_39', 'line_39-00_a', 'line_39-00_b', 'line_29-00_b', 'line_00-73');
    marsh[19] = new Array('Conveyer39', 'Conveyer10', 'VrashPech074', 'line_39', 'line_39-10_a', 'line_39-10_b', 'line_10-74');
    marsh[20] = new Array('Conveyer29', 'Conveyer10', 'VrashPech074', 'line_29', 'line_29-10_a', 'line_29-10_b', 'line_39-10_b', 'line_10-74');
    marsh[21] = new Array('Refregerator072', 'Conveyer07', 'Elevator041', 'Ventilator316', 'Conveyer01', 'line_72', 'line_72-07_a', 'line_72-07_b', 'line_07', 'line_71-07_b', 'line_07-041_b', 'line_041', 'line_041-01_a', 'line_041-01_b', 'line_041-01_c');
    marsh[22] = new Array('Refregerator072', 'Conveyer07', 'Elevator041', 'Ventilator316', 'Conveyer11', 'line_72', 'line_72-07_a', 'line_72-07_b', 'line_07', 'line_71-07_b', 'line_07-041_b', 'line_041', 'line_041-11_a', 'line_041-11_b', 'line_041-11_c');
    marsh[23] = new Array('Refregerator072', 'Conveyer07', 'Elevator241', 'Ventilator316', 'Conveyer01', 'line_72', 'line_72-07_a', 'line_72-07_b', 'line_07', 'line_71-07_b', 'line_07-241_a', 'line_07-241_b', 'line_17-241_c', 'line_241', 'line_241-01_a', 'line_241-01_b', 'line_241-01_c');  
    marsh[24] = new Array('Refregerator072', 'Conveyer07', 'Elevator241', 'Ventilator316', 'Conveyer11', 'line_72', 'line_72-07_a', 'line_72-07_b', 'line_07', 'line_71-07_b', 'line_07-241_a', 'line_07-241_b', 'line_17-241_c', 'line_241', 'line_241-11_a', 'line_241-11_b', 'line_241-11_c');
    marsh[25] = new Array('Refregerator072', 'Conveyer17', 'Elevator241', 'Ventilator316', 'Conveyer01', 'line_72', 'line_72-17_a', 'line_72-17_b', 'line_17', 'line_17-241_a', 'line_17-241_b', 'line_17-241_c', 'line_241', 'line_241-01_a', 'line_241-01_b', 'line_241-01_c');
    marsh[26] = new Array('Refregerator072', 'Conveyer17', 'Elevator241', 'Ventilator316', 'Conveyer11', 'line_72', 'line_72-17_a', 'line_72-17_b', 'line_17', 'line_17-241_a', 'line_17-241_b', 'line_17-241_c', 'line_241', 'line_241-11_a', 'line_241-11_b', 'line_241-11_c');
    marsh[27] = new Array('Refregerator072', 'Conveyer17', 'Elevator141', 'Ventilator203', 'Conveyer01', 'line_72', 'line_72-17_a', 'line_72-17_b', 'line_17', 'line_17-141', 'line_141', 'line_141-01_a', 'line_141-01_b', 'line_141-01_c');
    marsh[28] = new Array('Refregerator072', 'Conveyer17', 'Elevator141', 'Ventilator203', 'Conveyer11', 'line_72', 'line_72-17_a', 'line_72-17_b', 'line_17', 'line_17-141', 'line_141', 'line_141-11_a', 'line_141-11_b', 'line_141-11_c');
    marsh[29] = new Array('Refregerator071', 'Conveyer07', 'Elevator041', 'Ventilator316', 'Conveyer01', 'line_71', 'line_71-07_a', 'line_71-07_b', 'line_07', 'line_07-041_b', 'line_041', 'line_041-01_a', 'line_041-01_b', 'line_041-01_c');
    marsh[30] = new Array('Refregerator071', 'Conveyer07', 'Elevator041', 'Ventilator316', 'Conveyer11', 'line_71', 'line_71-07_a', 'line_71-07_b', 'line_07', 'line_07-041_b', 'line_041', 'line_041-11_a', 'line_041-11_b', 'line_041-11_c');
    marsh[31] = new Array('Refregerator071', 'Conveyer07', 'Elevator241', 'Ventilator316', 'Conveyer01', 'line_71', 'line_71-07_a', 'line_71-07_b', 'line_07', 'line_07-241_a', 'line_07-241_b', 'line_17-241_c', 'line_241', 'line_241-01_a', 'line_241-01_b', 'line_241-01_c');
    marsh[32] = new Array('Refregerator071', 'Conveyer07', 'Elevator241', 'Ventilator316', 'Conveyer11', 'line_71', 'line_71-07_a', 'line_71-07_b', 'line_07', 'line_07-241_a', 'line_07-241_b', 'line_17-241_c', 'line_241', 'line_241-11_a', 'line_241-11_b', 'line_241-11_c');
    marsh[33] = new Array('Refregerator071', 'Conveyer17', 'Elevator241', 'Ventilator316', 'Conveyer01', 'line_71', 'line_71_17_a', 'line_71_17_b', 'line_72-17_b', 'line_17', 'line_17-241_a', 'line_17-241_b', 'line_17-241_c', 'line_241', 'line_241-01_a', 'line_241-01_b', 'line_241-01_c');
    marsh[34] = new Array('Refregerator071', 'Conveyer17', 'Elevator241', 'Ventilator316', 'Conveyer11', 'line_71', 'line_71_17_a', 'line_71_17_b', 'line_72-17_b', 'line_17', 'line_17-241_a', 'line_17-241_b', 'line_17-241_c', 'line_241', 'line_241-11_a', 'line_241-11_b', 'line_241-11_c');
    marsh[35] = new Array('Refregerator071', 'Conveyer17', 'Elevator141', 'Ventilator203', 'Conveyer01', 'line_71', 'line_71_17_a', 'line_71_17_b', 'line_72-17_b', 'line_17', 'line_17-141', 'line_141', 'line_141-01_a', 'line_141-01_b', 'line_141-01_c');
    marsh[36] = new Array('Refregerator071', 'Conveyer17', 'Elevator141', 'Ventilator203', 'Conveyer11', 'line_71', 'line_71_17_a', 'line_71_17_b', 'line_72-17_b', 'line_17', 'line_17-141', 'line_141', 'line_141-11_a', 'line_141-11_b', 'line_141-11_c');
    marsh[37] = new Array('Conveyer793', 'BallsMill792', 'Elevator791', 'Ventilator198', 'line_793-792', 'line_792-791', 'line_791');
    marsh[38] = new Array('Conveyer783', 'BallsMill782', 'Elevator781', 'Ventilator199', 'line_783-782', 'line_782-781', 'line_781');
    marsh[39] = new Array('Conveyer773', 'BallsMill772', 'Elevator771', 'Ventilator201', 'line_773-772', 'line_772-771', 'line_771');
    marsh[40] = new Array('Conveyer763', 'BallsMill762', 'Elevator761', 'Ventilator202', 'Grohot76', 'line_763', 'line_762', 'line_761', 'line_76');
    marsh[41] = new Array('Conveyer542', 'Elevator541', 'Conveyer55', 'Conveyer52', 'Ventilator315', 'Ventilator57', 'line_542-541', 'line_541-55', 'line_55-52');
    marsh[42] = new Array('Conveyer552', 'Elevator551', 'Conveyer54', 'Conveyer51', 'Ventilator315', 'Ventilator57', 'line_552-551', 'line_551-54', 'line_54-51');
    marsh[43] = new Array('Conveyer481', 'Elevator49', 'Conveyer47', 'Conveyer18', 'Ventilator204', 'Ventilator336', 'Ventilator206', 'line_481-49', 'line_49-47_a', 'line_49-47_b', 'line_49-47_c', 'line_49-47_d', 'line_47', 'line_18-47_a', 'line_18-47_b', 'line_18-47_c');
    marsh[44] = new Array('Conveyer481', 'Elevator49', 'Conveyer47', 'Bunker', 'Ventilator204', 'Ventilator336', 'line_481-49', 'line_49-47_a', 'line_49-47_b', 'line_49-47_c', 'line_49-47_d', 'line_47', 'line_bunker-47_a', 'line_bunker-47_b', 'line_bunker-47_c');
    marsh[45] = new Array('SushBaraban433', 'Elevator432', 'Conveyer46', 'Conveyer47', 'Conveyer18', 'Ventilator43', 'line_433-432', 'line_432-431_a', 'line_432-46_a', 'line_432-46_b', 'line_46-47_a', 'line_46-47_b', 'line_46-47_c', 'line_46-47_d', 'line_49-47_d', 'line_47', 'line_18-47_a', 'line_18-47_b', 'line_18-47_c');
    marsh[46] = new Array('SushBaraban433', 'Elevator432', 'Conveyer46', 'Conveyer47', 'Bunker', 'Ventilator43', 'line_433-432', 'line_432-431_a', 'line_432-46_a', 'line_432-46_b', 'line_46-47_a', 'line_46-47_b', 'line_46-47_c', 'line_46-47_d', 'line_49-47_d', 'line_47', 'line_bunker-47_a', 'line_bunker-47_b', 'line_bunker-47_c');
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
        var Element = document.getElementById(element);
		var flag = getCookie('MarshSelectFlag'); //считываем кукис флага выделения маршрута
//				var tmp = 'element='+element+' state='+state;
//				alert(tmp);
        if (Element != null)
        {  
			var ClassName = GetElementClass(Element.className);
			switch (ClassName)
			{
				case ('Marsh'):
				{
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
							
					var MarshNum = GetElementValue(element, "_");
					var i = 0;
					if (marsh[MarshNum].length == 0)
					{ 
						arert('Неверный номер маршрута');
						return 0;
					}  
					else
					{
						while (i < marsh[MarshNum].length)
						{
							var Element_marsh = document.getElementById(marsh[MarshNum][i]);
							if (Element_marsh != null)
							{  
								ClassName_marsh = GetElementClass(Element_marsh.className);
								switch (state)
								{
									case ('1'):
										for (var j=0; j<Elements.length; j++)
										{
											ElementName = GetElementName(Elements[j], "=");
											ElementValue = GetElementValue(Elements[j], "=");
											if (ElementName == marsh[MarshNum][i])
											{
												if (ElementValue == 3)
												{
													if (Element_marsh.className != ClassName_marsh+'_marsh_Assembled_mech_on') //собирается и механизм включен
														Element_marsh.className = ClassName_marsh+'_marsh_Assembled_mech_on';
												}
												else
												{
													if (Element_marsh.className != ClassName_marsh+'_marsh_Assembled') //собирается и механизм выключен
														Element_marsh.className = ClassName_marsh+'_marsh_Assembled';
												}		
											}
										}
									break;  
									case ('3'):
										for (var j=0; j<Elements.length; j++)
										{
											ElementName = GetElementName(Elements[j], "=");
											ElementValue = GetElementValue(Elements[j], "=");
											if (ElementName == marsh[MarshNum][i])
											{
												if (ElementValue == 3)
												{
													if (Element_marsh.className != ClassName_marsh+'_marsh_DisAssembled_mech_on') //разбирается и механизм включен
														Element_marsh.className = ClassName_marsh+'_marsh_DisAssembled_mech_on';
												}
												else
												{
													if (Element_marsh.className != ClassName_marsh+'_marsh_DisAssembled') //разбирается и механизм выключен
														Element_marsh.className = ClassName_marsh+'_marsh_DisAssembled';
												}		
											}
										}
									break; 
								}  
							} 
							i++; 
						}							
					}	
				}	
				break;
				default:
				{
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
//          request.open("POST", "/ASUTP/php/1.php", false);
          request.send(null);
//          alert('request');          
        }
        else
//          alert('ошибка при создании xmlrequest!');
		location.reload(true);
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
			  if (flag == 1)
				Element.className = ClassName+'_Selected';
			  else
				Element.className = ClassName+'_Off';
            break; 
          }  
        } 
        i++; 
      }  
    }   			
		
