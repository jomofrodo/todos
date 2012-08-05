<?php ob_start();  session_start(); ?>
<?include_once($_SERVER['DOCUMENT_ROOT']."/_include/ch.php");  ?>
<?php

// Todos Slide Viewer

// Jomo 01/05

// Takes a category of Todos registered images
// Outputs one at a time to a screen with
//      <img src=rec_pid>
//      <title> // caption
//      <description>  // text description
//      < >         // next/previous buttons

include_once($_SERVER['DOCUMENT_ROOT']."/_todos3/lib_todos.php");

if(0) print_r($args);
$gallery_pid = $cat_pid;
$cat_table = todosGetCatTableName($gallery_pid);
$rec_num = $_POST['rec_num'];
if(! $rec_num) $rec_num = 0;
$num_recs = getNumImages($gallery_pid,$err);

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

if($rec_num == 0) $flgBeginning = 1;
if($rec_num == ($num_recs-1)) $flgEnd = 1;
$err = '';
$img_pid = getImageFile($gallery_pid,$rec_num,$err);
$img_caption = getCaption($img_pid,$err);
$img_description = getDescription($img_pid,$err);
$img_source = getSource($img_pid,$err);
$gallery_title = todosGetTitle($gallery_pid);
$gallery_description = getCatDescription($gallery_pid,$err);
$gallery_context = todosGetVal($gallery_pid,IDX_1,EO_CLASS_PAGE,TD_PNAME_DESCRIPTION);
$img_url = getURL($img_pid,$err);
$img_src = urlencode($img_url);     // at this poing, todos does not enforce urlencoding in
                            // the tdURL field. hmmm
$photogalTitle=$gallery_title;
$photogalDesc=$gallery_description;
?>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>Free Burma Rangers :: Reports :: Photo Gallery :: <?=$photoGalTitle?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<meta name="description" content="The Free Burma Rangers (FBR) is a multi-ethnic humanitarian service movement. They bring help, hope and love to people in the war zones of Burma. Ethnic pro-democracy groups send teams to be trained, supplied and sent into the areas under attack to provide emergency assistance and human rights documentation. Together with other groups, the teams work to serve people in need. The teams are to avoid contact with the Burma Army but cannot run if the people cannot run." />

<meta name="keywords" content="Free Burma Rangers, Karen, Karen State, Karenni, Shan, Lahu, Arakan areas of Burma, Myanmar, Human Rights" />

<meta http-equiv="Page-Enter" content="blendTrans(Duration=1.00)">

<link rel="stylesheet" type="text/css" href="/css/fbr.css" />

<script type="text/javascript" language="JavaScript1.2" src="/menu/stm31.js"></script>

</head>
  <!-- Start main body container cell -->
        <!-- begin gallery title and date -->
        <table cellpadding="0" cellspacing="0" border="0" style="margin-top: 24px;" width="100%">
          <tr>
            <td class="breadcrumb"><?=$bc?></td>
            <td width="30%" align="right"><span class="greytext"><?=$rec_num+1?>/<?=$num_recs?> </span></td>
          </tr>
        </table>
        <div class="divdashedline"><img src="/image/blank.gif" /></div>
        <table>
			<tr>
			<td class="reporttitle"><?=$gallery_title?>
			<td>&nbsp;
			<td><em><?=$photogalDesc?></em>
		</table>

          <!-- end gallery title and description -->

        <form method=POST>

          <input type=hidden name=rec_num value="<?=$rec_num?>">

          <input type=hidden name=pAction>

          <input type=hidden name=gallery_pid value="<?=$gallery_pid?>">

          <table border=0 cellpadding="0" cellspacing="0" align="center">

            <tr>

              <td width="50%" align="right"> 

                <input type=image src="/image/left_arrow.gif" onClick="this.form.pAction.value='goBack'"

<? if($flgBeginning) print(" disabled=true "); ?> name="prev photo" style="padding-right:20px;padding-bottom:10px;"

>

              </td>

              <td width="50%" align="left"> 

                <input type=image src="/image/right_arrow.gif" onClick="this.form.pAction.value='goForward';this.form.submit()"
				<? if($flgEnd) print(" disabled=true ");?> name="next photo" 
				style="padding-right:20px;padding-bottom:10px;"

				>



</td>

</tr>

<tr>

	<td colspan="2" class="galleryimage">

              </td>

            </tr>

            <tr> 

              <td colspan="2">

                <div align="center"><a href="<?=$img_pid?>"><img src="<?=$img_pid?>" alt="<?$img_description?>" border=0 height=279 ></a></div>

              </td>

            </tr>

            <tr> 

              <td colspan="2">&nbsp;</td>

            </tr>

            <tr> 

              <td colspan="2" class="gallerycaption"><strong><?=$img_caption?> 

                </strong><br />

                <br />

                <?=$img_description?></td>

            </tr>

          </table>
        </form>
      <div class="divdashedline"><img src="/image/blank.gif" /></div>
      <div align=center><em><?=$gallery_context?></em>
        <!-- End main body container cell --> </td>

    </tr>

    <tr> 

      <td class="footer" style="text-align:left;">&copy; 2007 Free Burma Rangers 

        | <a href="mailto:info@freeburmarangers.org" class="footerlink">Contact 

        webmaster</a></td>

      <td class="footer" style="text-align:right;">&nbsp;</td>

    </tr>

  </table>

</div>

</body>

</html>

<?php

// Todos Slide Viewer

// Jomo 01/05

// Takes a category of Todos registered images

// Outputs one at a time to a screen with

//      <img src=rec_pid>

//      <title> // caption

//      <description>  // text description

//      < >         // next/previous buttons







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



if($rec_num == 0) $flgBeginning = 1;

if($rec_num == ($num_recs-1)) $flgEnd = 1;



function getNumImages($gallery_pid,&$err){

    //Get the number of images contained in this recordset

    GLOBAL $cat_table;

    $rec_ct = todosGetCatRecordCt($cat_table);

    return($rec_ct);

}

function getImageFile($gallery_pid,$rec_num=0, &$err){

    //Get an image belonging to category gallery_pid (usually a directory)

    GLOBAL $cat_table;

    $sql = todosGenCatSQL($cat_table);

    $page=$rec_num ;

    $rs = todosExecSQL($sql,$page,0,0,0,1);

    $rec_pid = $rs->fields[0];

    return ($rec_pid);

}

function  getCaption($rec_pid,&$err){

    GLOBAL $cat_table;

    // get image caption, aka title

    $imgCaption = todosGetFieldVal($rec_pid,'title',$cat_table,'pid');

    return($imgCaption);

}

function getCatDescription($gallery_pid,&$err){

    GLOBAL $cat_table;

    $catDescription = todosGetVal($gallery_pid,IDX_1,'category','description');

    //$imgDescription = todosGetFieldVal($rec_pid,'description',$cat_table,'pid');

    return($catDescription);

}

function getDescription($rec_pid,&$err){

    GLOBAL $cat_table;

    //$imgDescription = todosGetVal($rec_pid,IDX_1,'image','description');

    $imgDescription = todosGetFieldVal($rec_pid,'description',$cat_table,'pid');

    return($imgDescription);

}

function getSource($rec_pid,&$err){

   GLOBAL $cat_table;

    //$imgSource= todosGetVal($rec_pid,IDX_1,'image','source');

    $imgSource = todosGetFieldVal($rec_pid,'source',$cat_table,'pid');

     return($imgSource);

}

function getURL ($rec_pid,&$err){

    $imgURL = todosGetURL($rec_pid);

    return($imgURL);

}



?>