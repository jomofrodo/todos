<? include_once ('./lib_todos.php');  ?>
<? @include_once($_SERVER['DOCUMENT_ROOT'] . "/_include/ch.php");          ?>
<?      include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
?>
<?php
// Todos Page Editor
// Jomo 01/05
// Takes a Todos category and steps through with simple editing
// Outputs one at a time to a screen with page source in an
// in-line frame for reference
//      <iframe src=rec_pid>
//      <title> // title
//      <description>  // text description
//      < >         // next/previous buttons

if(0) print_r($args);
if(! $cat_pid) $cat_pid = $_GET['cat_pid'];
$rec_num = $_POST['rec_num'];
if(!$rec_num) $rec_num= $_GET['rec_num'];

switch($pAction){
    case 'goForward':
            $rec_num++;
            break;
    case 'goBack':
            $rec_num--;
            break;
    default:
            break;
}
$rec_pid = getRecFile($cat_pid,$rec_num,$err);
$rec_title = getTitle($rec_pid);
$rec_description = getDescription($rec_pid);
$rec_url = getURL($rec_pid,$err);
$td_class = getTDClass($td_class,$cat_pid,$err);
$rec_src = urlencode($rec_url);     // at this poing, todos does not enforce urlencoding in
                            // the tdURL field. hmmm
$num_recs = getNumRecs($cat_pid);
if($rec_num == 0) $flgBeginning = 1;
if($rec_num == ($num_recs-1)) $flgEnd = 1;
$numSlct = getNumSlct($num_recs,$rec_num);
$flgRealOnly=1;
$flgDisplay =1;
$params = 'title,source,date,url,description';
$ch = todosGetClassHash($rec_pid,$td_class,$params,'',$flgRealOnly,$flgDisplay);


function getNumRecs($cat_pid,&$err){
    //Get the number of images contained in this recordset
    $cat_table = getCatTableName($cat_pid);
    $rec_ct = getCatRecordCt($cat_table);
    return($rec_ct);
}
function getRecFile($cat_pid,$rec_num=0, &$err){
    //Get record at offset rec_num in category cat_pid (usually a directory)
    $cat_table = getCatTableName($cat_pid);
    $sql = todosGenCatSQL($cat_table);
    $page=$rec_num ;
    $rs = todosExecSQL($sql,$page,0,0,0,1);
    $rec_pid = $rs->fields[0];
    return ($rec_pid);
}
function  getTitle($rec_pid,&$err){
    // get image title
    $recTitle = todosGetVal($rec_pid,IDX_0,'image','title');
    return($recTitle);
}
function getDescription($rec_pid,&$err){
    $recDescription = todosGetVal($rec_pid,IDX_1,'image','description');
    return($recDescription);
}
function getURL ($rec_pid,&$err){
    $recURL = todosGetURL($rec_pid);
    return($recURL);
}
function getTDClass(&$td_class,$cat_pid,&$err){
    if($td_class) return($td_class);
    $td_class = todos_GetBassClass($cat_pid);
    if(! $td_class) $td_class = TD_BASS_CLASS;
    return($td_class);
}
function getNumSlct($num_recs,$rec_num){
    $i = 0;
    $numSlct = "<SELECT name='rec_num_slct' onchange=this.form.rec_num.value=this.value;this.form.submit();>";
    while($i < $num_recs-1){
        $j = $i + 1;    // rec number is zero based
                        // but display is 1 based
    $numSlct .= "<option value=$i";
    if($rec_num==$i) $numSlct .= " selected ";
    $numSlct .= ">$j</option>\n";
    $i++;
    }
    $numSlct .= "</SELECT>";
    return($numSlct);
}


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
<?=$numSlct;?>
<!-- end of Nav buttons -->

<tr><td>
<iframe width=400 height=279 name=irec src="<?=$rec_url?>"></iframe>
<tr><td class='caption'><?=$rec_title?>
<tr><td class='description'><?=$rec_description?>
<tr><td class='description'><?=$rec_pid?>
<!-- Shim row -->
<tr><td><img src="/images/shim.gif" width=300>
</table>
<td valign=top>
<table border=0>
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
