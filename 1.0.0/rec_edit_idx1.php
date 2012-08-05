<? ob_start();
//session_cache_limiter('private'); ?>
<?//@include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");?>
<?
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
     include_once(dirname(__FILE__)."/_inc_Rec_hdr.php");
?>
<?
		if(! $pForm) $pForm 	= 'recProperties';
       $rec_url = todosConvPID2URL($rec_pid);

 ### Switch PAction

		switch($pAction){
			case "Update"	: ## update the record and stay on the edit page
					  $ret = todosUpdateCHash($args,$ct=0);
					  break;
			case "Cancel"	: break;
			case "CatProperties" :
						$url = TODOS_ROOT . PAGE_CAT_EDIT .
							"?cat_pid=$cat_pid&rspage=$rspage";
					  	header("Location: $url");
						break;
			case "Delete"	:
					if((! $confirm) &&(! $cancel)){
					echo "<table><tr height=275 valign=center><td>\n";
					echo "<div align=center>\n";
    					echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
                    echo "<input type=hidden name=rtrn_url value=\"".$_SERVER['HTTP_REFERER']."\">";
   					echo "Current Filename is: ".$rec_pid ." ";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"rec_pid\" VALUE=\"$rec_pid\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cat_pid\" VALUE=\"$cat_pid\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"rec_num\" VALUE=\"$rec_num\">\n";
    					#echo "<INPUT TYPE=\"HIDDEN\" NAME=\"td_class\" VALUE=\"$td_class\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"pAction\" VALUE=\"Delete\">\n";
					echo ("Delete this record?<br>\n");
					echo "<INPUT type=checkbox name=flgDeleteAllCats> Delete from all categories.<br>\n";
					echo "<INPUT type=checkbox name=flgDeleteFile> Delete physical file.<br>\n";
					echo "<P>\n";
 					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"confirm\" VALUE=\"Delete\">\n";
					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\"Cancel\"><BR>\n";
					echo "</div>\n";
					exit;
					break;
					}
					if($confirm){
						$ret = todosDeleteCHash($args);
   					    #$ret = deleteTodos_Form($args);
 	                    ## Delete Physical File
	                    if($flgDeleteFile) $ret = deleteFile($rec_pid);
                        $url = $rtrn_url;
                        if(! $url){$url = TODOS_ROOT . PAGE_TODOS_SUMMARY; }
                        if(preg_match("/\?/",$url))$url .=  "&cat_pid=$cat_pid";
                        else $url .= "?cat_pid=$cat_pid";
                        $url .= "&td_class=$td_class&rspage=$rspage&rec_num=$rec_num";
						ob_end_clean();
						header("Location:$url");
						break;
					}
					break;
			case "Mv"	:
            case "Move" :
					if((! $confirm) &&(! $cancel)){
                	echo "<div align=center>\n";
					echo "<table border=0><tr height=275 align=center valign=center><td>\n";
					echo "<table border=0>\n";
                   if($errmsg){
                      print("<tr><td colspan=2><font color=red>$errmsg</font>\n");
                   }
                   echo "<tr><td>\n";
  					echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
   					echo "Current Filename is:<td> ".$rec_pid ." ";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"old_rec_pid\" VALUE=\"$rec_pid\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cat_pid\" VALUE=\"$cat_pid\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"td_class\" VALUE=\"$td_class\">\n";
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"pAction\" VALUE=\"Mv\">\n";
					echo "<tr><td>New file name:<td>\n";
					echo "<input name=\"new_rec_pid\" size=35 value=\"$rec_pid\">\n";
					echo ("<tr><td>Move this record to new name?<br>\n");
					echo "<tr><td colspan=3 align=center>&nbsp;\n";
					echo "<tr><td colspan=3 align=center>\n";
 					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"confirm\" VALUE=\"Move/Rename\">\n";
					echo "<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\"Cancel\"><BR>\n";
					echo "</table>\n";
					echo "</table>\n";
					echo "</div>\n";
					exit;
					break;
					}
					if($confirm){
                        $ret1 = todosMoveFile($args);
                        if($ret1){
                           $ret2 = todosMoveCHash($args);
                           $rec_pid = $new_rec_pid;
   						   $url = TODOS_ROOT . PAGE_REC_EDIT .
						   "?rec_pid=$rec_pid&cat_pid=$cat_pid&td_class=$td_class&rspage=$rspage";
                        }
                        else{
                            $new_name = $args[new_rec_pid];
                            $msg = ("Problem renaming file to $new_name. <br> ");
                            $msg .= ("Check for a problem in the path. Remember that paths are case-SenSiTive.<br>");
                            $rec_pid = $old_rec_pid;
                     		$url = TODOS_ROOT . PAGE_REC_EDIT .
						    "?rec_pid=$rec_pid&pAction=Mv&errmsg=$msg&cat_pid=$cat_pid&td_class=$td_class&rspage=$rspage";
                        }
    				    ob_end_clean();
						header("Location:$url");
						break;
					}
					if($cancel){
						$url = TODOS_ROOT . PAGE_REC_EDIT .
						"?rec_pid=$old_rec_pid&cat_pid=$cat_pid&td_class=$td_class&rspage=$rspage";
						ob_end_clean();
						header("Location:$url");
						break;
					}
					break;

   			case "SaveFile"	:
						if($confirm) {
  						  $fp=fopen(SITE_PATH_ROOT.$rec_pid,"w");
 						  fputs($fp,stripslashes($code));
 						  fclose($fp);
						}
						break;
			case "Upload"	:
						if(0) var_dump($_FILES);
						if(0) exit;
   						$basedir= SITE_PATH_ROOT;
						$uploadfile = $basedir . $rec_pid;
						$uploadfile = preg_replace ('/\/\//','/',$uploadfile);
						if(0) print("Copying a file: $ufile,$basedir,$rec_pid<br>");
						copyFile($uploadfile);
					break;

		}

    if($pForm == 'recProperties'){
			$flgRealOnly=1;
               $flgDisplay =1;
			$ch = todosGetClassHash($rec_pid,$td_class,'','',$flgRealOnly,$flgDisplay);
		}
	else $td = todosGetTodos_Form($args);


?>
<html>



<body>
<div id=lyrEdit  style="position:absolute; width:395px; height:405px; z-index:3; left: 398px; top: 111px"> 
  <form name=frmRecEdit method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value='<?=$pAction?>'>
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name="flgMode">
  <input type=hidden name="pForm" value="recProperties">
  <input type=hidden name="rec_pid" value='<?= $rec_pid ?>'>
  <input type=hidden name="cat_pid" value='<?= $cat_pid ?>'>
  <input type=hidden name="tdID" value='<? echo $tdID?>'>
  <input type=hidden name="rspage" value=1>
  <input type=hidden name="srch_terms" value="<?=$srch_terms;?>">

<table border=1 width=70%>
<?
    		echo("	<tr> \n");
    		echo("	 <td> Class: \n");
    		echo("	 <td colspan=2> \n");
				todosSelectClass('td_class',$td_class,'',0,1,1,$flgReverse);
				echo("	<input type=checkbox name=flgReverse value='1' \n");
				if($flgReverse) echo("checked");
				echo("			onchange=\"this.form.submit( )\"	\n");
				echo("			onclick=\"this.form.submit( )\"	\n");
				echo("		>\n");

				echo("		<td>\n");
 				echo("	<tr> <td colspan=7 align=center>\n");
					switch($pForm){
						case 'recProperties'	:	todosEditCHash($ch,$td_class,$cols); break;
						case 'recRelations'	:	todosPrintTodos($td);
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
					echo(" <input type=submit value=\"Move\"");
  					echo("	onclick=\"this.form.pAction.value = this.value\">");
					echo(" <input type=submit value=\"Summary\"");
  					echo("	onclick=\"this.form.pAction.value = this.value\">");
					echo(" <input type=submit value='AddNew' onclick='this.form.pAction.value=this.value'> ");

                    ?>

</table>
</form>
</div>
<div id="lyrRecView" style="position:absolute; width:360px; height:385px; z-index:3; left: 18px; top: 116px"> 
  <a href='<?=$rec_pid?>' target='_recView'><iframe frameborder=1 src="<?=$rec_pid?>" height=100% width=100% ></a> 
</div>