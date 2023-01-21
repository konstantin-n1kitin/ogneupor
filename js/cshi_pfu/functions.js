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
      function SwitchElement(element, state)
      {
        var Element = document.getElementById(element);
        if (Element != null)
        {
//      Element.value = state;
          var ClassState = GetElementState(Element.className);
		      var ClassName = GetElementClass(Element.className);
          if (ClassState == "_Off" || ClassState == "_On" || ClassState == "_Manual" || ClassState == "_UnCertain" || ClassState == "_Avar" || 
              ClassState == "_off" || ClassState == "_on" || ClassState == "_manual" || ClassState == "_Local" || ClassState == "_Off2")
          {
//            if (ClassName == "Conveyer616A")
//              alert(ClassName + "" + ClassState + " = " + state);    

//              alert(ClassName);
//              alert(ClassState);
              switch(state)
              {
                case ('0'):
                  {
                    switch (ClassName) // Делаем выборку 
                    {
                      case ('Mode'):
                        Element.innerHTML = "РУЧ";
//                        Element.innerHTML = "RUCH";
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;
                      case ('MD'):
                        Element.innerHTML = "Мест";
//                        Element.innerHTML = "Mest";
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;
                      case ('CycleStart'):
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;
                      case ('CycleStop'):
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;
                      case ('WorkAdjustment'):
                        Element.innerHTML = "Режим - НАЛАДКА";
//                        Element.innerHTML = "Regime - NALADKA";
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;  
                      case ('Overload'):
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break; 
                      case ('ScaleState'):
                        Element.innerHTML = "Весы готовы";
//                        Element.innerHTML = "Vesi gotovi";
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;                          
                      case ('GruboTochno'):
                        Element.innerHTML = "Г/Т";
//                        Element.innerHTML = "G/T";
                        if (Element.className != ClassName+'_UnCertain')
                          Element.className = ClassName+'_UnCertain';
                        break;                          
                      case ('Conveyer616A'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Conveyer_616A_text').className = 'Conveyer616AText_Off';
                        }  
                        break;                          
                      case ('Conveyer616'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Conveyer_616_text').className = 'Conveyer616Text_Off';
                        }  
                        break;                          
                      case ('Conveyer618-1'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Conveyer_618-1_text').className = 'Conveyer618-1Text_Off';
                        }  
                        break;                          
                      case ('Elevator618-2'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Elevator_618-2_text').className = 'Elevator618-2Text_Off';
                        }  
                        break;                          
                      case ('Ventilyator274'):
                        if (Element.className != ClassName+'_Off')
                        { 
                          Element.className = ClassName+'_Off';
                          document.getElementById('Ventilyator274_text').className = 'Ventilyator274Text_Off';
                        }
                        break;                          
                      case ('Press'):
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;                          
                      default:
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break; 
                    }
                  }  
                  break;
                case ('1'):
                {
                    switch (ClassName) // Делаем выборку 
                    {
                      case ('Mode'):
                        Element.innerHTML = "АВТ";
//                        Element.innerHTML = "AVT";
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;
                      case ('MD'):
//                        Element.innerHTML = "Dist";
                        Element.innerHTML = "Дист";
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;
                      case ('CycleStart'):
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;
                      case ('CycleStop'):
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;
                      case ('WorkAdjustment'):
//                        Element.innerHTML = "regime - RABOTA";
                        Element.innerHTML = "Режим - РАБОТА";
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;  
                      case ('Overload'):
                        Element.className = ClassName+'_On';
                        break;  
                      case ('ScaleState'):
//                        Element.innerHTML = "vesi gotovi";
                        Element.innerHTML = "Весы готовы";
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;                          
                      case ('GruboTochno'):
//                        Element.innerHTML = "Tochno";
                        Element.innerHTML = "Точно";
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;                          
                      case ('Conveyer616A'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Conveyer_616A_text').className = 'Conveyer616AText_Off';
                        }  
                        break;                          
                      case ('Conveyer616'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Conveyer_616_text').className = 'Conveyer616Text_Off';
                        }  
                        break;                          
                      case ('Conveyer618-1'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Conveyer_618-1_text').className = 'Conveyer618-1Text_Off';
                        }  
                        break;                          
                      case ('Elevator618-2'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Elevator_618-2_text').className = 'Elevator618-2Text_Off';
                        }  
                        break;                          
                      case ('Ventilyator274'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Ventilyator274_text').className = 'Ventilyator274Text_On';
                        }
                        break;                          
                      case ('Press'):
                        if (Element.className != ClassName+'_Off2')
                          Element.className = ClassName+'_Off2';
                        break;                          
                      default:
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break; 
                    }
                  }  
                  break;
                case ('2'):
                    switch (ClassName)
                    {
                      case ('CycleStart'):
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;
                      case ('Overload'):
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;  
                      case ('ScaleState'):
                        Element.innerHTML = "Весы готовы";
//                        Element.innerHTML = "Vesi gotovi";
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;                          
                      case ('GruboTochno'):
                        Element.innerHTML = "Грубо";
//                        Element.innerHTML = "Grubo";
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;                          
                      case ('Conveyer616A'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_616A_text').className = 'Conveyer616AText_On';
                        }  
                        break;                          
                      case ('Conveyer616'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_616_text').className = 'Conveyer616Text_On';
                        }  
                        break;                          
                      case ('Conveyer618-1'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_618-1_text').className = 'Conveyer618-1Text_On';
                        }  
                        break;                          
                      case ('Elevator_618-2'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Elevator_618-2_text').className = 'Elevator618-2Text_On';
                        }  
                        break;                          
                      case ('Press'):
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;                          
                      default:
                        Element.className = ClassName+'_Off';
                        break;  
                    }
                  break;  
                case ('3'):
                  switch (ClassName)
                  {
                      case ('CycleStart'):
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;
                      case ('CycleStart'):
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;
                      case ('Overload'):
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;  
                      case ('ScaleState'):
                        Element.innerHTML = "Весы готовы";
//                        Element.innerHTML = "Vesi gotovi";
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;                          
                      case ('Conveyer616A'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_616A_text').className = 'Conveyer616AText_On';
                        }  
                        break;                          
                      case ('Conveyer616'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_616_text').className = 'Conveyer616Text_On';
                        }  
                        break;                           
                      case ('Conveyer618-1'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_618-1_text').className = 'Conveyer618-1Text_On';
                        }  
                        break;                          
                      case ('Elevator618-2'):
//                        alert(Element.className + " != " + ClassName + "" + ClassState + " = " + state);   
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Elevator_618-2_text').className = 'Elevator618-2Text_On';
                        }  
                        break;                          
                      default:  
                        Element.className = ClassName+'_Off';
                        break; 
                  }      
                  break;
                case ('4'):
                  switch (ClassName)
                  {
                      case ('CycleStart'):
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;
                      case ('CycleStop'):
                        if (Element.className != ClassName+'_On')
                          Element.className = ClassName+'_On';
                        break;
                      case ('Overload'):
                        if (Element.className != ClassName+'_UnCertain')
                          Element.className = ClassName+'_UnCertain';
                        break;  
                      case ('ScaleState'):
                        Element.innerHTML = "Авария";
//                        Element.innerHTML = "Avariya";
                        if (Element.className != ClassName+'_Avar')
                          Element.className = ClassName+'_Avar';
                        break;                          
                      case ('Conveyer616A'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_616A_text').className = 'Conveyer616AText_On';
                        }  
                        break;                          
                      case ('Conveyer616'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_616_text').className = 'Conveyer616Text_On';
                        }  
                        break;                          
                      case ('Conveyer618-1'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_618-1_text').className = 'Conveyer618-1Text_On';
                        }  
                        break;                          
                      case ('Elevator618-2'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Elevator_618-2_text').className = 'Elevator618-2Text_On';
                        }  
                        break;                          
                      default:  
                        Element.className = ClassName+'_Off';
                        break; 
                  }
                  break;
                case ('5'):
                    switch (ClassName) // Делаем выборку 
                    {
                      case ('Overload'):
                        if (Element.className != ClassName+'_UnCertain')
                          Element.className = ClassName+'_UnCertain';
                        break;  
                      case ('ScaleState'):
                        Element.innerHTML = "Местный";
//                        Element.innerHTML = "Mestniy";
                        if (Element.className != ClassName+'_Local')
                          Element.className = ClassName+'_Local';
                      case ('Conveyer616A'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_616A_text').className = 'Conveyer616AText_On';
                        }  
                        break;                          
                      case ('Conveyer616'):
                        if (Element.className != ClassName+'_On')
                        {  Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_616_text').className = 'Conveyer616Text_On';
                        }  
                        break;                          
                      case ('Conveyer618-1'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Conveyer_618-1_text').className = 'Conveyer618-1Text_On';
                        }  
                        break;                          
                      case ('Elevator618-2'):
                        if (Element.className != ClassName+'_On')
                        {
                          Element.className = ClassName+'_On';
                          document.getElementById('Elevator_618-2_text').className = 'Elevator618-2Text_On';
                        }  
                        break;                          
                    }    
                  break;
                case ('6'):
                    switch (ClassName) // Делаем выборку 
                    {
                      case ('Overload'):
                        if (Element.className != ClassName+'_Off')
                          Element.className = ClassName+'_Off';
                        break;  
                      case ('Conveyer616A'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Conveyer_616A_text').className = 'Conveyer616AText_Off';
                        }  
                        break;                          
                      case ('Conveyer616'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Conveyer_616_text').className = 'Conveyer616AText_Off';
                        }  
                        break;                          
                      case ('Conveyer618-1'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Conveyer_618-1_text').className = 'Conveyer618-1Text_Off';
                        }  
                        break;                          
                      case ('Elevator618-2'):
                        if (Element.className != ClassName+'_Off')
                        {
                          Element.className = ClassName+'_Off';
                          document.getElementById('Elevator_618-2_text').className = 'Elevator618-2Text_Off';
                        }  
                        break;                          
                    }    
                  break;   
                case ('999'):
                    switch (ClassName) // Делаем выборку 
                    {
                      case ('MD'):
                        Element.innerHTML = "М/Д";
//                        Element.innerHTML = "M/D";
                        if (Element.className != ClassName+'_UnCertain')
                          Element.className = ClassName+'_UnCertain';
                        break;
                    }    
                  break;    
                default:
                    Element.className = ClassName+'_Off';
                    break;  
              }
          }
          else
          {
            if (element == 'Cycle_state_num')
            {
              if (state > 0)
              {
                            
                if (Element.className != ClassName+'_On')
                {
                  document.getElementById('VortexText').className = 'VortexText_On';
                  Element.innerHTML = state;
                }
              }
              else
              {
                if (Element.className != ClassName+'_Off')
                {
                  document.getElementById('VortexText').className = 'VortexText_Off';
                  Element.innerHTML = state;
                }
              }
            }
            else    
              Element.innerHTML = state;
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
//          request.open("POST", "/ASUTP/php/cshi_pfu/AddMoreInformation.php?mnemo=2", false); 
          request.open("POST", "/ASUTP/php/GetOPCData.php?mnemo=2", false); 
          request.send(null);
//          alert('request');          
        }
        else
          alert('ошибка при создании xmlrequest!');
        setTimeout('SendRequest()', 1000); 
      }	