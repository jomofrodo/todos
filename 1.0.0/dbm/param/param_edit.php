<?php ob_start();?>
<?php  include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php"); ?>
<?php
  // Page to edit all fields for a selected param
  // Allows update
  // Allows delete
  // Called from class detail
  // Cancel returns to class detail
  //
  // This is the start of the todos dbm
  //
  // Jomo 2/07

    // first we have to do our includes
     include_once(TODOS_PATH_ROOT ."/lib_todos.php");
     include_once(TODOS_PATH_ROOT ."/_inc_Cat_hdr.php");

    // Page variables
    $flgPage = 1;
    $ct = 0;
    $eoClass = $eoClass;                // from GET originally
    $pName = $pName;
    $pClass = $pClass;              // This is a param eoClass. If passed into this page
                                    // and it is different from eoClass, it is an ancestor
                                    // class, and we are about to do a class param override

    // Override
    if(! $flgOverride) $flgOverride=0;
    if($pClass && ($pClass != $eoClass)) $flgOverride=1;

    if($flgOverride) $sql = "SELECT * FROM tblParams WHERE pName='$pName' AND eoClass='$pClass'";
    else  $sql = "SELECT * FROM tblParams WHERE pName='$pName' AND eoClass='$eoClass'";

		switch($pAction){
			case "Update"	: ## update the record and stay on the edit page
                    if($isa==EO_CLASS_EO){$msg="Cannot edit an EO entry";break; }
                    if($flgOverride){
                        $ret = todosAddNewParam($args);
                        $msg= "Class param updated. Override created.";

                    }
                    else{
    					  $ret = todosUpdateParam($args);
                          if(!$ret) $msg = "Class record updated";
                    }
					  break;
			case "Cancel"	: break;
			case "Delete"	:
                    if(!$pConfirm){
                           $flgConfirm = 1;
                            $msg = "<font color=red>Really delete this record?</font> ";
 	                        $msg .= (" <input type=submit value=\"Confirm\"");
  	                        $msg .= ("	onclick=\"this.form.pConfirm.value = this.value\">");
                            $msg .= "<input type=submit value='Cancel'  onclick=\"this.form.pAction.value=this.value;\">";
                            $msg .= "<input type=hidden name=pConfirm>";
                            break;
                    }
						$ret = todosDeleteParam($pName,$eoClass);
                        if(!$ret){
    						$url = TODOS_ROOT . PAGE_CLASS_EDIT  .
    						"?eoClass=$eoClass&rspage=$rspage";
                            ob_end_clean();
    						header("Location:$url");
                            exit();
                        }
                        else{
                            $msg = $ret;
                        }
						break;
			case "ClassEdit":
			case "Return"	:
					$url = TODOS_ROOT . PAGE_CLASS_EDIT .
						"?eoClass=$eoClass&rspage=$rspage";
						header("Location:$url");
					  	break;
		}

    //Get the record for the single param
    $myRS = mysql_query($sql);
    $rsFlds = todosExecSQL($sql);

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


<form name=frmTodosAdmin method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value="<?=$pAction?>">
  <input type=hidden name="eoClass" value="<?=$eoClass?>">
  <input type=hidden name="flgOverride" value="<?=$flgOverride?>">
  <input type=hidden name="pName" value="<?=$pName?>">
  <div align=center>
  <h1>IDX: <?="$eoClass: $pName"?> </h1>
    <div align=center><h2><?=$msg?></h2> </div>
    <hr>
    <h2>Param Fields</h2>
    <div align=center>


   <table border=0 cellpadding=2 width=50%>
     <tr height=0> 
            <td width=5 height=0><input type=image src="/images/spacer.gif" value="Search" onclick="this.form.pAction.value=this.value" width=0 length=0 height=0>
            <td width=20>
            <td width=25>
	    <td width=5>
    <tr>
<?

    if(! $flgConfirm){
    //Print the class hash
        print("<tr bgcolor=#C0C0C0>");
        print("<th>#");
        print("<th>Param Name");
        print("<th>Param Val");
        print("</tr>");

        $ct =0;
        $row = mysql_fetch_object($myRS);
       while($ct < $rsFlds->_numOfFields){
        $fld = mysql_fetch_field($myRS,$ct);
        $fldName = $fld->name;
        $fldVal = $rsFlds->fields[$ct];
        $fldType = $fld->type;
        $default = $fld->default;
        $max_length = mysql_field_len($myRS,$ct);
        $max_length<50?$size=$max_length:$size=50;
        $rows = ($max_length/50);
        $cols = 50;


        $ct++;
        $bgcolor = "#FFFFFF";
        if($ct%2) $bgcolor = "#CFCFCFCF";
        print("<tr>");
        print("<th align=left bgcolor=$bgcolor>$ct</th>");
        print("<th align=left bgcolor=$bgcolor>$fldName</th>");
        print ("<td bgcolor=$bgcolor>");

        switch($fldName){
            case FLD_PNAME:
                print ("$pName");
                break;

            case FLD_EO_CLASS:
                print("$eoClass");  //Always edits/updates for current eoClass
                break;
            case FLD_P_TYPE:
               $slcPType = printSelect(FLD_P_TYPE,TBL_PARAMS,FLD_P_TYPE,FLD_P_TYPE,$fldVal);
                break;
            case FLD_P_DISPTYPE:
                $pDispTypes = todosGetListPDispTypes();
                $slcPDispType = printSelect(FLD_P_DISPTYPE,TBL_PARAMS,FLD_P_DISPTYPE,FLD_P_DISPTYPE,$fldVal);
                break;
            case FLD_P_VALTYPE:
                $pValTypes = todosGetListPValTypes();
                $slcPValType = printSelect(FLD_P_VALTYPE,TBL_PARAMS,FLD_P_VALTYPE,FLD_P_VALTYPE,$fldVal);
                break;
            case FLD_TD_TYPE:
                $tdTypes = todosGetListTDTypes();
                $slcTDType = printSelect(FLD_TD_TYPE,TBL_PARAMS,FLD_TD_TYPE,FLD_TD_TYPE,$fldVal);
                break;
            default:
                if($max_length <= 50){
                        print ("<input name='$fldName' value='$fldVal' size=$size max_size=$max_length>\n");

                }
                else{
                        print("<textarea name='$fldName' rows='$rows' cols='$cols'>$fldVal</textarea>");

                }
        }
    }    print("</table>");

    print("<div align=center>");
    print("<table width=80%>");
    echo(" <tr><td>&nbsp;\n");
	echo(" <tr><td colspan=8 align=center>\n");
	echo(" <input type=submit value=\"Update\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Delete\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Return\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
    print("</table>");
    print("</div>");

    print("</table>"); // close the class params table
    print("</div>");
    } // end of flgConfirm
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