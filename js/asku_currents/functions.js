﻿      var request;
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
        var EqualityPosition2 = Element.indexOf("|");
        ElementValue = Element.slice(EqualityPosition+1,EqualityPosition2);
        return ElementValue;
      }
      function GetElementStatus(Element)
      {
        var EqualityPosition = Element.indexOf("|");
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
          ElementStatus = GetElementStatus(Elements[i]);
//          alert('EN='+ElementName+'  EV='+ElementValue);      
          SwitchElement(ElementName, ElementValue, ElementStatus);
        }
      }
//------------------------------------------------------------------------------
       function SwitchElement(ID, value, status)
      {
        var Element = document.getElementById(ID);
        if (Element != null)
        {}
        else
        {
          var obj = document.getElementById('CSHI_hearting_water');
          if (obj != null) // ТЕПЛОФИКАЦИОННАЯ ВОДА ЦШИ
          {
            switch (ID)
            {
              case '2502':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура прямой 
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2561':
                obj.rows[3].cells[1].innerHTML = value+" т/ч"; // Расход прямой 
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2554':
                obj.rows[4].cells[1].innerHTML = value+" Гкал/ч"; // Тепловая мощность прямой
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2503':
                obj.rows[5].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура обратной
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2562':
                obj.rows[6].cells[1].innerHTML = value+" т/ч"; // Расход обратной
                obj.rows[6].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2555':
                obj.rows[7].cells[1].innerHTML = value+" Гкал/ч"; // Тепловая мощность обратной
                obj.rows[7].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }  
          var obj = document.getElementById('CSI_hearting_water');
          if (obj != null) // ТЕПЛОФИКАЦИОННАЯ ВОДА ЦСИ
          {
            switch (ID)
            {
              case '2438':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура прямой 
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2565':
                obj.rows[3].cells[1].innerHTML = value+" т/ч"; // Расход прямой 
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2543':
                obj.rows[4].cells[1].innerHTML = value+" Гкал/ч"; // Тепловая мощность прямой
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2439':
                obj.rows[5].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура обратной
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2566':
                obj.rows[6].cells[1].innerHTML = value+" т/ч"; // Расход обратной
                obj.rows[6].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2544':
                obj.rows[7].cells[1].innerHTML = value+" Гкал/ч"; // Тепловая мощность обратной
                obj.rows[7].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }  
          var obj = document.getElementById('CSHI_compressed_air');
          if (obj != null) // СЖАТЫЙ ВОЗДУХ ЦШИ
          {
            switch (ID)
            {
              case '2446':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2391':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '327':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2647':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }  
          var obj = document.getElementById('CSHI_compressed_air_gas_cleaning');
          if (obj != null) // СЖАТЫЙ ВОЗДУХ (ГАЗООЧИСТКА) ЦШИ
          {
            switch (ID)
            {
              case '3612':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '3613':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '3615':
                obj.rows[4].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }  
          var obj = document.getElementById('CSI_compressed_air');
          if (obj != null) // СЖАТЫЙ ВОЗДУХ ЦСИ
          {
            switch (ID)
            {
              case '2424':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2434':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2364':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2616':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }  
		  var obj = document.getElementById('CSHI_formovka_compressed_air');
          if (obj != null) // СЖАТЫЙ ВОЗДУХ ЦСИ
          {
            switch (ID)
            {
              case '3892':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '3893':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '3895':
                obj.rows[4].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }  
          var obj = document.getElementById('CSI_natural_gas');
          if (obj != null) // Природный газ ЦСИ
          {
            switch (ID)
            {
              case '2417':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2427':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2344':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2649':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }  
          var obj = document.getElementById('CSHI_oxygen');
          if (obj != null) // КИСЛОРОД ЦШИ
          {
            switch (ID)
            {
              case '2447':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
//                obj.rows[2].cells[2].innerHTML = 4098; // Код ошибки
                break;
              case '2390':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '326':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2698':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }  
          var obj = document.getElementById('CSI_oxygen');
          if (obj != null) // КИСЛОРОД ЦСИ
          {
            switch (ID)
            {
              case '2342':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2442':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2443':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2615':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }  
          var obj = document.getElementById('CSI_steam');
          if (obj != null) // ПАР ЦСИ
          {
            switch (ID)
            {
              case '2420':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура прямой 
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2430':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2691':
                obj.rows[4].cells[1].innerHTML = value+" т/ч"; // Расход 
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2693':
                obj.rows[5].cells[1].innerHTML = value+" Гкал/ч"; // Тепловая мощность
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }          
		  var obj = document.getElementById('CSI_steam_teh');
          if (obj != null) // ПАР ЦСИ технология
          {
            switch (ID)
            {
              case '2239':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура прямой 
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2238':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2240':
                obj.rows[4].cells[1].innerHTML = value+" т/ч"; // Расход 
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2246':
                obj.rows[5].cells[1].innerHTML = value+" Гкал/ч"; // Тепловая мощность
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }          
          var obj = document.getElementById('CMDO_natural_gas');
          if (obj != null) // ПРИРОДНЫЙ ГАЗ ЦМДО
          {
            switch (ID)
            {
 /*           case '222':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '221':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '220':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '223':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
              break;*/

/*              case '607':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '592':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '591':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;*/
              case '595':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
			}
          }          
          var obj = document.getElementById('CSHI_natural_gas');
          if (obj != null) // ПРИРОДНЫЙ ГАЗ ЦШИ (высокая сторона)
          {
            switch (ID)
            {
              case '2642':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2643':
                obj.rows[3].cells[1].innerHTML = value+" МПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '3007':
                obj.rows[4].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }          
          var obj = document.getElementById('CSHI_koks_gas_rorate_furn_1');
          if (obj != null) // КОКСОВЫЙ ГАЗ ВРАЩ. ПЕЧЬ 1
          {
            switch (ID)
            {
              case '2369':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;
              case '2448':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;
              case '325':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;
              case '3008':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }          
          var obj = document.getElementById('CSHI_koks_gas_rorate_furn_2');
          if (obj != null) // КОКСОВЫЙ ГАЗ ВРАЩ. ПЕЧЬ 2
          {
            switch (ID)
            {
              case '2370':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2449':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '24': 
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '3009':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }          
          var obj = document.getElementById('CSHI_koks_gas_dry_1');
          if (obj != null) // КОКСОВЫЙ ГАЗ СУШ. БАРАБАН 1
          {
            switch (ID)
            {
              case '94':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '95':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '96': 
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '322':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }          
          var obj = document.getElementById('CSHI_koks_gas_dry_2');
          if (obj != null) // КОКСОВЫЙ ГАЗ СУШ. БАРАБАН 2
          {
            switch (ID)
            {
              case '323':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2383':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2384':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2395':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }          
          var obj = document.getElementById('CSHI_koks_gas_dry_3');
          if (obj != null) // КОКСОВЫЙ ГАЗ СУШ. БАРАБАН 3
          {
            switch (ID)
            {
              case '2396':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2397':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '3183':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '3185':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }          
          var obj = document.getElementById('CSHI_koks_gas_dry_dpu_csi');
          if (obj != null) // КОКСОВЫЙ ГАЗ СУШ. БАРАБАНЫ ДПУ ЦСИ
          {
            switch (ID)
            {
              case '2488':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2487':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '1945':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '1944':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }
          var obj = document.getElementById('CSI_koks_gas_dry_dp_and_fu_csi');
          if (obj != null) // КОКСОВЫЙ ГАЗ СУШ. БАРАБАНЫ ДПУ ЦСИ
          {
            switch (ID)
            {
              case '2423':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2433':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2348':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '3332':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }
					var obj = document.getElementById('CSHI_koks_gas_tp1_reserve');
          if (obj != null) // Коксовый газ на туннел.печи ЦШИ (резервный газопровод)
          {
            switch (ID)
            {
              case '3240':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '67':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '68':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '328':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }
		  var obj = document.getElementById('CSI_koks_gas_forge_furn');
          if (obj != null) // Коксовый газ на кузнечную печь ЦСИ
          {
            switch (ID)
            {
              case '2088':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2087':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2086':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2090':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }
		  var obj = document.getElementById('CSHI_rotary_furn');
		  if (obj != null) // Вращающиеся речи ЦШИ
          {
			switch (ID)
            {
				case '3211':
					obj.rows[1].cells[1].innerHTML = value+" Па";
				break;
				case '3212':
					obj.rows[2].cells[1].innerHTML = value+" Па";
				break;
				case '3213':
					obj.rows[3].cells[1].innerHTML = value+" Па";
				break;
				case '3208':
					obj.rows[4].cells[1].innerHTML = value+" Па";
				break;
				case '3209':
					obj.rows[5].cells[1].innerHTML = value+" Па";
				break;
				case '3210':
					obj.rows[6].cells[1].innerHTML = value+" Па";
				break;
				case '3214':
					obj.rows[7].cells[1].innerHTML = value+" Па";
				break;
				case '3217':
					obj.rows[8].cells[1].innerHTML = value+" <sup>o</sup>C";
				break;
				case '3215':
					obj.rows[9].cells[1].innerHTML = value+" <sup>o</sup>C";
				break;
				case '3216':
					obj.rows[10].cells[1].innerHTML = value+" <sup>o</sup>C";
				break;
				case '3220':
					obj.rows[11].cells[1].innerHTML = value+" <sup>o</sup>C";
				break;
				case '3221':
					obj.rows[12].cells[1].innerHTML = value+" <sup>o</sup>C";
				break;
				case '3218':
					obj.rows[13].cells[1].innerHTML = value+" <sup>o</sup>C";
				break;
				case '3219':
					obj.rows[14].cells[1].innerHTML = value; 
				break;
				case '3222':
					obj.rows[15].cells[1].innerHTML = value+" В"; 
				break;
				case '3224':
					obj.rows[16].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; 
				break;
			}
          }
		  var obj = document.getElementById('CSI_koks_gas_heating_fu_1_2');
          if (obj != null) // Коксовый газ Нагревательные печи №1 и №2 термического участка РМУ ЦСИ
          {
            switch (ID)
            {
              case '2441':
                obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C"; // Температура
                obj.rows[2].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2440':
                obj.rows[3].cells[1].innerHTML = value+" кПа"; // Давление
                obj.rows[3].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2091':
                obj.rows[4].cells[1].innerHTML = value+" кПа"; // Перепад давления
                obj.rows[4].cells[2].innerHTML = status; // Код ошибки
                break;   
              case '2445':
                obj.rows[5].cells[1].innerHTML = value+" м<sup>3</sup>/ч"; // Расход 
                obj.rows[5].cells[2].innerHTML = status; // Код ошибки
                break;
            }
          }		  
		  var obj = document.getElementById('CSHI_desiccator1');
          if (obj != null) // 
          {
            switch (ID)
            {
				case '3174':
					obj.rows[1].cells[1].innerHTML = value+" <sup>o</sup>C"; 
				break;
				case '3175':
					obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C";
                break;
				case '3176':
					obj.rows[3].cells[1].innerHTML = value+" Па";
                break;
            }
          }
		  var obj = document.getElementById('CSHI_desiccator2');
          if (obj != null) // 
          {
            switch (ID)
            {
				case '3177':
					obj.rows[1].cells[1].innerHTML = value+" <sup>o</sup>C"; 
				break;
				case '3178':
					obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C";
                break;
				case '3179':
					obj.rows[3].cells[1].innerHTML = value+" Па";
                break;
            }
          }
		  var obj = document.getElementById('CSHI_desiccator3');
          if (obj != null) // 
          {
            switch (ID)
            {
				case '3180':
					obj.rows[1].cells[1].innerHTML = value+" <sup>o</sup>C"; 
				break;
				case '3181':
					obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C";
                break;
				case '3182':
					obj.rows[3].cells[1].innerHTML = value+" Па";
                break;
            }
          }
		  var obj = document.getElementById('CSI_desiccator1');
          if (obj != null) // 
          {
            switch (ID)
            {
				case '1890':
					obj.rows[1].cells[1].innerHTML = value+" <sup>o</sup>C"; 
				break;
				case '1891':
					obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C";
                break;
				case '1889':
					obj.rows[3].cells[1].innerHTML = value+" Па";
                break;
				case '1888':
					obj.rows[4].cells[1].innerHTML = value+" м<sup>3</sup>/ч";
                break;
            }
          }
		  var obj = document.getElementById('CSI_desiccator2');
          if (obj != null) // 
          {
            switch (ID)
            {
				case '1899':
					obj.rows[1].cells[1].innerHTML = value+" <sup>o</sup>C"; 
				break;
				case '1900':
					obj.rows[2].cells[1].innerHTML = value+" <sup>o</sup>C";
                break;
				case '1893':
					obj.rows[3].cells[1].innerHTML = value+" Па";
                break;
				case '1892':
					obj.rows[4].cells[1].innerHTML = value+" м<sup>3</sup>/ч";
                break;
            }
          }
		  var obj = document.getElementById('outside_temp');
          if (obj != null) // 
          {
			if (ID=="temp_outside")
			{
				obj.innerHTML=Math.round(value)+"°C";
			}
		  }
		  var obj = document.getElementById('total_oxygen');
          if (obj != null) // 
          {
			if (ID=="oxygen_total")
			{
				obj.rows[0].cells[1].innerHTML = value+" м<sup>3</sup>/ч";
			}
		  }
		  var obj = document.getElementById('total_koks_gas');
          if (obj != null) // 
          {
            switch (ID)
            {
				case 'koks_gas_cshi':
					obj.rows[0].cells[1].innerHTML = value+" м<sup>3</sup>/ч";
				break;
				case 'koks_gas_csi':
					obj.rows[1].cells[1].innerHTML = value+" м<sup>3</sup>/ч";
				break;
				case 'koks_gas_total':
					obj.rows[2].cells[1].innerHTML = value+" м<sup>3</sup>/ч";
				break;
            }
          }
		  var obj = document.getElementById('total_natural_gas');
          if (obj != null) // 
          {
			if (ID=="natural_gas_total")
			{
				obj.rows[0].cells[1].innerHTML = value+" м<sup>3</sup>/ч";
			}
		  }
		  var obj = document.getElementById('total_compressed_air');
          if (obj != null) // 
          {
			if (ID=="compressed_air_total")
			{
				obj.rows[0].cells[1].innerHTML = value+" м<sup>3</sup>/ч";
			}
		  }
		  var obj = document.getElementById('total_heating_water');
          if (obj != null) // 
          {
			if (ID=="heating_water_total")
			{
				obj.rows[0].cells[1].innerHTML = value+"";
			}
		  }
		  var obj = document.getElementById('electro');
          if (obj != null) // 
          {
            switch (ID)
            {
				case '181':
					obj.rows[0].cells[1].innerHTML = value+" у. е.";
				break;
				case '183':
					obj.rows[1].cells[1].innerHTML = value+" у. е.";
				break;
				case '184':
					obj.rows[2].cells[1].innerHTML = value+" у. е.";
				break;
				case '185':
					obj.rows[3].cells[1].innerHTML = value+" у. е.";
				break;
				case '186':
					obj.rows[4].cells[1].innerHTML = value+" у. е.";
				break;
				case '187':
					obj.rows[5].cells[1].innerHTML = value+" у. е.";
				break;
				case '188':
					obj.rows[6].cells[1].innerHTML = value+" у. е.";
				break;
				case '189':
					obj.rows[7].cells[1].innerHTML = value+" у. е.";
				break;
				case '190':
					obj.rows[8].cells[1].innerHTML = value+" у. е.";
				break;
				case '191':
					obj.rows[9].cells[1].innerHTML = value+" у. е.";
				break;
				case '192':
					obj.rows[10].cells[1].innerHTML = value+" у. е.";
				break;
				case '193':
					obj.rows[11].cells[1].innerHTML = value+" у. е.";
				break;
				case '194':
					obj.rows[12].cells[1].innerHTML = value+" у. е.";
				break;
            }
          }
          var obj = document.getElementById('drinking_water_1');
          if (obj != null) //
          {
            switch (ID)
            {
              case '2502': //Номер ID из БД АСКУ ТЭР
                obj.rows[2].cells[1].innerHTML = value+" Па";
                break;
              case '2503':
                obj.rows[3].cells[1].innerHTML = value+" м3/ч";
                break;
            }
          }
          var obj = document.getElementById('drinking_water_2');
          if (obj != null) //
          {
            switch (ID)
            {
              case '181': //Номер ID из БД АСКУ ТЭР
                obj.rows[2].cells[1].innerHTML = value+" Па";
                break;
              case '181':
                obj.rows[3].cells[1].innerHTML = value+" м3/ч";
                break;
            }
          }
          var obj = document.getElementById('drinking_water_3');
          if (obj != null) //
          {
            switch (ID)
            {
              case '181': //Номер ID из БД АСКУ ТЭР
                obj.rows[2].cells[1].innerHTML = value+" Па";
                break;
              case '181':
                obj.rows[3].cells[1].innerHTML = value+" м3/ч";
                break;
            }
          }
          var obj = document.getElementById('drinking_water_4');
          if (obj != null) //
          {
            switch (ID)
            {
              case '181': //Номер ID из БД АСКУ ТЭР
                obj.rows[2].cells[1].innerHTML = value+" Па";
                break;
              case '181':
                obj.rows[3].cells[1].innerHTML = value+" м3/ч";
                break;
            }
          }
          var obj = document.getElementById('drinking_water_5');
          if (obj != null) //
          {
            switch (ID)
            {
              case '181': //Номер ID из БД АСКУ ТЭР
                obj.rows[2].cells[1].innerHTML = value+" Па";
                break;
              case '181':
                obj.rows[3].cells[1].innerHTML = value+" м3/ч";
                break;
            }
          }
          var obj = document.getElementById('drinking_water_6');
          if (obj != null) //
          {
            switch (ID)
            {
              case '181': //Номер ID из БД АСКУ ТЭР
                obj.rows[2].cells[1].innerHTML = value+" Па";
                break;
              case '181':
                obj.rows[3].cells[1].innerHTML = value+" м3/ч";
                break;
            }
          }
        }
      }
//------------------------------------------------------------------------------
      function processRequestChange()
      {
        if (request.readyState == 4)
        {
          //alert('ответ:'+request.responseText);
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
//          request.open("POST", "pfu_connection_to_webserver.php?mnemo=4", false); 
          request.open("POST", "/ASUTP/php/asku_currents/AddMoreInformation.php", false); 
          request.send(null);
//          alert('request');          
        }
        else
          alert('ошибка при создании xmlrequest!');
        setTimeout('SendRequest()', 2000); 
      }
//------------------------------------------------------------------------------
      function Click(obj, table_name, cell_num) {
          var value = obj.innerHTML;
          var index = 3;
          var param = "";
          var obj = document.getElementById(table_name);
          // последовательнный перебор всех рядов в каждой таблице
          param = obj.rows[2+cell_num].cells[0].innerHTML;
          CreateAlarmWindow(value, table_name, param);
      }      
//------------------------------------------------------------------------------
      function over(elem, backGrcolor) {
          elem.style.backgroundColor = backGrcolor
          elem.style.color = 'red'
      }
//------------------------------------------------------------------------------
      function out(elem) {
          elem.style.backgroundColor = ''
          elem.style.color = 'red'
      }
//------------------------------------------------------------------------------
      function CreateAlarmWindow(value, table_name, param) {
          var ErrorMassive = new Array ()
          var index = 0
          var message
          
          if (value & 1) {
            ErrorMassive[index] = "Выключение питание, отказ платы преобразователя \n"
            index++
          }
          if (value & 2) {
            ErrorMassive[index] = "Выход значения за пределы \n"
            index++
          }
          if (value & 4) {
            ErrorMassive[index] = "Ошибка при преобразовании по формуле \n"
            index++
          }
          if (value & 8) {
            ErrorMassive[index] = "Ошибка в значении по ссылке формуле \n"
            index++
          }
          if (value & 16) {
            ErrorMassive[index] = "Первый интервал после инициализации архивов \n"
            index++
          }
          if (value & 32) {
            ErrorMassive[index] = "Сдвиг времени (по команде ЦК) \n"
            index++
          }
          if (value & 64) {
            ErrorMassive[index] = "Данные пока не готовы, опрос следует повторить позже \n"
            index++
          }
          if (value & 128) {
            ErrorMassive[index] = "Канал не описан (не корректно описан) в конфигурации \n"
            index++
          }
          if (value & 256) {
            ErrorMassive[index] = "Перезагрузка по команде \n"
            index++
          }
          if (value & 512) {
            ErrorMassive[index] = "Временный характер отказа \n"
            index++
          }
          if (value & 1024) {
            ErrorMassive[index] = "Выход за пределы применимости формулы \n"
            index++
          }
          if (value & 2048) {
            ErrorMassive[index] = "Выход за верхний предел \n"
            index++
          }
          if (value & 1024) {
            ErrorMassive[index] = "Выход за нижний предел \n"
            index++
          }
          if (value & 4096) {
            ErrorMassive[index] = "Специальный режим (глобальная авария) \n"
            index++
          }
          if (value & 8192) {
            ErrorMassive[index] = "Ручной ввод данных \n"
            index++
          }
          if (value & 16384) {
            ErrorMassive[index] = "- \n"
            index++
          }
          if (value & 32768) {
            ErrorMassive[index] = "- \n"   
            index++
          }        
          if (index > 0) {
            message = "Параметр:     " + param + "\n"
            message += "Код ошибки:   " + value + "\n\n"
            message += "Расшифровка кода ошибки: \n"
            for (var i = 0; i < index; i++) {
              message += i+1 + ")  " + ErrorMassive[i]
            }
            window.alert(message)
          } 
      } 
//------------------------------------------------------------------------------
/*      $(document).ready(function()
      {
         $("#NavigationBar2 .navbar a").hover(function()
         {
            $(this).children("span").stop().fadeTo(100, 0);
         }, function()
         {
            $(this).children("span").stop().fadeTo(100, 1);
         })
      });			*/