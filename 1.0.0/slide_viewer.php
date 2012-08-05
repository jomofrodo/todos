<html><head>
<meta http-equiv="Page-Enter" content="blendTrans(Duration=1.00)">
<? @include_once($_SERVER['DOCUMENT_ROOT'] . "/_include/ch.php");          ?>
<? include_once ('./lib_todos.php');  ?>

<?php
// Todos Slide Viewer
// Jomo 01/05
// Takes a category of Todos registered images
// Outputs one at a time to a screen with
//      <img src=rec_pid>
//      <title> // caption
//      <description>  // text description
//      < >         // next/previous buttons

if(0) print_r($args);
if(! $cat_pid) $cat_pid = $_GET['cat_pid'];
$rec_num = $_POST['rec_num'];

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
$err = '';
$img_pid = getImageFile($cat_pid,$rec_num,$err);
$img_caption = getCaption($img_pid,$err);
$img_description = getDescription($img_pid,$err);
$img_source = getSource($img_pid,$err);
$cat_title = todosGetTitle($cat_pid);
$img_url = getURL($img_pid,$err);
$img_src = urlencode($img_url);     // at this poing, todos does not enforce urlencoding in
                            // the tdURL field. hmmm
$num_recs = getNumImages($cat_pid,$err);
if($rec_num == 0) $flgBeginning = 1;
if($rec_num == ($num_recs-1)) $flgEnd = 1;

function getNumImages($cat_pid,&$err){
    //Get the number of images contained in this recordset
    $cat_table = getCatTableName($cat_pid);
    $rec_ct = getCatRecordCt($cat_table);
    return($rec_ct);
}
function getImageFile($cat_pid,$rec_num=0, &$err){
    //Get an image belonging to category cat_pid (usually a directory)
    $cat_table = getCatTableName($cat_pid);
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
function getSource($rec_pid,&$err){
    $imgSource= todosGetVal($rec_pid,IDX_1,'image','source');
    return($imgSource);
}
function getURL ($rec_pid,&$err){
    $imgURL = todosGetURL($rec_pid);
    return($imgURL);
}

?>
<link rel="stylesheet" href="/css/ccb_main.css">
<body>

<form method=POST>
<input type=hidden name=rec_num value="<?=$rec_num?>">
<input type=hidden name=pAction>
<input type=hidden name=cat_pid value="<?=$cat_pid?>">
  <div id="Layer3" style="position:absolute; width:210px; height:70px; z-index:3; left: 48px; top: 21px"> 
    <div id="Layer2" style="position:absolute; width:315px; height:80px; z-index:2; left: -27px; top: 111px" > 
      <h1><?=$cat_title?></h1></div>
    <div id="Layer1" style="position:absolute; width:445px; height:260px; z-index:1; left: -27px; top: 201px; overflow: visible"><a href="<?=$img_pid?>"><img src="<?=$img_pid?>" border=0 height=279 align="right"></a></div>
    <div id="Layer4" style="position:absolute; width:200px; height:46px; z-index:3; left: 293px; top: 151px"> 
      <input type=image src="<?= TODOS_ROOT . "/images/btn_prev.gif"?>" onClick="this.form.pAction.value='goBack'"
<? if($flgBeginning) print(" disabled=true "); ?> name="image"
>
      <input type=image src="<?= TODOS_ROOT . "/images/btn_next.gif"?>" onClick="this.form.pAction.value='goForward';this.form.submit()"
<? if($flgEnd) print(" disabled=true ");?> name="image"
>
      <!-- end of Nav buttons --> <!-- Image Row --> <!-- Nav buttons --></div>  
    <div id="Layer5" style="position:absolute; width:225px; height:275px; z-index:4; left: 443px; top: 196px; overflow: auto"><?=$img_description?></div>
    <div id="LayerCaption" align="right" style="position:absolute; width:200px; height:25px; z-index:5; left: 168px; top: 481px; overflow: visible"> 
      <div class='caption'>
        <div align="right"><?=$img_caption?> </div>
      </div>
        
    </div>
    <div id="Layer6" style="position:absolute; width:45px; height:30px; z-index:6; left: 373px; top: 481px"> 
      <div class='source'>--<?=$img_source?></div>
    </div>
  </div>
  </form>