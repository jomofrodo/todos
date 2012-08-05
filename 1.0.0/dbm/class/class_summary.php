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
    $pgClassEdit = TODOS_ROOT . PAGE_CLASS_EDIT;

    $sql = "SELECT * FROM " . TBL_EOCLASSES;
    $sql .= " ORDER BY ";
    if($sort){  // sort monkey business
        $pos = strpos($oldsort,$sort);
        if($pos){
            $oldsort = preg_replace('/$sort,?/','',$oldsort);
            if(pos ==1){
            $sort = "$sort desc, $oldsort";
            }
            else{
            $sort = "$sort, $oldsort";
            }
        }
        $sql .= "$sort, ";
     }
    $sql .= "  eoClass, idxClassPriority, idxSort ";

    GLOBAL $gDBtd;
    $db = $gDBtd;
    $rs = todosExecSQL($sql,$rspage);


		switch($pAction){
			case "Update"	: ## update the record and stay on the edit page
					  $ret = todosUpdateCHash($args,$ct=0);
						$td = todosGetClassHash($rec_pid,$eo_class,'',$debug); break;
						break;
			case "Cancel"	: $td = todosGetClassHash($rec_pid,$td_class);break;
			case "Delete"	: 
						$ret = todosDeleteCHash($args);
						$ret = deleteTodos_Form($args);
						$url = TODOS_ROOT . PAGE_CAT_SUMMARY . 
						"?rec_pid=$rec_pid&td_class=$td_class&rspage=$rspage";
						header("Location:$url");
						break;
			case "AddNew"	: 
					  preg_match('/(\/.*\/).*/',$rec_pid,$amatch);
					  $pidnew = $amatch[1];
					  $pidnew .= "<NEW_PAGE_NAME>";
					  $url = TODOS_ROOT . PAGE_REC_ADDNEW . 
						"?rec_pid=$pidnew&rec_pid=$rec_pid&td_class=$td_class";
					  header("Location:$url");
					  break;
			case "ClassSummary":
			case "Summary"	:
					$url = TODOS_ROOT . PAGE_CAT_SUMMARY .
						"?rec_pid=$rec_pid&td_class=$td_class&rspage=$rspage";
						header("Location:$url");
					  	break;
			default		: 
						$td = todosGetClassHash($rec_pid,$eo_class,'',$debug); break;

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
		
<h1>IDX Class Editor </h1>

<form name=frmTodosAdmin method=post enctype="multipart/form-data">
  <input type=hidden name="pAction" value="editHash">
  <input type=hidden name="rspage" value=1>
  <input type=hidden name="oldsort" value="<?$sort?>">
  <input type=hidden name="sort" value="<?$sort?>">
  <table border=0 cellpadding=2>
     <tr height=0> 
            <td width=5 height=0><input type=image src="/images/spacer.gif" value="Search" onclick="this.form.pAction.value=this.value" width=0 length=0 height=0>
            <td width=100> 
            <td width=100> 
	    <td width=250>
    <tr>
    <script language=javascript>
    function aSort(f,srt){
        //sets the value of the sort field in the form to param srt
        //alert(srt);
        f.sort.value=srt;
        f.submit();
    }
    </script>
    <?//List the classes
        print("<tr bgcolor=#C0C0C0>");
        print("<th>#");
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
        $eoClass = $rs->fields[FLD_EO_CLASS];
        $eoType = $rs->fields[FLD_EO_TYPE];
        $eoClassDescription = $rs->fields[FLD_EO_CLASSDESCRIPTION];
        $idxSort = $rs->fields[FLD_IDX_SORT];
        $idxClassPriority = $rs->fields[FLD_IDX_CLASSPRIORITY];
        $tdx  = $rs->fields[FLD_TDX];
        $isa  =$rs->fields[FLD_ISA];
        print("<a href=\"PAGE_CLASS_EDIT?eoClass=$eoClass\">");
        print("<tr bgcolor=$bgcolor>");
        print("<td width=2><a href=\"$pgClassEdit?eoClass=$eoClass\">$ct</a>");
        print("<td width=15><a href=\"$pgClassEdit?eoClass=$eoClass\"> $eoClass</a>");
        print("<td width=12><a href=\"$pgClassEdit?eoClass=$eoClass\"> $eoType</a>");
        print("<td width=25><a href=\"$pgClassEdit?eoClass=$eoClass\"> $eoClassDescription</a>");
        print("<td width=2><a href=\"$pgClassEdit?eoClass=$eoClass\"> $idxSort</a>");
        print("<td width=4><a href=\"$pgClassEdit?eoClass=$eoClass\"> $idxClassPriority</a>");
        print("<td width=1><a href=\"$pgClassEdit?eoClass=$eoClass\"> $tdx</a>");
        print("<td width=5><a href=\"$pgClassEdit?eoClass=$eoClass\"> $isa</a>");
        print("</tr>");
        print("</a>");
        $rs->MoveNext();
    }


    ?>

<?


	echo(" <tr><td>&nbsp;\n");
	echo(" <tr><td colspan=8 align=center>\n");
	echo(" <input type=submit value=\"Add New\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");
	echo(" <input type=submit value=\"Cancel\"");
  	echo("	onclick=\"this.form.pAction.value = this.value\">");

	
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