<? include_once ('./lib_todos.php');  ?>
<? @include_once($_SERVER['DOCUMENT_ROOT'] . "/_include/ch.php");          ?>
<?      include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
?>
<?php
// Todos Slide Viewer
// Jomo 01/05
// Takes a list of Todos registered images
// Outputs one at a time to a screen with
//      <img src=pid>
//      <title> // caption
//      <description>  // text description
//      < >         // next/previous buttons

if(0) print_r($args);
if(! $cat_pid) $cat_pid = $_GET['cat_pid'];
$rec_num = $_POST['rec_num'];
$td_class = todosGetCatVal($cat_pid,'bass_class');
$args['td_class']= $td_class;

switch($pAction){
	case "Update"	: ## update the record and stay on the edit page
		  $ret = todosUpdateCHash($args,$ct=0);
		  break;
     case 'goForward':
            $rec_num++;
            break;
    case 'goBack':
            $rec_num--;
            break;
    default:
            break;
}
$rec_pid = getImageFile($cat_pid,$rec_num,$err);
$img_caption = getCaption($rec_pid,$err);
$img_description = getDescription($rec_pid,$err);
$img_url = getURL($rec_pid,$err);
$img_src = urlencode($img_url);     // at this poing, todos does not enforce urlencoding in
                            // the tdURL field. hmmm
$num_recs = getNumImages($cat_pid,$err);
if($rec_num == 0) $flgBeginning = 1;
if($rec_num == ($num_recs-1)) $flgEnd = 1;
$flgRealOnly=1;
$flgDisplay =1;
$td_class = 'image';
$params = 'title,source,date,description';
$ch = todosGetClassHash($rec_pid,$td_class,$params,'',$flgRealOnly,$flgDisplay);

/*
function getNumImages($cat_pid,&$err){
    //Get the number of images contained in this recordset
    $cat_table = todosGetCatTableName($cat_pid);
    $rec_ct = todosGetCatRecordCt($cat_table);
    return($rec_ct);
}
function getImageFile($cat_pid,$rec_num=0, &$err){
    //Get list of images belonging to category cat_pid (usually a directory)
    $cat_table = todosGetCatTableName($cat_pid);
    $sql = todosGenCatSQL($cat_table);
    $page=$rec_num ;
    $rs = todosExecSQL($sql,$page,0,0,0,1);
    $rec_pid = $rs->fields[0];
    return ($rec_pid);
}
function  getCaption($rec_pid,&$err){
    // get image caption
    $imgCaption = todosGetVal($rec_pid,IDX_0,'image','title');
    return($imgCaption);
}
function getDescription($rec_pid,&$err){
    $imgDescription = todosGetVal($rec_pid,IDX_1,'image','description');
    return($imgDescription);
}
function getURL ($rec_pid,&$err){
    $imgURL = todosGetURL($rec_pid);
    return($imgURL);
}
*/

?>
<body>

<form name=frmImgEdit method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value='<?=$pAction?>'>
  <input type=hidden name=cat_pid value="<?=$cat_pid?>">
  <input type=hidden name="rec_pid" value='<?= $rec_pid ?>'>
  <input type=hidden name="srch_terms" value="<?=$srch_terms;?>">
  <input type=hidden name=rec_num value="<?=$rec_num?>">
<table border=0><tr><td> <!-- image display cell -->
<table class='image' border=0>
<tr><td align=center>
<!-- Nav buttons -->
<input type=image src="<?= TODOS_ROOT . "/images/btn_prev.gif"?>" onclick="this.form.pAction.value='goBack'"
<? if($flgBeginning) print(" disabled=true "); ?>
>
<input type=image src="<?= TODOS_ROOT . "/images/btn_next.gif"?>" onclick="this.form.pAction.value='goForward';this.form.submit()"
<? if($flgEnd) print(" disabled=true ");?>
>
<!-- end of Nav buttons -->

<tr><td class='image'>
<a href="<?=$rec_pid?>"><img src="<?=$rec_pid?>" border=0 width=300> </a>
<tr><td class='caption'><?=$img_caption?>
<tr><td class='description'><?=$img_description?>
<!-- Shim row -->
<tr><td><img src="<?= TODOS_ROOT ."/images/shim.gif"?>" width=300>
</table>
<td valign=top>
<table width=600 border=0>
<?
			echo("	<tr> <td colspan=7 valign=top align=center>\n");
			todosEditCHash($ch,$td_class,$cols);
			echo(" </td></tr>\n");
			echo(" <tr><td>&nbsp;\n");
			echo(" <tr><td colspan=4 align=center>\n");
			echo(" <input type=submit value=\"Update\"");
  			echo("	onclick=\"this.form.pAction.value = this.value\">");
			echo(" <input type=submit value=\"Cancel\"");
 			echo("	onclick=\"this.form.pAction.value = this.value\">");
			echo(" <input type=submit value=\"Delete\"");
  			echo("	onclick=\"this.form.pAction.value = this.value\">");
                    ?>
</td>
</table>
</td></tr></table>
</form>