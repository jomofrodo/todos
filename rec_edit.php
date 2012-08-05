<?@include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");?>
<?
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_hdr.php");
?>
<? //Default pMode
	if(0) print("pMode: $pMode<br>\n");
	if(! $pMode) $pMode = 'EDIT';

     //edit the category if no rec_pid specified
     if(! $rec_pid) $rec_pid = $cat_pid;
?>
<!--  ################################################################ -->
<form name=frmRecSelect method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value='<?=$pAction?>'>
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name="flgMode">
  <input type=hidden name="pForm" value="recProperties">
  <input type=hidden name="rec_pid" value='<?= $rec_pid ?>'>
  <input type=hidden name="tdID" value='<? echo $tdID?>'>
  <input type=hidden name="rspage" value=1>
  <input type=hidden name="srch_terms" value="<?=$srch_terms;?>">
  <table border=1>
	<tr>
		<td colspan=5>
		<? #todosListRelated($page_id);?>
    <tr>
	<th width=150 class=th>IDX Record Editor
    <td colspan=2>
     <? 	printSelect("rec_pid_slc",'tblTodos','tdPageID','tdPageID',$rec_pid,0,1,'',
		'',
		0,'this.form.rec_pid.value=this.value;this.form.submit();'); ?>

	<th colspan=3 align=left>
    <? // Sub Modes
       $EDIT_MODES = array("EDIT"=>'EDIT',"IDX1"=>'Properties',"IDX2"=>'Link Files',"IDX3"=>'Relations',"CAT"=>'Categories');
       editModeButtons($EDIT_MODES,$pMode,0);
 	?>
      </table>
 </form>
<!--  ################################################################ -->

  <table border=1 width=100% height=500>
	
<!--  ################################################################ -->
    <tr> <td colspan=7 align=center valign=top>
<?

	switch($pMode){
		case "EDIT"	:
				$currFile = convPID2Path($rec_pid);
				if(!file_exists($currFile)){
				  include_once(dirname(__FILE__)."/div_rec_selector.php");
				  print("Could not locate the requested file: $rec_pid<br>");
				 ?>
					<script language=javascript>
					JM_toggleLayer('','LayerRecordSelector');
					</script>
				 <?
				 break;
				}


			switch ($doc_type){

				case "STANDARD":  ?>
  <form name=frmRecEdit method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value='<?=$pAction?>'>
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name="flgMode">
  <input type=hidden name="pForm" value="recProperties">
  <input type=hidden name="rec_pid" value='<?= $rec_pid ?>'>
  <input type=hidden name="tdID" value='<? echo $tdID?>'>
  <input type=hidden name="rspage" value=1>
  <input type=hidden name="srch_terms" value="<?=$srch_terms;?>">
  <?
                   echo(" <tr><td colspan=4 align=center valign=top>\n");
                   echo "<INPUT TYPE=\"SUBMIT\" NAME=\"confirm\" VALUE=\"Save\"\n";
     		       echo("	onclick=\"this.form.pAction.value='SaveFile'\">");
    		       echo(" <input type=submit value=\"Cancel\"");
     		       echo("	onclick=\"this.form.pAction.value = this.value\">");
    		       echo(" <input type=submit value=\"Delete\"");
      		       echo("	onclick=\"this.form.pAction.value = this.value\">");
    		       echo(" <input type=submit value=\"Move\"");
      		       echo("	onclick=\"this.form.pAction.value = this.value\">");

    					$fp=fopen($currFile,"r");
    					$contents=fread($fp,filesize($currFile));
                    echo "<tr><td valign=top align=center colspan=4>";
    				echo "<TEXTAREA NAME=\"code\" rows=\"20\" cols=\"80\">\n";
    				echo htmlspecialchars($contents);
     				echo "</TEXTAREA><BR>\n";
                    $focusElement = 'code';
     				break;
				case "BIN":
    				echo(" <tr><td colspan=4 align=center valign=top>\n");
    				echo(" <input type=submit value=\"Delete\"");
      				echo("	onclick=\"this.form.pAction.value = this.value\">");
   		            echo(" <input type=submit value=\"Move\"");
      		       echo("	onclick=\"this.form.pAction.value = this.value\">");

					$url = SITE_ROOT . $rec_pid;
					//print ("<frameset rows=\"100%\">\n");
					//print ("<frame name=\"rec_frame\" src=\"$url\" \n");
					//print ("</frameset>\n");
    				print ("<tr width=100% height=100% valign=top>\n");
					print ("<td width=100% height=500 valign=top>");
					print ("<iframe name=\"rec_frame\" src=\"$url\" \n");
					print ("width=\"100%\" height=\"100%\" >");
					print ("</iframe>");
                    $focusElement = 'rec_frame';
					break;
			}
            echo "</FORM></BODY></HTML>";

			break;

		case "IDX1"	:
			include_once(dirname(__FILE__)."/rec_edit_idx1.php");
			break;
		case "IDX2":
			include_once(dirname(__FILE__)."/idx2.php");
			break;
		case "IDX3":
			include_once(dirname(__FILE__)."/rec_edit_idx3.php");
			break;
		case "CAT":
			include_once(dirname(__FILE__)."/rec_edit_cat.php");
			break;
	}



?>

  </table>
<!-- ###########################################################################  -->
</form>

<?php include_once($_SERVER[DOCUMENT_ROOT] . "/_include/cf.php"); ?>
 <script language="javascript">
	if(document.forms['frmRecEdit']){
		//$idx = 'code';
        $idx ='<?=$focusElement?>';
        if(! $idx) $idx = 1;
		$form = document.forms['frmRecEdit'];
		$length = $form.elements.length;
		$elem = $form.elements[$idx];
		//alert($elem.type);
		if(($elem.type == 'hidden')&&($idx < $length)){
			$idx++;
			$elem = $form.elements[$idx];
		}
		//alert($elem.name);
		if($elem.type == 'text'){
			$elem.focus();
			$elem.select();
		}
	}
</script>