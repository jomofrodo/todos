<?
/*#####################################################################*
**                              START                                  *
**                          CONFIGURATION                              *
**#####################################################################*/
$doc_root = $_SERVER['DOCUMENT_ROOT'];
if(!$doc_root){
	print("No DOCUMENT ROOT variable on this server.<br>");
	print("Please define SITE_PATH_ROOT in todosConfig.php, usually in _include<br>");
}
/*
 	Site specific configurations in /_include/todosConfig.php
*/
include_once($doc_root."/_include/todosConfig.php");

define("SITE_PATH_ROOT", 	$doc_root);
// e.g. "/www/servers/AoM/www"

define("TODOS_ROOT", 		'/_todos3');
define("TODOS_PATH_ROOT", 	SITE_PATH_ROOT . TODOS_ROOT);
define("TODOS_VIRT_ROOT",	TODOS_ROOT);
define("FLG_LINKS_IN_NEW_WINDOW",	0);	## Set to 1 to have documents appear in sub-window
//define("TARGET_VIEW",       '_self');
//define("TARGET_EDIT",       '_td_edit');
define("FLG_CVS",			0);	## Set to 1 for CVS control
					## CVS not quite ready for prime time
					## jomo 1/05
#$mysqlver = system("mysql -V");
#$mysqlver = preg_match("/[0-9]\.[0-9]\.[0-9]+/",$mysqlver);
#$mysqlver = preg_replace("/([0-9]\.)[0-9]\.([0-9]+)/","$1$2",$mysqlver);
#define("MySQLVer",		$mysqlver);
// Above is nice, but outputs the MySQL version line to the screen
// darn.
define("MySQLVer",		"4.15");

define("IDX3_CLASS_TABLES",	0);		## Turn on/off use of the idx3_<class> tables
						## Still in development

#define("DEBUG", 0);
define("DEBUG", 1);

if(! $DB_SOFTWARE){ // Only set if not set in site specific config file
	## Database Connections
	  ## Todos DB
	$DB_SOFTWARE  = 	"mysql";
	$DB_LOCATION        = 	"localhost";
	$DB_ACCOUNT         =	"root";
	$DB_PASSWORD        =	'ROOT';
	$DB_DATABASE        =	'fbr';

     ## Login database
	//$DBL_SOFTWARE  = 	"mysql";
	$DBL_LOCATION        = 	"localhost";
	$DBL_ACCOUNT         =	"root";
	$DBL_PASSWORD        =	'ROOT';
	$DBL_DATABASE        =	'fbr';
}

function configReport(){
//
// Print out listing of config variables
//
print $doc_root . "<br>";
print("SITE_PATH_ROOT: 		". SITE_PATH_ROOT 	. "<br>");
print("TODOS_ROOT:		". TODOS_ROOT 		. "<br>");
print("TODOS_PATH_ROOT: 	". TODOS_PATH_ROOT 	. "<br>");
print("TODOS_VIRT_ROOT:	". TODOS_ROOT		. "<BR>");
print("OPTION_LINK_TARGET:	". OPTION_LINK_TARGET	. "<BR>");
print("FLG_LINKS_IN_NEW_WINDOW: ". FLG_LINKS_IN_NEW_WINDOW . "<BR>");	
print("FLG_CVS:			". FLG_CVS		. "<BR>");
print("MySQLVer:		". MySQLVer		. "<BR>");


return(0);
}

?>
