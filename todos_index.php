<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/_include/ch.php");
include_once("./lib_todos.php");

?>
<?php     $strDebug = "?DBGSESSID=1;d=1" ?>
<BODY>
<center>
<font size=+2>
<!-- <REVISIT> <a href="/_lib/Todos/todos.1.0.php">Search</a><br> -->
<a href="/_lib/Todos/<?=todos_1_1.$strDebug?>">Search</a><br>
<a href="/_lib/Todos/<?=todos_1_2.$strDebug?>">View Record</a><br>
<a href="/_lib/Todos/<?=todos_1_3.$strDebug?>">Edit a  Record</a><br>
<a href="/_lib/Todos/<?=todos_1_3_1.$strDebug?>">Edit Categories</a><br>
<a href="/_lib/Todos/<?=todos_1_4.$strDebug?>">Add a New Record</a><br>
<a href="/_lib/Todos/<?=todos_1_4_1.$strDebug?>">Add a New Category</a><br>
</font>
</center>
<? //include_once ($_SERVER['DOCUMENT_ROOT'] . "/_include/cf.php"); ?>
