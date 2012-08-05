/***********************************************************************************
*	(c) Ger Versluis 2000 version 5.411 24 December 2001 (updated Jan 31st, 2003 by Dynamic Drive for Opera7)
*	For info write to menus@burmees.nl		          *
*	You may remove all comments for faster loading	          *		
***********************************************************************************/
	var $site_url='http://d3dev';
	var $offsetX = 155;
	var $offsetY = 95;
	if(! $pageX) var $pageX	= 0;
	if(! $pageY) var $pageY	= 0;
	if(0) alert($cat_pid);
	if(0) alert($pid);
	if(0) alert($gFlgAdmin);


	if($pageX != 0) $offsetX = $pageX;
	if($pageY != 0) $offsetY = $pageY;

	if(0) alert("page X:Y -- " + $pageX + "," + $pageY);
	if(0) alert("X:Y -- " + $offsetX + "," + $offsetY);

	if($gFlgAdmin) var NoOffFirstLineMenus=8;			// Number of first level items
	else var NoOffFirstLineMenus=7;			// Number of first level items
	var NoOffFirstLineMenus=8;			// Number of first level items
	var LowBgColor='#0033cC';			// Background color when mouse is not over
	var LowSubBgColor='white';			// Background color when mouse is not over on subs
	var HighBgColor='blue';				// Background color when mouse is over
	var HighSubBgColor='blue';			// Background color when mouse is over on subs
	var FontLowColor='white';			// Font color when mouse is not over
	var FontSubLowColor='navy';			// Font color subs when mouse is not over
	var FontHighColor='gold';			// Font color when mouse is over
	var FontSubHighColor='gold';			// Font color subs when mouse is over
	var BorderColor='navy';				// Border color
	var BorderSubColor='blue';			// Border color for subs
	var BorderWidth=1;				// Border width
	var BorderBtwnElmnts=1;				// Border between elements 1 or 0
	var FontFamily="arial,comic sans ms,technical"	// Font family menu items
	var FontSize=9;					// Font size menu items
	var FontBold=1;					// Bold menu items 1 or 0
	var FontItalic=0;				// Italic menu items 1 or 0
	var MenuTextCentered='left';			// Item text position 'left', 'center' or 'right'
	var MenuCentered='left';			// Menu horizontal position 'left', 'center' or 'right'
	var MenuVerticalCentered='top';		// Menu vertical position 'top', 'middle','bottom' or static
	var ChildOverlap=.2;				// horizontal overlap child/ parent
	var ChildVerticalOverlap=.2;			// vertical overlap child/ parent
	var StartTop=$offsetY;				// Menu offset y coordinate
	var StartLeft=$offsetX;				// Menu offset x coordinate
	var VerCorrect=0;				// Multiple frames y correction
	var HorCorrect=0;				// Multiple frames x correction
	var LeftPaddng=3;				// Left padding
	var TopPaddng=2;				// Top padding
	var FirstLineHorizontal=1;			// SET TO 1 FOR HORIZONTAL MENU, 0 FOR VERTICAL
	var MenuFramesVertical=1;			// Frames in cols or rows 1 or 0
	var DissapearDelay=500;				// delay before menu folds in
	var TakeOverBgColor=1;				// Menu frame takes over background color subitem frame
	var FirstLineFrame='navig';			// Frame where first level appears
	var SecLineFrame='space';			// Frame where sub levels appear
	var DocTargetFrame='space';			// Frame where target documents appear
	var TargetLoc='';				// span id for relative positioning
	var HideTop=0;					// Hide first level when loading new document 1 or 0
	var MenuWrap=0;					// enables/ disables menu wrap 1 or 0
	var RightToLeft=0;				// enables/ disables right to left unfold 1 or 0
	var UnfoldsOnClick=1;				// Level 1 unfolds onclick/ onmouseover
	var WebMasterCheck=1;				// menu tree checking on or off 1 or 0
	var ShowArrow=1;				// Uses arrow gifs when 1
	var KeepHilite=1;				// Keep selected path highligthed
	var Arrws=['/_lib/hvmenu/tri.gif',5,10,'/_lib/hvmenu/tridown.gif',10,5,'/_lib/hvmenu/trileft.gif',5,10];	// Arrow source, width and height

function BeforeStart(){return}
function AfterBuild(){return}
function BeforeFirstOpen(){return}
function AfterCloseAll(){return}


// Menu tree
//	MenuX=new Array(Text to show, Link, background image (optional), number of sub elements, height, width);
//	For rollover images set "Text to show" to:  "rollover:Image1.jpg:Image2.jpg"

Menu1=new Array("Home","http://d3dev/","",0,20,80);

Menu2=new Array("News","/News");

Menu3=new Array("About Us","blank.htm","",6,20,110);
	Menu3_1=new Array("Dwyer & Associates","/About_Us/","",0,20,180);
	Menu3_2=new Array("Administration","/About_Us/administration.php","",0);
	Menu3_3=new Array("Contact Us","/About_Us/contact_us.php","",0);
	Menu3_4=new Array("Employment Opportunities","/About_Us/employment_opp.php","",0);
	Menu3_5=new Array("Markets","/About_Us/markets.php","",0);
	Menu3_6=new Array("Our Promise","/About_Us/our_promise.php","",0);

Menu4=new Array("Products","/Products","",7,20,110);
	Menu4_1=new Array("Products Home","/Products","",0,20,200);
	Menu4_2=new Array("Dwyer Specialties","/Products/dwyer","",0);
	Menu4_3=new Array("E & O","/Products/e_o","",0);
	Menu4_4=new Array("D & O","/Products/d_o","",0);
	Menu4_5=new Array("Casualty","/Products/casualty","",0);
	Menu4_6=new Array("Property","/Products/property","",0);
	Menu4_7=new Array("EPLI","/Products/epli","",0);

Menu5=new Array("Quotes","blank.htm","",1);
	Menu5_1=new Array("Online Quick Quotes","/Quotes","",0,20,140);


Menu6=new Array("Search","blank.htm","",3);
	Menu6_1=new Array("Quick Search","/Search","",0,20,140);
	Menu6_2=new Array("Full Text Search","/Search/full_text.php","",0);
	Menu6_3=new Array("Forms Library","/FORMS","",0);

Menu7=new Array("Login","/ACL/login.php","",0,20,80);

if($gFlgAdmin == 1){
Menu8=new Array("Admin","blank.htm","",3);
	Menu8_1=new Array("Category Admin","/_todos/cat_admin.php?cat_pid=" + $cat_pid,"",0,20,140);
	Menu8_2=new Array("Record Edit","/_todos/rec_edit.php?rec_pid=" + $pid,"",0);
	Menu8_3=new Array("Marketing","/_todos/cat_admin?cat_pid=/Marketing/idx","",0);
}
else{
	Menu8=new Array("Null","/blank.htm","",0,20,1);
}

