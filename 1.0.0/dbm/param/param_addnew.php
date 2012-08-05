<?php ob_start()?>
<?php  include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php"); ?>
<?php
  // Page to add a new param to a given class
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
    $flgAdded = 0;
    if(! $rspage) $rspage=0;
    $REQUEST_URI = $_SERVER[REQUEST_URI];
    $pgClassEdit = TODOS_ROOT . PAGE_CLASS_EDIT;
    $eoClass = $eoClass;                // from GET originally

    // Defaults
    if(! $pValType)         $pValType = PVALTYPE_PVAL;
    if(! $tdType)           $tdType = IDX_1;

   //Get the list of fields in TBL_PARAM
    //$flds = todosGetParamFields();
    $sql = "select * from " . TBL_PARAMS;
    $rsFlds = mysql_query($sql);

		switch($pAction){
			case "Add"	: ## update the record and stay on the edit page
 					  $ret = todosAddNewParam($args);
                      if(!$ret){
                          $flgAdded = 1;
                          $msg = "Param record added";
                      }
					  break;
            case "Cancel"   :
			case "Continue"	:
					$url = TODOS_ROOT . PAGE_CLASS_EDIT .
						"?eoClass=$eoClass&rspage=$rspage";
                        ob_end_clean();
						header("Location:$url");
                        exit();
					  	break;
		}


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
  <input type=hidden name="pName" value="<?=$pName?>">
  <div align=center>
  <h1>IDX Add New Param: <?="$eoClass: $pName"?> </h1>
    <div align=center><h2><?=$msg?></h2> </div>

   <table border=1 cellpadding=2 width=50%>
     <tr height=0> 
            <td width=5 height=0><input type=image src="/images/spacer.gif" value="Search" onclick="this.form.pAction.value=this.value" width=0 length=0 height=0>
            <td width=20>
            <td width=25>
	    <td width=5>
    <tr>
<?
     if(!$flgAdded){
    //Print the class hash
        print("<hr>");

        print("<h2>Param Fields</h2>");
        print("<div align=center>");
        print("<tr bgcolor=#C0C0C0>");
        print("<th>#");
        print("<th>Field Name");
        print("<th>Value");
        print("</tr>");

        $ct =0;
    while ($ct < mysql_num_fields($rsFlds)){
        $fld = mysql_fetch_field($rsFlds,$ct);
        $fldName = $fld->name;
        $fldType = $fld->type;
        $default = $fld->default;
        $max_length = $fld->max_length;
        $max_length<50?$size=$max_length:$size=50;

        $ct++;
        $bgcolor = "#FFFFFF";
        if($ct%2) $bgcolor = "#CFCFCFCF";
        print("<tr>");
        print("<th align=left bgcolor=$bgcolor>$ct</th>");
        print("<th align=left bgcolor=$bgcolor>$fldName</th>");
        print ("<td bgcolor=$bgcolor>");

        switch($fldName){
            case FLD_EO_CLASS:
               $eoClasses = todosGetListEOClasses();
               $slcPType = printSelect(FLD_EO_CLASS,TBL_EOCLASSES,FLD_EO_CLASSDESCRIPTION,FLD_EO_CLASS,$eoClass);
                break;
            case FLD_P_TYPE:
               $slcPType = printSelect(FLD_P_TYPE,TBL_PARAMS,FLD_P_TYPE,FLD_P_TYPE,$pType);
                break;
            case FLD_P_DISPTYPE:
                $pDispTypes = todosGetListPDispTypes();
                $slcPDispType = printSelect(FLD_P_DISPTYPE,TBL_PARAMS,FLD_P_DISPTYPE,FLD_P_DISPTYPE,$pDispType);
                break;
            case FLD_P_VALTYPE:
                $pValTypes = todosGetListPValTypes();
                $slcPValType = printSelect(FLD_P_VALTYPE,TBL_PARAMS,FLD_P_VALTYPE,FLD_P_VALTYPE,$pValType);
                break;
            case FLD_TD_TYPE:
                $tdTypes = todosGetListTDTypes();
                $slcTDType = printSelect(FLD_TD_TYPE,TBL_PARAMS,FLD_TD_TYPE,FLD_TD_TYPE,$tdType);
                break;
            default:
                print ("<input name='$fldName' value='$default' size=$size max_size=$max_length>\n");
                break;
        }
    }
     } //end flgAdded
    print("</table>");

    print("<div align=center>");
    print("<table width=80%>");
    echo(" <tr><td>&nbsp;\n");
	echo(" <tr><td colspan=8 align=center>\n");
    if(!$flgAdded){
	echo(" <input type=submit value=\"Add\"");
    echo(" enabled=false ");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Cancel\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
    }
    else{
	echo(" <input type=submit value=\"Continue\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
    }
    print("</table>");
    print("</div>");
    
    print("</table>"); // close the class params table
    print("</div>");
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