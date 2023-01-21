var cmThemeRibbonBase = '.';

try
{
	if (myThemeRibbonBase)
	{
		cmThemeRibbonBase = myThemeRibbonBase;
	}
}
catch (e)
{
}

var cmThemeRibbon =
{
  	    mainFolderLeft: '<div style="width: 14px; height: 28px" class="themeSpacerDiv" />',
        mainFolderRight: '<div style="width: 11px; height: 28px" class="themeSpacerDiv" />',
        mainItemLeft: '<div style="width: 14px; height: 28px" class="themeSpacerDiv" />',
        mainItemRight: '<div style="width: 11px; height: 28px" class="themeSpacerDiv" />',
        folderLeft: '<div style="width: 24px; height: 23px" class="themeSpacerDiv" />',
        folderRight: '<div style="width: 14px; height: 23px" class="themeSpacerDiv" />',
        itemLeft: '<div style="width: 24px; height: 23px" class="themeSpacerDiv" />',
        itemRight: '<div style="width: 14px; height: 23px" class="themeSpacerDiv" />',
        mainSpacing: 0,
        subSpacing: 0,
        delay: 100
};

var cmThemeRibbonHSplit = [_cmNoClick, '<td  class="ThemeRibbonMenuSplitLeft"><div></div></td>' +
					                          '<td  class="ThemeRibbonMenuSplitText"><div></div></td>' +
					                          '<td  class="ThemeRibbonMenuSplitRight"><div></div></td>'
		                         ];

var cmThemeRibbonMainVSplit = [_cmNoClick, '<div>' +
                            '<table height="30" width="10" ' +
                            ' cellspacing="0"><tr><td class="ThemeRibbonHorizontalSplit">' +
                           '|</td></tr></table></div>'];

var cmThemeRibbonMainHSplit = [_cmNoClick, '<td  class="ThemeRibbonMainSplitLeft"><div></div></td>' +
					                          '<td  class="ThemeRibbonMainSplitText"><div></div></td>' +
					                          '<td  class="ThemeRibbonMainSplitRight"><div></div></td>'
		                           ];    
 
     