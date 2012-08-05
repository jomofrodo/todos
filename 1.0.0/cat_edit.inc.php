<?php  include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php"); ?>
<?php
  // Page to add and edit Category entries in the todos database
  // This is very much the same as rec_edit, except that entities here
  // must have an idx1 category record
?>


<?
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");

  $nav_global="admin";
  $nav_section="";
  $page_short_title="Todos Admin";
  global $page_id;
 

	$MODES = array('IDX1'=>'Properties',
			'IDX3'=>'Relations',
			'CAT'=>'Sub-Categories',	
			'NAV'=>'Sub-Nav',
			'ADM'=>'Members');


	$pAction 	= $_POST['pAction'];
	if(! $pAction) $pAction = 'editHash';
	
		$cat_pid='';

		#$td_class = EO_CLASS_CATEGORY;
		$class = EO_CLASS_CATEGORY;
		$sql_class = "AND tdType='idx0' AND tdClass='" . EO_CLASS_CATEGORY . "'";

		$args = $_POST;	
		if(! $args) $args = $_GET;
		extract($args);		//turn form values into variables
		todosFixFormVars($args);
		$args['debug'] = 0;
		##################
		if(0) print("POST: <br>");
		if(0) var_dump($_POST);
		if(0) print("<br>GET: <br>");
		if(0) var_dump($_GET);
		if(0) print("<br>Args: <br>");
		if(0) var_dump($args);
		if(0) print ("<br>pAction: $pAction<br>");	
		if(0) exit;
		
		##################
		## CAT_PID !!!
		if(0) print("<br>CatPID: $cat_pid<br>");
		if($cat_pid) $_SESSION['cat_pid'] = $cat_pid;
		else $cat_pid	= $_SESSION['cat_pid'];
		## For when input coming from a todosPrintTodos Form
		#if(($rec_pid) && (! $cat_pid)){ 
		#	 $cat_pid = $rec_pid;
		#	$args[cat_pid] = $cat_pid;
		#}
		## can't find it, go to the library 
		if(!$cat_pid) $cat_pid = todosGetCatPID();
		$cat_title	= todosGetCatVal($cat_pid,TD_PNAME_TITLE);

		## RSPage
		if(0) print("RSPage: $rspage<br>");
		if($rspage) $_SESSION['rspage'] = $rspage;
		else $rspage	= $_SESSION['rspage'];

		## TD Class
		if($td_class) $_SESSION['td_class'] = $td_class;
		else $td_class = $_SESSION['td_class'];
		## Num_rows
		if($num_rows) $_SESSION['num_rows'] = $num_rows;
		else $num_rows	= $_SESSION['num_rows'];


		if($flgMode){
		   switch($pMode){
			case "IDX3"	:
					  $url = TODOS_ROOT . "/cat_edit_idx3.php" . 
						"?cat_pid=$cat_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					  ob_end_clean();
					  header("Location:$url");
					  break;
			case "IDX1"	:
					  $url = TODOS_ROOT . PAGE_CAT_EDIT .
						"?cat_pid=$cat_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					  ob_end_clean();
					  header("Location:$url");
					  break;
			case "CAT"	:
					  $url = TODOS_ROOT . "/cat_edit_cat.php" .
						"?cat_pid=$cat_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					  ob_end_clean();
					  header("Location:$url");
					  break;
			case "NAV"	:
					  $url = TODOS_ROOT . "/cat_edit_nav.php" .
						"?cat_pid=$cat_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					  ob_end_clean();
					  header("Location:$url");
					  break;
			case "ADM"	:
					  $url = TODOS_ROOT . PAGE_CAT_ADMIN .
						"?cat_pid=$cat_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					  ob_end_clean();
					  header("Location:$url");
					  break;
			default		:
					  break;
		   }
		}
		
		switch($pAction){
			case "Update"	: ## update the record and stay on the edit page
					$args[rec_pid] = $cat_pid;
					  $ret = todosUpdateCHash($args,$ct=0);
						$td = todosGetClassHash($cat_pid,$class,'',$debug); break;
						break;
			case "Delete"	: 
					if((! $confirm) &&(! $cancel)){
					echo "<div align=center>\n";
    					echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
					echo "<table class='table1' cellspacing=4 cellpadding=4 border=0><tr>\n";
   					echo "<th align=right>Current category record is: <td>".$cat_pid ."<br> ";
					echo " <tr><th align=right >Title: <td>$cat_title<br>\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"rec_pid\" VALUE=\"$rec_pid\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cat_pid\" VALUE=\"$cat_pid\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"td_class\" VALUE=\"$td_class\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"pAction\" VALUE=\"Delete\">\n";
					echo "<tr><td colspan=3 align=center>\n";
					#echo ("Delete this record?<br>\n");
					echo "<tr><td colspan=3>\n";
					echo "<INPUT type=checkbox name=flgDeleteFile> Delete physical file.\n";
					echo "<tr><td colspan=3 align=center>\n";
 					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"confirm\" VALUE=\"Delete\">\n";
					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\"Cancel\"><BR>\n";
					echo "</table>\n";
					echo "</div>\n";
					exit;
					break;
					}
					if($confirm){
						$ret = todosDeleteCHash($args);
						$ret = deleteTodos_Form($args);
						$url = TODOS_ROOT . PAGE_CAT_ADMIN . 
							"?cat_pid=/idx&td_class=$td_class&rspage=$rspage";
						ob_end_clean();
						header("Location:$url");
						break;
					}
					break;
			case "AddNew"	: 
					  preg_match('/(\/.*\/).*/',$rec_pid,$amatch);
					  $pidnew = $amatch[1];
					  $pidnew .= "<NEW_PAGE_NAME>";
					  $url = TODOS_ROOT . PAGE_REC_ADDNEW . 
						"?rec_pid=$pidnew&cat_pid=$cat_pid&td_class=$td_class";
					  header("Location:$url");
					  break;
			case "Cancel"	: 
			case "CatSummary":
			case "Summary"	: 
					$url = TODOS_ROOT . PAGE_CAT_ADMIN . 
						"?cat_pid=$cat_pid&td_class=$td_class&rspage=$rspage";
						header("Location:$url");
					  	break;
			default		: 
					#$td = todosGetClassHash($cat_pid,$class,'',$debug);
					break;

		}
		$td = todosGetClassHash($cat_pid,$class,'',$debug); 

	###### DEBUG ############
	$args['debug'] = 0;
	if(0) print_r($args . "<br>");
	if(0) var_dump($gargs);
	if(0) print("PACTION: $pAction<br>");
	if(0) print("page_id: $page_id<br>");
	if(0) print("cat_edit: $rec_pid,$cat_pid,$class,$pAction,$rspage<br>");
	if(0) var_dump($td);
	########################
?>
<html>
	<head><title>Dwyer & Associates</title></head>
	<body>
		

<form name=frmTodosAdmin method=post enctype="multipart/form-data">
  <input type=hidden name="cat_pid" value="<?=$cat_pid;?>">
  <input type=hidden name="pAction" value="editHash">
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name="flgMode">
  <input type=hidden name="tdID" value='<? echo $tdID?>'>
  <input type=hidden name="td_class" value='<?=EO_CLASS_CATEGORY?>'>
  <input type=hidden name="rspage" value=1> 
  <table border=0>
     <tr height=0> 
            <td width=150 height=0><input type=image src="/images/spacer.gif" value="Update" onclick="this.form.pAction.value=this.value" width=0 length=0 height=0>
            <td width=100> 
            <td width=100> 
	    <td width=250>
      <tr><th colspan=2 align=left> <h1>IDX Category Editor </h1>
      <td colspan=2>Category: 

	<?  
	    if($cat_pid) todosSelectStruct($cat_pid,$cat_pid,'subcat','cat_pid',0,0); 
	    else todosSelectStruct('',$cat_pid,'subcat','cat_pid',0,0); 
	?>
      <input type=submit value="go" onclick=this.form.pAction.value='Go'>
	<td><?=$cat_pid?>
      <tr>
	<th colspan=4>

	<? foreach($MODES as $m=>$title){
		print ("<input type=submit name='$m' value='$title' \n");
		print ("	onclick=\"this.form.pMode.value=this.name;\n");
	 	print ("		this.form.flgMode.value=1;\"\n");
	 	if($pMode == $m) print("disabled=\"true\" \n");
	   	print (">\n");
	   }
	?>
      <td> All Columns: <input type=checkbox name=cols value='*' 
				<? if($cols) echo("checked");?>
				onchange="this.form.submit( )"
				onclick="this.form.submit( )"
			>

	<tr>
</table>

<!--################################################################################-->
<!--
<script language="javascript">
</script>
-->
<!--################################################################################-->