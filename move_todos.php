<?php
// Move Todos Records
// Jomo 1/05
// Basically need to move a lot of CCB record from / to /IDX
   include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");
   $args= $_GET;
   extract($args);
   $ret = 0;
   if($flgSubmit){
     $ret = fixSearchPattern($pattern,$sql_pat);
     $ret1 = todosMoveABunchofPIDs($pattern,$replace,$sql_pat);
     $ret2 = todosMoveABunchofURLs($pattern,$replace,$sql_pat);
     $mvCt = $ret1 + $ret2;
   }


//################################################################
function fixSearchPattern(&$pattern, &$sql_pat){
     // add escapes to slashes
    $pattern = preg_replace("/\//", '\/', $pattern);
    // unescape a closing $ string end indicator
    $pattern = preg_replace('/\\\$/','$',$pattern);

        // for sql searching, replace * wildcards with %
        $sql_pat = $pattern ;
        $ret = preg_match("/\*/",$sql_pat);
        if(! $ret) $sql_pat .= '%';  // wild card end of string by default
        $sql_pat = preg_replace('/\.\*/','%',$sql_pat);
        $sql_pat = preg_replace('/\*/','%',$sql_pat);

    // locaters
    $ret = preg_match("/\*/",$pattern);
    if(! $ret) $pattern = "^" . $pattern;
    // get rid of wildcarding for preg_replace
    $pattern = preg_replace ("/\*/",'',$pattern);
    return(0);
}
//################################################################
function todosMoveABunchOfPIDs($pattern,$replace,$sql_pat){
// Move a bunch of todos table entries with tdPID of form
//   <pattern>.*  to <new_value>.*
//

    //Get list of records to move
    $sql = "SELECT * FROM tblTodos WHERE tdPageID like '$sql_pat'";
    $rs = todosExecSQL($sql);
	$rs->MoveFirst();
    $numRows = $rs->_numOfRows;
    $ct = 0;
		while(! $rs->EOF){
			$old_pid = $rs->fields['tdPageID'];
            $new_pid = preg_replace("/$pattern/",$replace,$old_pid);
            $args['new_rec_pid'] = $new_pid;
            $args['old_rec_pid'] = $old_pid;
            $ret = todosMoveCHash($args);
			if(0) print("updating Cat Table: $cat_table,$cid,$pid,$pname,$val<br>");
			$rs->MoveNext();
            $ct++;
		}
    if(0) print("Moved $ct records<br>");
    return($ct);
}
//################################################################
function todosMoveABunchOfURLs($pattern,$replace,$sql_pat){
// Move a bunch of todos table entries with tdIR: of form
//   <pattern>.  to <replace>.
// Relies on the fact (assumption) that the tdURL is usually a PID!!
//

    //Get list of records to move
    $sql = "SELECT * FROM tblTodos WHERE tdURL like '$sql_pat'";
    $rs = todosExecSQL($sql);
	$rs->MoveFirst();
    $numRows = $rs->_numOfRows;
    $ct = 0;
		while(! $rs->EOF){
			$old_pid = $rs->fields['tdURL'];
            $new_pid = preg_replace("/$pattern/",$replace,$old_pid);
            $args['new_rec_pid'] = $new_pid;
            $args['old_rec_pid'] = $old_pid;
            $ret = todosMoveCHash($args);
			if(0) print("updating Cat Table: $cat_table,$cid,$pid,$pname,$val<br>");
			$rs->MoveNext();
            $ct++;
		}
    if(0) print("Moved $ct records<br>");
    return($ct);
}

?>
        <h1>Move Todos</h1>
        <P>Move todos with PageIDs starting with the string given in Search Pattern to having the starting string given in Replacement.
    <form name=frmMoveTodos method=GET>
    <input type=hidden name=flgSubmit value=1>
    <table>
    <tr><td>Search Pattern<td><input name=pattern type=text>
    <tr><td>Replacement<td><input name="replace" type=text>
    <tr><td colspan=2 align=center><input type=submit>
    </table>
</form>
<? if($mvCt) print ("<font color=red>Moved $mvCt records from $pattern to $replace</font><br>");
    elseif ($flgSubmit) print ("<font color=red>No records found matching $pattern</font><br>"); ?>
        <P> e.g., Search Pattern: "/News*"<br>
                    Replacement:  "/IDX/News"<br>
        will move every td with a PageID that starts with "/News" to start with "/IDX/News".<br>
        <P>Note: Do not use quotation marks when specifying "Pattern" and "Replacement"
        <P> Wildcarding allowed with either '.*' or '%' grammar.
