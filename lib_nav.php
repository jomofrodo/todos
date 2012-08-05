<?php

/*###############################################################
##  php navigation library for dwyerinsur.com
#
#	
#	function setPageDocs($pag(_id,&$pd_html){
#	function setNavVars(){
#	function setNavGlobal($dir){
#	function setNavSection($dir){
#	function setPageTitle($file){
#	function setPageShortTitle($title){
#	function gNavLink($nav_section){
#	function arrowIf($nav_section){
#	function gNavName($nav_section){
#
#	Navigation Bars
#	function navProducts($nav_bar){
#	function getNavPage($nav_page_id,$flgPrint,&$flgSide)
#	function getNavSection($nav_section)
#################################################################*/

//print("Hello World"); 

//include_once(dirname(__FILE__)."/todosInclude.php");
//include_once(dirname(__FILE__)."/lib_todos.php");


if(!@$gDBtd->PConnect($DB_LOCATION, $DB_ACCOUNT, $DB_PASSWORD, $DB_DATABASE)) { lib_login_db_failure(); }
/*============================ GLOBAL-STUFF ===========================*/
/*============================ ^^^^^^^^^^^^ ==========================*/

GLOBAL	  $nav_global; 		// global navigational section name
GLOBAL    $nav_section;		// navigational sub-section name (e.g., "Products")
GLOBAL    $page_title;		// Name of the page (converted from file name)
GLOBAL    $page_short_title;	// The short display name of the page
GLOBAL    $page_id;		// unique id for the page (relative URL)
GLOBAL    $nav_page_id;		// ID for determining nav structure for current page
GLOBAL    $page_sidebar;	// not used
GLOBAL    $page_docs;		// Subdocs linked to this page
GLOBAL    $page_apps;		// Applications linked to this page
GLOBAL	  $cat_pid;		// category pid



//################################################################
function setPageDocs($page_id,&$pd_html,$td_type='x',$td_class='x',$pname=''){
//Get the list of documents linked to this page from the  todos db
//Write these out in an html list
//return the list

  GLOBAL $gDBtd;

//Default td_type is the DOC type
if ($td_type=='x'){$td_type=IDX_3;}
if ($td_class=='x'){$td_class=EO_CLASS_SUBDOC;}
if ($page_id==""){$page_id = "/";}

	### Using the idx3_class tables !!!
	$tbl = '';
	if(IDX3_CLASS_TABLES){
		$tbl_name = IDX_3 . "_" . $td_class;
		$tbl = $_SESSION[$tbl_name];
		if(! $tbl){
                	$flgExists = utilTableExists($tbl,$gDBtd);
			if(! $flgExists){ $tbl = '';}
			else{
			 	$tbl = $tbl_name;
				$_SESSION[$tbl_name] = $tbl_name;	
			}
		}
	}
	if(! $tbl) $tbl = 'tblTodos';

//############
// DEBUG
//$page_id = "page1";
//############

  $db = $gDBtd;

//       $sql_a =<<<SQL
//		SELECT  *
//		from 	tbl_users;
//SQL;
       $sql_a =<<<SQL
		SELECT  tdURL, tdTitle
		FROM 	$tbl
		WHERE	tdPageID = '$page_id' 
		AND	tdType 	 LIKE '$td_type'
		AND	tdClass	 =  '$td_class'
SQL;
	if($pname) $sql_a .= " AND pName LIKE '$pname%'\n";
	$sql_a .= " ORDER BY tdTitle\n";

        $result = $db->Execute($sql_a);
	if(0) print_r("PageDocs SQL: $sql_a <BR>");
	//print_r("Result: $result"); exit;
	$num_rows =  $result->_numOfRows; 
	if ($num_rows==0){return($num_rows);}
	$pd_html = "<UL>\n";
	while(!$result->EOF) {
		$url = $result->fields['tdURL'];
		$title = $result->fields['tdTitle'];
		#$url = $result->fields[0];
		if(! $title) $title = todosGetVal($url,$td_type,$td_class,$pname);
		if(! $title) $title = todosGetVal($url,'',$td_class,TD_PNAME_TITLE,TD_VALTYPE_FLD_TDTITLE);
		//Write out the HTML line for each member of rs
		$pd_html .= "<LI><A href=\"" . $url ."\">";
		$pd_html .= $title;
		$pd_html .= "</A></LI>\n";
		$result->MoveNext();
	}
	$pd_html .= "</UL>\n";
	//print $pd_html;
	return($num_rows);
}
//################################################################
function setNavVars($flgSide=0){
//set all the Nav elements for the page
// based on the URL and passed in variables
GLOBAL	  $nav_global;
GLOBAL    $nav_section;
GLOBAL    $page_title;
GLOBAL    $page_short_title;
GLOBAL    $page_id;
GLOBAL    $nav_page_id;
GLOBAL    $page_sidebar;
GLOBAL    $page_docs;
GLOBAL	  $page_apps;
GLOBAL	  $navSection;
GLOBAL	  $navPage;
GLOBAL	  $navSide;
GLOBAL	  $cat_pid;

   if(0) print("setNavVars(0): $flgSide,$flgSide2,$flgSide3,$global_id,$page_id,$cat_pid<br>");

  //$url = sprintf("%s%s%s","http://",$HTTP_HOST,$REQUEST_URI);
  $url = $_SERVER["REQUEST_URI"];
  // Add "index.php" or whatever to non-specified directory URIs
  if (preg_match('/.*\/$/',$url)) $url .= HTTP_INDEX;
  if(0) print("URL: $url<br>");
  $url = parse_url($url);
  if(0) var_dump($url);
  $page_id = $url{"path"};	//Relative path to the file
  $str_Application_Class = 'application';	// <MAGIC STRING>

  //print_r ("PageID: $page_id <BR>");

  $path = pathinfo($url["path"]);
  $dir 	= $path["dirname"];
  $file = $path["basename"];
  $ext 	= $path["extension"];
 
if(0){
  print_r ("Path: $path <BR>");
  print_r ("Dir: $dir <BR>");
  print_r ("File: $file <BR>");
  print_r ("Ext: $ext <BR>");
}

  $char0 = substr($page_id,0,1);
  if ($char0 != "/"){$page_id = ("/" . $page_id);} 

  ($nav_global) ? $x=1		:  	setNavGlobal($dir);
  ($nav_section) ? $x=1		:  	setNavSection($dir);
  ($page_title) ? $x=1		: 	setPageTitle($page_id,$file);
  ($page_short_title) ? $x=1	: 	setPageShortTitle($page_title);
//($page_sidebar) ? $x=1 	: 	setPageSideBar($file);
  ($page_docs) ? $x=1		: 	setPageDocs($page_id,$page_docs);
//  ($page_apps) ? $x=1		: 	setPageDocs($page_id,$page_apps,'idx3',EO_CLASS_APPS);
  ($page_apps)	? $x=1		:	setPageDocs($page_id,$page_apps,IDX_3,EO_CLASS_PRODUCT,
						$str_Application_Class);
  ($nav_page_id) ? $x=1		:	$nav_page_id = $page_id;

  $global_id = "/" . $nav_global . "/idx";
  if(0) print("setNavVars(1): $flgSide,$global_id,$page_id,$cat_pid<br>");
  $cat_pid = todosGetCatPID();
   if(0) print("cat_pid: $cat_pid<br>");

  ## Set Nav Bars
	$navSection = getNavSection($page_id,$cat_pid,0,$flgSide);
	//$navPage = getNavPage($page_id,$cat_pid,0,$flgSide,$flgSide3);
    	//if($cat_pid) $navSide  = todosSelectStruct($cat_pid,$cat_pid,'subnav','cat_pid',0,0,'111111','0',$onchange,'5',0,-1); 
     ## Side Nav
	if($flgSide){ $navSide = getNavSide($global_id,$cat_pid,0);}

     if(0)	print("setNavVars(2): $flgSide,$global_id,$page_id,$cat_pid<br>");
		

if(0) print("setNavVars: $page_id,$nav_global,$nav_section,$page_title,$page_short_title,$cat_pid<br>");
  
	
  return(0);

}

//################################################################
function setNavGlobal($dir){
//set Global Nav heading
// based on the URL and passed in variables
  GLOBAL $nav_global; 
 if(0) var_dump("$dir<br>");
  $dir = explode('/',$dir);   	//turn it into an array
  $dir =  $dir[1]; 	//get the root level directory

   $nav_global = $dir;
//  switch($dir) {
//	case "" 	:	$nav_global = "Home"; break;
//	case "News" 	:	$nav_global = "News"; break;
//	case "About_Us" :	$nav_global = "About_Us"; break;
//	case "News"	: 	$nav_global = "News"; break;
//	case "Search" 	:	$nav_global = "Search"; break;
//	case "ACL" 	:	$nav_global = "Login"; break;
//	default:		$nav_global = $dir;
// }
  if(0) print("nav_global: $nav_global<br>");
  return $nav_global;
}
//################################################################
function setNavSection($dir){
//set  Nav section
// based on the directory of the file
  GLOBAL $nav_global; 
  GLOBAL $nav_section; 
  $nav_section = explode('/',$dir);  
  $nav_section=$nav_section[2];    //always set to second directory from root
  if ($nav_section == ""){
	$nav_section = $nav_global;
  }
  $nav_section = strtoupper($nav_section);
  $nav_section = preg_replace('/_/'," ",$nav_section);
  $nav_section = strtoupper($nav_section);
  switch($nav_section) {
	case "" 	:	$nav_section = "Home"; break;
	case "E O"	:	$nav_section = "E & O"; break;
	case "D O" 	:	$nav_section = "D & O"; break;
  }
	if(0) print("setNavSection: $nav_section<br>");
  return($nav_section);
}
//################################################################
function setPageTitle($page_id,$file){
//set Page Title based on the name of the file
//
  GLOBAL $nav_global; 
  GLOBAL $page_title; 

	$page_title = todosGetTitle($page_id);
	if(0) print("setPageTitle: todosTitle: $page_title<br>");
	//var_dump($page_id);
	//var_dump($page_title);exit;
	if($page_title){return($page_title);}

	// If no idx0 tdTitle, calculate from file name

  $page_title = explode('.',$file);  
  $page_title=$page_title[0];    	//always set to first part of file name
  $pattern = "/_/";			//swap spaces for underscores
  $replace = " ";
  $page_title = preg_replace($pattern,$replace,$page_title);
  $page_title = ucwords($page_title);
  return($page_title);
}
//################################################################
function setPageShortTitle($title){
//set Page Short Title based on the title
//
  GLOBAL $nav_global; 
  GLOBAL $page_short_title; 
  $t = $title;
  $st = $t;
  // *******  FOR NOW, SHORT TITLE SAME AS TITLE ************ //
  //$t = explode(' ',$title);  
  //$t=$t[0];    	//always set to first word in the title 
  //$page_short_title = ucwords($t);

  $st = $t;
  $ret = preg_match("/([\w]+).*/",$st,$amatch);
	$st = $amatch[1];
	if(0) var_dump($amatch);
	if(0) print("<br>Short Title: $st<br>");
	$page_short_title = $st;
  return($st);
}
//################################################################
function gNavLink($nav_section,$menu='',$x='',$y='',$nav_name='',$nav_link=''){
  //Print a link to a global nav section
  //Switch the $nav_link based on the global tag
	//print("\n" .$nav_global."\n");
	//print("<BR>");
	//exit;

GLOBAL $nav_global;

  if(! $nav_link){
   switch($nav_section) {
	case "Home"	: $nav_link = "/"; break;
	case "Products"	: $nav_link = "/Products/"; break;
	case "About_Us"	: $nav_link = "/About_Us/"; break;
	case "News"	: $nav_link = "/News/"; break;
	case "Search"	: $nav_link = "/Search/"; break;
	case "Login"	: $nav_link = "/ACL/login.php"; break;
	case "ACL"	: $nav_link = "/ACL/login.php"; break;
	default	: 	  $nav_link = "/" . $nav_section;
  }
 }
  if($menu){$nav_link = "javascript:void()";}
  $imgsrc = arrowIf($nav_section);
  if(! $nav_name) $nav_name = gNavName($nav_section);
  if($nav_name == ''){$nav_name = $nav_section;}
  ## Set Arrow and highlight color
  $match = "/" . strtoupper($nav_name) ."/";
  $match = preg_replace('/ /', '_', $match);
  if(preg_match($match,strtoupper($nav_global))){
      $nav_name = ("<font class=topnav_a>" . $nav_name . "</font>");
  }
  $html = "<img name='goldarrow' src=" . $imgsrc ;
  $html .= "><a href=\"" . $nav_link . "\" " ;
  if($menu){	$html .= " menu=" . $menu;}
  if($x){	$html .= " x=" .$x;}
  if($y){	$html .= " y=" .$y;}
  $html .= " onmouseover='closeAll();'";
  $html .= " class=topnav>" . $nav_name . "</a>&nbsp;&nbsp;\n";

  ##if(0) print("gNavLink: $nav_section,$nav_link,$nav_name,$nav_global<br>");
  print($html);
}
//################################################################
function getNavSide($page_id,$cat_pid,$flgPrint=1){
// Print a side bar navigation select
   if(0) print("getNavSide: $page_id,$cat_pid,$flgPrint<br>");
   $navSide = 'nav_side_' . $page_id;
  //Check for a session variable
	if($_SESSION[$navSide]){
		if($flgPrint) print($_SESSION[$navSide]);
		return($_SESSION[$navSide]);
	}

   $idx_file = INDEX_FILE;

   preg_match('/^(.*)\//',$page_id,$navg);
   $nav_dir = $navg[1];
   $nav_section_id = $navg[1];
   $nav_section_id .=  "/" . INDEX_FILE;
   $td_class = EO_CLASS_SUBNAV;
   $td_type = IDX_3;
   $rs = todosGetRS($nav_section_id,$td_type,$td_class,'','','','','',0);    //Array of IDX3 linked docs
   $td3 = todosGetTodos($rs);    //Array of IDX3 linked docs
	##if(0) printTodos($td3);

   $cat_title = todosGetVal($cat_pid,IDX_0,'category',TD_PNAME_TITLE,TD_VALTYPE_FLD_TDTITLE);
   if(0) print("navSection catPID/page_id: $cat_pid,$page_id<br>");
   $nav_bar = $td3;
   $y = count($nav_bar);
   if(! $y){
	$_SESSION[$navSide] = '';
	return($_SESSION[$navSide]);
   }

    // Lots of subnavs . . .

	  $select_name = 'cat_pid';
	  $current_val = preg_replace('/index.php/','',$page_id);	
	  $flgAll	= 0;
	  $valAll	= $nav_dir;
	  $flgSelect 	= 0;
	  $flgSubmit	= 1;
	  $size		= $y;
	  $width	= 15;
	  #$onchange	= 'this.form.action=this.value;';
	  $onchange	= 'document.location=this.value;';
	  $flgPrint	= 0;
	  $flgMode	= '10110';

	  $html = ("<FORM>\n");
	  $html .= ("<SELECT width=$width size=$size onchange=$onchange>\n");
	  
  	   for($i=0;$i<$y;$i++){
		$url 	= $nav_bar[$i]["td_url"];
		$pid 	= $url;
		$url 	= preg_replace("/$idx_file$/",'',$url);
		$title 	= $nav_bar[$i]["td_title"];
		if(! $title) $title = todosGetVal($pid,$td_type,$td_class,
			TD_PNAME_TITLE,TD_VALTYPE_FLD_TDTITLE);
		if(! $title) $title = todosGetVal($pid,'',$td_class,TD_PNAME_TITLE,TD_VALTYPE_FLD_TDTITLE);
		$html .= "<OPTION value=$url>" . "  <a href=\"" . $url ."\">". $title ."</a> &nbsp; \n ";
    	   }
	  $html .= ("</SELECT>");
	  $html .= ("</FORM>\n");
	
  ## DEBUG 
  	if(0)print ("navSection: $page_id,$current_val,$cat_pid,$flgSideMenu<br>");
	if(0) print("HTML: $html<br>");
   	##if(0)printTodos($td3);
	##if(0) exit;
  ## DEBUG 

	##if(0) var_dump($html);
	if(0) print("flgSideMenu: $flgSide,$flgSideMenu,$y<br>");

	if($flgPrint) print($html);
	$_SESSION[$navSide] = $html;
  return($html);
}
//################################################################
function getNavSection($page_id,$cat_pid,$flgPrint=1,$flgSide=0){
// Print the correct settion nav bar for the current page

   if(0) print("getNavSection: $page_id,$cat_pid,$flgPrint,$flgSide<br>");
   $ltgrnbox = "<img src='". IMG_BULLET1 ."'>";
   $navSection = 'nav_' . $page_id;
   $flgSideName = $navSection . 'flgSide';
	

   $idx_file = INDEX_FILE;

   $navg = explode('/',$page_id);
   $nav_dir = $navg[1];
   $nav_section_id = "/" . $navg[1] . "/" . INDEX_FILE;
   $td_class = EO_CLASS_SUBNAV;
   $td_type = IDX_3;
   $rs = todosGetRS($nav_section_id,$td_type,$td_class,'','','','','',0);    //Array of IDX3 linked docs
   $td3 = todosGetTodos($rs);    //Array of IDX3 linked docs
	##if(0) printTodos($td3);

   $cat_title = todosGetVal($cat_pid,IDX_0,'category',TD_PNAME_TITLE,TD_VALTYPE_FLD_TDTITLE);
   if(0) print("navSection catPID/page_id: $cat_pid,$page_id<br>");

   if($flgSelf){
		$td_url = todosGetURL($cat_pid);
		$rs = todosGetRS($cat_pid,IDX_0);
		$td = todosGetTodos($rs);
		$td[0]['td_url'] = $td_url;
		$td3 = array_merge($td,$td3);
   }
   $nav_bar = $td3;
   $y = count($nav_bar);


   $array_html = array();

	## if(($y < 7) || ($flgSide == -1)){
	if(! $flgSide){
      	      ### Check to see if already registered
		if($_SESSION[$navSection]){
				if($flgPrint) print($_SESSION[$navSection]);
				$flgSideMenu = $_SESSION[$flgSideName];
				return($_SESSION[$navSection]);
		}
  	   for($i=0;$i<$y;$i++){
		$url 	= $nav_bar[$i]["td_url"];
		$pid 	= $url;
		$url 	= preg_replace("/$idx_file$/",'',$url);
		$title 	= $nav_bar[$i]["td_title"];
		if(! $title) $title = todosGetVal($pid,$td_type,$td_class,
			TD_PNAME_TITLE,TD_VALTYPE_FLD_TDTITLE);
		if(! $title) $title = todosGetVal($pid,'',$td_class,TD_PNAME_TITLE,TD_VALTYPE_FLD_TDTITLE);
		$html .= $ltgrnbox . "  <a href=\"" . $url ."\">". $title ."</a> &nbsp; \n ";
    	   }
	}
	else{
 	   // Lots of subnavs . . .
	  $flgSideMenu = 1;

	  $select_name = 'cat_pid';
	  $current_val = preg_replace('/index.php/','',$page_id);	
	  $flgAll	= 0;
	  $valAll	= $nav_dir;
	  $flgSelect 	= 0;
	  $flgSubmit	= 1;
	  $size		= 15;
	  #$onchange	= 'this.form.action=this.value;';
	  $onchange	= 'document.location=this.value;';
	  $flgPrint	= 0;
	  $flgMode	= '10110';

	  $html = ("<FORM>\n");
	  $html .= ("<SELECT size=$size onchange='document.location=this.value'>\n");
	  
  	   for($i=0;$i<$y;$i++){
		$url 	= $nav_bar[$i]["td_url"];
		$pid 	= $url;
		$url 	= preg_replace("/$idx_file$/",'',$url);
		$title 	= $nav_bar[$i]["td_title"];
		if(! $title) $title = todosGetVal($pid,$td_type,$td_class,
			TD_PNAME_TITLE,TD_VALTYPE_FLD_TDTITLE);
		if(! $title) $title = todosGetVal($pid,'',$td_class,TD_PNAME_TITLE,TD_VALTYPE_FLD_TDTITLE);
		$html .= "<OPTION value=$url>" . "  <a href=\"" . $url ."\">". $title ."</a> &nbsp; \n ";
    	   }
	  $html .= ("</SELECT>");
	  $html .= ("</FORM>\n");
	 # asort($array_html);
	 # $html = printSelectArray($select_name,$array_html,
	 #	$current_val,$flgAll,$flgSelect,$flgSubmit,$onchange,
	 #	'',$valAll,$size);
	}
	
  ## DEBUG 
  	if(0)print ("navSection: $page_id,$nav_section_id,$current_val,$cat_pid,$flgSideMenu<br>");
   	##if(0)print_r("navSection navSectionID: $nav_section_id <br>");
	if(0) print("HTML: $html<br>");
   	##if(0)printTodos($td3);
	##if(0) exit;
  ## DEBUG 

	##if(0) var_dump($html);
	if(0) print("flgSideMenu: $flgSide,$flgSideMenu,$y<br>");

	if($flgPrint) print($html);
	$_SESSION[$navSection] = $html;
	$_SESSION[$flgSideName] = $flgSideMenu;
  return($html);
}

//################################################################
function getNavPage($rec_pid,$cat_pid,$flgPrint=0,$flgSide,&$flgSideMenu){
//
//	Get the navSection for the current page
//	if not exist, get navSection for the parent category
//	return html value

	if(0) print("getNavPage: $rec_pid,$flgPrint,$flgSide,$flgSideMenu<br>");

        if(0) print("navPage: $rec_pid<br>");
	$rec_pid = todosConvURL2PID($rec_pid);
        $navPage = '';
	##<WEIRD>
	$cat_pid = $_GET['cat_pid'];
	if(! $cat_pid) $cat_pid = todosGetCatPID($rec_pid);
        if(0) print("navPage:CAT PID: $cat_pid<br>");
	$navPage = getNavSection($rec_pid,$cat_pid,0,$flgSide);
	if(! $navPage){
         if ($cat_pid != '/idx'){
                 $navPage = getNavSection($cat_pid,$cat_pid,0,$flgSide);
		  if(0) print("getNavPage:cat_pid: $cat_pid<br>");
                if(! $navPage){
                        $cat_parent = todosGetParentCategory($cat_pid);
                        ##if(0) print("navPage:CAT PARENT: $cat_parent<br>");
                         if($cat_parent != '/idx') $navPage = getNavSection($cat_parent,$cat_pid,0,$flgSide);
                }
	 }
        }
	if(0) print("navPage: $rec_pid,$cat_pid,$cat_parent,$flgPrint<br>");
	if(0) print("navPage: $navPage<br>");

	if($flgPrint) print($navPage);
	return($navPage);
}

//################################################################
function navPage1($rec_pid,$flgPrint=1){
### NOT CURRENTLY IN USE
// Print the  nav bar for a given page
// Find the nav0,nav1,...navN section navs
// Put them into a html block
// Optionally print the block
// Return the block

   GLOBAL $nav_global;

   $td_class = EO_CLASS_SUBNAV;
   $ret = preg_match('/^(\/.*)(\/.*)?(\/.*)?/',$rec_pid,$amatch);
   if(! $ret) exit("Couldn't match $rec_pid against the navPage regexp.<br>");
   $nav0 = getNavSection($amatch[1]);	//Root navSection
   $nav1 = getNavSection($amatch[2]);
   $nav2 = getNavSection($amatch[3]);
}

//################################################################
function arrowIf($this_global){
  //Show a gold arrow if page is under this section
  GLOBAL $nav_global;
  $match = "/" . strtoupper($this_global) ."/";
  if(preg_match($match,strtoupper($nav_global))){
	$imgsrc = IMG_ROOT . "/goldarrow.gif";
	##if(0) print("arrow: $imgsrc<br>");
      return($imgsrc );
  }
  else {
     return(IMG_ROOT . "/spacer.gif width=15");
  }
}

//################################################################
function gNavName($this_global){
  //Print section name, in yellow if current nav_section
  // Could do more -- like translating to a display name
  GLOBAL $nav_global;
  $nav_name;		//Display name for global nav section

  //print $this_global; exit;
	$nav_name = $this_global;
  switch($nav_name) {
	case "About_Us" :	$nav_name = "About Us"; break;
	case "ACL" 	:	$nav_name = "Login"; break;
  }
	##if(0) print("gNavName: $this_global, $nav_name<br>");
	return($nav_name); 
}

//####################################################################
//#########  END OF LIB_NAV.INC.PHP ##################################
//####################################################################
?>
