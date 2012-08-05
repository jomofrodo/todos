<? //session_cache_limiter('private'); ?>
<?php include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php"); ?>
<?
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
?>
<?php
  // Page to add new Categories to the filesystem and todos database
  // JoMo Netazoic 6.2003
  // Jomo Netazoic 1.2005

  $nav_global="admin";
  $nav_section="";
  $page_short_title="Add New Category";
	// Protect for admin users only
	//lib_login_protect_page_group(99);
	//$logged_in = lib_login_valid_user();
          $args = $_POST;
          $files = $_FILES;
          extract($files);
	## Set Page Variables
      $err_msg = '';
      $errTodosExists   = "This category already exists in Todos. ";
      $errPath          = "Directories in path do not exist.";
      $errDirExists     = "A directory with that name already exists.";
      $errURACow        = "You are a cow!";
      $txtFileExists    = "This file already exists. ";

      $txtVirtualDir     = "Virtual category (do not create directory)?";
      $txtLinkDirPages     = "Link pages in this directory to the category?";
      $txtLinkRecursive    = "Link all pages underneath this directory?";

     //cat Name
            // cat_title will be used for td_title
            $td_title = $cat_title;
            $args['td_title'] = $cat_title;
            if(! $cat_title) $cat_title =  STR_NEW_CAT_NAME;
     //Cat Description
            // cat_description will be used for description parameter
     //cat PID

    // check if an index file exists
    //$idxpath = convPID2Path($idx_pid);
     $feIdx = file_exists($idxpath);

     //Page type
     $td_class = EO_CLASS_CATEGORY;
	 if(0) var_dump($args);

	switch($pAction){

        case "SaveParams":
               // create page todos entries
                $ret = todosAddNewCHash($args,$ct=0);
                if($ret){
                     $err_msg = $ret;
                      break;  }
                else{
 			          $url = TODOS_ROOT . PAGE_REC_EDIT . "?rec_pid=$cat_pid&cat_pid=$cat_pid&glimpse_pid=$cat_pid&pMode=IDX2";
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
		default		:
				 break;

	}
	###### DEBUG ############
	if(0) var_dump($args);
	if(0) var_dump($gargs);
	if(0) print("PACTION: $pAction<br>");
	if(0) print("page_id: $page_id<br>");
	if(0) exit;
	if($err) print($err);
	########################

function checkFormVars($rec_pid,&$err_msg,$flgPrint){
		$flgErr = 0;
		$str_new_name = STR_NEW_CAT_NAME;
        preg_match("/\.(.*)$/",$rec_pid,$amatch);
        $extension = $amatch[1];
		if(preg_match("/$str_new_name/", $rec_pid)){
		   $flgErr = 1;
		   $err_msg = ("Please enter a directory name for this category.<br>");
		}
          else if($extension && !$flgPage && !$flgConfirm){
          $flgErr = 1;
          $err_msg = "Page location does not look like a directory.<BR> \n";
          $err_msg .=  "Are you sure this is not a standard page?";
          $err_msg .= "<input type=checkbox name=flgConfirm value=1 > yes";
          $err_msg .= "<input type=checkbox name=flgConfirm value=0 > no";
          }

		if ($flgPrint){
			print("<font color=red>Error: ");
			print($err_msg);
			print("</font>\n");
		}
		if(0) print("checkFormVars: $rec_pid, $str_new_name<br>");
		if(0) exit;
		return($flgErr);
}


?>
<script language=javascript>
function checkSys(){
	//alert("javascript");
}
function setFName(e){
				$fname = e.value;
				//alert("fname:" + $fname);
				e.form.ufile.value=e.value;
			  	re = /^.*\\(\w+)$/;
			  	re = /^(.*\\)(.*)$/;
			  	re2 = /^(.*\/)(.*)$/;
				re3 = /\s/g;
				re4 = /\.(.*)/;
				$fname = $fname.replace(re,"$2");
				$ftitle = $fname;
				$ftitle = $ftitle.replace(re4," ($1)");
				e.form.cat_title.value = $ftitle;
				$fname = $fname.replace(re3,"_");
				$fname = $fname.toLowerCase();
				//$rec_pid = e.form.fname.value;
			  	//$rec_pid = $rec_pid.replace(re2,"$1");
				e.form.fname.value = $fname;
}
function setFDir(e,d,p){
				$cname = e.value;
				//alert("cname:" + $cname);
                $dir = p.value;
                //alert("dir: " + $dir);
                $dir += "/" + e.value;
				d.value=e.value;
			  	re = /^.*\\(\w+)$/;
			  	re = /^(.*\\)(.*)$/;
			  	re2 = /^(.*\/)(.*)$/;
				re3 = /\s/g;
				re4 = /\.(.*)/;
                re5 = /\/\//;
				$dir = $dir.replace(re,"$2");
                $dir = $dir.replace(re5,"/");
                d.value=$dir;
}
</script>

<form name=frmTodosAdmin method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value="<?=$pAction?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
  <input type=hidden name="ufile" >
  <input type=hidden name="focus" value="<?=$focus;?>" >
  <input type=hidden name=DBGSESSID value="">

  <table border=0 width=100%>
<!--
     <tr>
            <td width=150 height=1><input type=image src="/images/spacer.gif" value="AddNew" onclick="this.form.pAction.value=this.value"
				width=0 length=0 height=0>
            <td width=150> &nbsp;
            <td width=175> &nbsp;
	    <td width=200>&nbsp;
    <tr>
-->
<?
    print ("<tr><th align=left colspan=4><h2>Add New Category:  $rec_pid</h2>");
    if($err_msg){
        print("<tr><th colspan=4><font color=red>Error: $err_msg</font></th>");}
       if($fe){
        print("<tr>      \n");
        print("<td>     \n");
        print("<td>     \n");
        print("<td colspan=2>     \n");
        print("<input type=checkbox name=flgLink ");
        if($flgLink) print("checked ");
        print("> Link existing directory.");
        print("<tr>      \n");
        print("<td>     \n");
        print("<td>     \n");
        print("<td colspan=2>     \n");
        print("<input type=checkbox name=flgLinkDirPages ");
        if($flgLinkDirPages) print("checked ");
        print("> $txtLinkDirPages.");
        print("<tr>      \n");
        print("<td>     \n");
        print("<td>     \n");
        print("<td colspan=2>     \n");
       print("<input type=checkbox name=flgLinkRecursive ");
       if($flgLinkRecursive) print("checked ");
       print("> $txtLinkRecursive");
   }

switch($pAction){
   //Class Hash
   default:
              // td Class -- Type of record to add
              $cat_pid = $new_cat_pid;
               $td = todosGetClassHash($cat_pid,$td_class,'','',0,1);
         		$paramList = 'title,description,bass_class,col_names,col_sort';
  	          if(0) print("td_class: $my_td_class<br>");
               print("<tr><td><th align=left valign=top>");
               print ("Page Attributes:<td align=left>\n");
               Print ("<tr><td><td colspan=2>");
               todosEditCHash($td,$td_class,$cols,$flgClassOnly,$paramList);

    	echo(" </td></tr>\n");
	echo(" <tr><td>&nbsp;\n");
	echo(" <tr><td colspan=4 align=center>\n");
	echo(" <input type=submit value=\"Save\"");
  	echo("	onclick=\"this.form.pAction.value ='SaveParams'\">");
	echo(" <input type=submit value=\"Cancel\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");

               break;

}//End Switch



?>
</form>
  </table>



