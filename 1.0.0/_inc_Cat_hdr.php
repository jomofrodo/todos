<?php
ob_start();
  // Standard routines for Todos pages
  // standard form argument variables set inc _inc_args.php

  $nav_global="admin";
  $nav_section="";
  $page_short_title="Todos Admin";
  global $page_id;
  global $m1;       //  '/IDX_ROOT/'



$DOC_ROOT = $_SERVER[DOCUMENT_ROOT];
$args = $_POST;
if(!$args) $args=$_GET;
extract($args);

   if(!$cat_pid){
       $rec_pid = todosGetRecPID();
       $cat_pid = todosGetCatPID($rec_pid);
   }
    	if(! $bass_cat) $bass_cat = $cat_pid;
        $cat_table = todosGetCatTableName($cat_pid);

if(0) print ("cat_pid: $cat_pid<br>\n");

	//$MODES = array('VIEW'=>'View','EDIT'=>'Edit','IDX1'=>'Properties','IDX3'=>'Relations','CAT'=>'Categories','SUMMARY'=>'Summary');
	$MODES = array('VIEWCAT'=>'View',
                    'EDITCAT'=>'Edit',
                    'ADDNEWCAT'=>'Add New',
                    'SUMMARY'=>'Summary');
	$BUTTONS = array('VIEWCAT'=>IMG_BTN_VIEW,
                    'EDITCAT'=>IMG_BTN_EDIT1,
                    'ADDNEWCAT'=>IMG_BTN_ADDNEW,
                    'IDX1'=>IMG_BTN_IDX1,
                    'IDX2'=>IMG_BTN_IDX2,
                    'IDX3'=>IMG_BTN_IDX3,
                    'CAT'=>IMG_BTN_CAT,
                    'SUMMARY'=>IMG_BTN_SUMMARY,
                    'FULL'=>IMG_BTN_FULLSCREEN);
	$BUTTONS_ACTIVE = array(
			'VIEWCAT'=>IMG_BTN_VIEW_ACTIVE,
			'EDITCAT'=>IMG_BTN_EDIT1_ACTIVE,
            'ADDNEWCAT'=>IMG_BTN_ADDNEW_ACTIVE,
			'IDX1'=>IMG_BTN_IDX1_ACTIVE,
			'IDX2'=>IMG_BTN_IDX2_ACTIVE,
			'IDX3'=>IMG_BTN_IDX3_ACTIVE,
			'CAT'=>IMG_BTN_CAT_ACTIVE,
			'SUMMARY'=>IMG_BTN_SUMMARY_ACTIVE,
			'FULL'=>IMG_BTN_FULLSCREEN_ACTIVE);
	$flgButtons=1;

### Switch MODE
		if($flgMode){
		   switch($pMode){
			case "VIEWCAT"	:
                    //depends on cat_viewer being set up in ndx as default handler for
                    // /idx requests
                      $url  = todosGetURL($cat_pid);
                      $bass_class = todosGetCatVal($cat_pid,'bass_class');
                      $url .="?bass_class=$bass_class";
					  ob_end_clean();
					  header("Location: $url");
					  break;
			case "VIEW"	:
					  $url = TODOS_ROOT . PAGE_REC_VIEW .
						"?rec_pid=$rec_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					  ob_end_clean();

					  header("Location: $url");
					  break;
			case "EDITCAT"	:
                      $td_class = EO_CLASS_CATEGORY;
                      $pMode = IDX1;
					  $url = TODOS_ROOT . PAGE_REC_EDIT .
						"?rec_pid=$cat_pid&td_class=$td_class&td_type=idx3&pMode=$pMode&pAction=Edit&recMode=staticRec";
					  ob_end_clean();
					  header("Location: $url");
					  break;
			case "EDIT"	:
					  $url = TODOS_ROOT . PAGE_REC_EDIT .
						"?rec_pid=$rec_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode&pAction=Edit&recMode=selectCatRecs";
					  ob_end_clean();
					  header("Location: $url");
					  break;
			case "IDX3"	:
					  $url = TODOS_ROOT . PAGE_IDX3_EDIT .
						"?rec_pid=$rec_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					 if(0) print("$url");
					 if(0) exit();
					  ob_end_clean();
					  header("Location: $url");
                      break;
			case "IDX2"	:
					  $url = TODOS_ROOT . PAGE_IDX2_EDIT .
						"?cat_pid=$cat_pid&rec_pid=$rec_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					  ob_end_clean();
					  header("Location: $url");
                      exit();
					  break;
			case "IDX1"	:
					  $url = TODOS_ROOT . PAGE_IDX1_EDIT .
						"?rec_pid=$rec_pid&bass_class=$td_class&td_type=idx1&pMode=$pMode";
					  ob_end_clean();
					  header("Location: $url");
					  break;
			case "CAT"	:
					  $url = TODOS_ROOT . PAGE_REC_EDIT_CAT .
						"?rec_pid=$rec_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					  ob_end_clean();
					  header("Location: $url");
					  break;
			case "SRC"	:
					  $url = TODOS_ROOT . PAGE_REC_EDIT_SRC .
						"?rec_pid=$rec_pid&cat_pid=$cat_pid&bass_class=$td_class&td_type=idx3&pMode=$pMode";
					  ob_end_clean();
					  header("Location: $url");
					  break;
			case "SUMMARY"	:
					$url = TODOS_ROOT . PAGE_CAT_ADMIN .
						"?cat_pid=$cat_pid&rspage=$rspage";
					if(0) print("URL: $url<br>");
					if(0) exit;
					ob_end_clean();
					header("Location: $url");
				  	break;
			case "FULL":
					$url = SITE_ROOT . $rec_pid . '?target="full_screen"';
					if(0) print ("URL: $url<br>");
					if(0) exit;
					header("Location: $url");
					break;
            case "ADDNEW"	:
					  //preg_match('/(\/.*\/).*/',$cat_pid,$amatch);
					  //$pidnew = $amatch[1];
					  $pidnew = preg_replace($m1,'',$cat_pid);
					  //$pidnew .= STR_NEW_PAGE_NAME;
					  $url = TODOS_ROOT . PAGE_REC_ADDNEW .
						"?cat_pid=$cat_pid";
					  ob_end_clean();
					  header("Location: $url");
					  break;
            case "ADDNEWCAT"	:
					  //preg_match('/(\/.*\/).*/',$cat_pid,$amatch);
					  //$pidnew = $amatch[1];
					  //$pidnew = preg_replace($m1,'',$cat_pid);
                      //$pidnew .= STR_NEW_PAGE_NAME;
					  $url = TODOS_ROOT . PAGE_CAT_ADDNEW .
						"?cat_pid=$cat_pid";
					  ob_end_clean();
					  header("Location: $url");
					  break;

			default		:
					  break;
		   }
		}





function editModeButtons($arrModeList,$pMode,$flgButtons){
    GLOBAL $BUTTONS;
    GLOBAL $BUTTONS_ACTIVE;
    foreach($arrModeList as $m=>$title){
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
}
	###### DEBUG ############
	$args['debug'] = 0;
	if(0) var_dump($td);
	if(0) print_r($args . "<br>");
	if(0) var_dump($gargs);
	if(0) print("PACTION: $pAction<br>");
	if(0) print("page_id: $page_id<br>");
	if(0) print("edit_rec: $rec_pid,$cat_pid,$td_class,$bas_class,$pAction,$rspage,$pForm<br>");
	########################
?>
<html>
	<head><title>Todos</title>
<script language="JavaScript">
<!--
function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_showHideLayers() { //v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}
function JM_toggleLayer(e,id){
	if(! id) id = e.id;
	//alert(id);
	v = document.getElementById(id).style.visibility;
	v = (v=='hidden')?'show':'hide';
	MM_showHideLayers(id,'',v);
}
//-->
</script>
<body>
<form name=frmMode method=POST>
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name="rec_pid" value="<?=$rec_pid;?>">
  <input type=hidden name="flgMode">
  <input type=hidden name="rspage" value="<?=$rspage?>">
  <? editModeButtons($MODES,$pMode,$flgButtons); ?>
  <? // category select dropdown
		$onchange = 'this.form.rspage.value=1;this.form.submit();';
		//todosSelectStruct($bass_cat,$cat_pid,'subcat','','','','','',$onchange);
        todosSelectCats(IDX_ROOT_PAGEID,$cat_pid);

  ?>
 </form>
</form>