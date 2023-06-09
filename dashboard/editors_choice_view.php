<?php
 

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/editors_choice.php");
	include("$currDir/editors_choice_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('editors_choice');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "editors_choice";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`editors_choice`.`id`" => "id",
		"IF(    CHAR_LENGTH(`blogs1`.`title`) || CHAR_LENGTH(`blogs1`.`author`), CONCAT_WS('',   `blogs1`.`title`, ' :author ', `blogs1`.`author`), '') /* Blog */" => "blog"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`editors_choice`.`id`',
		2 => 2
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`editors_choice`.`id`" => "id",
		"IF(    CHAR_LENGTH(`blogs1`.`title`) || CHAR_LENGTH(`blogs1`.`author`), CONCAT_WS('',   `blogs1`.`title`, ' :author ', `blogs1`.`author`), '') /* Blog */" => "blog"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`editors_choice`.`id`" => "ID",
		"IF(    CHAR_LENGTH(`blogs1`.`title`) || CHAR_LENGTH(`blogs1`.`author`), CONCAT_WS('',   `blogs1`.`title`, ' :author ', `blogs1`.`author`), '') /* Blog */" => "Blog"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`editors_choice`.`id`" => "id",
		"IF(    CHAR_LENGTH(`blogs1`.`title`) || CHAR_LENGTH(`blogs1`.`author`), CONCAT_WS('',   `blogs1`.`title`, ' :author ', `blogs1`.`author`), '') /* Blog */" => "blog"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'blog' => 'Blog');

	$x->QueryFrom = "`editors_choice` LEFT JOIN `blogs` as blogs1 ON `blogs1`.`id`=`editors_choice`.`blog` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = false;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 0;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "editors_choice_view.php";
	$x->RedirectAfterInsert = "editors_choice_view.php?SelectedID=#ID#";
	$x->TableTitle = "پیشنهاد نویسنده";
	$x->TableIcon = "resources/table_icons/bullet_star.png";
	$x->PrimaryKey = "`editors_choice`.`id`";

	$x->ColWidth   = array(  150);
	$x->ColCaption = array("Blog");
	$x->ColFieldName = array('blog');
	$x->ColNumber  = array(2);

	// template paths below are based on the app main directory
	$x->Template = 'templates/editors_choice_templateTV.html';
	$x->SelectedTemplate = 'templates/editors_choice_templateTVS.html';
	$x->TemplateDV = 'templates/editors_choice_templateDV.html';
	$x->TemplateDVP = 'templates/editors_choice_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->ShowRecordSlots = 0;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `editors_choice`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='editors_choice' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `editors_choice`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='editors_choice' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`editors_choice`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: editors_choice_init
	$render=TRUE;
	if(function_exists('editors_choice_init')){
		$args=array();
		$render=editors_choice_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: editors_choice_header
	$headerCode='';
	if(function_exists('editors_choice_header')){
		$args=array();
		$headerCode=editors_choice_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: editors_choice_footer
	$footerCode='';
	if(function_exists('editors_choice_footer')){
		$args=array();
		$footerCode=editors_choice_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>