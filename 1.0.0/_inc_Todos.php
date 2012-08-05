<?
//################################################################
//include_once(dirname(__FILE__)."/lib_acl.php");
include_once(dirname(__FILE__)."/adodb/adodb.inc.php");
include_once(dirname(__FILE__)."/adodb/adodb-pager.inc.php");
include_once(dirname(__FILE__)."/lib_util.php");
//include_once($_SERVER['DOCUMENT_ROOT'] . "/_lib/ACL/login.inc.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/_lib/adodb/adodb.inc.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/_lib/adodb/adodb-pager.inc.php");
//if (SITE_HDR) include_once ($_SERVER['DOCUMENT_ROOT'] . SITE_HDR);
include_once(dirname(__FILE__)."/_inc_config.php");

define("OS_DIR_SEPARATOR",'/');               //this works for windows and linux?

//######################### FIELD NAMES
$IDX_0	= 'idx0';
$IDX_1	= 'idx1';
$IDX_2	= 'idx2';
$IDX_3	= 'idx3';

$_todos_DB	= '/_todos/DB';
if(! TODOS_ROOT) define("TODOS_ROOT",    "/_lib/Todos/");
if(! TODOS_PATH) define("TODOS_PATH",   "\." . "/_lib/Todos/");
define("SITE_PATH_ROOT", $doc_root);
//Set to correct location for server -- normally set in conf file
define("DIR_IDX", $_SERVER['DOCUMENT_ROOT']."/_todos/idx.sql");
define("IDX_PWD",'frodo');
define("IDX_0", 'idx0');
define("IDX_1",'idx1');
define("IDX_2",'idx2');
define("IDX_3",'idx3');

if(!defined('INDEX_FILE'))define("INDEX_FILE",'index.php');
if(!defined('IDX_FILE'))define("IDX_FILE", 'idx');
//if(!defined('IDX'))define("IDX"		, '/');
if(!defined('IDX_ROOT'))define("IDX_ROOT"	, '/IDX');
if(!defined('IDX_ROOT_CATNAME'))define("IDX_ROOT_CATNAME", "idx");
//IDX_ROOT_PAGEID needs to be an absolute reference, e.g., "/idx"
if(!defined('IDX_ROOT_PAGEID'))define("IDX_ROOT_PAGEID",'/idx');
if(!defined('IDX_ROOT_DIR'))define("IDX_ROOT_DIR",'/');
define("HTTP_INDEX",'index.php');
define("HTML_INDEX",'index.php');

define("SELF_STRING",'SELF');


define("EO_CLASS_EO",'_eo');
define("EO_CLASS_TD",'_td');
define("EO_CLASS_SUBCAT", 	'subcat');
define("EO_CLASS_SUBDOC",	'subdoc');
define("EO_CLASS_SUBNAV",	'subnav');
define("EO_CLASS_CATEGORY",	'category');
define("EO_CLASS_VCLASS",	'vclass');
define("EO_CLASS_APPS", 	'application');
define("EO_CLASS_PRODUCT", 	'product');
define("EO_CLASS_FORM", 	'form');
define("EO_CLASS_MEMBER_OF", 	'member_of');
define("EO_CLASS_MEMBER", 	'member');
define("EO_CLASS_PAGE", 	'page');
define("EO_CLASS_DIRECTORY", 	'directory');



define("EO_STATUS_ACTIVE",	'active');
define("TD_STATUS_ACTIVE",	1);

define("TD_BASS_CLASS",'page');
define("TD_BASS_PARAM_LIST",'title,url');
define("TD_PTR_PARAM_LIST",'description');

define("TD_VALTYPE_PVAL",          'pval');

define("TD_PNAME_BASS_CLASS",      'bass_class');
define("TD_PNAME_COL_NAMES",	     'col_names');
define("TD_PNAME_COL_SORT",	     'col_sort');
define("TD_PNAME_DESCRIPTION", 	'description');
define("TD_PNAME_EXT_PK_FLD", 	'ext_pk_fld');
define("TD_PNAME_FLG_PUBLIC", 	'flg_public');
define("TD_PNAME_FLG_CRB", 	    'flgCRB');
define("TD_PNAME_FLG_UL", 	    'flgUL');
define("TD_PNAME_HDGS", 	          'col_names');
define("TD_PNAME_HELLO", 	     'hello');
define("TD_PNAME_HTML_REC_BLOCK",  'html_rec_block');
define("TD_PNAME_IDX_TABLE",    'idx_table');
define("TD_PNAME_INDEX", 		 'index');
define("TD_PNAME_LONG_TITLE", 	'long_title');
define("TD_PNAME_MEMBER_OF",	     'member_of');
define("TD_PNAME_NAME",		     'name');
define("TD_PNAME_PARAM_SORT",	     'p_sort');
define("TD_PNAME_PID",		     'pid');
define("TD_PNAME_PK", 		     'pk');
define("TD_PNAME_REC_VIEWER",       'rec_viewer');  //catgegory record viewer
define("TD_PNAME_SRCH_COLS",	     'srch_cols');
define("TD_PNAME_TABLENAME",     'tbl_name');
define("TD_PNAME_TITLE", 	     'title');
define("TD_PNAME_URL", 		     'url');
define("TD_PNAME_VCLASS", 	     'vclass');

define("TD_VALTYPE_FLD_TDTITLE",'fld_tdTitle');
define("TD_VALTYPE_URL", 'url');

define("TD_LOC_CLASSES", TODOS_VIRT_ROOT . 'Classes/');

define("TD_STYLE_BREADCRUMB", 'breadcrumb');

define("PTYPE_DATE",	'date');
define("PTYPE_TIMESTAMP",'timestamp');

define("STR_VAL_SPRTR",'__');
define("STR_LINK_VAL",'__LINK__');
define("STR_NEW_PAGE_NAME", '<new_page_name>');
define("STR_NEW_CAT_NAME", '<new_category_name>');

define("REPLACE_AMP", "_amp;_");

define ("RS_MAX_RECORDS", 10);
define("IDX_FLD_ALLVALS",'idxAllVals');


define("PAGE_TD_UPLOAD", 	'/td_upload.php');
define("PAGE_REC_EDIT", 	'/rec_edit_idx1.php');
define("PAGE_REC_EDIT_SRC", 	'/rec_edit_src.php');
define("PAGE_REC_VIEW", 	'/rec_viewer.php');
define("PAGE_IDX1_EDIT", 	'/rec_edit_idx1.php');
define("PAGE_IDX2_EDIT", 	'/rec_edit_idx2.php');
define("PAGE_IDX3_EDIT", 	'/rec_edit_idx3.php');
define("PAGE_REC_EDIT_CAT", 	'/rec_edit_cat.php');
define("PAGE_SEARCH", 		'/search.php');
define("PAGE_PID_FIND", 	'/pid_find.php');
define("PAGE_REC_ADDNEW", 	'/rec_addnew.php');
define("PAGE_REC_LINK", 	'/rec_link.php');
define("PAGE_CAT_SUMMARY", 	'/cat_admin.php');
define("PAGE_CAT_EDIT", 	'/cat_edit.php');
define("PAGE_CAT_EDIT_IDX1",    '/cat_edit_idx1.php');
define("PAGE_CAT_ADMIN", 	    '/cat_admin.php');
define("PAGE_CAT_VIEWER", 	    '/cat_viewer.php');
define("PAGE_CAT_ADDNEW", 	    '/cat_addnew.php');
define("PAGE_CLASS_EDIT", 	    '/dbm/class/class_edit.php');
define("PAGE_CLASS_SUMMARY",    '/dbm/class/class_summary.php');
define("PAGE_PARAM_ADDNEW", 	'/dbm/param/param_addnew.php');
define("PAGE_PARAM_EDIT", 	    '/dbm/param/param_edit.php');
define("PAGE_TODOS_SEARCH", 	'/cat_viewer.php');
define("PAGE_TODOS_SUMMARY", 	'/todos.php');
define("PAGE_TODOS_DETAIL", 	'/rec_viewer.php');
define("PAGE_TODOS_EDIT",     	'/rec_edit.php');
define("PAGE_TODOS_ADDNEW", 	'/rec_addnew.php');

define("todos_1_0",         PAGE_TODOS_SEARCH);
define("todos_1_1",         PAGE_TODOS_SUMMARY);
define("todos_1_2",         PAGE_TODOS_DETAIL);
define("todos_1_3",         PAGE_TODOS_EDIT);
define("todos_1_3_1",       PAGE_CAT_ADMIN);
define("todos_1_4",         PAGE_TODOS_ADDNEW);
define("todos_1_4_1",       PAGE_CAT_ADDNEW);

define("PARAM_DISPTYPE_NON", 'non');

define("IMG_ROOT", "/images");
define("TODOS_IMAGES", TODOS_ROOT 	. "/images");
define("IMG_BTN_UPLOAD", TODOS_IMAGES 	.'/btn_edit.gif');
define("IMG_BULLET1", TODOS_IMAGES 	. '/ltgreenbox.gif');
define("IMG_BTN_ADDNEW", TODOS_IMAGES 	. '/btn_addnew.gif');
define("IMG_BTN_ADDNEW_ACTIVE", TODOS_IMAGES . '/btn_addnew_active.gif');
define("IMG_BTN_VIEW", TODOS_IMAGES 	. '/btn_view.gif');
define("IMG_BTN_EDIT", TODOS_IMAGES 	. '/btn_edit.gif');
define("IMG_BTN_EDIT1", TODOS_IMAGES 	. '/btn_edit1.gif');
define("IMG_BTN_IDX1", TODOS_IMAGES 	. '/btn_idx1.gif');
define("IMG_BTN_IDX3", TODOS_IMAGES 	. '/btn_idx3.gif');
define("IMG_BTN_CAT", TODOS_IMAGES 	. '/btn_categories.gif');
define("IMG_BTN_SUMMARY", TODOS_IMAGES 	. '/btn_summary.gif');
define("IMG_BTN_FULLSCREEN", TODOS_IMAGES . '/btn_fullscreen.gif');
define("IMG_BTN_VIEW_ACTIVE", TODOS_IMAGES . '/btn_view_active.gif');
define("IMG_BTN_EDIT_ACTIVE", TODOS_IMAGES . '/btn_edit_active.gif');
define("IMG_BTN_EDIT1_ACTIVE", TODOS_IMAGES . '/btn_edit1_active.gif');
define("IMG_BTN_IDX1_ACTIVE", TODOS_IMAGES . '/btn_idx1_active.gif');
define("IMG_BTN_IDX3_ACTIVE", TODOS_IMAGES . '/btn_idx3_active.gif');
define("IMG_BTN_CAT_ACTIVE", TODOS_IMAGES . '/btn_categories_active.gif');
define("IMG_BTN_SUMMARY_ACTIVE", TODOS_IMAGES . '/btn_summary_active.gif');

define("SITE_IMAGES", "/image");  // note the non-singular
define("IMG_BLANK", SITE_IMAGES . '/blank.gif');
define("IMG_DASHED_LINE", SITE_IMAGES . '/dashed_line.gif');

define("TARGET_VIEW",       '_tgt_view');
define("TARGET_EDIT",       '_tgt_edit');

define("EXT_CLASS", 	'cls');
define("EXT_PDF", 	'pdf');
define("EXT_PHP", 	'php');

define("PID_EXCLUDED",      "^_|^\.[A-Za-z]|CVS");
define("PID_STD_FILTER",      "*.(php|htm(l)?|jpg|gif|png|pdf|doc|txt|crd)$");


define("TBL_TODOS",         'tblTodos');
define("TBL_PARAMS",        'tblParams');
define("TBL_EOCLASSES",     'tblEOClasses');
define("TBL_TDCLASSES",     'tblTDClasses');
define("TBL_EOTYPES",       'tblEOTypes');
define("TBL_TDTYPES",       'tblTDTypes');
define("TBL_TT_TODOS", 	'tt_Todos');

define("FLD_TDID",	            'tdID');
define("FLD_PID",	            'tdPageID');
define("FLD_TD_PAGEID",	        'tdPageID');
define("FLD_TD_TITLE",	        'tdTitle');
define("FLD_TD_URL",	        'tdURL');
define("FLD_TD_CLASS",	        'tdClass');
define("FLD_TD_TYPE",	        'tdType');
define("FLD_TD_PVAL",              'pVal');
define("FLD_P_TYPE",	        'pType');
define("FLD_P_DISPTYPE",        'pDispType');
define("FLD_P_VALTYPE",         'pValType');
define("FLD_PNAME",	            'pName');
define("FLD_EO_CLASS",          'eoClass');
define("FLD_EO_TYPE",           'eoType');
define("FLD_EO_TYPEDESCRIPTION" ,'eoTypeDescription');
define("FLD_EO_CLASSDESCRIPTION",'eoClassDescription');
define("FLD_IDX_SORT",           'idxSort');
define("FLD_IDX_CLASSPRIORITY",  'idxClassPriority');
define("FLD_TDX",                'tdx');
define("FLD_ISA",                'isa');

//PVAL Types
define("PVALTYPE_PVAL",             'pval');
define("PVALTYPE_URL",              'url');

//TESTS
define("EO_CLASS_TEST",             'test');
define("EO_CLASS_TINSTANCE",        'tInstance');
define("EO_CLASS_QUESTION",         'question');
define("EO_CLASS_QINSTANCE",        'qInstance');
define("TD_PNAME_TESTID",           'test_id');
define("TD_PNAME_TINSTANCEID",     'tInstance_id');
define("TD_PNAME_TEST_USERNAME",    'tUserName');
define("TD_PNAME_TEST_USERAGE",     'tUserAge');
define("TD_PNAME_TEST_USEREMAIL",   'tUserEmail');
define("TD_PNAME_QUESTIONID",       'question_id');
define("TD_PNAME_QPROMPT",          'qPrompt');
define("TD_PNAME_QHELP",            'qHelp');
define("TD_PNAME_QANSWER",          'qAnswer');
define("TD_PNAME_QSORT",            'qSort');
define("TD_PNAME_QIMG",             'qImage');
define("TD_PNAME_QTIMESTART",       'qTimeStart');
define("TD_PNAME_QTIMEEND",         'qTimeEnd');
define("TD_PNAME_QTIME",            'qTime');
define("PAGE_TEST_VIEWER",          '/test_viewer.php');
define("PAGE_TEST_END",             '/goodbye.php');
define("PAGE_TEST_START",           '/hello.php');

// CSS Classes
define("CSS_TR",                    'tablerow');
define("CSS_TR1",                   'tablerow.alt1');
define("CSS_DASHED_LINE", 	        'divdashedline');
define("CSS_TABLE_HEADER",          'tableheader');
define("CLASS_DASHED_LINE", 	    'divdashedline');
define("CLASS_TABLE_HEADER",        'tableheader');


// Templates
define("TEMPLATE_IDX", "idx.tplt.php");
define("TEMPLATE_CAT", "idx.tplt.php");
define("TEMPLATE_PAGE", "page.tplt.php");
define("TEMPLATE_DIR", TODOS_PATH_ROOT . "_tplt/");

define("MAX_UPLD_FILE_SIZE", 10000000);    //10MB

if (!defined('PHP_EOL')) define ('PHP_EOL', strtoupper(substr(PHP_OS,0,3) == 'WIN') ? "\r\n" : "\n");
?>