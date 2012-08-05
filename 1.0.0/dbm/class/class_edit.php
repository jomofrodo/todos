<?php  include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php"); ?>
<?php
  // Page to view the summary of all classes defined in tbleoclasses
  // Allows selecting a class for view/edit
  // Allows adding a new class
  //
  // This is the start of the todos dbm
  //
  // Jomo 2/07

    // first we have to do our includes
     include_once(TODOS_PATH_ROOT ."/lib_todos.php");
     include_once(TODOS_PATH_ROOT ."/_inc_Cat_hdr.php");

    // Page variables
    $flgPage = 1;
    if(! $rspage) $rspage=0;
    $ct = 0;
    $REQUEST_URI = $_SERVER[REQUEST_URI];
    $pgClassSummary = TODOS_ROOT . PAGE_CLASS_SUMMARY;
    $pgParamEdit = TODOS_ROOT .PAGE_PARAM_EDIT;
    $ch = todosGetClassHash($pid,$eoClass);

    $sql = "SELECT * FROM  " . TBL_EOCLASSES;
    $sql .= " WHERE eoClass ='$eoClass' ";
    $sql .= " ORDER BY ";
    $sql .= "  eoClass, idxClassPriority, idxSort ";


    GLOBAL $gDBtd;
    $db = $gDBtd;


		switch($pAction){
            case "Add Param":  // Add a new param for this class to tblParams
 					$url = TODOS_ROOT . PAGE_PARAM_ADDNEW .
						"?eoClass=$eoClass&rspage=$rspage";
						header("Location:$url");
					  	break;

			case "Update"	: ## update the record and stay on the edit page
                    if($isa==EO_CLASS_EO){$msg="Cannot edit an EO entry";break; }
					  $ret = todosUpdateClass($args);
                      if(!$ret) $msg = "Class record updated";
					  break;
			case "Cancel"	: break;
			case "Delete"	:
                    if($isa == EO_CLASS_EO){
                            $msg = "Cannot delete an EO entry";
                            break;
                    }
                    if(!$pConfirm){
                            $msg = "<font color=red>Really delete this class?</font> ";
 	                        $msg .= (" <input type=submit value=\"Confirm\"");
  	                        $msg .= ("	onclick=\"this.form.pConfirm.value = this.value\">");
                            $msg .= "<input type=submit value='Cancel'  onclick=\"this.form.pAction.value=this.value;\">";
                            $msg .= "<input type=hidden name=pConfirm>";
                            break;
                    }
 					$ret = todosDeleteClass($eoClass);
                    if(!$ret){
						$url = $pgClassSummary  .
						"?rspage=$rspage";
						header("Location:$url");
                    }
                    else{
                        $msg = $ret;
                    }
					break;
			case "ClassSummary":
			case "Summary"	:
					$url = $pgClassSummary .
						"?eo_class=$eoClass&rspage=$rspage";
						header("Location:$url");
					  	break;
		}

      $rs = todosExecSQL($sql,$rspage);

	###### DEBUG ############
	$args['debug'] = 0;
	if(0) print_r($args . "<br>");
	if(0) var_dump($gargs);
	if(0) print("PACTION: $pAction<br>");
	if(0) print("page_id: $page_id<br>");
	if(0) print("class_edit: $rec_pid,$td_class,$pAction,$rspage<br>");
	if(0) var_dump($td);
	########################
?>


	
<html>
	<head><title>Todos</title></head>
	<body>
		
<h1>IDX Editor: <?=$eoClass?> </h1>

<form name=frmTodosAdmin method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value="<?=$pAction?>">
  <input type=hidden name="eoClass" value="<?=$eoClass?>">
  <div align=center><h2><?=$msg?></h2> </div>

   <table border=0 cellpadding=2>
     <tr height=0> 
            <td width=5 height=0><input type=image src="/images/spacer.gif" value="Search" onclick="this.form.pAction.value=this.value" width=0 length=0 height=0>
            <td width=100> 
            <td width=100> 
	    <td width=5>
    <tr>
    <?//List the class
        print("<tr bgcolor=#C0C0C0>");
        print("<th>Class");
        print("<th>Type");
        print("<th>Description");
        print("<th><a onclick=\"aSort(this.form,'idxSort');\">Idx Sort</a>");
        print("<th>Class Priority");
        print("<th>Tdx(?)");
        print("<th>isa");
        print("</tr>");

    $ct =0;

    while(! $rs->EOF){
        $ct++;
        $bgcolor = "#FFFFFF";
        if($ct%2) $bgcolor = "#CFCFCFCF";

        $eoClass    = $rs->fields[FLD_EO_CLASS];
        $eoType     = $rs->fields[FLD_EO_TYPE];
        $eoClassDescription = $rs->fields[FLD_EO_CLASSDESCRIPTION];
        $idxSort    = $rs->fields[FLD_IDX_SORT];
        $idxClassPriority = $rs->fields[FLD_IDX_CLASSPRIORITY];
        $tdx        = $rs->fields[FLD_TDX];
        $isa        = $rs->fields[FLD_ISA];
        $sqlSlc     = "SELECT eoType, eoType from tblEOTypes ORDER BY eoType";
        $slcEOType  = printSelect('eoType',TBL_EOTYPES,FLD_EO_TYPE,FLD_EO_TYPE,$eoType,0,1,$sqlSlc,'',0,'','','',1,0,0);
        $slcIsa     = printSelect('isa',TBL_EOTYPES,FLD_EO_TYPE,FLD_EO_TYPE,$isa,0,1,$sqlSlc,'',0,'','','',1,0,0);

        print("<tr bgcolor=$bgcolor>");
       print("<td width=15>$eoClass</a>");
        print("<td width=12>$slcEOType");
        print("<td width=25><input type=text name=eoClassDescription value='$eoClassDescription'>");
        print("<td width=2><input size=2 type=text name='idxSort' value='$idxSort'>");
        print("<td width=4><input size=4 type=text name='idxClassPriority' value='$idxClassPriority'>");
        print("<td width=1><input size=1 type=text name='tdx' value='$tdx'>");
        print("<td width=5>$slcIsa");
        print("</tr>");

        $rs->MoveNext();
    }
    print("</table>");
    print("<div align=center>");
    print("<table width=50%>");

   echo(" <tr><td>&nbsp;\n");
	echo(" <tr><td colspan=8 align=center>\n");
	echo(" <input type=submit value=\"Update\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Cancel\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Delete\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Summary\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
    print("</table>");
    print("</div>");


    //Print the class hash
        print("<hr>");

        print("<h2>Class Parameters</h2>");
        print("<div align=center>");
        print("<table width=90%>");
        print("<tr bgcolor=#C0C0C0>");
        print("<th>#");
        print("<th>Param Name");
        print("<th>Description");
        print("<th>Title");
        print("<th>eoClass");
        print("<th>idxSort");
        print("</tr>");

        $ct =0;
    foreach($ch as $key => $value){
		$td1 = $value;
		//extract($td1);
		if(0) var_dump($td1);
		//#if(0) exit;

		$pClass 	= $td1['eoClass'];
		$tdtype		= $td1['tdType'];
		$ptype		= $td1['pType'];
		$pName 		= $td1['pName'];
		$pdescription	= $td1['pDescription'];
		$pvaltype	= $td1['pValType'];
		$pvallist	= $td1['pValList'];
		$plength	= $td1['pLength'];
		$pdispwidth	= $td1['pDispWidth'];
		$val		= $td1['val'];
        $pDispTitle = $td1['pDispTitle'];
        $pTitle     = $td1['pDispTitle'];
        $idxSort    = $td1['idxSort'];

		if(0) print(" $pname,$val<br>");
        $ct++;
        $bgcolor = "#FFFFFF";
        if($ct%2) $bgcolor = "#CFCFCFCF";

        print("<tr>");
        print("<td><a href=\"$pgParamEdit?pName=$pName&eoClass=$eoClass&pClass=$pClass\">$ct</a>");
        print("<td>$pName</td>");
        print("<td>$pdescription</td>");
        print("<td>$pDispTitle</td>");
        print("<td>$pClass</td>");
        print("<td>$idxSort</td>");
        print("</tr>");

    }
    
    print("</table>"); // close the class params table
    print("</div>");

    print("<div align=center>");
    print("<table width=50%>");

    echo(" <tr><td>&nbsp;\n");
	echo(" <tr><td colspan=8 align=center>\n");
	echo(" <input type=submit value=\"Add Param\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	    print("</table>");
    print("</div>");

    ?>

<?



	
?>
</form>
  </table>

<script language="javascript">
		$1st = 10;
                $idx = 0;
		$form = document.forms[0];
		$elem = $form.elements[$1st];
                //alert($elem.name);
		$elem.focus();
		$elem.select();
</script>