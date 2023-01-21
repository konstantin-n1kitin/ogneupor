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
          var ClassState = GetElementState(Element.className);
          var ClassName = GetElementClass(Element.className);
          if (ClassState == "_Off" || ClassState == "_On")  
          {
//            alert('EN='+ElementName+'  EV='+ElementValue);
            switch(state)
            {
              case ('False'):
                if (Element.className != ClassName+'_Off')
                {
                  Element.className = ClassName+'_Off';
                  SelectSwitchState(Element, state);
                }
                break;
              case ('True'):
                if (Element.className != ClassName+'_On')
                {
                  Element.className = ClassName+'_On';
                  SelectSwitchState(Element, state);
                }
                break;
              default:
                break;
            }
          }
          else  
          {
            if (element == 'STATE_1' || element == 'STATE_2')
            {
              if (state == 1) 
              {
                if (element == 'STATE_1')
                {
                  alert ('state1='+state);
                  document.getElementById('STATE_1').innerHTML = "<font style='font-size:16px' color='#000000' face='Arial'>Регулирование температуры по <a href='./index.html'>графику №1</a></font>";
                  document.getElementById('DryName_1').innerHTML = "<font style='font-size:19px' color=lime face='Arial'>Камерное сушило №1</font>";
//                  document.getElementById('DryName_2').innerHTML = "<font style='font-size:19px' color=black face='Arial'>Камерное сушило №2</font>";
                }
                if (element == 'STATE_2')
                {
                  alert ('state2='+state);
                  document.getElementById('STATE_2').innerHTML = "<font style='font-size:16px' color='#000000' face='Arial'>Регулирование температуры по <a href='./index.html'>графику №1</a></font>";
                  document.getElementById('DryName_2').innerHTML = "<font style='font-size:19px' color=lime face='Arial'>Камерное сушило №2</font>";
  //                document.getElementById('DryName_1').innerHTML = "<font style='font-size:19px' color=black face='Arial'>Камерное сушило №1</font>";
                }
              } 
              else
              {
                if (element == 'STATE_1')
                {
                  document.getElementById('STATE_1').innerHTML = "<font style='font-size:16px' color='#000000' face='Arial'>Регулирование температуры <font color='red'>отключено</font>";
                  document.getElementById('DryName_1').innerHTML = "<font style='font-size:19px' color=black face='Arial'>Камерное сушило №1</font>";
//                  document.getElementById('DryName_2').innerHTML = "<font style='font-size:19px' color=lime face='Arial'>Камерное сушило №2</font>";
                }
                if (element == 'STATE_2')
                {
                  document.getElementById('STATE_2').innerHTML = "<font style='font-size:16px' color='#000000' face='Arial'>Регулирование температуры <font color='red'>отключено</font>";
                  document.getElementById('DryName_2').innerHTML = "<font style='font-size:19px' color=black face='Arial'>Камерное сушило №2</font>";
//                  document.getElementById('DryName_1').innerHTML = "<font style='font-size:19px' color=lime face='Arial'>Камерное сушило №1</font>";
                }
              }  
            }
            else
              Element.innerHTML = state;
        }  
      }
      else
      {
        if (element == 'StepsCount_dry1' || element == 'StepsIndex_dry1' || element == 'StepCurrentTime_dry1' || 
            element == 'StepTime_dry1' || element == 'ChartCurrentTime_dry1' || element == 'ChartTime_dry1' ||
            element == 'StepsCount_dry2' || element == 'StepsIndex_dry2' || element == 'StepCurrentTime_dry2' || 
            element == 'StepTime_dry2' || element == 'ChartCurrentTime_dry2' || element == 'ChartTime_dry2')
        {
          var obj = document.getElementById('Parametrs_dry1');
          if (obj != null) //Сушило 1
          {
            switch (element)
            {
              case 'StepsIndex_dry1':
                obj.rows[1].cells[1].innerHTML = state; // Текущий шаг 
                break;
              case 'StepsCount_dry1':
                obj.rows[2].cells[1].innerHTML = state; // Всего шагов
                break;
              case 'StepCurrentTime_dry1':
                obj.rows[3].cells[1].innerHTML = RecountTime(state); // Текущее время шага 
                break;
              case 'StepTime_dry1':
                obj.rows[4].cells[1].innerHTML = RecountTime(state); // Время шага
                break;
              case 'ChartCurrentTime_dry1':
                obj.rows[5].cells[1].innerHTML = RecountTime(state); // Текущее время диаграммы 
                break;
              case 'ChartTime_dry1':
                obj.rows[6].cells[1].innerHTML = RecountTime(state); // Время диаграммы
                break;
            }
          }  
          obj = document.getElementById('Parametrs_dry2');
          if (obj != null) //Сушило 2
          {
            switch (element)
            {
              case 'StepsIndex_dry2':
                obj.rows[1].cells[1].innerHTML = state; // Текущий шаг 
                break;
              case 'StepsCount_dry2':
                obj.rows[2].cells[1].innerHTML = state; // Всего шагов
                break;
              case 'StepCurrentTime_dry2':
                obj.rows[3].cells[1].innerHTML = RecountTime(state); // Текущее время шага 
                break;
              case 'StepTime_dry2':
                obj.rows[4].cells[1].innerHTML = RecountTime(state); // Время шага
                break;
              case 'ChartCurrentTime_dry2':
                obj.rows[5].cells[1].innerHTML = RecountTime(state); // Текущее время диаграммы 
                break;
              case 'ChartTime_dry2':
                obj.rows[6].cells[1].innerHTML = RecountTime(state); // Время диаграммы
                break;
            }
          }            
        }    

        if (element == 'ExistFire_dry1' || element == 'ExistFire_dry2' || element == 'MinPresureGas_dry1' || 
            element == 'MinPresureAir_dry1' || element == 'MinPresureInDry_dry1' || element == 'MinPresureGas_dry2' || 
            element == 'MinPresureAir_dry2' || element == 'MinPresureInDry_dry2')
        {
          obj = document.getElementById('Blocks_dry1');
//          alert('element='+element+'   state='+state);
          var value = "";
          if (obj != null) //Сушило 1
          {
            switch (element)
            {
              case 'MinPresureGas_dry1':
                if (state == 'True') value = "<font style='color:green;'>да</font>";
                else value = "<font style='color:red;'>нет</font>";
                obj.rows[1].cells[1].innerHTML = value; // Минимальное давление газа 
                break;
              case 'MinPresureAir_dry1':
                if (state == 'True') value = "<font style='color:green;'>да</font>";
                else value = "<font style='color:red;'>нет</font>";
                obj.rows[2].cells[1].innerHTML = value; // Минимальное давление воздуха 
                break;
              case 'MinPresureInDry_dry1':
                if (state == 'True') value = "<font style='color:green;'>да</font>";
                else value = "<font style='color:red;'>нет</font>";
                obj.rows[3].cells[1].innerHTML = value; // Минимальное давление в сушиле 
                break;
              case 'ExistFire_dry1':
                if (state == 'True') value = "<font style='color:green;'>да</font>";
                else value = "<font style='color:red;'>нет</font>";
                obj.rows[4].cells[1].innerHTML = value; // Контроль факела в сушиле 
                break;
            }
          }    
          obj = document.getElementById('Blocks_dry2');
//          alert('element='+element+'   state='+state);
          var value = "";
          if (obj != null) //Сушило 2
          {
            switch (element)
            {
              case 'MinPresureGas_dry2':
                if (state == 'True') value = "<font style='color:green;'>да</font>";
                else value = "<font style='color:red;'>нет</font>";
                obj.rows[1].cells[1].innerHTML = value; // Минимальное давление газа 
                break;
              case 'MinPresureAir_dry2':
                if (state == 'True') value = "<font style='color:green;'>да</font>";
                else value = "<font style='color:red;'>нет</font>";
                obj.rows[2].cells[1].innerHTML = value; // Минимальное давление воздуха 
                break;
              case 'MinPresureInDry_dry2':
                if (state == 'True') value = "<font style='color:green;'>да</font>";
                else value = "<font style='color:red;'>нет</font>";
                obj.rows[3].cells[1].innerHTML = value; // Минимальное давление в сушиле 
                break;
              case 'ExistFire_dry2':
                if (state == 'True') value = "<font style='color:green;'>да</font>";
                else value = "<font style='color:red;'>нет</font>";
                obj.rows[4].cells[1].innerHTML = value; // Контроль факела в сушиле 
                break;
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
          request.open("POST", "/ASUTP/php/GetOPCData.php?mnemo=4", false); 
//          request.open("POST", "AddMoreInformation.php", false); 
          request.send(null);
//          alert('request');          
        }
        else
          alert('ошибка при создании xmlrequest!');
        setTimeout('SendRequest()', 5000); 
      }
      
      function SelectSwitchState(Element, state)
      {
        var ClassName = GetElementClass(Element.className);
        switch (Element.id)
        {
          case 'Mode_dry1':
            switch (state)
            {
              case ('False'):
                  document.getElementById('Mode_dry1_manual_text').className = 'SwitchMode_On';
                  document.getElementById('Mode_dry1_auto_text').className = 'SwitchMode_Off';
              case ('True'):
                  document.getElementById('Mode_dry1_manual_text').className = 'SwitchMode_Off';
                  document.getElementById('Mode_dry1_auto_text').className = 'SwitchMode_On';
              break;
            }
            break;  
          case 'Mode_dry2':
            switch (state)
            {
              case ('False'):
                document.getElementById('Mode_dry2_manual_text').className = 'SwitchMode_On';
                document.getElementById('Mode_dry2_auto_text').className = 'SwitchMode_Off';
                break;
              case ('True'):
                  document.getElementById('Mode_dry2_manual_text').className = 'SwitchMode_Off';
                  document.getElementById('Mode_dry2_auto_text').className = 'SwitchMode_On';
              break;
            }
            break;  
          case 'ManualAuto_dry1':
            switch (state)
            {
              case ('False'):
                document.getElementById('ManualAuto_dry1_manual_text').className = 'SwitchMode_On';
                document.getElementById('ManualAuto_dry1_auto_text').className = 'SwitchMode_Off';
                break
              case ('True'):
                document.getElementById('ManualAuto_dry1_manual_text').className = 'SwitchMode_Off';
                document.getElementById('ManualAuto_dry1_auto_text').className = 'SwitchMode_On';
                break;
            }
            break;  
          case 'ManualAuto_dry2':
            switch (state)
            {
              case ('False'):
                document.getElementById('ManualAuto_dry2_manual_text').className = 'SwitchMode_On';
                document.getElementById('ManualAuto_dry2_auto_text').className = 'SwitchMode_Off';
                break;
              case ('True'):
                document.getElementById('ManualAuto_dry2_manual_text').className = 'SwitchMode_Off';
                document.getElementById('ManualAuto_dry2_auto_text').className = 'SwitchMode_On';
                break;
            }
            break;  
        }
      }
      
      function RecountTime(time)
      {
        var hour = 0;
        var minute = 0;
        var result_str = "";
        if (time >= 60)
        {
          hour = (time - time%60)/60;
          minute = time%60;
          result_str = hour+"ч. " +minute+"м.";
        }
        else
          result_str = "0ч. " +time+"м.";
        return result_str;  
      }