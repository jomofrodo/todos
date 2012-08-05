<?php   //session_cache_limiter('private'); ?>
<?if (SITE_HDR) include_once ($_SERVER['DOCUMENT_ROOT'] . "/_include/ch.php"); ?>
<?
     include_once(dirname(__FILE__)."/lib_todos.php");
     $flgAdmin = login_CheckValidated();
     if($flgAdmin) include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
?>
<?php
       $dbgsessid="1;d=1;p=0";
       //$dbgsessid="";
  # FORM Variables
	if(0) print("search: CatPID: $cat_pid<br>");
	$args = $_POST;
	if(!$args) $args=$_GET;
        extract($args);
	if(0)var_dump($args);
    if($flgAdmin) $flgEdit = 1;
    $args['flgEdit'] = $flgEdit;
	## RSPage
	if(0) print("RSPage: $rspage<br>");
	if($rspage) $_SESSION['rspage'] = $rspage;
	else $rspage	= $_SESSION['rspage'];
	  ## Always page output
	if(! $rspage) $args['rspage'] = 1;
	## Srch Terms
	if(0) print("srch_terms: $srch_terms<br>");
	#if(defined($srch_terms)) $_SESSION['srch_terms'] = $srch_terms;
	#else{
	#	 $srch_terms	= $_SESSION['srch_terms'];
	#	 $args['srch_terms'] = $srch_terms;
	#}

	## Cat and Bass Cat
	#if(! $cat_pid) $flgSearch = 1;
	if(! $cat_pid){
		 $cat_pid = todosGetCatPID($_SERVER['HTTP_REFERER']);
		  if(0) print("CatPID: $cat_pid<br>");
		 if(! $cat_pid) $cat_pid = IDX_ROOT;
		 $args[cat_pid] = $cat_pid;
	}
	#if(! $bass_cat) $bass_cat = $cat_pid;
	if(! $bass_cat) $bass_cat = IDX_ROOT;
     ## Bass Class
     if(! $bass_class) $bass_class = todos_GetBassClass($cat_pid,$rec_pid);
	## Display Class
	$vclass 		= 'search';
	$args['vclass'] 	= $vclass;
	#if($cat_pid == '/idx'){
	#	$col_names = 'title,source,description';
	#	$args['col_names'] = $col_names;
	#}
	#
	if(0) print("Search: $cat_pid,$bass_cat,$srch_terms,$flgSearch,$rspage<br>");

?>
<html>
 <head>
  <title><?=SITE_NAME;?></title>
 </head>
 <body bgcolor=#FFFFFF text=#000000>
<center>
<table   width="100%" align="center" style="WIDTH: 100%">
	<form name=frmTDSrch method=post>
	<input type=hidden  name=cat_pid value="<?=$cat_pid;?>">
	<input type=hidden  name=DBGSESSID value="<?=$dbgsessid;?>">
  <tr>
    <td valign=top name="text">
	<table>

  	<tr><td class=td1>
	    <table>
<!--  ################################################################ -->
    <tr>
      <td colspan=2>
      <h2><?=$cat_title;?>:
      <td><td valign=bottom>
  	<input name="srch_terms" value="<?=$srch_terms;?>" width=100></h2>
<!--  ################################################################ -->
	<td colspan=5 align=center><input type=submit onclick="if(this.form.rspage){this.form.rspage.value=1}" value="Go">
    <tr>
	</table>
<!--
	<td><input type=radio name=srchFlds value="*" checked>all fields
	<td><input type=radio name=srchFlds value="fld_tdTitle">titles
	<td><input type=radio name=srchFlds value="p_type">type
	<td><input type=radio name=srchFlds value="fld_tdText">body
-->
	<td>&nbsp;

	<td>

	</table>
  <tr>
    <td name="index" valign=top>
	<table width=100%>
	<tr><td valign=top>
<?
		if(0) print("flgSearch: $flgSearch<br>");
		if(($flgSearch)){ todosSearch_Form($args);  }
		else{ todosListCategory_Form($args); }
        //else todosListCategory('',$cat_pid,$td_class,$col_names,0,$rs_page,$flgEdit,0,'',0,'',$vclass);
?>
	</table>
	</form>
</table>
</form>
<hr size=7 width=75%>
</body></html>
<script language="javascript">
		$frm = "frmTDSrch";
		$form = document.forms[$frm];
		//alert ($form.name);
		$1st = 'srch_terms';
                $idx = 0;
		$elem = $form.elements[$1st];
                //alert($elem.name);
		$elem.focus();
		$elem.select();
</script>
<?   include_once($_SERVER[DOCUMENT_ROOT] . "/_include/cf.php");