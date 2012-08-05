<? ob_start();
//session_cache_limiter('private'); ?>
<?@include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");?>
<?
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
?>
<?
    $recMode = 'staticRec';
     include_once(dirname(__FILE__)."/_inc_Rec_hdr.php");

?>
<?
  // Page to add and edit links in the todos database
  // this is a lot like idx3, except . . .
  //      this looks at files in the FS creating idx3 relations using idx2 style
  //      associations. It's cool, really. Trust me.
  // Three or four things to keep track of here
  //      rec_pid -- the record we are linking things to
  //      cat_pid -- navigational device to get us in the neighborhood
  //      glimpseDir  --  Directory of files to link to
  //      link_files    -- files to link to rec_pid
  //      unlink_pids  -- same thing, only not
  //      file -- ??

  $nav_global="admin";
  $nav_section="";
  $page_short_title="Todos Glimpse";

   session_register("GlimpseDir");
   //$excluded = "^_|^\.[A-Za-z]|CVS";
   $excluded = "^\.[A-Za-z]|CVS";
   $disp_length=30;

?>
<?
#####################################################################################
  //This is GLIMPSE
   ### This little bit of FS magic based on glimpse -- written by Derek Young, downloaded from:
## 	http://codewalkers.com/getcode.php?id=60
##	Thanks Derek!
     // NOTE rec_pid and cat_pid can be a little slippery on this page.
    // rec_pid is actually the record that is acting as the "category"
    // cat_pid is just used to navigate to different locations in the Todos FS
    // for purposes of displaying records to link
    // link_records and unlink_records are the files to make members of rec_pid
   $basedir= SITE_PATH_ROOT;
  #$basedir= "/www/servers/Dwyer/d3dev/upload";
   $PHP_SELF = $_SERVER['PHP_SELF'];
   $URI        = $_SERVER['REQUEST_URI'];
   if(0) print_r($args);
	$link_pids = array();
    $reload = 0;
    $link_pids = $args['link_pid'];
    $link_subcats = $args['link_subcat'];
    ### GlimpseDir is a real headache!  Maybe the whole concept is wrong??
    ###
    //get rid of bogus GET settings
     if($glimpse_pid == "") $glimpse_pid = null;
     if($DirOverride && ($DirOverride != "")){
        if($cat_pid){$glimpse_pid = $cat_pid;$rec_pid = $cat_pid;}
     }
     if(!$glimpse_pid && $file) $glimpse_pid = $file;
     //if(!$glimpse_pid && $cat_pid){ $glimpse_pid = $cat_pid; $rec_pid=$cat_pid;}
     if(!$glimpse_pid && $cat_pid){ $glimpse_pid = $cat_pid;}
     if(!$glimpse_pid) $glimpse_pid= IDX_ROOT;
     if(! $cat_pid) $cat_pid = $glimpse_pid;
     if(! $rec_pid) $rec_pid = IDX_ROOT;              //Link to root if no record


        //Change Directory
    // $action=$_GET['action'];
     //if($action=='chdr')$glimpse_dir = $_GET['glimpse_dir'];
     if(!$glimpse_dir)$glimpse_dir = todosConvPID2URL($glimpse_pid);
      // which doesn't work for '/idx' for some reason
     if($glimpse_dir == '\\') $glimpse_dir = IDX_ROOT_DIR;

     $glimpse_dir = preg_replace("/\/([\w\d]+)?\/\.\./U","",$glimpse_dir);
     if(0) print("glimpse_dir: $glimpse_dir  ||");
     if(0) print("basedir: $basedir");
     $GlimpseDir=$basedir . $glimpse_dir;
     //$RRGlimpseDir=preg_replace("/".IDX_ROOT_CATNAME."/",'',$glimpse_pid);
     // $GlimpseDir = $glimpse_pid . "/";  //Redundant??
     //$glimpse_pid == IDX_ROOT?$GlimpseDir = $glimpse_pid:$GlimpseDir = $glimpse_pid ."/";
     // following only useful if IDX something other than system directory
     // symbol, e.g., "/"
     //$GlimpseDir = preg_replace("/".IDX."/",'',$GlimpseDir);//strip index file
    //<ASSUMPTION>
    // files
    $dir = $basedir. $glimpse_dir;
    $dir = preg_replace("/\/\//",'/',$dir);
    $files = getDirFiles($dir,'*',$strFilter,$excluded,0);
    $dir_pids =todosGetDirPIDs($dir,$strFilter,$excluded);

    //Todos
    $rec_pid = $rec_pid;
    $cat_pid = $cat_pid;
    $dir = $dir;
    $glimpse_pid = $glimpse_pid;
    $GlimpseDir=$GlimpseDir;
    $td_class = $td_class;      // class of record we are linking to
    $bass_class = $bass_class;  // bass class
    $addons = "?rec_pid=$rec_pid&cat_pid=$cat_pid&glimpse_pid=$glimpse_pid&td_class=$td_class";
    if(0) print_r($args);
 ### Done assigning variables, now do something with them
     // DEBUGGING
	if(0){
	    if($link_pids){
		checkMembers($rec_pid,$link_pids,$dir);
		if(0) exit();
	    }
	}
 switch($gAction){

        case 'LinkAll':
            // link/unlink selected files to the rec_pid
            $link_pids = $dir_pids;
            todosLinkRecs2Cat($rec_pid,$link_pids,$bass_class);
            break;
        case 'UnlinkAll':
            $unlink_pids = $dir_pids;
            todosUnlinkFiles($rec_pid,$unlink_pids,$bass_class);
            break;
        case "Link":
 	        if($unlink_pid) $unlink_pids = getUnlinkFiles($dir_pids,$link_pids,$dir);
            if($unlink_subcat) $unlink_subcats = getUnlinkSubcats($dir_pids,$link_subcats,$dir);
            if($link_pids)  todosLinkRecs2Cat($rec_pid,$link_pids,$bass_class);
            if($unlink_pids) todosUnlinkFiles($rec_pid,$unlink_pids);
            if($link_subcats) todosLinkSubs2Cat($rec_pid,$link_subcats);
            if($unlink_subcats) todosUnlinkSubcats($rec_pid,$unlink_subcats);
            break;
       case "root":
            $GlimpseDir = IDX_ROOT;
            break;
       case "cancel":
            $gAction = "";
            break;
        case "Delete"	:
				if((! $confirm) &&(! $cancel)){
				echo "<table width=90%><tr height=75 valign=center><td>\n";
				echo "<div align=center>\n";
					echo "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
                echo "<input type=hidden name=rtrn_url value=\"".$_SERVER['HTTP_REFERER']."\">";
				echo "Current Filename is: <b>".$file_pid ."</b><br> ";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"rec_pid\" VALUE=\"$rec_pid\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"file_pid\" VALUE=\"$file_pid\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cat_pid\" VALUE=\"$cat_pid\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"rec_num\" VALUE=\"$rec_num\">\n";
                    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"glimpse_dir\" VALUE=\"$glimpse_dir\">\n";
					#echo "<INPUT TYPE=\"HIDDEN\" NAME=\"td_class\" VALUE=\"$td_class\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"gAction\" VALUE=\"Delete\">\n";
				echo ("Delete this record?<br>\n");
				echo "<INPUT type=checkbox name=flgRecursion checked> Delete from all categories.<br>\n";
				echo "<P>\n";
				echo "<INPUT TYPE=\"SUBMIT\" NAME=\"confirm\" VALUE=\"Delete\">\n";
				echo "<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\"Cancel\"><BR>\n";
				echo "</div>\n";
				exit;
				break;
				}
				if($confirm){   // unlink from all categories
                   todosUnlinkRec($file_pid,$rec_pid,$flgRecursion);
 				    #$ret = deleteTodos_Form($args);
                    ## Delete Physical File
                     $ret = deleteFile($file_pid);
                    //have to do this again now:
                    // not working for some reason . . .
                    //reloadnow($files,$dir_pids);
                    $url = $PHP_SELF .
					   "?rec_pid=$rec_pid&glimpse_dir=$glimpse_dir&cat_pid=$cat_pid&td_class=$td_class&rspage=$rspage";
				    ob_end_clean();
					header("Location:$url");
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
				echo "Current Filename is:<td> ".$file_pid ." ";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"old_file_pid\" VALUE=\"$file_pid\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"rec_pid\" VALUE=\"$rec_pid\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cat_pid\" VALUE=\"$cat_pid\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"td_class\" VALUE=\"$td_class\">\n";
                    echo "<INPUT TYPE=\"HIDDEN\" NAME=\"glimpse_dir\" VALUE=\"$glimpse_dir\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" NAME=\"gAction\" VALUE=\"Mv\">\n";
				echo "<tr><td>New file name:<td>\n";
				echo "<input name=\"new_file_pid\" size=35 value=\"$file_pid\">\n";
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
                    $ret1 = todosMoveFile($old_file_pid,$new_file_pid);
                    if($ret1){
                       //$ret = todosRemoveFromCategory($old_file_pid,$rec_pid);
                       $ret2 = todosMoveCHash($old_file_pid,$new_file_pid);
                       $file_pid = $new_file_pid;
					   $url = $PHP_SELF .
					   "?rec_pid=$rec_pid&glimpse_dir=$glimpse_dir&cat_pid=$cat_pid&td_class=$td_class&rspage=$rspage";
                    }
                    else{
                        $new_name = $args[new_file_pid];
                        $msg = ("Problem renaming file to $new_name. <br> ");
                        $msg .= ("Check for a problem in the path. Remember that paths are case-SenSiTive.<br>");
                        $file_pid = $old_file_pid;
                 		$url = $PHP_SELF .
					    "?file_pid=$file_pid&gAction=Mv&errmsg=$msg&glimpse_dir=$glimpse_dir&rec_pid=$rec_pid&cat_pid=$cat_pid&td_class=$td_class&rspage=$rspage";
                    }
				    ob_end_clean();
					header("Location:$url");
                    reloadnow(&$files,&$dir_pids);
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

       case "Upload":
            $fname = $_FILES['userfile']['name'];
            //preg_match("/([a-zA-Z._ ]*)/",$fname,$amatch);
            //$fn = $amatch[1];
            $fn = $fname;
            if(! $fn) print("Could not determine upload file name from $fname");
             else{
                    copyFile('userfile',$dir.$fn);
                    reloadnow($files,$dir_pids);
             }
             break;
       case "Touch":
           $touch_path = $dir . OS_DIR_SEPARATOR . $touchfile;
           $touch_pid = todosConvPath2PID($touch_path);
            touch($touch_path);
            chmod($touch_path,0777);
            chgrp($touch_path,'users');
            if($link_touch)  todosLinkRec2Cat($touch_pid,$rec_pid,EO_CLASS_MEMBER);
            reloadnow($files,$dir_pids);
            break;
       case "MkDir":
            $mkdir_path = $dir . OS_DIR_SEPARATOR . $mkdirfile;
           $ret = createDir($mkdir_path);
            reloadnow($files,$dir_pids);
            break;
 }


?>
<?php
// Page Specific Utility Functions

function checkMembers($rec_pid,$files,$dir){
   //Debugging routine
   // run through the list of files and report on membership
   // 1 = member
   // 0 = non-member
	$i=0;
    while($file = array_shift($files)){
        $i++;
        //$filename=$dir.$file;
        $filename = $file;
        $filename= preg_replace("/\/\//","/",$filename);
	    $file_pid = todosConvPath2PID($filename);
        $flgCheck = todosCheckCatMember($file_pid,$rec_pid);
	    print("checkMember: $rec_pid,$file_pid: $flgCheck<br>");
    }
    return(0);
}

function getUnlinkFiles($files,$link_pids,$dir){
	// Get the list of files in this directory
	// not linked to the parent
	// returns an array
	$i=0;
	$files_fix = array();
	if(! $link_pids) $link_pids = array();
    while($file = array_shift($files)){
       //$filename=$dir.$file;
       $filename=$file;
       $filename= preg_replace("/\/\//","/",$filename);
	   $files_fix[$i] = $filename;
 	   $i++;
 	   if(0) print("$i: $filename<br>");
    }
    $unlink_pids = array_diff($files_fix,$link_pids);
	// hmmm , that was easy
	if(0) $debug=1;
	if($debug) print("getUnlinkFiles:<br>");
	if($debug) print("Files:<br>");
	if($debug) print_r($files_fix);
	if($debug) print("<br>Link Files:<br>");
	if($debug) print_r($link_pids);
	if($debug) print("<br>UnLink Files:<br>");
	if($debug) print_r($unlink_pids);

	return($unlink_pids);
}

function getUnlinkSubcats($files,$link_subcats,$dir){
	// Get the list of files in this directory
	// not linked to the parent
	// returns an array
	$i=0;
	$cats_fix = array();
	if(! $link_pids) $link_pids = array();
    if(! $link_subcats) $link_subcats = array();
    while($file = array_shift($files)){
       $filename=$file;
       $filename= preg_replace("/\/\//","/",$filename);
       if(preg_match("/.*\.$/",$filename)) continue;  // skip . and ..
       //if (filetype($filename) != 'dir') continue;
	   $cats_fix[$i] = $filename;
 	   $i++;
 	   if(0) print("$i: $filename<br>");
    }
    $unlink_cats = array_diff($cats_fix,$link_subcats);
	// hmmm , that was easy
	return($unlink_cats);
}

function reloadnow($files,$dir_pids) {
    /*
    global $URI;
    //global $addons;
    header("Status: 302 Moved");
    header("Location: $URI" . $addons);
    exit();
    */
    global $dir;
    //extract($args);
    $files = getDirFiles($dir,'*',$strFilter,$excluded,0);
    $dir_pids =todosGetDirPIDs($dir,$strFilter,$excluded);

}

function inputFile($f_pid,$t='',$d=''){
   $inpFile = <<< EOF
    <input name="userfile" type=file size=45
		onchange='
				$fname = this.value;
				if($d)this.form.$d.value=this.value;
			  	re = /^.*\\(\w+)$/;
			  	re = /^(.*\\)(.*)$/;
			  	re2 = /^(.*\/)(.*)$/;
				re3 = /\s/g;
				re4 = /\.(.*)/;
				$fname = $fname.replace(re,"$2");
                if($t){
				    $ftitle = $fname;
				    $ftitle = $ftitle.replace(re4," ($1)");
				    this.form.$t.value = $ftitle;
                }
				$fname = $fname.replace(re3,"_");
				$fname = $fname.toLowerCase();
				$f_pid = this.form.$f_pid.value;
			  	$f_pid = $f_pid.replace(re2,"$1");
				this.form.$f_pid.value = $f_pid + $fname;
			'>
EOF;
    return($inpFil);
}
?>



<div align=center>
<form name=frmLink action="<?=$URI?>" ENCTYPE="multipart/form-data" method=POST>
  <input type=hidden name="pAction" value='<?=$pAction?>'>
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name=gAction value=''>
  <input type=hidden name="flgMode">
  <input type=hidden name=cat_pid value="<?=$cat_pid?>">
  <input type=hidden name=glimpse_dir value="<?=$glimpse_dir?>">
  <input type=hidden name=glimpse_pid value="<?=$glimpse_pid?>">
  <input type=hidden name="rec_pid" value='<?= $rec_pid ?>'>
  <input type=hidden name="srch_terms" value="<?=$srch_terms;?>">
  <input type=hidden name=DirOverride>
  <input type=hidden name=unlink_subcat>
  <input type=hidden name=unlink_pid value=0>
  <input type=hidden name=link_file>
  <input type=hidden name=flgCheckAll value=0>

    <div class='title1' align=left>Record: <?=$rec_pid?></div>
<TABLE width=90% BORDER="1">

<tr><td colspan=5>

    <table border=0 width=90%><tr><td >

    <? //todosSelectSubCats(IDX_ROOT_PAGEID,$glimpse_pid,1,1,0,"this.form.glimpse_pid.value=this.value;this.form.submit()",1,1,'glimpse_pid_set')?>
    <br>
    Dir: <?=$glimpse_dir?>
        <td>
        <td>
        <td>Filter: <input name="strFilter" value="<?=$strFilter?>">
        <input type=submit value="Go">
        <td>
    </table>
<TR><TH>Filename<TH>Type<TH>Size<TH>Action<TH>Linu/Unlink</TR>
<?
    while($file = array_shift($files)){
        $i++;
        if(0) print("file: $file<br>");
        $filename = basename($file);
        $filename_disp = substr($filename,0,$disp_length);
        $file_pid = todosConvPath2PID($file);
        $fileurl = todosConvPID2URL($file_pid);
         $flgCheck = todosCheckCatMember($file_pid,$rec_pid);
        $fileType = filetype($file);
        if(0) print("filename: $filename, filepid: $file_pid, filetype($file)<br>");
        echo "<TR>";
        echo "<TD>" . htmlspecialchars($filename_disp) . "</TD>\n";
        echo "<TD>" . filetype($file) . "</TD>\n";
        echo "<TD>" . filesize($file) . "</TD>\n";
        echo "<TD>";
        if($fileType=="file") {         //view
        echo "<A HREF=\"$fileurl\" target=\"".TARGET_VIEW."\">View</A> ";
                                          //edit
        echo "<A HREF=\"". TODOS_ROOT . PAGE_REC_EDIT ."?rec_pid=$file_pid&pMode=EDIT\" target=". TARGET_EDIT . ">Edit</a>";
                                        // delete
        echo " <A HREF=\"". TODOS_ROOT . PAGE_IDX2_EDIT ."?rec_pid=$rec_pid&glimpse_dir=$glimpse_dir&file_pid=$file_pid&pMode=IDX2&gAction=Delete\" target=". "_self" . ">Del</a>";
                                        //Move
        echo " <A HREF=\"". TODOS_ROOT . PAGE_IDX2_EDIT ."?rec_pid=$rec_pid&glimpse_dir=$glimpse_dir&file_pid=$file_pid&pMode=IDX2&gAction=Mv\" target=". "_self" . ">Mv</a>";
    }
        if($fileType=="dir") { //chdir
                echo "<A HREF=\"".TODOS_ROOT.PAGE_IDX2_EDIT."?action=chdr&glimpse_dir=$fileurl&rec_pid=$rec_pid&cat_pid=$cat_pid\">ChDr</A> ";
        }
        echo "<td>\n";
                                        //link/unlink
         echo " l/u<INPUT name=link_pid[] type=checkbox value='$file_pid'";
          if (($flgCheckAll) || ($flgCheck)){         echo " checked ";
           }                                //unlink
         echo " onClick=\"if(!this.checked){this.form.unlink_pid.value=this.value;}\"
         //this.form.submit();\" ";
         echo ">";                            //subcat
         if(($fileType=="dir") && ($filename != "..")) {
             echo" sub<INPUT name=link_subcat[] type=checkbox value='$file_pid'";
             if(todosCheckCatMember($file_pid,$rec_pid,EO_CLASS_SUBCAT)) echo "checked";
             echo " onClick=\"if(!this.checked){this.form.unlink_subcat.value=this.value;} \" ";
         }
         //echo "<INPUT type=button value=Link name=\"$filename\"";
         //echo "onClick=\"this.form.link_file[$i].checked=1;this.form.submit()\"";
         //echo " >";
         // Unlink button
        if($flgUnlinkButton){
             echo "<INPUT type=button value=\"un-link\" name=\"$filename\"";
             echo "onClick=\"this.form.unlink_pid.value=this.name;";
             echo "this.form.gAction.value='UnLink';this.form.submit()\">";
        }
         echo "</TD>";
         echo "</TR>\n";
     }
  ?>
<tr><td colspan=5>
<input type=button value="Check All" onClick="this.form.flgCheckAll.value=1;this.form.submit()">
<input type=button value="Link All" onClick="this.form.gAction.value='LinkAll';this.form.submit()">
<input type=button value="UnLink All" onClick="this.form.gAction.value='UnlinkAll';this.form.submit()">

<input type=button name="btnLU" value="Link/Unlink"  onClick="this.form.gAction.value='Link';this.form.submit()">

</TABLE>
<BR>

<INPUT NAME="userfile" TYPE="file">

    <INPUT TYPE="Button" NAME="upload" VALUE="Upload" onClick="this.form.gAction.value='Upload';this.form.submit()"><BR>

<INPUT TYPE="TEXT" NAME="touchfile" >

    <INPUT TYPE="Button" NAME="touch"  VALUE="Touch" onClick="this.form.gAction.value='Touch';this.form.submit()">
<?         echo " l/u<INPUT name=link_touch type=checkbox value=1";
           echo " checked ><BR>";
           ?>

<INPUT TYPE="TEXT" NAME="mkdirfile">

    <INPUT TYPE="Button" NAME="mkdir"  VALUE="Mkdir" onClick="this.form.gAction.value='MkDir';this.form.submit()"><BR>

</FORM>

</table>

</div>



</BODY>

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

</HTML>