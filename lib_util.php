<?php
########
#################################################################
function addArray(&$array, $key, $val)
{
  $tempArray = array($key => $val);
  $array = array_merge($array, $tempArray);

	if(0) var_dump("$array<br>");
}
//########################################################################
// jomo 2/05
function uploadErr($arrFile,$flgVerbose=0,$flgReturn=0){
// Report on an error with file upload
//   PARAMETERS:
//          $arrFile -- this is the $_FILES super variable or equivalent
//          $flgVerbose -- print the error message
//          $flgReturn;
//                  0 -- return the error
//                  1 -- return the error message
//   OUTPUT:
//          If an error found, prints the error message
//          Returns the value of the error, 0 if no error
/*
UPLOAD_ERR_OK
Value: 0; There is no error, the file uploaded with success.

UPLOAD_ERR_INI_SIZE
Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.

UPLOAD_ERR_FORM_SIZE
Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.

UPLOAD_ERR_PARTIAL
Value: 3; The uploaded file was only partially uploaded.

UPLOAD_ERR_NO_FILE
Value: 4; No file was uploaded.

UPLOAD_ERR_NO_TMP_DIR
Value: 6; Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.
*/

    $intErr = $arrFile['error'];
  switch($intErr){
        case 0:   $msg = "There is no error, the file uploaded with success.";
                    break;
        case 1:   $msg = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                    break;
        case 2:   $msg = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                    break;
        case 3:   $msg = "The uploaded file was only partially uploaded.";
                    break;
        case 4:   $msg = "No file was uploaded.";
                    break;
        case 5:   $msg = "Unknown file upload error. The file did not upload successfully.";
                    break;
        case 6:   $msg = "Missing a temporary folder -- there is an error in the PHP setup on this server. Please contact the system administrator.";
                    break;
  }
   if(0){
        if($intErr){
             print("<PRE>");
             print "File upload not successful!  Here's some debugging info:\n";
  	         print_r($_FILES);
	         print_r("UploadFile: $uploadfile<br>");
	         print "</pre>";
        }
   }
  if($flgVerbose) print($msg);
  if($flgReturn){
       return($msg);
  }
  else{
         return($intErr);
  }
}
########################################################################
// Jomo 2003
// Uses the php $_FILES super variable collection
function copyFile($srcfname,$uploadfile){
	print "<pre>";
    $intErr = uploadErr ($_FILES[$srcfname],0);
	if (move_uploaded_file($_FILES[$srcfname]['tmp_name'], $uploadfile)) {
	   if(0) {
	     print "File is valid, and was successfully uploaded. ";
 	     print "Here's some more debugging info:\n";
 	     print_r($_FILES);
	   }
	} else {
            uploadErr($_FILES[$srcfname]);
            /*
  	  print "File upload not successful!  Here's some debugging info:\n";
  	  print_r($_FILES);
	  print_r("UploadFile: $uploadfile<br>");
	  print "</pre>";
	  //if(0) exit;
      */
	}
	print "</pre>";
	return(0);
}
################################################################################
function date2iso($date){
## take a date in the form 04/03/2003 and return ISO format date
##  will accept dates in any of the following formats:
##	yyyy-mm-dd  (just returns as is)
##	yy--mm-dd   (adds "20" to the year)
##	mm.dd.[cc]yy 
##	mm/dd/[cc]yy
##		-- fills in zeros in front of day and mo if necc.
##
	$mo 	= 0;
	$day 	= 0;
	$yr     = 0;
	$date_orig = $date;

	if (preg_match("/^\w{4}-\w{2}-\w{2}$/",$date))$date_iso= $date;
	if (preg_match("/^\w{2}-\w{2}-\w{2}$/",$date)) $date_iso = "20" . $date;
	## 12/03/03
	if (preg_match("/(^\d{1,2})\/(\d{1,2})\/(\d{2,4})$/",$date,$amatch)){ 
		$mo = $amatch[1]; $day = $amatch[2]; $yr = $amatch[3];
		if (strlen($mo) == 1) {$mo = "0" . $mo;}
		if (strlen($day) == 1) {$day = "0" . $day;}
		if (strlen($yr) == 2){$yr = "20" . $yr;}
		$date_iso = "$yr-$mo-$day";
	}
	## 12.03.03
	elseif (preg_match("/(^\d{1,2})\.(\d{1,2})\.(\d{2,4})$/",$date,$amatch)){ 
		$mo = $amatch[1]; $day = $amatch[2]; $yr = $amatch[3];
		if (strlen($mo) == 1) {$mo = "0" . $mo;}
		if (strlen($day) == 1) {$day = "0" . $day;}
		if (strlen($yr) == 2){$yr = "20" . $yr;}
		$date_iso = "$yr-$mo-$day";
	}
	## 12.03
	elseif (preg_match("/(^\d{1,2})\.(\d{2,4})$/",$date,$amatch)){ 
		$mo = $amatch[1]; $yr = $amatch[2];
		if (strlen($mo) == 1) {$mo = "0" . $mo;}
		if (strlen($yr) == 2){$yr = "20" . $yr;}
		$day = '01';
		$date_iso = "$yr-$mo-$day";
	}
	## 12/03
	elseif (preg_match("/(^\d{1,2})\/(\d{2,4})$/",$date,$amatch)){ 
		$mo = $amatch[1]; $yr = $amatch[2];
		if (strlen($mo) == 1) {$mo = "0" . $mo;}
		if (strlen($yr) == 2){$yr = "20" . $yr;}
		$day = '01';
		$date_iso = "$yr-$mo-$day";
	}## 20041231
	elseif (preg_match("/^(\d{4})(\d{2})(\d{2})$/",$date,$amatch)){
		$yr = $amatch[1]; $mo = $amatch[2]; $day = $amatch[3];
		$date_iso = "$yr-$mo-$day";
    }
	
	if(0) print("date2iso: $date_orig,$date_iso<br>");
	if(! $date_iso) { print("unrecognized date format: $date<br>");exit;}
	###<DEBUG> 
	###<DEBUG> 
	return($date_iso);
}
#########################################################
## ISO date format converters
## many thanks to Jon Haworth at: http://www.laughing-buddha.net/jon/php/dates/
##

function iso2ts ($iso, $hour=3, $min=0, $sec=0)
{
  $d = substr($iso, 8, 2);
  $m = substr($iso, 5, 2);
  $y = substr($iso, 0, 4);
  return mktime($hour, $min, $sec, $m, $d, $y);
}

function mdy2ts	   ($str) { $ts =  strtotime($str);if(0) print("mdy2ts: $str,$ts<br>");return$ts;  }
function iso2dmy   ($iso) { return ts2dmy(iso2ts($iso));   }
function iso2mdy   ($iso) { return ts2mdy(iso2ts($iso));   }
function iso2short ($iso) { return ts2short(iso2ts($iso)); }
function iso2long  ($iso) { return ts2long(iso2ts($iso));  }

function ts2iso    ($ts) { return date("Y-M-d", $ts);  }
function ts2dmy    ($ts) { return date("d/m/Y", $ts);  }
function ts2mdy    ($ts) { $mdy=date("m/d/Y", $ts);if(0) print("tsmdy: $ts, $mdy<br>"); return($mdy); } 
function ts2short  ($ts) { return date("M d y", $ts);  }
function ts2long   ($ts) { return date("jS F Y", $ts); }
function ts2datetime   ($ts) { return date("M d y H:s A", $ts); }
function myts2mdy($val){ preg_match("/(^\d{4})(\d{2})(\d{2})(.*?)/",$val,$m); $mdy = "$m[2]/$m[3]/$m[1]"; return($mdy); }
function myts2mdyhms($val){
		preg_match("/(^\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(.*?)/",$val,$m);
		$mdyhms = "$m[2]/$m[3]/$m[1]  $m[4]:$m[5]:$m[6]"; 
		if(0) print("myts2mdyhms: $val, $mdyhms<br>");
		return($mdyhms);
	}


//################################################################
#function printSelectArray(	$select_name, $A,$current_val='x', $flgAll='0', $flgSelect='0',
#		 $flgSubmit=0, $onChange='', $txtAll='--All Values--', $valAll='%',$size=1,$multiple=false,
#		$flgOutput=0){
##
//
// Arrayified version of printSelect
//	assumes an array of $val=>$display
//
function printSelectArray(
			$select_name,
			$A='',
			$current_val='x',
			$flgAll='0',
			$flgSelect='0',
			$flgSubmit=0,
			$onChange='',
			$txtAll='',
			$valAll='%',
			$size=1,
			$multiple = false,
			$flgOutput = 0
		){

	if ($textAll == '') $txtAll = ' -- All Values --';
	if ($valAll == '') $valAll = '%';

//Print a select HTML form object

				$slc = ("<SELECT name=" . $select_name );
	if($flgSubmit) 		$slc .= (" onchange='this.form.submit()'");
	if($onChange) 		$slc .= (" onchange='$onChange'");
	if($size > 1)		$slc .= (" size='$size' ");
	if($multiple)		$slc .= (" multiple ");
				$slc .= (">\n");
	if($flgAll) 	$slc .= ("<OPTION value='$valAll'>$txtAll</OPTION>\n");
	if($flgSelect) 	$slc .= ("<OPTION value=''>&nbsp;&nbsp;&nbsp;--  Please Select  --</OPTION>\n");
	if($A){
	  foreach($A as $val=>$display){
		$slc .= ("<OPTION ");
		$slc .= ("value='" . $val . "' " );
		if($val == $current_val){
			$slc .= ("selected ");
		}
		$slc .= (">");
		$slc .= ($display);
		$slc .= ("</OPTION>");
	}}
	$slc .= ('</SELECT>');
	if($flgOutput) print ($slc);
	if(0) print("printSelectArray: $select_name,$current_val, $flgAll,$flgSelect,$txtAll,$valAll,$flgOutput,$size<br>");
	return($slc);
}

################################################################################
function mysql2lt($dt){
#
# Convert a MySQL timestamp to localtime format
#
        $yr  = substr($dt,0,4);
        $yr = $yr - 1900;               # Arggh
        $mo  = substr($dt,4,2);
        $mo = $mo -1;                   # Months are zero based
        $day = substr($dt,6,2);
        $hr  = substr($dt,8,2);
        $min = substr($dt,10,2);
        $sec = substr($dt,12,2);
	
	## <REVISIT> don't know how to set this
	## Daylight Savings Time flag 
	$flgDS = 0;

        $lt = mktime($hr,$min,$sec,$mo,$day,$yr,$flgDS);

	if(0) print("mysql2lt: $dt,$lt<br>");
        return ($lt);
}

#########################################################
function fileStat($filename){
## Return the stats for filename
##
	// open the file
	$fp = fopen("$filename", "r");

	// gather statistics
	$fstat = fstat($fp);

	// close the file
	fclose($fp);
	return($fstat);
/*
this will output : 

Array
(
    [dev] => 771
    [ino] => 488704
    [mode] => 33188
    [nlink] => 1
    [uid] => 0
    [gid] => 0
    [rdev] => 0
    [size] => 1114
    [atime] => 1061067181
    [mtime] => 1056136526
    [ctime] => 1056136526
    [blksize] => 4096
    [blocks] => 8
)
*/
 

}
	

#########################################################
#########################################################
## Multi-Dimensional Array Quick Sort
##  many thanks to fmmarzoa and Richard dot C dot Mitchell at http://us2.php.net/manual/en/function.sort.php
##

function array_qsort2 (&$array, $column=0, $order=SORT_ASC, $first=0, $last= -2) 
{ 
 // $array  - the array to be sorted 
 // $column - index (column) on which to sort 
 //          can be a string if using an associative array 
 // $order  - SORT_ASC (default) for ascending or SORT_DESC for descending 
 // $first  - start index (row) for partial array sort 
 // $last   - stop index (row) for partial array sort 

 if($last == -2) $last = count($array) - 1; 
 if($last > $first) { 
   $alpha = $first; 
   $omega = $last; 
   $guess = $array[$alpha][$column]; 
   while($omega >= $alpha) { 
    if($order == SORT_ASC) { 
       while($array[$alpha][$column] < $guess) $alpha++; 
       while($array[$omega][$column] > $guess) $omega--; 
     } else { 
       while($array[$alpha][$column] > $guess) $alpha++; 
       while($array[$omega][$column] < $guess) $omega--; 
     } 
     if($alpha > $omega) break; 
    $temporary = $array[$alpha]; 
     $array[$alpha++] = $array[$omega]; 
     $array[$omega--] = $temporary; 
   } 
  array_qsort2 ($array, $column, $order, $first, $omega); 
  array_qsort2 ($array, $column, $order, $alpha, $last); 
} 
} 

#########################################################
function array_qsort(&$array, $num = 0, $order = "ASC", $left = 0, $right = -1) {

if($right == -1) {
$right = count($array) - 1;
}

$links = $left;
$rechts = $right;
$mitte = $array[($left + $right) / 2][$num];

if($rechts > $links) {
do {
if($order == "ASC") {
while($array[$links][$num]<$mitte) $links++;
while($array[$rechts][$num]>$mitte) $rechts--;
} else {
while($array[$links][$num]>$mitte) $links++;
while($array[$rechts][$num]<$mitte) $rechts--;
}

if($links <= $rechts) {
$tmp = $array[$links];
$array[$links++] = $array[$rechts];
$array[$rechts--] = $tmp;
}

} while($links <= $rechts);

if ($left < $rechts) $array = array_qsort($array,$num,$order,$left, $rechts);
if ($links < $right) $array = array_qsort($array,$num,$order,$links,$right);
}

return $array;
}

/*---------------------------------------------------------------------*
** util_db_failure                                                     *
** unabashadly ripped off from php lib login -- thanks		       *
** a little better onscreen reporting if adodb can't find or use the   *
** database. added 0-8
**---------------------------------------------------------------------*/
function util_db_failure($db_location,$db_account,$db_database,$db_software,$db_password,$admin_email)
{
	
	$warning =<<<WARN
		<html><head><body bgcolor="red">
		<font face="Arial, Helvetica, sans-serif" color="#FFFFFF">
			<font size="5">
			database failure!
			</font><p>
			<font size="3">
			there has been a database failure in php_lib_login.
			please contact your system adminstrator <a href="mailto:$admin_email">here</a>
			and include the error message below:
			</font>
			<p>
		</font>
WARN;

	$warningtwo =<<<WARN2
		\$DB_LOCATION $db_location<br>
		\$DB_ACCOUNT $cb_account<br>
		\$DB_PASSWORD *******<br>
		\$DB_DATABASE $db_database<br>
		\$DATABASE_SOFTWARE $db_software<br>
WARN2;

	echo $warning;
	$gDB = NewADOConnection($db_software);
	$gDB->PConnect($db_location, $db_account, $db_password, $db_database);
	echo $warningtwo;


	die;
}

//################################################################
function utilConv2Display($val,$ptype){
//
//	Convert a stored value for display
///
	$db_val = $val;
	## Don't convert null values
	## Good idea??
	if(! $val) return;
	switch($ptype){
		case	'date': 
			$val = iso2mdy($val);
			break;
		case 	'timestamp':
			if(! $val) break;
			## MySQL timestamp
			if (strlen($val) == 14) $val = myts2mdyhms($val);
			## Unix timestamp
			else $val = ts2mdy($val);
			break;
	}

	if(0) print("Conv2Display: $val,$db_val<br>");
	return($val);
}

//################################################################
function utilConv2DB($val,$ptype){
//
//	Convert a Display value for db storage
///
	$disp_val = $val;
	switch($ptype){
		case	'date'		: $val = date2iso($val); break;
		case 	'timestamp'	: 
			
			if(preg_match("/\//",$val)) $val = mdy2ts($val); break;
	}
	$val = utilFixString($val);
	if(0) print("Conv2DB: $val, $disp_val<br>");
	return($val);
}

//################################################################
function utilFixString($val){
//
//	Fix a string for insertion into DB
//
	// NOT WORKING
	$val_orig = $val;
	$pattern = "/'/";
	$repl	=  "''";
	$val = preg_replace($pattern,$repl,$val);
	$pattern = "/''''/";
	$repl = "''";
	$val = preg_replace($pattern,$repl,$val);
	if(0) print("FixString: $val, $val_orig<br>");
	return($val);
}

function utilTableExists($tbl,$db) {
	if(0) print_r("table_exists: $table,$db<br>");
    $tbl = strtolower($tbl); // always convert to lowercase for comparison
	$sql = "SHOW TABLES";
	$tables = $db->Execute($sql);
	while (! $tables->EOF){
		$temp = $tables->fields[0];
	 	if(0) print("$temp<br>");
 	 	if(strtolower($temp) == strtolower($tbl)) { return TRUE; }
		$tables->MoveNext();
 	}
	return FALSE;
}
function fnTableExists($TableName,$db,$dbserver,$user,$pwd) {
//Verifies that a MySQL table exists
// from http://builder.com.com/5100-6371-1045433.html
// non odbc version of above
    if (!$oConn = @mysql_connect($server, $user, $pwd)) {
    $bRetVal = FALSE;
    } else {
    $bRetVal = FALSE;
    $result = mysql_list_tables($db, $oConn);
    while ($row=mysql_fetch_array($result, MYSQL_NUM)) {
    if ($row[0] ==  $TableName)
    $bRetVal = TRUE;
    break;
}
mysql_free_result($result);
mysql_close($oConn);
}
return ($bRetVal);
}

?>