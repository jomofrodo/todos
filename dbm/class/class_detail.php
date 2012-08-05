<?php  include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php"); ?>
<?php
  // Page to add and edit Class entries in the todos database
  // This is very much the same as rec_edit, except that entities here
  // must have an idx0 class record

  $nav_global="admin";
  $nav_section="";
  $page_short_title="Todos Admin";
 
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");

	if(! $pAction) $pAction = 'editHash';

		$sql_class = "AND tdType='idx0' AND tdClass='" . $td_class . "'";


		switch($pAction){
			case "Update"	: ## update the record and stay on the edit page
					  $ret = todosUpdateCHash($args,$ct=0);
						$td = todosGetClassHash($rec_pid,$eo_class,'',$debug); break;
						break;
			case "Cancel"	: $td = todosGetClassHash($rec_pid,$td_class);break;
			case "Delete"	: 
						$ret = todosDeleteCHash($args);
						$ret = deleteTodos_Form($args);
						$url = TODOS_ROOT . PAGE_CAT_SUMMARY . 
						"?rec_pid=$rec_pid&td_class=$td_class&rspage=$rspage";
						header("Location:$url");
						break;
			case "AddNew"	: 
					  preg_match('/(\/.*\/).*/',$rec_pid,$amatch);
					  $pidnew = $amatch[1];
					  $pidnew .= "<NEW_PAGE_NAME>";
					  $url = TODOS_ROOT . PAGE_REC_ADDNEW . 
						"?rec_pid=$pidnew&rec_pid=$rec_pid&td_class=$td_class";
					  header("Location:$url");
					  break;
			case "ClassSummary":
			case "Summary"	: 
					$url = TODOS_ROOT . PAGE_CAT_SUMMARY . 
						"?rec_pid=$rec_pid&td_class=$td_class&rspage=$rspage";
						header("Location:$url");
					  	break;
			default		: 
						$td = todosGetClassHash($rec_pid,$eo_class,'',$debug); break;

		}

	###### DEBUG ############
	$args['debug'] = 0;
	if(0) print_r($args . "<br>");
	if(0) var_dump($gargs);
	if(0) print("PACTION: $pAction<br>");
	if(0) print("page_id: $page_id<br>");
	if(0) print("class_edit: $rec_pid,$td_class,$pAction,$rspage<br>");
	if(0) var_dump($td);
	########################
	include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");
?>

<? 
	### Create the Params check boxes

	$flgPrint = 0;
	$flgRealOnly = 1;
	$Classes = todosGetClassList($td_class,'','','',$flgRealOnly,0);
	$sql .= "SELECT DISTINCT ";
	$sql .= "pName,pDescription \n";
	$sql .= "FROM tblParams as p\n";
	$sql .= "INNER JOIN tblEOClasses as eo on p.eoClass = eo.eoClass\n";
	$sql .= "WHERE (p.eoClass = '$td_class'\n";
	foreach ($Classes as $cl){
		$sql .= "OR p.eoClass = '$cl'\n";
	}
	$sql .= ")\n";
	$sql .= "AND pName != ''\n";
	#$sql .= "GROUP BY pName\n";
	$sql .= "ORDER by p.pDescription, p.idxSort, eo.idxClassPriority DESC\n";

	if(0) print($sql);

	$chkParams = printChkbox('col_names',$col_names,'tblParams','pName','pDescription',
			$sql);
?>
	
<html>
	<head><title>Todos</title></head>
	<body>
		
<h1>IDX Class Editor </h1>

<form name=frmTodosAdmin method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value="editHash">
  <input type=hidden name="tdID" value='<? echo $tdID?>'>
  <input type=hidden name="eo_class" value='<?=EO_CLASS_VCLASS?>'>
  <input type=hidden name="rspage" value=1> 
  <table border=0>
     <tr height=0> 
            <td width=150 height=0><input type=image src="/images/spacer.gif" value="Update" onclick="this.form.pAction.value=this.value" width=0 length=0 height=0>
            <td width=100> 
            <td width=100> 
	    <td width=250>
    <tr>
      <td>Class: 

      <td colspan=2> 
	<?  todosSelectClass($td_class,$td_class); ?>
      <input type=submit value="go" onclick=this.form.pAction.value='Go'>
      <td><input type=submit value="Summary" onclick=this.form.pAction.value='ClassSummary'>
    <tr>
      <td>Page: 

      <td colspan=2> 
	<input name="rec_pid" value="<?=$rec_pid ?>" width=500 size=50>
	<!--
	<? 
		printSelect("rec_pid",'tblTodos','tdPageID','tdPageID',$rec_pid,'','','',$sql_class); ?>
	-->
	<td><input type=submit value="AddNew" onclick='this.form.pAction.value=this.value'>
    <tr>
      <td> All Columns: <input type=checkbox name=cols value='*' 
				<? if($cols) echo("checked");?>
				onchange="this.form.submit( )"
				onclick="this.form.submit( )"
			>
    <tr>
	<td colspan=3>Params: <?= $chkParams?>
    </tr>
</table>
  <table border=0>
    <tr>
	<td colspan=7 align=center>
<?


	if(is_array($td)){
		switch($pAction){
			case "editHash"	: todosEditCHash($td,$td_class,$cols); break;
			case "printHash": printTodos($td); break;
			case "Update"	: todosEditCHash($td,$td_class,$cols); break;
			default		: todosEditCHash($td,$td_class,$cols); break;	
		}
	}
		
	echo(" </td></tr>\n");
	echo(" <tr><td>&nbsp;\n");
	echo(" <tr><td colspan=4 align=center>\n");
	echo(" <input type=submit value=\"Update\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Cancel\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Delete\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Summary\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");

	
?>
</form>
  </table>

<script language="javascript">
		$1st = 10;
                $idx = 0;
		$form = document.forms[0];
		$elem = $form.elements[$1st];
                //alert($elem.name);
		$elem.focus();
		$elem.select();
</script>