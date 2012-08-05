<?@include_once($_SERVER[DOCUMENT_ROOT] . "/_include/ch.php");?>
<?php

    // first we have to do our includes
	include_once(dirname(__FILE__)."/lib_todos.php");
	include_once(dirname(__FILE__)."/_inc_Cat_hdr.php");

    //List Cat Tables
    //Allow user to
    //   Inhale cat_table
    //   Del cat_table
    //   Show details (# recs)

    $args=$_POST;
    if(!$args) $args= $_REQUEST;
    extract($args);
    $cat_tables = todosListCatTables();
    $catArray = explode(',',$cat_tables);
    $cat_table_pid =  todosConvCatTable2PID($cat_table);


    switch($pAction){
            case "Inhale"   :
     			if(1) print("<br>inhaling $cat_table<br>");

                    $ct = todosInhaleCatTable($cat_table);
                    $msg = "Inserted/updated $ct records from $cat_table_pid<br>";
                    break;
            case "Delete"   :
                    todosDropCatTable($cat_table_pid);
                    break;
    }

    $cat_select = "<SELECT name='cat_table'>";
    foreach($catArray as $tbl){
            $cat_select .= "<OPTION value='$tbl'>$tbl</OPTION>";
    }
    $cat_select .= "</SELECT>";
?>
<HTML>
<head><title>Todos :: CatTable Admin</title>
</head>
<body>
   <div align=center><?=$msg?></div>
 <form>
    <?=$cat_select?> <br>
   <input type=hidden name=pAction value='<?=pAction?>'>
 	<input type=submit value="Inhale Category" onclick='this.form.pAction.value="Inhale"'>
 	<input type=submit value="Delete Category" onclick='this.form.pAction.value="Delete"'>
 </form>
