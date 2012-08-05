<? ob_start();
//session_cache_limiter('private'); ?>
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
      $errCatExists     = "This category already exists in Todos. ";
      $errPath          = "Directories in path do not exist.";
      $errDirExists     = "A directory with that name already exists.";
      $errURACow        = "You are a cow!";
      $txtFileExists    = "This file already exists. ";

      $txtVirtualDir     = "Virtual category (do not create directory)?";
      $txtLinkDirPages     = "Link pages in this directory to the category?";
      $txtLinkRecursive    = "Link all pages underneath this directory?";

      //Parent Category
      if (! $pcat_pid) $pcat_pid = $cat_pid;
      if (! $pcat_pid) $pcat_pid = IDX_ROOT_PAGEID;
       $flgPCatUpdate = $args['flgPCatUpdate'];
     //cat Title
     // cat_title will be used for td_title
            if(! $new_cat_title) $new_cat_title =  STR_NEW_CAT_NAME;
     //Cat Description
            // new_cat_description will be used for description parameter
     // NEW Cat DIR
     //if(!$pcat_pid) $pcat_pid = $cat_pid;    // Parent category
     $pcat_path = todosConvPID2Path($pcat_pid);
     $pcat_dir = todosConvPath2Dir($pcat_path);
     $flgRel = 1;
     $pcat_rel_dir = todosConvPath2Dir($pcat_dir,$flgRel);
     // which doesn't work for '/idx' for some reason
     if($pcat_dir == '\\') $pcat_dir = IDX_ROOT_DIR;

     //New cat dir
     if($new_cat_rel_dir) $new_cat_dir = todosConvPID2Path($new_cat_rel_dir);
     if(! $new_cat_dir) $new_cat_dir = $pcat_dir . OS_DIR_SEPARATOR . "<dir_name_here>";
     if ($flgPCatUpdate)$new_cat_dir = $pcat_dir . OS_DIR_SEPARATOR . basename($new_cat_dir);
       $dirpath = todosConvPID2Path($new_cat_dir);
     $fe = file_exists($dirpath);
     //New cat PID
     //The new cat_pid is based on the new_cat_dir
      $flgCat = 1;  // force category pid
      $new_cat_pid =  todosConvPath2PID($new_cat_dir,$flgCat);
      $new_cat_pid = preg_replace ("/\/\//","/",$new_cat_pid);

    // check if an index file exists
    //$idx_pid = $dirpath . IDX . IDX_FILE;
    //while(preg_match("/\/\//",$idx_pid))$idx_pid = preg_replace ("/\/\//","/",$idx_pid);
      $idx_pid = $new_cat_pid;
      $idxpath = todosConvPID2Path($idx_pid);
      $feIdx = file_exists($idxpath);

     //Page type
     $td_class = EO_CLASS_CATEGORY;
     $bass_class = todosGetBassClass($pcat_pid);
 	 if(0) var_dump($args);

	switch($pAction){
		case "AddNew"	:
			$ret = checkFormVars($new_cat_title,&$err_msg,0);
		    if(0) print("Return from checkFormVars: $ret<br>");
        	if($ret) break;
            //check to see if category already exists
            $chkID = todosGetField($new_cat_pid,FLD_TDID,IDX_0,EO_CLASS_CATEGORY);
            if($chkID){
                    $err_msg = $errCatExists;
                    break;
            }
                //$flgForce =1; //Create directory path if it does not exist
              // if directory exists, confirm continue
              /*
            if ($fe && (!$flgLink && ! $flgOverWrite)) {
                $err_msg = $txtDirExists ."-- please confirm link below.";
                //confirm linking an existing dir
                $txtConfirm =  $chkLink;
                $err_msg .= "<BR>" . $txtConfirm;
                break;
            }     */
               //if dir does not exist, create  -- or if overwrite flag on
               if((!$fe) || ($flgForce)&&(!$flgVirtualDir)){
                    $ret = createDir($new_cat_dir);
               }
              // Add Todos Category entries
                $flgSubCat = 1; // create subcat entry from new_cat to pcat_pid
                $args['td_title'] = $new_cat_title;
                $args['td_description'] = $new_cat_description;
                $args['pcat_pid'] = $pcat_pid;
                $args['bass_class'] = $bass_class;
                $ret = todosAddNewCategory($new_cat_pid,$args,$ct=0,$flgSubCat);
                $ret = todosCreateCatTable($new_cat_pid);

              // Create idx file, either using the upload file, or a template
              if($flgCreateIdx){
                    if($userfile['name']){
                        $flgUpload =1; // use upload file
                        copyFile('userfile',$idxpath);
                   }
                   else{
                        $tplt = TEMPLATE_DIR .  TEMPLATE_CAT;
                         $ret = createFile($idx_pid,$tplt,$flgForce);
                         if($ret){$err_msg = $ret; break; }
                   }
                }
                if($flgCreateIdx || $flgLinkIdx){
                      //Add idx to todos
                      $arrIdxArgs =  array();
                      $arrIdxArgs['td_class'] = 'page';
                      $arrIdxArgs['cat_pid'] = $cat_pid;
                      $arrIdxArgs['td_title'] = $td_title;
                      todosAddNewPage($idx_pid,$arrIdxArgs);
                      // Add category index entry
                      $ret = todosInsertTodos($cat_pid,IDX_1,EO_CLASS_CATEGORY,
                      TD_PNAME_INDEX,$idx_pid);
                }
                if($flgLinkDirPages){
                        linkSubDocs($new_cat_pid,$new_cat_dir,$strFilter);
                }
                $err_msg = '';
	          $url = TODOS_ROOT . PAGE_REC_EDIT . "?rec_pid=$new_cat_pid&pAction=Attributes";
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
		   $err_msg = ("Please enter a name for this category.<br>");
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
                //p can either be a form object, or a string value
                $dir = p.value;
                if(!$dir) $dir = p;
                //alert("dir: " + $dir);
                $dir += "/" + e.value;
				d.value=e.value;
			  	re = /^.*\\(\w+)$/;
			  	re = /^(.*\\)(.*)$/;
			  	re2 = /^(.*\/)(.*)$/;
				re3 = /\s/g;
				re4 = /\.(.*)/;
                re5 = /\/\//;
				//$dir = $dir.replace(re,"$2");
                //$dir = $dir.replace(re5,"/");
                $dir = $dir.replace(re3,"_");
                d.value=$dir;
}
</script>

<form name=frmTodosAdmin method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value="<?=$pAction?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
  <input type=hidden name="ufile" >
  <input type=hidden name="focus" value="<?=$focus;?>" >
  <input type=hidden name=DBGSESSID value="">
  <input type=hidden name=flgPCatUpdate value=0>

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
    print ("<tr><th align=left colspan=4><h2>Add New Category:  $new_cat_pid</h2>");
    if($err_msg){
        print("<tr><th colspan=4><font color=red>Error: $err_msg</font></th>");}
        /*
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

   }    */

switch($pAction){
 //###################### DEFAULT ###########################
     default:
          print("<tr><td>");
          print("<th >Parent Category:</th><td>");
	 //Category
      //todosSelectCategory($pcat_pid,$pcat_pid,0,1,0,0,'','',1,'pcat_pid');
      //todosSelectCats($pcat_pid,$pcat_pid);
      $onChange="this.form.flgPCatUpdate.value=1; this.form.submit();";
        todosSelectCats(IDX_ROOT_PAGEID,$pcat_pid,'pcat_pid',$onChange);

     // Cat Title
         print("<tr>      \n");
         print("<td>     \n");
 		print("<th>Category Title:<td>");
          print(" <input name=new_cat_title value=\"$new_cat_title\"");
          print(" onchange='setFDir(this, this.form.new_cat_rel_dir, \"$pcat_rel_dir\");' ");
          print(" width=500 size=50 > ");
          print("e.g., 'News'");
     // Cat Description
         print("<tr>      \n");
         print("<td>     \n");
		print("<th>Category Description:<td>");
          print(" <input name=new_cat_description value=\"$new_cat_description\"");
          print(" width=500 size=50 > ");
          print("e.g., 'News of the AoM'");
     // Cat Dir
         print("<tr>      \n");
         print("<td>     \n");
		print("<th>Directory (optional):<td>");
          print(" <input name=new_cat_rel_dir value=\"$new_cat_rel_dir\"");
          print(" width=500 size=50 > ");
          print("e.g., '/News'");
       // virtual dir checkbox
        print("<tr>      \n");
        print("<td>     \n");
        print("<td>     \n");
        print("<td colspan=2>     \n");
       print("<input type=checkbox name=flgVirtualDir ");
       if($flgVirtualDir) print("checked ");
       print("> $txtVirtualDir");

        print("<tr>      \n");
        print("<td>     \n");
        print("<td>     \n");
        print("<td colspan=2>     \n");

   if(!$fe) {
       print("<input type=checkbox name=flgCreateDir ");
       if($flgForce) print("checked ");
       print("> Create directory path if it does not exist.");
   }
        print("<tr>      \n");
        print("<td>     \n");
        print("<td>     \n");
        print("<td colspan=2>     \n");
   if($feIdx){
       print("<input type=checkbox name=flgLinkIdx ");
       if($flgLinkIdx) print("checked ");
       print("> Link existing index file.");
   }
   else{
       print("<input type=checkbox name=flgCreateIdx ");
       if($flgCreateIdx) print("checked ");
       print("> Create index file.");
   }

    // Local page to upload
	    print("<tr><td><th>\n");
         print("Index file to upload (optional):");
         print("<td> ");
         $uf = $userfile['name'];
         print("<input name=\"userfile\" value=\"$uf\" type=file size=45  onchange='setFName(this);'	>      \n");
         print("<!--  ################################################################ -->      \n");

   	echo(" </td></tr>\n");
	echo(" <tr><td>&nbsp;\n");
	echo(" <tr><td colspan=4 align=center>\n");
	echo(" <input type=submit value=\"AddNew\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Cancel\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");

          break;

}//End Switch



?>
</form>
  </table>



<?
function startTodos(){
    //Start a brand new Todos db
    // jomo 10/04
    //Following defined in todosInclude.php or _conf/todosConfig.php
    if(!defined('INDEX_FILE'))define("INDEX_FILE",'idx.php');
    if(!defined('IDX'))define("IDX"		, 'idx.php');
    if(!defined('IDX_ROOT'))define("IDX_ROOT"	, '/idx.php');

    $td_title = SITE_NAME;
    $cat_pid = "/" . IDX;
    $td_class = EO_CLASS_CATEGORY;
    // Set page IDX0
    $ct = todosCheckIDX0($cat_pid);
     if(!$ct){
         $ret = todosInsertTodos($cat_pid,IDX_0,$td_class,TD_PNAME_TITLE,$td_title);
         if(($td_class == EO_CLASS_CATEGORY) || $flgCategory){
        //add category specific entries
         $ret = todosAddNewCategory($cat_pid,$args,$ct=0,$cat_pid);
         }
     }
     todos_CreateSubCats($cat_pid);
     todos_LinkSubDocs($cat_pid);
}
function todosCreateSubCats($cat_pid,$subcats){
    // Create subcat entries for selected directory entries
      $td_class = EO_CLASS_SUBCAT;
      $ret = todosLinkRecs($cat_pid,$td_class,$subcats,'','directory');
      return($ret);
}

function linkSubDocs($cat_pid,$cat_dir,$strFilter){
  // create member links for all docs in a directory
  $file_pids = todosGetDirPIDs($cat_dir,$strFilter);
  $ret = todosLinkRecs2Cat($cat_pid,$file_pids);
  return($ret);
}