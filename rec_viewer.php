<? //session_cache_limiter('private'); ?>
<?php if (SITE_HDR) include_once ($_SERVER['DOCUMENT_ROOT'] . "/_include/ch.php"); ?>
<?
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
?>
<?
    $SITE_ROOT = $_SERVER['HTTP_HOST'];
    $url = SITE_ROOT . $rec_pid;
    if(0) print("URL: $url<br>");
?>

<? if(0) var_dump($_SERVER); ?>

</head>
<body topmargin=0 marginheight=0>
<form name=frmTodosViewer method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value='<?=$pAction?>'>
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name="flgMode">
  <input type=hidden name="pForm" value="recProperties">
  <input type=hidden name="rec_pid" value='<? echo $rec_pid?>'>
  <input type=hidden name="rspage" value=1> 
  <input type=hidden name="srch_terms" value="<?=$srch_terms;?>"> 
  <table border=0>
<!--
      <tr>	
	<th width=150>Todos 
		<?
/*
	   ## On/Off box

##		print ("<INPUT type=checkbox name=flgTodos \n");
##		if($flgTodos) print("checked ");
##		print (" value=1 onclick=\"this.form.submit()\" >");
 ##    if($flgTodos){ 
  	$edit_url = TODOS_ROOT . PAGE_REC_EDIT;  
    	$edit_url .= "?rec_pid=$rec_pid&bass_class=$td_class&td_type=idx3&pMode=EDIT";

	print("<th colspan=3 align=left>\n");
	     foreach($MODES as $m=>$title){
		if(! $flgButtons){
		print ("<input type=submit name='$m' value='$title' \n");
		print ("	onclick=\"this.form.pMode.value=this.name;\n");
	 	print ("		this.form.flgMode.value=1;\"\n");
	 	if($pMode == $m) print("disabled=\"true\" \n");
	   	print (">\n");
		}
		else{
	    //Buttons
		$src = $BUTTONS[$m];
	 	if($pMode == $m) $src= $BUTTONS_ACTIVE[$m];
		print ("<input type=\"image\" src=\"$src\" value='$m' \n");
		print ("	onclick=\"this.form.pMode.value=this.value;\n");
	 	print ("		this.form.flgMode.value=1;\"\n");
	   	print (">\n");
		}
	     }
  ##   }

*/
	?>

</table>
-->
<!--  ################################################################ -->
  <table border=0 width=100% height=500 valign=top>

    <tr width=100% height=100% valign=top>
    <td><!-- nav elements -->
      <? //Record select dropdown
        /*
          $cat_table = getCatTableName($cat_pid);
          $sql = "SELECT DISTINCT title,pid FROM $cat_table WHERE title != '' ORDER BY title";
          if (0) print($sql);
      	printSelect("rec_pid_slc",$cat_table,'title','pid',$rec_pid,0,1,$sql,'',0,'this.form.rec_pid.value=this.value;this.form.submit();','','',5);
        */
         ?>
      <? //Record related dropdown
/* Turning this off pending some future development
    -- Jomo 01/05
          $sql = "SELECT DISTINCT t2.tdPageID as pid,t2.tdTitle as title,t2.tdURL \n";
          $sql .= "FROM `tblTodos` as t1, tblTodos as t2 \n";
          $sql .= "WHERE t1.tdPageID =  '". $rec_pid ."' \n";
          $sql .= "AND t1.tdType='idx3' \n";
          $sql .= "AND t1.tdURL = t2.tdPageID \n";
          $sql .= "and t2.tdType = 'idx0' \n";
          if (0) print($sql);
      	printSelect("rec_pid_slc",'tbl_todos','title','pid',$rec_pid,0,1,$sql,'',0,'this.form.rec_pid.value=this.value;this.form.submit();','','',5); 
*/
?>
	<td rowspan=2 colspan=7 align=center width=100% height=100% valign=top>
     <?  //In-line Frames
          print("<iframe frameborder=1 src=\"$rec_pid\" width=100% height=2000>");
     /*

		switch ($doc_type){
			case "STANDARD":
				$file = '';
				if(0) print ("rec_pid: $rec_pid<br>");
				if($rec_pid){ $file = $_SERVER[DOCUMENT_ROOT]. $rec_pid; }
				if($file == $_SERVER[PATH_TRANSLATED]){$file='';}
				if( file_exists($file)){
					include_once($_SERVER[DOCUMENT_ROOT]. $rec_pid);
				}
				else {
				  include_once(dirname(__FILE__)."/div_rec_selector.php");
				  //print("Could not locate the requested file: $rec_pid<br>");
				 ?>
				<!--
      		  		<input type="button" name="btnSelect" value="Record"
				onClick="
				JM_toggleLayer('','LayerRecordSelector');
				">

				-->
				<script language=javascript>
				JM_toggleLayer('','LayerRecordSelector');
				</script>
				 <?
				}

				break;
			case "BIN":
				//$url = SITE_ROOT . $rec_pid;
				//print ("<frameset rows=\"100%\">\n");
				//print ("<frame name=\"rec_frame\" src=\"$url\" \n");
				//print ("</frameset>\n");
				print ("<iframe name=\"rec_frame\" src=\"$url\" \n");
				print ("width=\"100%\" height=\"100%\" >");
				print ("</iframe>");
				break;
			case "FULL":
				header( "Location:$url");
				break;
		}
  */

	?>


	</td>
    </tr>

  </table>
<!-- ###########################################################################  -->
<?  include_once(dirname(__FILE__)."/div_rec_selector.php"); ?>
</form>
<? if (defined(SITE_FTR)) include_once ($_SERVER['DOCUMENT_ROOT'] . SITE_FTR); ?>