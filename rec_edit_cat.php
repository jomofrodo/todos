<? ob_start();
//session_cache_limiter('private'); ?>
<?@include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");?>
<?
    /* Manage category membership and subcat relationships
       Lots of groovy sql, but basically easy idea
       3 looks:
            Member of
            Subcat of
            Subcats of

            For each look, show a select box for categories both in and out, with arrows both ways
            A little trickiness added to the sql so that subcat loops are avoided

            Jomo, 2.07
    */
    // first we have to do our includes
     include_once(dirname(__FILE__)."/lib_todos.php");
     include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");
     include_once(dirname(__FILE__)."/_inc_Rec_hdr.php");
?>
<!--  ################################################################ -->
<?

	$optCatIn='';
	$optCatOut='';

    //$cat_pid = $rec_pid;
    $bass_cat_pid = IDX_ROOT_PAGEID;

    //Cat Edit MODE
    // modeCatMember  -- this record is a member of these category
    // modeSubcatOf   --- this record is a subcat of these categories
    // modeSubcats    --  Subcats of this record
    define("MODE_CAT_MEMBER",   'modeCatMember');
    define("MODE_SUBCAT_OF",    'modeSubcatOf');
    define("MODE_SUBCATS",      'modeSubcats');

    $modeCats = $args['modeCats'];
    $cat_out 	= $_POST[cat_out];
	$cat_in		= $_POST[cat_in];
	$cat_table 	= $_SESSION['cat_table'];

    // If cat_pid is a subcat or member of some category, should show up in the
    // IN box


    //In SQL: Cats of which the record is a member
    $sql_memin = "SELECT DISTINCT t1.tdPageID,t1.tdTitle ";
    $sql_memin .= "FROM tblTodos as t1 ";
    $sql_memin .= " INNER JOIN tblTodos as t2 on t1.tdPageID = t2.tdPageID ";
    $sql_memin .= " WHERE (1=1) ";
    $sql_memin .= " AND t1.tdClass = 'category' ";
    $sql_memin .= " AND t1.pName = 'title' ";
    $sql_memin .= " AND (t2.tdClass = 'member') ";
    $sql_memin .= " AND t2.tdURL = '$rec_pid'";
    $sql_memin	.= " ORDER BY t1.tdTitle\n";
             //members in pageids only (arghh!)
    $sql_mip = "SELECT DISTINCT t1.tdPageID ";
    $sql_mip  .= "FROM tblTodos as t1 ";
    $sql_mip  .= " INNER JOIN tblTodos as t2 on t1.tdPageID = t2.tdPageID ";
    $sql_mip  .= " WHERE (1=1) ";
    $sql_mip  .= " AND t1.tdClass = 'category' ";
    $sql_mip  .= " AND t1.pName = 'title' ";
    $sql_mip  .= " AND (t2.tdClass = 'member') ";
    $sql_mip  .= " AND t2.tdURL = '$rec_pid'";

    //Cats that the record is not a member of
    $sql_memout = "SELECT DISTINCT t1.tdPageID,t1.tdTitle ";
    $sql_memout .= "FROM tblTodos as t1 ";
    $sql_memout .= " WHERE (1=1) ";
    $sql_memout .= " AND t1.tdClass = 'category' ";
    $sql_memout .= " AND t1.pName = 'title' ";
    $sql_memout .= " AND t1.tdPageID NOT IN ($sql_mip)";
    $sql_memout	.= " ORDER BY t1.tdTitle\n";

    // SUBCAT OF
    //Cats that this record is a subcat of
    $sql_subcatofin = "SELECT DISTINCT t1.tdPageID, t1.tdTitle ";
    $sql_subcatofin .= "FROM tblTodos as t1 ";
    $sql_subcatofin .= " INNER JOIN tblTodos as t2 on t1.tdPageID = t2.tdPageID ";
    $sql_subcatofin .= " WHERE (1=1) ";
    $sql_subcatofin .= " AND t1.tdClass = 'category' ";
    $sql_subcatofin .= " AND t1.pName = 'title' ";
    $sql_subcatofin .= " AND (t2.tdClass = 'subcat') ";
    $sql_subcatofin .= " AND t2.tdURL = '$rec_pid'";
    $sql_subcatofin	.= " ORDER BY t1.tdTitle\n";
              //subcat of in page ids (arghh!)
    $sql_soip = "SELECT DISTINCT t1.tdPageID ";
    $sql_soip  .= "FROM tblTodos as t1 ";
    $sql_soip  .= " INNER JOIN tblTodos as t2 on t1.tdPageID = t2.tdPageID ";
    $sql_soip  .= " WHERE (1=1) ";
    $sql_soip  .= " AND t1.tdClass = 'category' ";
    $sql_soip  .= " AND t1.pName = 'title' ";
    $sql_soip  .= " AND (t2.tdClass = 'subcat') ";
    $sql_soip  .= " AND t2.tdURL = '$rec_pid'";
                     // my subcats in pageids only (arghh!)
    $sql_msip = "SELECT DISTINCT t1.tdPageID ";
    $sql_msip  .= "FROM tblTodos as t1 ";
    $sql_msip  .= " INNER JOIN tblTodos as t2 on t1.tdPageID = t2.tdURL ";
    $sql_msip  .= " WHERE (1=1) ";
    $sql_msip  .= " AND t1.tdClass = 'category' ";
    $sql_msip  .= " AND t1.pName = 'title' ";
    $sql_msip  .= " AND (t2.tdClass = 'subcat') ";
    $sql_msip  .= " AND t2.tdPageID = '$rec_pid'";



    $sql_subcatofout = "SELECT DISTINCT t1.tdPageID, t1.tdTitle ";
    $sql_subcatofout .= "FROM tblTodos as t1 ";
    $sql_subcatofout .= " WHERE (1=1) ";
    $sql_subcatofout .= " AND t1.tdClass = 'category' ";
    $sql_subcatofout .= " AND t1.pName = 'title' ";
    $sql_subcatofout .= " AND t1.tdPageID NOT IN ";
    $sql_subcatofout .= " ($sql_soip) ";
        //Let's avoid subcat recursion, shall we . . .
    $sql_subcatofout .= " AND t1.tdPageID NOT IN ";
    $sql_subcatofout .= " ($sql_msip) ";
    $sql_subcatofout .= " ORDER BY t1.tdTitle\n";

       // SUBCATS
    //Cats that this record has as subcats
    $sql_mysubcats = "SELECT DISTINCT t1.tdPageID, t1.tdTitle ";
    $sql_mysubcats .= "FROM tblTodos as t1 ";
    $sql_mysubcats .= " INNER JOIN tblTodos as t2 on t1.tdPageID = t2.tdURL ";
    $sql_mysubcats .= " WHERE (1=1) ";
    $sql_mysubcats .= " AND t1.tdClass = 'category' ";
    $sql_mysubcats .= " AND t1.pName = 'title' ";
    $sql_mysubcats .= " AND (t2.tdClass = 'subcat') ";
    $sql_mysubcats .= " AND t2.tdPageID = '$rec_pid'";
    $sql_mysubcats	.= " ORDER BY t1.tdTitle\n";

       //Cats that this record does not have as subcats

    $sql_notmysubcats = "SELECT DISTINCT t1.tdPageID, t1.tdTitle ";
    $sql_notmysubcats .= "FROM tblTodos as t1 ";
    $sql_notmysubcats .= " WHERE (1=1) ";
    $sql_notmysubcats .= " AND t1.tdClass = 'category' ";
    $sql_notmysubcats .= " AND t1.pName = 'title' ";
    $sql_notmysubcats .= " AND t1.tdPageID NOT IN ";
    $sql_notmysubcats .= " ($sql_msip) ";
    //Let's avoid subcat recursion, shall we . . .
    $sql_notmysubcats .= " AND t1.tdPageID NOT IN ";
    $sql_notmysubcats .= " ($sql_soip) ";
    $sql_notmysubcats .= " ORDER BY t1.tdTitle\n";




    switch($modeCats){

        case MODE_SUBCAT_OF:
            $sql_in = $sql_subcatofin;
            $sql_out = $sql_subcatofout;
            $title_cat_in = "Subcategory of these Categories";
            $title_cat_out = "Not a Subcategory of these Categories";
            $link_class = EO_CLASS_SUBCAT;
            break;

        case MODE_SUBCATS:
            $sql_in = $sql_mysubcats;
            $sql_out = $sql_notmysubcats;
            $title_cat_in = "Subcategories of this Category";
            $title_cat_out = "Not Subcategories of this Category";
            $link_class = EO_CLASS_SUBCAT;
            break;

        case MODE_CAT_MEMBER:
        default:
            $sql_in = $sql_memin;
            $sql_out = $sql_memout;
            $title_cat_in = "Member of these Categories";
            $title_cat_out = "Not in these Categories";
            $link_class = EO_CLASS_MEMBER;
            break;

    }


	switch($pAction){
		case 'AddCat':
				if(0) print("CAT_ADD:");
				if(0) var_dump($cat_out);
                if(! $cat_out) break;
 				foreach($cat_out as $add_cat_pid){
					## if not already in cat_in
					$srch_pid = preg_replace("/\//",'\/',$add_cat_pid);
					$srch_pid = "\'" . $srch_pid . "\'";
					if(0) print("$srch_pid<br>");
					if(preg_match("/$srch_pid/",$slcCatIn)){
						if(0) print("FOUND<br>"); continue;}

                    switch($modeCats){
                        case MODE_SUBCATS:        //Add category as subcat of current record
                            $ret = todosLinkSub2Cat($add_cat_pid,$rec_pid);
                             break;
                        case MODE_SUBCAT_OF:  //Add current record as subcat of selected record(s)
                            $ret = todosLinkSub2Cat($rec_pid,$add_cat_pid);
                            break;
                        case MODE_CAT_MEMBER:
                            //Add current rec as member of selected cats
        					$ret = todosAddToCategory($rec_pid,$add_cat_pid);
        					## Add Record to Cat table entries
        					$ret = todosInsertIdxRecord($rec_pid,$add_cat_pid);
                            break ;
        				}
                }

				break;
		case 'RmCat':
				if(0) print("CAT_RM:");
				if(0) var_dump($cat_in);
                if(! $cat_in) break;
				foreach($cat_in as $rem_cat_pid){
                     switch($modeCats){
                        case MODE_SUBCATS:        //Remove category as subcat of current record
                            $ret = todosUnlinkSubCat($rem_cat_pid,$rec_pid);
                             break;
                        case MODE_SUBCAT_OF:  //Unlink current record as subcat of selected record(s)
                            $ret = todosUnlinkSubCat($rec_pid,$rem_cat_pid);
                            break;
                        case MODE_CAT_MEMBER:
          					$ret = todosRemoveFromCategory($rec_pid,$rem_cat_pid);
        					## Delete Cat table entries
        					$ret = todosDeleteIdxRecord($rec_pid,$rem_cat_pid);
                          break ;
        			  }

				}
				break;
	}
 /*
    $slcCatOut = printSelect("cat_out[]",'','tdTitle','tdPageID',$cat_out,0,0,
			$sql_out,'',0,"$addCat_enable;$rmCat_disable;$rmCat_unselect;",'','%',7,1,0);
	$slcCatIn = printSelect("cat_in[]",'','tdTitle','tdPageID',$cat_in,0,0,
			$sql_in,'',0,"$rmCat_enable;$addCat_disable;$addCat_unselect;",'','%',7,1,0);
  */
	// Little scripts
	$addCat_enable 		= 'this.form.arrwAddCat.disabled=false';
	$addCat_disable		= 'this.form.arrwAddCat.disabled=true';
	$rmCat_enable 		= 'this.form.arrwRmCat.disabled=false';
	$rmCat_disable 		= 'this.form.arrwRmCat.disabled=true';
	$rmCat_unselect		= 'this.form.cat_in.value=""';
	$addCat_unselect	= 'this.form.cat_out.value=""';

?>

<table>
  <form name=frmRecEdit method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" >
  <input type=hidden name="pMode" value="<?=$pMode;?>">
  <input type=hidden name="flgMode">
  <input type=hidden name="pForm" value="recProperties">
  <input type=hidden name="rec_pid" value='<?= $rec_pid ?>'>
  <input type=hidden name="tdID" value='<? echo $tdID?>'>
  <input type=hidden name="rspage" value=1>
  <input type=hidden name="srch_terms" value="<?=$srch_terms;?>">

<input type=hidden name="td_type" value="<?=IDX_3?>">

  <table border=0 cellpadding=4 cellspacing=4>
    <tr>
    <td colspan=7 width=200>
    <select name="modeCats" value="<?=$modeCats?>" onchange="this.form.submit()">
      <option value="<?=MODE_CAT_MEMBER?>" <?if($modeCats == MODE_CAT_MEMBER) print(" selected");?>>Member of</option>
      <option value="<?=MODE_SUBCAT_OF?>" <?if($modeCats == MODE_SUBCAT_OF) print(" selected");?>>Subcat of</option>
      <option value="<?=MODE_SUBCATS?>" <?if($modeCats == MODE_SUBCATS) print(" selected");?>>Subcats of this category</option>
    </select>
    <tr>
	<td colspan=7 align=center width=200>
		<h3><?=$title_cat_in?></h3>

		<? printSelect("cat_in[]",'','tdTitle','tdPageID',$cat_in,0,0,
			$sql_in,'',0,"$rmCat_enable;$addCat_disable;$addCat_unselect;",'','%',7,1)?>
	<td valign=center align=center>
		<p>&nbsp;
		<p>
		<input type=submit name=arrwRmCat value="-->" onclick="this.form.pAction.value='RmCat'"
			disabled=true>
		<p>
		<input type=submit name=arrwAddCat value="<--" onclick="this.form.pAction.value='AddCat'">
	<td>
		<h3><div align=center><?=$title_cat_out?></div></h3>
		<? printSelect("cat_out[]",'','tdTitle','tdPageID',$cat_out,0,0,
			$sql_out,'',0,"$addCat_enable;$rmCat_disable;$rmCat_unselect;",'','%',7,1)?>


  </table>
  <?
  	if(0) print("sql_in: $sql_in<br>");
	if(0) print("sql_out: $sql_out<br>");
  ?>

<!-- ###########################################################################  -->
</form>