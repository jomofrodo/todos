<? ob_start();
//session_cache_limiter('private'); ?>
<?@include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");?>
<?
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_hdr.php");
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
  //      unlink_files  -- same thing, only not
  //      file -- ??

  $nav_global="admin";
  $nav_section="";
  $page_short_title="Todos Glimpse";

   session_register("GlimpseDir");
   //$excluded = "^_|^\.[A-Za-z]|CVS";
   $excluded = "^\.[A-Za-z]|CVS";

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
	$link_files = array();
    $reload = 0;
    $link_files = $args['link_file'];
    $link_subcats = $args['link_subcat'];
    ### GlimpseDir is a real headache!  Maybe the whole concept is wrong??
    ###
    //get rid of bogus GET settings
     if($glimpse_pid == "") $glimpse_pid = null;
     if($DirOverride && ($DirOverride != "")){
        if($cat_pid){$glimpse_pid = $cat_pid;$rec_pid = $cat_pid;}
     }
     if(!$glimpse_pid && $file) $glimpse_pid = $file;
     if(!$glimpse_pid && $cat_pid){ $glimpse_pid = $cat_pid; $rec_pid=$cat_pid;}
     if(!$glimpse_pid){
            //why?
             $glimpse_pid= IDX_ROOT;
     }
     $glimpse_pid = preg_replace("/\/([\w\d]+)?\/\.\./U","",$glimpse_pid);
     $GlimpseDir = $glimpse_pid . "/";  //Redundant??
     //$glimpse_pid == IDX_ROOT?$GlimpseDir = $glimpse_pid:$GlimpseDir = $glimpse_pid ."/";
     // following only useful if IDX something other than system directory
     // symbol, e.g., "/"
     //$GlimpseDir = preg_replace("/".IDX."/",'',$GlimpseDir);//strip index file
     while(preg_match("/\/\//",$GlimpseDir)){
      $GlimpseDir = preg_replace("/\/\//","/",$GlimpseDir); //Make it a directory
     }
    //<ASSUMPTION>
    if(! $cat_pid) $cat_pid = IDX_ROOT;
    if(! $rec_pid) $rec_pid = IDX_ROOT;              //Link to root if no record
    // files
    $dir = $basedir . $GlimpseDir;
    $dir = preg_replace("/\/\//",'/',$dir);
    $files = getDirFiles($dir,'*',$strFilter,$excluded,0);

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
	    if($link_files){
		checkMembers($rec_pid,$link_files,$dir);
		if(0) exit();
	    }
	}
 switch($gAction){

        case 'LinkAll':
            // link/unlink selected files to the rec_pid
            $flgPID=1;
            $link_files = getDirFiles($dir,'*',$strFilter,$excluded,$flgPID);
            todos_LinkFiles2Cat($rec_pid,$link_files,$bass_class);
            break;
        case 'UnlinkAll':
            $flgPID =1;
            $unlink_files = getDirFiles($dir,'*',$strFilter,$excluded,$flgPID);
            todos_UnlinkFiles2Cat($rec_pid,$unlink_files,$bass_class);
            break;
        case "Link":
 	        if($unlink_file) $unlink_files = getUnlinkFiles($files,$link_files,$dir);
            if($unlink_subcat) $unlink_subcats = getUnlinkSubcats($files,$link_subcats,$dir);
            if($link_files)  todos_LinkFiles2Cat($rec_pid,$link_files,$bass_class);
            if($unlink_files) todos_UnlinkFiles2Cat($rec_pid,$unlink_files);
            if($link_subcats) todos_LinkSubs2Cat($rec_pid,$link_subcats);
            if($unlink_subcats) todos_UnlinkSubs2Cat($rec_pid,$unlink_subcats);
            break;
       case "root":
            $GlimpseDir = IDX_ROOT;
            break;
       case "cancel":
            $gAction = "";
            break;
       case "Upload":
            $fname = $_FILES['userfile']['name'];
            //preg_match("/([a-zA-Z._ ]*)/",$fname,$amatch);
            //$fn = $amatch[1];
            $fn = $fname;
            if(! $fn) print("Could not determine upload file name from $fname");
             else{
                    copyFile('userfile',$dir.$fn);
                    reloadnow();
             }
             break;
       case "Touch":
            touch($dir.$touchfile);
            chmod($dir.$touchfile,0777);
            chgrp($dir.$filename,'users');
            if($link_touch)  todos_LinkFiles2Cat($rec_pid,$dir.$touchfile,$bass_class);
            reloadnow($addons);
            break;
       case "MkDir":
           $ret = createDir($dir.$mkdirfile);
            reloadnow();
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
	    $file_pid = convPath2PID($filename);
        $flgCheck = checkCatMember($file_pid,$rec_pid);
	    print("checkMember: $rec_pid,$file_pid: $flgCheck<br>");
    }
    return(0);
}

function getUnlinkFiles($files,$link_files,$dir){
	// Get the list of files in this directory
	// not linked to the parent
	// returns an array
	$i=0;
	$files_fix = array();
	if(! $link_files) $link_files = array();
    while($file = array_shift($files)){
       $filename=$dir.$file;
       $filename= preg_replace("/\/\//","/",$filename);
	   $files_fix[$i] = $filename;
 	   $i++;
 	   if(0) print("$i: $filename<br>");
    }
    $unlink_files = array_diff($files_fix,$link_files);
	// hmmm , that was easy
	if(0) $debug=1;
	if($debug) print("getUnlinkFiles:<br>");
	if($debug) print("Files:<br>");
	if($debug) print_r($files_fix);
	if($debug) print("<br>Link Files:<br>");
	if($debug) print_r($link_files);
	if($debug) print("<br>UnLink Files:<br>");
	if($debug) print_r($unlink_files);

	return($unlink_files);
}

function getUnlinkSubcats($files,$link_subcats,$dir){
	// Get the list of files in this directory
	// not linked to the parent
	// returns an array
	$i=0;
	$cats_fix = array();
	if(! $link_files) $link_files = array();
    if(! $link_subcats) $link_subcats = array();
    while($file = array_shift($files)){
       $filename=$dir.$file;
       $filename= preg_replace("/\/\//","/",$filename);
       if(preg_match("/.*\.$/",$filename)) continue;  // skip . and ..
       if (filetype($filename) != 'dir') continue;
	   $cats_fix[$i] = $filename;
 	   $i++;
 	   if(0) print("$i: $filename<br>");
    }
    $unlink_cats = array_diff($cats_fix,$link_subcats);
	// hmmm , that was easy
	return($unlink_cats);
}

function reloadnow($addons) {
    global $URI;
    //global $addons;
    header("Status: 302 Moved");
    header("Location: $URI" . $addons);
    exit(); }
?>

<form name=frmLink action="<?=$URI?>" ENCTYPE="multipart/form-data" method=POST>
  <input type=hidden name="pAction" value='<?=$pAction?>'>
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name=gAction value=''>
  <input type=hidden name="flgMode">
  <input type=hidden name=glimpse_pid value="<?=$glimpse_pid?>">
  <input type=hidden name=cat_pid value="<?=$cat_pid?>">
  <input type=hidden name="rec_pid" value='<?= $rec_pid ?>'>
  <input type=hidden name="srch_terms" value="<?=$srch_terms;?>">
  <input type=hidden name=DirOverride>
  <input type=hidden name=unlink_subcat>
  <input type=hidden name=unlink_file value=0>
  <input type=hidden name=link_file>
  <input type=hidden name=flgCheckAll value=0>


<? //Table Header
    print("<table><tr>");
	if(0) print("pMode: $pMode<br>\n");
    print("<th width=150 class=th>IDX Record Editor");
    print("<td colspan=2>");
   	printSelect("rec_pid_slc",'tblTodos','tdPageID','tdPageID',$rec_pid,0,1,'',
		'',
		0,'this.form.rec_pid.value=this.value;this.form.submit();');
	print("<th colspan=3 align=left>");
  // Sub Modes
       $EDIT_MODES = array("EDIT"=>'EDIT',"IDX1"=>'Properties',"IDX2"=>'Link Files',"IDX3"=>'Relations',"CAT"=>'Categories');
       editModeButtons($EDIT_MODES,$pMode,0);
    print("</tr></table>");
  // start HTML
?>

<div align=center>
<TABLE BORDER="1">

<tr><td colspan=5>

    <table border=0><tr><td >
    <? todosSelectCategory($cat_pid,$glimpse_pid,'',1,0,'',"this.form.glimpse_pid.value=this.value;this.form.submit()",1,1,'glimpse_pid_set')?> Dir: <?=$GlimpseDir?>
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
        $filename=$dir.$file;
        $filename= preg_replace("/\/\//","/",$filename);
        $fileurl=rawurlencode($GlimpseURL."/".$file);
        $filepid = convPath2PID($filename);
        $fileurl = $GlimpseDir . rawurlencode($file);
        $flgCheck = checkCatMember($filepid,$rec_pid);
        echo "<TR>";
        echo "<TD>" . htmlspecialchars($file) . "</TD>\n";
        echo "<TD>" . filetype($filename) . "</TD>\n";
        echo "<TD>" . filesize($filename) . "</TD>\n";
        echo "<TD>";
        if(filetype($filename)=="file") {  //view
        echo "<A HREF=\"$fileurl\" target=\"".TARGET_VIEW."\">View</A> ";
                                          //edit
        echo "<A HREF=\"". TODOS_ROOT . PAGE_REC_EDIT ."?rec_pid=$filepid&pMode=EDIT\" target=". TARGET_EDIT . ">Edit</a>";
                                        // delete
        echo " <A HREF=\"". TODOS_ROOT . PAGE_IDX2_EDIT ."?rec_pid=$filepid&pMode=IDX2&pAction=Delete\" target=". TARGET_EDIT . ">Del</a>";
    }
        if(filetype($filename)=="dir") { //chdir
                echo "<A HREF=\"".TODOS_ROOT.PAGE_IDX2_EDIT."?action=chdr&glimpse_pid=$fileurl&rec_pid=$rec_pid&cat_pid=$cat_pid\">ChDr</A> ";
        }
        echo "<td>\n";
                                        //link/unlink
         echo " l/u<INPUT name=link_file[] type=checkbox value=\"$filename\"";
          if (($flgCheckAll) || ($flgCheck)){         echo " checked ";
           }                                //unlink
         echo " onClick=\"if(!this.checked){this.form.unlink_file.value=this.value;}\"
         //this.form.submit();\" ";
         echo ">";                            //subcat
         if(filetype($filename)=="dir") {
             echo" sub<INPUT name=link_subcat[] type=checkbox value=\"$filename\"";
             if(checkCatMember(convPath2PID($filename),$rec_pid,EO_CLASS_SUBCAT)) echo "checked";
             echo " onClick=\"if(!this.checked){this.form.unlink_subcat.value=this.value;} \" ";
         }
         //echo "<INPUT type=button value=Link name=\"$filename\"";
         //echo "onClick=\"this.form.link_file[$i].checked=1;this.form.submit()\"";
         //echo " >";
         // Unlink button
        if($flgUnlinkButton){
             echo "<INPUT type=button value=\"un-link\" name=\"$filename\"";
             echo "onClick=\"this.form.unlink_file.value=this.name;";
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
