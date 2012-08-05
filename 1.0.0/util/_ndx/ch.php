<?php ob_start() ?>
<?php 	
	if(0) print ("Hello World<br>");
        //session_cache_limiter('nocache');
        //session_cache_limiter('public');
	session_start();
        if(0) print("Session vars: " . SID . "," . session_id() . "<br>");
	if(0) var_dump($_SESSION);
?>
<HTML>
<HEAD>

<!-- Include at top of page
PHP VARIABLES
   $nav_global
   $nav_section
   $page_title
   $page_short_title
-->

<link rel="stylesheet" href="/_include/dwyer3.css">
<link rel="stylesheet" href="/_include/hmenu.css">
<script language="javascript" src="/_lib/HMenu.js"></script>
<script language="javascript" src="/_lib/js/lib_jsutil.js"></script>

<?php
	//$arr = get_defined_vars();
        //print_r($arr["_REQUEST"]);
        //print "\n";
        //print_r($arr["_SERVER"]);
        //print "\n";
	if(0) print_r(array_keys(get_defined_vars()));

include_once(dirname(__FILE__)."/../_lib/Todos/lib_todos.php");
include_once(dirname(__FILE__)."/../_lib/Todos/lib_nav.php");
include_once(dirname(__FILE__)."/../_lib/ACL/login.inc.php");
include_once(dirname(__FILE__)."/../_include/menus.shtml");

        // Set navigational elements for this page
        setNavVars() ;

// Check login credentials	
	$logged_in = lib_login_valid_user();
	if(0) print("user:	$gUser<br>");
	if(0) print("usrGroup: $gUserGroup<br>");

?>



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- #BeginEditable "doctitle" -->
<title>Dwyer & Associates</title>
<!-- #EndEditable --> 

</head>

<body bgcolor="#FFFFFF" onload="initMenu();" onclick="hideMenu();">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="131"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="120"><a href="/"><img align="bottom" 
		alt="D&amp;A Logo" border="0" src="/images/D&amp;ALGOBL_2.gif" naturalsizeflag="0" width="120"></a></td>
          <td width="10">&nbsp;</td>
          <td width="100%"><img border="0" height="70" src="/images/dwyer_banner.gif"></td>
        </tr>
        <tr> 
          <td width="120" valign="top"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              
          <tr bgcolor="#003366"> 
            <td height="37"> <div align="center"> <!--SECTION TITLE--> 
              <div class="section_title">
		<? echo $nav_section; ?>
		</div>
                </td>
              </tr>
              <tr> 
                <td>
		<div class="main_page_title" align="center">
		<!--PAGE SHORT TITLE-->
		<? echo $page_short_title; ?></div></td>
              </tr>
	<!-- ###################### Page NavBar #################### -->
              <tr> 
                <td>
		<!-- #BeginEditable "Sidebar" -->
	
<!--
		<div name=page_navbar class=page_navbar> 
		<? #getNavPage($nav_page_id); ?>
	 	</div>
-->
		<!-- #EndEditable -->
                </td>
              </tr>
	<!-- ######################################################### -->
	<!-- ########### APPLICATION HEADER ####################### -->
              <tr bgcolor="#FFCC00"> 
                <td> 
                  <div align="center"><font size="2"><b>
			<? if ($page_apps){echo "applications";}?>
			</b></font>
		</div>
                </td>
              </tr>
	<!-- ########### APPLICATIONS ####################### -->
              <tr> 
                <td> 
                  <font size=2>
	<div class="sidedocs">
			<? if($page_short_title != 'Index') echo $page_apps; ?>
	</div>
                    <!-- #EndEditable --> 
                  </font>
                  </td>
              </tr>
	<!-- ########### DOCUMENTS HEADER ####################### -->
              <tr bgcolor="#FFCC00"> 
                <td> 
                  <div align="center"><font size="2"><b>
			<? if ($page_docs){echo "documents";}?>
			<!--DOCUMENTS HEADER--></b></font>
		</div>
                </td>
              </tr>
	<!-- ########### DOCUMENTS ####################### -->
              <tr> 
                <td> 
                  <font size=2>
                    <!-- #BeginEditable "Documents" -->
	<div class="sidedocs">
			<? if($page_short_title != 'Index') echo $page_docs; ?>
	</div>
                    <!-- #EndEditable --> 
                  </font>
                  </td>
              </tr>
	<!-- ############## END OF DOCUMENTS ############## -->
	<!-- ########### Side Navigation ####################### -->
              <tr> 
                <td> 
                  <font size=2>
	<div class="sidedocs">
			<?=  $navSide; ?>
	</div>
                  </font>
                  </td>
              </tr>
	<!-- ############## END OF DOCUMENTS ############## -->
            </table>
          </td>
          
      <td width="10" bgcolor="#0033CC" valign="top" align="center" height="100%"><img border="0" height="100%" src="/images/tall_line.gif" width="10"></td>
          <td width="100%" valign="top" > 
<form align=bottom action="/Search/index.php?cat_pid=/idx&rspage=1" method=post>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="24">
              <tr> 
                <td width=7 bgcolor=white><img height=8 src="/images/spacer.gif" width=8> 
                
            <td height="34" bgcolor="#003366" width="100%"> 
<!-- ################# Global Nav Bar ###################### --> 
	<div class=global_navbar> 
		<?	gNavLink("Home",'','',''); ?> 
		<?	gNavLink("Quotes","Quotes",190,120); ?> 
              	<?	gNavLink("Products",'Products',290,120); ?> 
		<?	gNavLink("Search",'','',''); ?> 
			<input name=srch_terms size=7>
			<input type=submit value="go">
              	<?	if($gFlgAdmin) gNavLink("Admin",'Admin','700','120'); ?> 
<!-- ################# END Global Nav Bar ################# --> 
            </td>
              </tr>
              <tr> 
				<td  bgcolor=white width="7"> 
                <td width="100%" >
<!-- #################### SUB MENU ################################ -->
<div name=product_navbar class=section_navbar> 
	<? if(! $navPage) echo $navSection; ?>
	<?= $navPage; ?>
 </div>
<!-- ########################  END OF SUB MENU ######################## -->
                </td>
              </tr>
            </table>  
</form>
    <table width="100%" border="0" cellspacing="4" cellpadding="4">
              <tr> 
                <td width=100%>

<!-- ########################  END OF COMMON HEADER  ######################## -->
