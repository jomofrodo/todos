<?php
  // include file to pull out various standard form arguments
  // Created By: Jomo 10/04

$DOC_ROOT = $_SERVER[DOCUMENT_ROOT];

 	$pAction 	= $_POST['pAction'];
	$cat_pid = '';

 	$this_url = $_SERVER[REQUEST_URI];
	$this_url = preg_replace('/\?.*$/','',$this_url);
	if(0) print("$this_url, $PHP_SELF");

    GLOBAL $cat_pid;
    GLOBAL $rec_pid;
    GLOBAL $td_class;
    GLOBAL $rs_page;

   		## Look for POST variables first
		$args = $_POST;
		if(! $args) $args = $_GET;
	if(0) var_dump($args);
		todosFixFormVars($args);
		$args['debug'] = 0;
		extract($args);		//turn form values into variables
        if(! $rec_pid) if ($pid) $rec_pid = $pid;  // archaic form from todosPrintCHash
		if(! $rec_pid){
                // could get the refering page, or not
                //{$rec_pid = todosGetRefererPID(); }
 		     // $rec_pid		        = convRepl2PID($rec_pid);
 		     // $rec_pid		        = todosConvURL2PID($rec_pid);
           }

		$cat_pid	            = $args['cat_pid'];
 		$td_class	            = $args['td_class'];
 		$srch_terms	            = $args['srch_terms'];
 		## Then look for GET variables that may have been overlooked because of POST data
 		if(! $rspage) $rspage	= $_GET['rspage'];
 		if(! $srch_terms) $srch_terms = $_GET['srch_terms'];

        ## Then do the tougher stuff
         if(! $bass_cat) $bass_cat = IDX_ROOT;
         if(! $bass_class) $bass_class = todosGetBassClass($cat_pid,$rec_pid);

  	### Make heroic efforts to determine params if not provided
 		if(! $td_class) $td_class = $bass_class;
 		if(! $td_class) $td_class = todosGetTDClass($rec_pid);
  		if(! $cat_pid) $cat_pid = todosGetCatPID($rec_pid);


          //Use the category if no rec_pid specified
           //if(! $rec_pid) $rec_pid = $cat_pid;
           if(! $cat_title) $cat_title = todosGetTitle($cat_pid);
 		if(! $col_names) $col_names = todosGetClassVal($td_class, TD_PNAME_COL_NAMES,0);
 		if($flgAllColumns){
 					$col_names = '*';
 					$args['col_names'] = '*';
 		}
         //<REVISIT>
 		if ($flgReverse){
 			$cols		= '*';		## Debug??
 			$bass_class = $td_class;
 		}
 if(0) print ("cat_pid: $cat_pid<br>\n");
?>
