<?php
	ob_start();
	//session_cache_limiter('nocache');
	session_start();
#$args = $_REQUEST;
#extract($args);
if(0) var_dump($_GET);
$pid = $_REQUEST['pid'];
$flgPHP = 0;
$flgMktg = 0;
$flgNormal = 1;
if(preg_match("/\.php/",$pid)) $flgPHP =1;
if(preg_match("/.*Press_Release/",$pid)){ $flgMktg =1; $flgNormal=0;}
$pid = preg_replace('/\/\//', '/', $pid);
if(0) print("flgPHP: $flgPHP<br>");
if(0) print("PID: $pid<br>");
if(0) print("ndx: fn - $fn, pid - $pid<BR>");

if(0) exit;

// ouput with headers and footers
if($flgNormal) include_once("$_SERVER[DOCUMENT_ROOT]/_include/ch.php");
if($flgMktg) include_once("$_SERVER[DOCUMENT_ROOT]/News/Press_Releases/_include/mh.php");
include_once("$_SERVER[DOCUMENT_ROOT]/$pid");
if($flgMktg) include_once("$_SERVER[DOCUMENT_ROOT]/News/Press_Releases/_include/mf.php");
if($flgNormal) include_once("$_SERVER[DOCUMENT_ROOT]/_include/cf.php");

ob_end_flush();
?>
