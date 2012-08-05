<? //session_cache_limiter('private'); ?>
<?@include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");?>
<?php

    // first we have to do our includes
	include_once(dirname(__FILE__)."/lib_todos.php");
	include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");

  // Page to list a category summary
  // AS of this entry, todos is in mySQL database todosDI on lucy

  $nav_global="admin";
  $nav_section="";
  $page_short_title="Todos Admin";

  $dbgsessid="";  //DEBUG
	### Process POST or GET args

		if(0) var_dump($args);
		if(0) var_dump($_REQUEST);
		if(0) var_dump($_SERVER);
		todosFixFormVars($args);
	### Set some argument defaults
		if(! $args['rspage']) $args['rspage']=1;
		if(! $args['pAction']) $args['pAction'] = 'Category';

		## For now, always in admin mode
		$modeAdmin = 1;
		if($modeAdmin) $args['flgEdit'] = 1;

	## Set Page Variables
		$flgEdit	= $args['flgEdit'];
		$flgAllColumns	= $args['flgAllColumns'];
		$flgShowInactive= $args['flgShowInactive'];
		$srch_terms	= $args['srch_terms'];
		$col_names	= $args['col_names'];
 		$confirm	= $args['confirm'];
		$cancel		= $args['cancel'];
		$flgRecursion	= $args['flgRecursion'];
  		if($flgAllColumns){
					$col_names = '*';
					$args['col_names'] = '*';
		}
        //td_class override
          if(!$td_class){
            $td_class = todosGetCatVal($cat_pid,TD_PNAME_BASS_CLASS);
         }

        //Page Navigation
        $flgPage = $args['flgPage'];
        if(! $flgPage) $flgPage = 1;

	###### DEBUG ############
	$args['debug'] = 0;
	#if(0) var_dump($args);
	if(0) print("PACTION: $pAction<br>");
	#if(0) print("page_id: $page_id<br>");
	if(0) print("catAdmin: $bass_cat,$cat_pid,$cat_title,$td_class,$pAction<br>");
	#if(0) exit;
	########################
		switch($pAction){
			
			case "GoCategory"  	:
						$args['rspage']=1;
						break;
			
			case "AddNew"	:
					  preg_match('/(\/.*\/).*/',$cat_pid,$amatch);
					  $pidnew = $amatch[1];
					  $pidnew .= "<NEW_PAGE_NAME>";
					  $url = TODOS_ROOT . PAGE_REC_ADDNEW .
						//"?rec_pid=$pidnew&cat_pid=$cat_pid&td_class=$td_class";
						"?cat_pid=$cat_pid";
					  if(0) print ("$url<br>");
					  header("Location:$url");
					  break;
			case "Link"	:
					$url = TODOS_ROOT . PAGE_IDX2_EDIT .
						"?cat_pid=$cat_pid&rec_pid=$cat_pid&td_class=$td_class";
					  header("Location:$url");
					  break;
					
			case "ReCalc"	:
					todosCreateCatTable($cat_pid);
					break;
			case "EditCat"	:
					$url = TODOS_ROOT . PAGE_REC_EDIT .  "?rec_pid=$cat_pid&cat_pid=$cat_pid&td_class=" . EO_CLASS_CATEGORY;
					ob_end_clean();
					header("Location:$url");
					break;
			case "Commit"	:
					$tbl_name = todosGetCatTableName($cat_pid);
					if((! $confirm) &&(! $cancel)){
					echo "<table border=1 width=100%><tr height=275 valign=center><td>\n";
					echo "<div align=center>\n";
    					echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cat_pid\" VALUE=\"$cat_pid\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"pAction\" VALUE=\"Commit\">\n";
					echo ("<b>Commit records in this category to $tbl_name and  CVS?</b><br>\n");
					echo ("<p>");
					echo ("<input type=checkbox name=\"flgRecursion\" value=1>");
					echo ("Update all categories in the category chain.\n");
					echo "<P>\n";
 					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"confirm\" VALUE=\"Commit\">\n";
					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\"Cancel\"><BR>\n";
					echo "</div>\n";
					exit;
					break;
					}
					if($confirm){
						$ret = todosCommitCategory($cat_pid,$flgRecursion);
						break;
					}
					break;
			case "LoadIDX"	:
					if((! $confirm) &&(! $cancel)){
					echo "<table border=1 width=100%><tr height=275 valign=center><td>\n";
					echo "<div align=center>\n";
    					echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cat_pid\" VALUE=\"$cat_pid\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"pAction\" VALUE=\"LoadIDX\">\n";
					echo ("<b>Load the records for this category from an idx mysqldump file?</b><br>\n");
					echo ("<p>");
					echo ("<input type=checkbox name=\"flgRecursion\" value=1>");
					echo ("Update all categories in the category chain.\n");
					echo "<P>\n";
 					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"confirm\" VALUE=\"Commit\">\n";
					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\"Cancel\"><BR>\n";
					echo "</div>\n";
					exit;
					break;
					}
					if($confirm){
						$ret = todosLoadCategory($cat_pid,$flgRecursion);
						break;
					}
					break;
			default		: break;
		}





?>
<html>
	<head><title><? echo($cat_title)?></title></head>
	<body>
		
<div align="left"><h1><? echo($cat_title) ?> </h1></div>
<div align="left"><h2>cat table: <?=$cat_table?></h2></div>

<?php
    $frmAction = $_REQUEST["PHP_SELF"];
    $frmAction .= $strDebug;
?>
<form name=frmSrchMain method=get action="">
  <input type=hidden name="pAction" value="Category">
  <input type=hidden name=DBGSESSID value="<?=$DBGSESSID?>">
  <input type=hidden name="td_class" value="<?$td_class?>">
  <table>
     <tr> 
            <td colspan=4> 
    <tr>
      <td>Category: 

      <td> 
	<?  
	    #if($cat_pid) todosSelectCategory($cat_pid,$cat_pid,0,0);
        todosSelectCats(IDX_ROOT_PAGEID,$cat_pid,'cat_pid','this.form.td_class.value="";this.form.submit();');
	    //if($cat_pid) todosSelectStruct($cat_pid,$cat_pid,'subcat','cat_pid',0,0);
	    //else todosSelectStruct('',$cat_pid,'subcat','cat_pid',0,0);

	?>
      <td>TD Class: 
      <td> 
	<? $ret = todosSelectClass('slc_td_class',$td_class,'',0,1,1,1,'this.form.td_class.value=this.value;this.form.col_names.value=""'); ?>
		Show inactive:
		<input type=checkbox name='flgShowInactive' value=1
			<? if ($flgShowInactive) print "checked" ?>
			onclick="this.form.submit();" >
    <tr>
	<td>
	<td colspan=1 > Search: <input name=srch_terms value="<?=$srch_terms;?>">
	<input type=submit value="Go" onclick="this.form.pAction.value='GoCategory';">
	<td>Display Columns:
	<td> 
		<input type=checkbox name='flgAllColumns' value=1 
			<? if ($flgAllColumns) print "checked" ?>
			onclick="this.form.submit();" >
		<input name='col_names' value='<?=$col_names;?>'>
    <tr>
	
     <tr> 
       <td colspan=4 align=center> 

	<input type=submit value="ReCalc Category" onclick="this.form.pAction.value='ReCalc';">
	<input type=submit value="Add New Record" onclick='this.form.pAction.value="AddNew"'>
	<input type=submit value="Link Records" onclick='this.form.pAction.value="Link"'>
	<input type=submit value="Edit Category" onclick='this.form.pAction.value="EditCat"'><br>
<!--	<input type=submit value="Commit Changes" onclick='this.form.pAction.value="Commit"'>  -->
<!--	<input type=submit value="Load Category" onclick='this.form.pAction.value="LoadIDX"'>  -->
  </table>
</form>
<form>
<?

		if(0) print("pAction: $pAction<br>");
		switch($pAction){
			case "GoCategory" : 
					$args['rspage']=1;
					if(0) var_dump($args);
					if(0) exit;
					todosListCategory_Form($args);break;
			default 	: //todosListCategory_Form($args);break;
                        todosListCategory($cat_title,$cat_pid,$td_class,$col_names,$flgNoHdrs,$flgPage,$flgEdit,$flgForm,$num_recs,$flgTarget,$flgShowInactive,$vclass,$debug);
                        break;
        }
		
	
?>
</form>
<script language="javascript">
		$frm = 'frmSrchMain';
		$1st = 'srch_terms';
                $idx = 0;
		$form = document.forms[$frm];
		$elem = $form.elements[$1st];
                //alert($elem.name);
		$elem.focus();
		$elem.select();
</script>