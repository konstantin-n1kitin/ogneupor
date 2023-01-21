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
          if (ClassState == "_Off" || ClassState == "_On" || ClassState == "_Manual")
          {
            switch(state)
            {
              case ('0'):
              {
                if (Element.className != ClassName+'_Off')
                  Element.className = ClassName+'_Off';
                break;
              }
              case ('1'):
              {
//            alert(ClassName + "" + ClassState + " = " + state);  
                if (Element.className != ClassName+'_On')
                  Element.className = ClassName+'_On';
                break;
              }
              case ('False'):
              {
                if (Element.className != ClassName+'_Off')
                  Element.className = ClassName+'_Off';
                break;
              }
              case ('True'):
              {
                if (Element.className != ClassName+'_On')
                  Element.className = ClassName+'_On';
                break;
              }
              default:  
                if (Element.className != ClassName+'_Off')
                  Element.className = ClassName+'_Off';
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
          request.open("POST", "/ASUTP/php/GetOPCData.php?mnemo=8", false); 
          request.send(null);
//          alert('request');          
        }
        else
          alert('ошибка при создании xmlrequest!')
        setTimeout('SendRequest()', 3000) 
      }