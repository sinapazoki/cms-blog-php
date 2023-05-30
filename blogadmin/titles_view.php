<?php
 

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/titles.php");
	include("$currDir/titles_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('titles');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "titles";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`titles`.`id`" => "id",
		"`titles`.`website_name`" => "website_name",
		"`titles`.`tagline`" => "tagline",
		"`titles`.`icon`" => "icon",
		"`titles`.`keywords`" => "keywords",
		"`titles`.`short_description`" => "short_description",
		"`titles`.`detailed_description`" => "detailed_description",
		"`titles`.`bannertext1`" => "bannertext1",
		"`titles`.`bannertext2`" => "bannertext2",
		"`titles`.`bannertext3`" => "bannertext3",
		"`titles`.`bannertext4`" => "bannertext4",
		"`titles`.`address`" => "address",
		"`titles`.`email`" => "email",
		"`titles`.`phone`" => "phone",
		"`titles`.`googlemap`" => "googlemap"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`titles`.`id`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5,
		6 => 6,
		7 => 7,
		8 => 8,
		9 => 9,
		10 => 10,
		11 => 11,
		12 => 12,
		13 => 13,
		14 => 14,
		15 => 15
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`titles`.`id`" => "id",
		"`titles`.`website_name`" => "website_name",
		"`titles`.`tagline`" => "tagline",
		"`titles`.`icon`" => "icon",
		"`titles`.`keywords`" => "keywords",
		"`titles`.`short_description`" => "short_description",
		"`titles`.`detailed_description`" => "detailed_description",
		"`titles`.`bannertext1`" => "bannertext1",
		"`titles`.`bannertext2`" => "bannertext2",
		"`titles`.`bannertext3`" => "bannertext3",
		"`titles`.`bannertext4`" => "bannertext4",
		"`titles`.`address`" => "address",
		"`titles`.`email`" => "email",
		"`titles`.`phone`" => "phone",
		"`titles`.`googlemap`" => "googlemap"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`titles`.`id`" => "ID",
		"`titles`.`website_name`" => "Website Name",
		"`titles`.`tagline`" => "Tagline",
		"`titles`.`keywords`" => "Keywords",
		"`titles`.`short_description`" => "Short description",
		"`titles`.`detailed_description`" => "Detailed description",
		"`titles`.`bannertext1`" => "Bannertext1",
		"`titles`.`bannertext2`" => "Bannertext2",
		"`titles`.`bannertext3`" => "Bannertext3",
		"`titles`.`bannertext4`" => "Bannertext4",
		"`titles`.`address`" => "Address",
		"`titles`.`email`" => "Email",
		"`titles`.`phone`" => "Phone",
		"`titles`.`googlemap`" => "Googlemap"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`titles`.`id`" => "id",
		"`titles`.`website_name`" => "website_name",
		"`titles`.`tagline`" => "tagline",
		"`titles`.`keywords`" => "keywords",
		"`titles`.`short_description`" => "short_description",
		"`titles`.`detailed_description`" => "detailed_description",
		"`titles`.`bannertext1`" => "bannertext1",
		"`titles`.`bannertext2`" => "bannertext2",
		"`titles`.`bannertext3`" => "bannertext3",
		"`titles`.`bannertext4`" => "bannertext4",
		"`titles`.`address`" => "address",
		"`titles`.`email`" => "email",
		"`titles`.`phone`" => "phone",
		"`titles`.`googlemap`" => "googlemap"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array();

	$x->QueryFrom = "`titles` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "titles_view.php";
	$x->RedirectAfterInsert = "titles_view.php?SelectedID=#ID#";
	$x->TableTitle = "تنظیمات سایت";
	$x->TableIcon = "resources/table_icons/drugs_com.png";
	$x->PrimaryKey = "`titles`.`id`";

	$x->ColWidth   = array(  150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150);
	$x->ColCaption = array("نام سایت", "شعار سایت", "آیکون", "کلمات کلیدی", "توضیح کوتاه", "توضیحات", "Bannertext1", "Bannertext2", "Bannertext3", "Bannertext4", "آدرس", "ایمیل", "تلفن", "نقشه گوگل");
	$x->ColFieldName = array('website_name', 'tagline', 'icon', 'keywords', 'short_description', 'detailed_description', 'bannertext1', 'bannertext2', 'bannertext3', 'bannertext4', 'address', 'email', 'phone', 'googlemap');
	$x->ColNumber  = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

	// template paths below are based on the app main directory
	$x->Template = 'templates/titles_templateTV.html';
	$x->SelectedTemplate = 'templates/titles_templateTVS.html';
	$x->TemplateDV = 'templates/titles_templateDV.html';
	$x->TemplateDVP = 'templates/titles_templateDVP.html';

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
		$x->QueryWhere="where `titles`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='titles' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `titles`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='titles' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`titles`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: titles_init
	$render=TRUE;
	if(function_exists('titles_init')){
		$args=array();
		$render=titles_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: titles_header
	$headerCode='';
	if(function_exists('titles_header')){
		$args=array();
		$headerCode=titles_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: titles_footer
	$footerCode='';
	if(function_exists('titles_footer')){
		$args=array();
		$footerCode=titles_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>