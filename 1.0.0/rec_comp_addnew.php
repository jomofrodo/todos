<? //session_cache_limiter('private'); ?>
<?php include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php"); ?>
<?
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
     if(0) print_r($args);
?>
<?php
  // Page to add and edit links in the todos database
   		## Set Page Variables

		$flgClassOnly = 0;
        $new_rec_pid = null;
		$pAction 	= $args['pAction'];
		$cols 		= $args['cols'];
		$page_id	= $args['page_id'];
		$cat_pid	= $args['cat_pid'];
        $cat_name   = todosGetCatVal($cat_pid,TD_PNAME_TITLE);
		$td_class	= $args['td_class'];
        if(! $td_class) $td_class = todosGetCatVal($cat_pid,TD_PNAME_BASS_CLASS);
        $my_td_class = $td_class;
        //<REVISIT><HACK>
        $paramList = 'title,date,description,keywords,url';

		if(! $new_rec_pid)	$new_rec_pid = STR_NEW_PAGE_NAME;
		if($new_rec_pid == STR_NEW_PAGE_NAME) {
			$pid_base = todosConvPID2URL($cat_pid);
 			$new_rec_pid = "$pid_base" . OS_DIR_SEPARATOR. "$new_rec_pid";
            $new_rec_pid = preg_replace("/\/\//","/",$new_rec_pid);
		}
		
	switch($pAction){
		case "AddNew"	:
				$ret = checkFormVars($new_rec_pid);
				if(0) print("Return from checkFormVars: $ret<br>");
				if($ret) break;
				$ret = todosAddNewCHash($args,$ct=0);
				$url = TODOS_ROOT . PAGE_REC_EDIT . "?rec_pid=$new_rec_pid&cat_pid=$cat_pid&td_class=$td_class";
				if($ufile){
					if(0) var_dump($_FILES);
   					$basedir= $_SERVER[DOCUMENT_ROOT]; 
					$uploadfile = $basedir . $new_rec_pid;
					$uploadfile = preg_replace ('/\/\//','/',$uploadfile);
					if(0) print("Copying a file: $ufile,$basedir,$new_rec_pid<br>");
					copyFile($uploadfile);
				}
				if(0) print("Location:$url<br>");
				if(0) exit;
				ob_end_clean();
				header("Location:$url"); 
				break;
		case "Cancel"	: $url = $REQUEST_URI;
				ob_end_clean();
				header("Location:$url"); 
				break;
		case "Upload"	:
   				$basedir= $_SERVER[DOCUMENT_ROOT]; 
				preg_match('/(\/.*\/).*/',$pid,$amatch);
				$filedir = $amatch[1];
				if(0) print("Copying a file: $userfile,$basedir,$filedir,$userfile_name<br>");
				if(0) exit;
   				copy($userfile,$basedir.$filedir.$userfile_name);   
				break;
		default		:
                $flgRealOnly=1;
                $flgDisplay=0;  //scritchy scritchy
				$ch = todosGetClassHash($new_rec_pid,$td_class,'','',$flgRealOnly,$flgDisplay); break;

	}
	//$ch = todosGetClassHash($new_rec_pid,$td_class,'',$debug);
	###### DEBUG ############
	if(0) var_dump($args);
	if(0) var_dump($gargs);
	if(0) print("PACTION: $pAction<br>");
	if(0) print("page_id: $page_id<br>");
	if(0) print("addnew_rec: $pid,$new_rec_pid,$page_id,$cat_pid,$my_td_class,$pAction<br>");
	if(0) exit;
	if($err) print($err);
	########################

function checkFormVars($new_rec_pid){
		$flgErr = 0;
		$str_new_name = STR_NEW_PAGE_NAME;
		if(preg_match("/$str_new_name/", $new_rec_pid)){
			$flgErr = 1;
			$err = ("Please enter a name for this file on the web server.<br>");
		}
		if ($flgErr){
			print("<font color=red>Error: ");
			print($err);
			print("</font>\n");
		}
		if(0) print("checkFormVars: $new_rec_pid, $str_new_name<br>");
		if(0) exit;
		return($flgErr);
}



?>
<html>
	<head><title>Dwyer & Associates</title></head>
	<body>
		
<h1>Add New Record </h1>
<div align=center>
<form name=frmTodosAdmin method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value="LoadRecTemplate">
  <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
  <input type=hidden name="ufile" value="<?=$ufile;?>">

  <table border=0 width=60%>
     <tr> 
            <td width=150><input type=image src="/images/spacer.gif" value="AddNew" onclick="this.form.pAction.value=this.value"
				width=0 length=0 height=0>
            <td width=150> &nbsp;
            <td width=175> &nbsp;
	    <td width=200>&nbsp;
    <tr>
<!-- ##################################################################### -->
      <td>Category: <?=$cat_name?><br>
	<?           //todosSelectCats(IDX_ROOT_PAGEID,$cat_pid);
 ?>
      &nbsp;
<!-- ##################################################################### -->
      <td><td>TD Class:  <?=$td_class?> <br>

	<? 	if(0) print("td_class: $my_td_class<br>");
		//todosSelectClass($td_class,$my_td_class,'',0,1,1,1); ?>
			<!--<input type=checkbox name=cols value='*'
				<? //if($cols) echo("checked");?>
				onchange="this.form.submit( )"
				onclick="this.form.submit( )"
			>                   -->
    <tr>
<!-- ##################################################################### -->
      <td colspan=4 class='th'>
		<b>Page:</b> 
 
	<input name="new_rec_pid" value="<? echo($new_rec_pid) ?>" width=500 size=50><br>
	
	<b>upload:</b>
		<input name="userfile" type=file size=45
		onchange='
				$fname = this.value;
				this.form.ufile.value=this.value;
			  	re = /^.*\\(\w+)$/;
			  	re = /^(.*\\)(.*)$/;
			  	re2 = /^(.*\/)(.*)$/;
				re3 = /\s/g;
				re4 = /\.(.*)/;
				$fname = $fname.replace(re,"$2");
				$ftitle = $fname;
				$ftitle = $ftitle.replace(re4," ($1)");
				this.form.title.value = $ftitle;
				$fname = $fname.replace(re3,"_");
				$fname = $fname.toLowerCase();
				$new_rec_pid = this.form.new_rec_pid.value;
			  	$new_rec_pid = $new_rec_pid.replace(re2,"$1");
				this.form.rec_pid.value = $new_rec_pid + $fname;
			'>
    <tr>
	<td colspan=4 align=center>
<?


	if(is_array($ch)){
		switch($pAction){
			default		:
            //todosEditCHash($ch,$td_class,$cols);
            todosEditCHash($ch,$td_class,$cols,$flgClassOnly,$paramList);
            break;
		}
	}
		
	echo(" </td></tr>\n");
	echo(" <tr><td>&nbsp;\n");
	echo(" <tr><td colspan=4 align=center>\n");
	echo(" <input type=submit value=\"AddNew\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Cancel\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");

	
?>
</form>
  </table>

<script language="javascript">
		$1st = "rec_pid";
                $idx = 0;
		$form = document.forms[0];
		$elem = $form.elements[$1st];
                //alert($elem.name);
		$elem.focus();
		$elem.select();
</script>