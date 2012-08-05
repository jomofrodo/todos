<? ob_start();
//session_cache_limiter('private'); ?>
<?@include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");?>
<?
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
     include_once(dirname(__FILE__)."/_inc_Rec_hdr.php");
?>
<?
  // Page to add and edit links in the todos database
  // Three or four things to keep track of here
  //      rec_pid -- the record we are linking things to
  //      cat_pid -- navigational device to get us in the neighborhood
  //      glimpse_pid  --  File to view links from
  //      link_pids    -- pids to link to rec_pid
  //      unlink_pids  -- same thing, only not


   $basedir= SITE_PATH_ROOT;
    $PHP_SELF = $_SERVER['PHP_SELF'];
    $URI        = $_SERVER['REQUEST_URI'];
 	$args = $_POST;
	if(!$args) $args = $_GET;
	extract($args);
   	if(0) var_dump($args);

    $rec_pid        = $rec_pid;
    $cat_pid        = $cat_pid;
    $glimpse_pid    = $glimpse_pid;
    $link_class     = $link_class;
    $disp_length    = 30;

    if(! $link_class) $link_class = EO_CLASS_MEMBER;

    //if($DirOverride){if($cat_pid) $glimpse_pid = $cat_pid;}
    if(!$glimpse_pid && $cat_pid) $glimpse_pid = $cat_pid;
    if(!$glimpse_pid) $glimpse_pid= IDX_ROOT;

    //$rsGlimpse = null; // recordset holder
    $rsGlimpse = todosGetLinkedPIDs($glimpse_pid,'member',$strFilter);
    $glimpse_pids = getGlimpsePIDs($rsGlimpse);

    switch($gAction){

        case 'LinkAll':
            // link/unlink selected files to the rec_pid
             todosLinkRecs2Cat($rec_pid,$glimpse_pids,$link_class);
             break;
        case 'UnlinkAll':
            todosUnlinkRecs($rec_pid,$glimpse_pids,$link_class);

            break;
        case "Link":
        case "Unlink";
            if($unlink_pid) $unlink_pids = getUnlinkPIDs($glimpse_pids,$link_pids);
            if($unlink_subcat) $unlink_subcats = getUnlinkSubcats($files,$link_subcats,$dir);
            if($link_pids)  todosLinkRecs2Cat($rec_pid,$link_pids,$link_class);
            if($unlink_pids) todosUnlinkRecs($rec_pid,$unlink_pids,$link_class);
            if($link_subcats) todosLinkSubs2Cat($rec_pid,$link_subcats);
            if($unlink_subcats) todosUnlinkSubcats($rec_pid,$unlink_subcats);
           break;

        case "Delete";
            todosDeleteTodos($delete_pid);
            deleteFile($delte_pid);
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
            if($link_touch)  todosLinkRecs2Cat($rec_pid,$dir.$touchfile,$bass_class);
            reloadnow($addons);
            break;
       case "MkDir":
           $ret = createDir($dir.$mkdirfile);
            reloadnow();
            break;
 }

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
"http://www.w3c.org/TR/REC-html40/loose.dtd">
<HTML>
<HEAD>
<TITLE>Filesystem Todos Linker-Current Directory="<?=$glimpse_pid;?>"</TITLE>
</HEAD>
<BODY>

<TABLE BORDER="1">
<form name=frmLink action="<?=$URI?>" method=POST>
<input type=hidden name=DirOverride>
<tr><td colspan=7>
    <table width=100% border=0><tr><td >Linked Todos: <?=$glimpse_pid?>
        <td>&nbsp;&nbsp;
        <td>Filter: <input name="strFilter" value="<?=$strFilter?>">
        <td>
        <?       $onChange = "this.form.glimpse_pid.value=this.value;
                this.form.submit()";
        todosSelectCats(IDX_ROOT_PAGEID,$glimpse_pid,'slc_glimpse',$onChange) ?>
    </table>

<TR><TH>PID<th>Title<TH>Type<TH>Links<TH>Action</TR>
<input type=hidden name=cat_pid value="<?=$cat_pid?>">
<input type=hidden name=rec_pid value="<?=$rec_pid?>">
<input type=hidden name=glimpse_pid value="<?=$glimpse_pid?>">
<input type=hidden name=link_class value="<?=$link_class?>">
<input type=hidden name=gAction value="<?=$gAction?>">
<input type=hidden name=unlink_pid>
<input type=hidden name=link_pid>
<input type=hidden name=flgCheckAll value=0>
<input type=hidden name=flgLinkAll value=0>
<input type=hidden name=flgUnLinkAll value=0>
<input type=hidden name=file>

<!-- for use with rec_edit.php -->
<input type=hidden name=pAction value='glimpse'>
<? // Todos iterator code
    // This corresponds to the directory navigator in todos_link_pids.php
    // $rec_pid is the current working record that we are linking things to
    // $glimpse_pid is the linking root
    $rsGlimpse = todosGetLinkedPIDs($glimpse_pid,'member',$strFilter);
   $rowct = $rsGlimpse->_numOfRows;
    $rsGlimpse->MoveFirst();
    while(! $rsGlimpse->EOF){
        $link_pid = $rsGlimpse->fields['tdPageID'];
        $link_pid_disp = substr($link_pid,0,$disp_length);
        $tdTitle = $rsGlimpse->fields['tdTitle'];
        $tdCount = $rsGlimpse->fields['tdCount'];
        $tdClass = $rsGlimpse->fields['tdClass'];
        $i++;
        $link_pidurl=rawurlencode($link_pid);
        $pid_path = todosConvPID2Path($link_pid);
	    $flgLink = todosCheckCatMember($link_pid,$rec_pid);
        echo "<TR>";
        echo "<TD>" . "<a href=\"$link_pid\" alt=\"$link_pid\">$link_pid_disp</a></TD>\n";
        echo "<td>$tdTitle</td>\n";
        echo "<TD>" . $tdClass . "</TD>\n";
        echo "<TD>" . $tdCount . "</TD>\n";
        echo "<TD>";
        //echo "<A HREF=\"$PHP_SELF?action=chglimpse&pMode=IDX3&glimpse_pid=$link_pid&rec_pid=$rec_pid\"><font size=2>View Links</font></A>";

        //echo "<INPUT type=button value=\"un-link\" name=\"$link_pid\"";
       // echo "onClick=\"this.form.gAction.value='Unlink';this.form.unlink_pid.value=this.name;this.form.submit()\">";
          echo " l/u<INPUT name=link_pids[] type=checkbox value=\"$link_pid\"";
          if (($flgCheckAll) || ($flgLink)){         echo " checked ";
           }                                //unlink
         echo " onClick=\"if(!this.checked){this.form.unlink_pid.value=this.value;}\"
         //this.form.submit();\" ";
         echo ">";                            //subcat
        echo "</TD>";
        echo "</TR>\n";
        $rsGlimpse->MoveNext();
    }
    $rsGlimpse->close();
?>


<tr><td colspan=5>
<input type=button value="Check All" onClick="this.form.flgCheckAll.value=1;this.form.submit()">
<input type=button value="Link All" onClick="this.form.gAction.value='LinkAll';this.form.submit()">
<input type=button value="UnLink All" onClick="this.form.gAction.value='UnlinkAll';this.form.submit()">
<input type=button name="btnLU" value="Link/Unlink"  onClick="this.form.gAction.value='Link';this.form.submit()">

</form>
</TABLE>

<BR>
<FORM ENCTYPE="multipart/form-data" METHOD="POST" >
<input type=hidden name=rec_pid value="<?=$rec_pid?>">
<input type=hidden name=cat_pid value="<?=$cat_pid?>">
<input type=hidden name=glimpse_pid value="<?$glimpse_pid?>">
<input type=hidden name=pMode value="<?=$pMode?>">
<INPUT NAME="userfile" TYPE="file">
    <INPUT TYPE="SUBMIT" NAME="upload" VALUE="Upload"><BR>
<INPUT TYPE="TEXT" NAME="touchfile">
    <INPUT TYPE="SUBMIT" NAME="touch"  VALUE="Touch"><BR>
<INPUT TYPE="TEXT" NAME="mkdirfile">
    <INPUT TYPE="SUBMIT" NAME="mkdir"  VALUE="Mkdir"><BR>
</FORM>

</BODY>
</HTML>
<?php
    // Library functions specific to linking and unlinking directory contents to
    //  a specified rec_pid
    //



function getGlimpsePIDs($rsGlimpse){
	// Get an array of all pids in this glimpse category
	// returns an array
    //
    // $rsGlimpse is the list of records pids belonging to the glimpse category
	$i=0;
	if(! $glimpse_pids) $glimpse_pids = array();
    $rsGlimpse->MoveFirst();
    while(! $rsGlimpse->EOF){
        $glimpse_pid = $rsGlimpse->fields['tdPageID'];
	   $glimpse_pids[$i] = $glimpse_pid;
 	   $i++;
       $rsGlimpse->MoveNext();
     }
	return($glimpse_pids);
    $rsGlimpse = null;
}

function getUnlinkPIDs($glimpse_pids,$link_pids){
	// Get the list of records in this glimpse category
	// not linked to the current record
	// returns an array
    //
    // $rsGlimpse is the list of records pids belonging to category
	$i=0;
	$unlink_pids = array();
     $unlink_pids = array_diff($glimpse_pids,$link_pids);
	// hmmm , that was easy
	return($unlink_pids);
}

   function reloadnow() {
    global $URI;
    global $addons;
    header("Status: 302 Moved");
    header("Location: $URI" . $addons);
    exit(); }
?>