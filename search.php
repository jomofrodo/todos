<?php //session_cache_limiter('private'); ?>
<?php  @include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php"); ?>
<?php	include_once(dirname(__FILE__)."/lib_todos.php"); ?>
<?php

  # FORM Variables
	if(0) print("search: CatPID: $cat_pid<br>");

	if(0)var_dump($args);
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
  <tr>
    <td valign=top name="text">
	<table>

  	<tr><td class=td1>
	    <table>
		<tr valign=top>
			<td><h2>Search: </h2>
<!--  ################################################################ -->
    <td>
  	<input name="srch_terms" value="<?=$srch_terms;?>" width=100>
   	<td colspan=5 align=center><input type=submit onclick="if(this.form.rspage){this.form.rspage.value=1}" value="Go">

    <td colspan=2>
      Category:
	<?
		$onchange = 'this.form.rspage.value=1';
		todosSelectStruct($bass_cat,$cat_pid,'','','','','','',$onchange); ?>
<!--  ################################################################ -->
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
   	<tr><td class=th1 width=100%>Search Results
	<tr><td valign=top>
<?
		if(0) print("flgSearch: $flgSearch<br>");
		if(($flgSearch)) todosSearch_Form($args);
		else todosListCategory_Form($args);
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