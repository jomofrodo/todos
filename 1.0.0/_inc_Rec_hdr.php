<? /*

     Record context header to include at top of all Todos admin pages

     if recMode 'selectAllRecs' -- print a select box for all records
     if recMode 'selectCatRecs' -- print a select box with just records from current category
     if recMode 'staticRec'    -- Just print the current rec_pid
     if recMode 'editRec ' -- Output rec_pid in updateable input box
*/
?>
<?  // pAction switcher for Record level actions
		switch($pAction){
			case "Cancel"	: break;
			case "ClassProperties" :
						$url = TODOS_ROOT . PAGE_CLASS_EDIT .
							"?td_class=$td_class&rspage=$rspage";
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
                        //$url = $rtrn_url;
                        //if(! $url){$url = TODOS_ROOT . PAGE_TODOS_SUMMARY; }
                        $url = TODOS_ROOT . PAGE_CAT_SUMMARY;
                        if(preg_match("/\?/",$url))$url .=  "&cat_pid=$cat_pid";
                        else $url .= "?cat_pid=$cat_pid";
                        $url .= "&td_class=$td_class&rspage=$rspage&rec_num=$rec_num";
						ob_end_clean();
						header("Location:$url");
						break;
					}
					break;
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
    					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"pAction\" VALUE=\"Move\">\n";
					echo "<tr><td>New file name:<td>\n";
					echo "<input name=\"new_rec_pid\" size=35 value=\"$rec_pid\">\n";
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
                        $ret1 = todosMoveFile($old_rec_pid,$new_rec_pid);
                        if($ret1){
                           $ret2 = todosMoveCHash($old_rec_pid,$new_rec_pid);
                           $rec_pid = $new_rec_pid;
   						   $url = $PHP_SELF .
						   "?rec_pid=$rec_pid&cat_pid=$cat_pid&td_class=$td_class&rspage=$rspage";
                        }
                        else{
                            $new_name = $args[new_rec_pid];
                            $msg = ("Problem renaming file to $new_name. <br> ");
                            $msg .= ("Check for a problem in the path. Remember that paths are case-SenSiTive.<br>");
                            $rec_pid = $old_rec_pid;
                     		$url = $PHP_SELF .
						    "?rec_pid=$rec_pid&pAction=Mv&errmsg=$msg&cat_pid=$cat_pid&td_class=$td_class&rspage=$rspage";
                        }
    				    ob_end_clean();
						header("Location:$url");
						break;
					}
					if($cancel){
						$url = $PHP_SELF .
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
           case "Touch":
                $file = todosConvPID2Path($rec_pid);
                touch($file);
                chmod($file,0777);
                chgrp($file,'users');
                if($link_touch)  todosLinkRecs2Cat($rec_pid,$cat_pid,$bass_class);
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
?>
<!------------------------------------- Edit Mode Form ---------------------  -->
<form name=frmLink action="<?=$URI?>" ENCTYPE="multipart/form-data" method=POST>
  <input type=hidden name="pAction" value='<?=$pAction?>'>
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name=gAction value=''>
  <input type=hidden name="flgMode">
  <input type=hidden name=glimpse_pid value="<?=$glimpse_pid?>">
  <input type=hidden name=cat_pid value="<?=$cat_pid?>">
  <input type=hidden name="rec_pid" value='<?= $rec_pid ?>'>
  <input type=hidden name="td_class" value='<?= $td_class ?>'>
  <input type=hidden name="srch_terms" value="<?=$srch_terms;?>">
  <input type=hidden name=DirOverride>
  <input type=hidden name=unlink_subcat>
  <input type=hidden name=unlink_file value=0>
  <input type=hidden name=link_file>
   <input type=hidden name="DBGSESSID" value="<?=$DBGSESSID;?>">


<? //Table Header
    print("<table><tr>");
	if(0) print("pMode: $pMode<br>\n");
    print("<th width=150 class=th>IDX Record Editor");
    print("<td colspan=2>");
    /* Record Edit Modes: */

    switch($recMode){
            case 'selectAllRecs':
               	printSelect("rec_pid_slc",'tblTodos','tdPageID','tdPageID',$rec_pid,0,1,'',
	           	'',
		        0,'this.form.rec_pid.value=this.value;this.form.submit();');
                break;
            case 'editRec':
                print ("<input name='rec_pid_edit' onChange='this.form.rec_pid.value=this.value;
                                this.form.pAction.value=\'Mv\';'>");
                break;

            case 'staticRec':
                default:
                print("<b>$rec_pid</b>&nbsp;");
                break;
           case 'selectCatRecs':
           default:
      		   $sql_rs = "SELECT tdURL as rec_pid from tblTodos\n";
    		   $sql_rs .= " WHERE tdPageID = '$cat_pid'\n";
    		   $sql_rs .= " AND tdClass = 'member'\n";
    		   $sql_rs .= " GROUP BY tdURL";
               printSelect('rec_pid_slc','tblTodos','rec_pid','rec_pid',$rec_pid,0,1,$sql_rs,
       		            '',
    	            	0,'this.form.rec_pid.value=this.value;this.form.submit();');
                break;


    }

	print("<th colspan=3 align=left>");
  // Sub Modes
       $EDIT_MODES = array("IDX1"=>'Properties',"IDX2"=>'Link Files',"IDX3"=>'Relations',"CAT"=>'Categories',"SRC"=>'Source',"VIEW"=>'View');
       editModeButtons($EDIT_MODES,$pMode,0);
    print("</tr></table>");
  // start HTML
?>
</form>
<!-------------------------------------  /Edit Mode Form ---------------------  -->