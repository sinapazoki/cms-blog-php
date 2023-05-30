<?php

// Data functions (insert, update, delete, form) for table titles

 

function titles_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('titles');
	if(!$arrPerm[1]){
		return false;
	}

	$data['website_name'] = makeSafe($_REQUEST['website_name']);
		if($data['website_name'] == empty_lookup_value){ $data['website_name'] = ''; }
	$data['tagline'] = makeSafe($_REQUEST['tagline']);
		if($data['tagline'] == empty_lookup_value){ $data['tagline'] = ''; }
	$data['keywords'] = makeSafe($_REQUEST['keywords']);
		if($data['keywords'] == empty_lookup_value){ $data['keywords'] = ''; }
	$data['short_description'] = makeSafe($_REQUEST['short_description']);
		if($data['short_description'] == empty_lookup_value){ $data['short_description'] = ''; }
	$data['detailed_description'] = br2nl(makeSafe($_REQUEST['detailed_description']));
	$data['bannertext1'] = makeSafe($_REQUEST['bannertext1']);
		if($data['bannertext1'] == empty_lookup_value){ $data['bannertext1'] = ''; }
	$data['bannertext2'] = makeSafe($_REQUEST['bannertext2']);
		if($data['bannertext2'] == empty_lookup_value){ $data['bannertext2'] = ''; }
	$data['bannertext3'] = makeSafe($_REQUEST['bannertext3']);
		if($data['bannertext3'] == empty_lookup_value){ $data['bannertext3'] = ''; }
	$data['bannertext4'] = makeSafe($_REQUEST['bannertext4']);
		if($data['bannertext4'] == empty_lookup_value){ $data['bannertext4'] = ''; }
	$data['address'] = makeSafe($_REQUEST['address']);
		if($data['address'] == empty_lookup_value){ $data['address'] = ''; }
	$data['email'] = makeSafe($_REQUEST['email']);
		if($data['email'] == empty_lookup_value){ $data['email'] = ''; }
	$data['phone'] = makeSafe($_REQUEST['phone']);
		if($data['phone'] == empty_lookup_value){ $data['phone'] = ''; }
	$data['googlemap'] = makeSafe($_REQUEST['googlemap']);
		if($data['googlemap'] == empty_lookup_value){ $data['googlemap'] = ''; }
	$data['icon'] = PrepareUploadedFile('icon', 3072000,'jpg|jpeg|gif|png', false, '');
	if($data['icon']) createThumbnail($data['icon'], getThumbnailSpecs('titles', 'icon', 'tv'));
	if($data['icon']) createThumbnail($data['icon'], getThumbnailSpecs('titles', 'icon', 'dv'));

	/* for empty upload fields, when saving a copy of an existing record, copy the original upload field */
	if($_REQUEST['SelectedID']){
		$res = sql("select * from titles where id='" . makeSafe($_REQUEST['SelectedID']) . "'", $eo);
		if($row = db_fetch_assoc($res)){
			if(!$data['icon']) $data['icon'] = makeSafe($row['icon']);
		}
	}

	// hook: titles_before_insert
	if(function_exists('titles_before_insert')){
		$args=array();
		if(!titles_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `titles` set       `website_name`=' . (($data['website_name'] !== '' && $data['website_name'] !== NULL) ? "'{$data['website_name']}'" : 'NULL') . ', `tagline`=' . (($data['tagline'] !== '' && $data['tagline'] !== NULL) ? "'{$data['tagline']}'" : 'NULL') . ', ' . ($data['icon'] != '' ? "`icon`='{$data['icon']}'" : '`icon`=NULL') . ', `keywords`=' . (($data['keywords'] !== '' && $data['keywords'] !== NULL) ? "'{$data['keywords']}'" : 'NULL') . ', `short_description`=' . (($data['short_description'] !== '' && $data['short_description'] !== NULL) ? "'{$data['short_description']}'" : 'NULL') . ', `detailed_description`=' . (($data['detailed_description'] !== '' && $data['detailed_description'] !== NULL) ? "'{$data['detailed_description']}'" : 'NULL') . ', `bannertext1`=' . (($data['bannertext1'] !== '' && $data['bannertext1'] !== NULL) ? "'{$data['bannertext1']}'" : 'NULL') . ', `bannertext2`=' . (($data['bannertext2'] !== '' && $data['bannertext2'] !== NULL) ? "'{$data['bannertext2']}'" : 'NULL') . ', `bannertext3`=' . (($data['bannertext3'] !== '' && $data['bannertext3'] !== NULL) ? "'{$data['bannertext3']}'" : 'NULL') . ', `bannertext4`=' . (($data['bannertext4'] !== '' && $data['bannertext4'] !== NULL) ? "'{$data['bannertext4']}'" : 'NULL') . ', `address`=' . (($data['address'] !== '' && $data['address'] !== NULL) ? "'{$data['address']}'" : 'NULL') . ', `email`=' . (($data['email'] !== '' && $data['email'] !== NULL) ? "'{$data['email']}'" : 'NULL') . ', `phone`=' . (($data['phone'] !== '' && $data['phone'] !== NULL) ? "'{$data['phone']}'" : 'NULL') . ', `googlemap`=' . (($data['googlemap'] !== '' && $data['googlemap'] !== NULL) ? "'{$data['googlemap']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"titles_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: titles_after_insert
	if(function_exists('titles_after_insert')){
		$res = sql("select * from `titles` where `id`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!titles_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('titles', $recID, getLoggedMemberID());

	return $recID;
}

function titles_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('titles');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='titles' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='titles' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: titles_before_delete
	if(function_exists('titles_before_delete')){
		$args=array();
		if(!titles_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	sql("delete from `titles` where `id`='$selected_id'", $eo);

	// hook: titles_after_delete
	if(function_exists('titles_after_delete')){
		$args=array();
		titles_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='titles' and pkValue='$selected_id'", $eo);
}

function titles_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('titles');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='titles' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='titles' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['website_name'] = makeSafe($_REQUEST['website_name']);
		if($data['website_name'] == empty_lookup_value){ $data['website_name'] = ''; }
	$data['tagline'] = makeSafe($_REQUEST['tagline']);
		if($data['tagline'] == empty_lookup_value){ $data['tagline'] = ''; }
	$data['keywords'] = makeSafe($_REQUEST['keywords']);
		if($data['keywords'] == empty_lookup_value){ $data['keywords'] = ''; }
	$data['short_description'] = makeSafe($_REQUEST['short_description']);
		if($data['short_description'] == empty_lookup_value){ $data['short_description'] = ''; }
	$data['detailed_description'] = br2nl(makeSafe($_REQUEST['detailed_description']));
	$data['bannertext1'] = makeSafe($_REQUEST['bannertext1']);
		if($data['bannertext1'] == empty_lookup_value){ $data['bannertext1'] = ''; }
	$data['bannertext2'] = makeSafe($_REQUEST['bannertext2']);
		if($data['bannertext2'] == empty_lookup_value){ $data['bannertext2'] = ''; }
	$data['bannertext3'] = makeSafe($_REQUEST['bannertext3']);
		if($data['bannertext3'] == empty_lookup_value){ $data['bannertext3'] = ''; }
	$data['bannertext4'] = makeSafe($_REQUEST['bannertext4']);
		if($data['bannertext4'] == empty_lookup_value){ $data['bannertext4'] = ''; }
	$data['address'] = makeSafe($_REQUEST['address']);
		if($data['address'] == empty_lookup_value){ $data['address'] = ''; }
	$data['email'] = makeSafe($_REQUEST['email']);
		if($data['email'] == empty_lookup_value){ $data['email'] = ''; }
	$data['phone'] = makeSafe($_REQUEST['phone']);
		if($data['phone'] == empty_lookup_value){ $data['phone'] = ''; }
	$data['googlemap'] = makeSafe($_REQUEST['googlemap']);
		if($data['googlemap'] == empty_lookup_value){ $data['googlemap'] = ''; }
	$data['selectedID']=makeSafe($selected_id);
	if($_REQUEST['icon_remove'] == 1){
		$data['icon'] = '';
	}else{
		$data['icon'] = PrepareUploadedFile('icon', 3072000, 'jpg|jpeg|gif|png', false, "");
		if($data['icon']) createThumbnail($data['icon'], getThumbnailSpecs('titles', 'icon', 'tv'));
		if($data['icon']) createThumbnail($data['icon'], getThumbnailSpecs('titles', 'icon', 'dv'));
	}

	// hook: titles_before_update
	if(function_exists('titles_before_update')){
		$args=array();
		if(!titles_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `titles` set       `website_name`=' . (($data['website_name'] !== '' && $data['website_name'] !== NULL) ? "'{$data['website_name']}'" : 'NULL') . ', `tagline`=' . (($data['tagline'] !== '' && $data['tagline'] !== NULL) ? "'{$data['tagline']}'" : 'NULL') . ', ' . ($data['icon']!='' ? "`icon`='{$data['icon']}'" : ($_REQUEST['icon_remove'] != 1 ? '`icon`=`icon`' : '`icon`=NULL')) . ', `keywords`=' . (($data['keywords'] !== '' && $data['keywords'] !== NULL) ? "'{$data['keywords']}'" : 'NULL') . ', `short_description`=' . (($data['short_description'] !== '' && $data['short_description'] !== NULL) ? "'{$data['short_description']}'" : 'NULL') . ', `detailed_description`=' . (($data['detailed_description'] !== '' && $data['detailed_description'] !== NULL) ? "'{$data['detailed_description']}'" : 'NULL') . ', `bannertext1`=' . (($data['bannertext1'] !== '' && $data['bannertext1'] !== NULL) ? "'{$data['bannertext1']}'" : 'NULL') . ', `bannertext2`=' . (($data['bannertext2'] !== '' && $data['bannertext2'] !== NULL) ? "'{$data['bannertext2']}'" : 'NULL') . ', `bannertext3`=' . (($data['bannertext3'] !== '' && $data['bannertext3'] !== NULL) ? "'{$data['bannertext3']}'" : 'NULL') . ', `bannertext4`=' . (($data['bannertext4'] !== '' && $data['bannertext4'] !== NULL) ? "'{$data['bannertext4']}'" : 'NULL') . ', `address`=' . (($data['address'] !== '' && $data['address'] !== NULL) ? "'{$data['address']}'" : 'NULL') . ', `email`=' . (($data['email'] !== '' && $data['email'] !== NULL) ? "'{$data['email']}'" : 'NULL') . ', `phone`=' . (($data['phone'] !== '' && $data['phone'] !== NULL) ? "'{$data['phone']}'" : 'NULL') . ', `googlemap`=' . (($data['googlemap'] !== '' && $data['googlemap'] !== NULL) ? "'{$data['googlemap']}'" : 'NULL') . " where `id`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="titles_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: titles_after_update
	if(function_exists('titles_after_update')){
		$res = sql("SELECT * FROM `titles` WHERE `id`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id'];
		$args = array();
		if(!titles_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='titles' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function titles_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('titles');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}


	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='titles' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='titles' and pkValue='".makeSafe($selected_id)."'");
		if($arrPerm[2]==1 && getLoggedMemberID()!=$ownerMemberID){
			return "";
		}
		if($arrPerm[2]==2 && getLoggedGroupID()!=$ownerGroupID){
			return "";
		}

		// can edit?
		if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){
			$AllowUpdate=1;
		}else{
			$AllowUpdate=0;
		}

		$res = sql("select * from `titles` where `id`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'titles_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
	}else{
	}

	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/titles_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/titles_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Title details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert){
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return titles_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return titles_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if($_REQUEST['Embedded']){
		$backAction = 'AppGini.closeParentModal(); return false;';
	}else{
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id){
		if(!$_REQUEST['Embedded']) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$$(\'form\')[0].writeAttribute(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate){
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return titles_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" onclick="return confirm(\'' . $Translation['are you sure?'] . '\');" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', ($ShowCancel ? '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>' : ''), $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)){
		$jsReadOnly .= "\tjQuery('#website_name').replaceWith('<div class=\"form-control-static\" id=\"website_name\">' + (jQuery('#website_name').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#tagline').replaceWith('<div class=\"form-control-static\" id=\"tagline\">' + (jQuery('#tagline').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#icon').replaceWith('<div class=\"form-control-static\" id=\"icon\">' + (jQuery('#icon').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#keywords').replaceWith('<div class=\"form-control-static\" id=\"keywords\">' + (jQuery('#keywords').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#short_description').replaceWith('<div class=\"form-control-static\" id=\"short_description\">' + (jQuery('#short_description').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#detailed_description').replaceWith('<div class=\"form-control-static\" id=\"detailed_description\">' + (jQuery('#detailed_description').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#bannertext1').replaceWith('<div class=\"form-control-static\" id=\"bannertext1\">' + (jQuery('#bannertext1').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#bannertext2').replaceWith('<div class=\"form-control-static\" id=\"bannertext2\">' + (jQuery('#bannertext2').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#bannertext3').replaceWith('<div class=\"form-control-static\" id=\"bannertext3\">' + (jQuery('#bannertext3').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#bannertext4').replaceWith('<div class=\"form-control-static\" id=\"bannertext4\">' + (jQuery('#bannertext4').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#address').replaceWith('<div class=\"form-control-static\" id=\"address\">' + (jQuery('#address').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#email').replaceWith('<div class=\"form-control-static\" id=\"email\">' + (jQuery('#email').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#email, #email-edit-link').hide();\n";
		$jsReadOnly .= "\tjQuery('#phone').replaceWith('<div class=\"form-control-static\" id=\"phone\">' + (jQuery('#phone').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#googlemap').replaceWith('');\n";
		$jsReadOnly .= "\tjQuery('#googlemap, #googlemap-edit-link').hide();\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif($AllowInsert){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array();
	foreach($lookup_fields as $luf => $ptfc){
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']){
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent hspacer-md" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] && !$_REQUEST['Embedded']){
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-success add_new_parent hspacer-md" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus-sign"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(id)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(website_name)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(tagline)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(icon)%%>', ($noUploads ? '' : '<input type=hidden name=MAX_FILE_SIZE value=3072000>'.$Translation['upload image'].' <input type="file" name="icon" id="icon">'), $templateCode);
	if($AllowUpdate && $row['icon'] != ''){
		$templateCode = str_replace('<%%REMOVEFILE(icon)%%>', '<br><input type="checkbox" name="icon_remove" id="icon_remove" value="1"> <label for="icon_remove" style="color: red; font-weight: bold;">'.$Translation['remove image'].'</label>', $templateCode);
	}else{
		$templateCode = str_replace('<%%REMOVEFILE(icon)%%>', '', $templateCode);
	}
	$templateCode = str_replace('<%%UPLOADFILE(keywords)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(short_description)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(detailed_description)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(bannertext1)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(bannertext2)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(bannertext3)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(bannertext4)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(address)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(email)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(phone)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(googlemap)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', safe_html($urow['id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(website_name)%%>', safe_html($urow['website_name']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(website_name)%%>', html_attr($row['website_name']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(website_name)%%>', urlencode($urow['website_name']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(tagline)%%>', safe_html($urow['tagline']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(tagline)%%>', html_attr($row['tagline']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(tagline)%%>', urlencode($urow['tagline']), $templateCode);
		$row['icon'] = ($row['icon'] != '' ? $row['icon'] : 'blank.gif');
		if( $dvprint) $templateCode = str_replace('<%%VALUE(icon)%%>', safe_html($urow['icon']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(icon)%%>', html_attr($row['icon']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(icon)%%>', urlencode($urow['icon']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(keywords)%%>', safe_html($urow['keywords']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(keywords)%%>', html_attr($row['keywords']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(keywords)%%>', urlencode($urow['keywords']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(short_description)%%>', safe_html($urow['short_description']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(short_description)%%>', html_attr($row['short_description']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(short_description)%%>', urlencode($urow['short_description']), $templateCode);
		if($dvprint || (!$AllowUpdate && !$AllowInsert)){
			$templateCode = str_replace('<%%VALUE(detailed_description)%%>', safe_html($urow['detailed_description']), $templateCode);
		}else{
			$templateCode = str_replace('<%%VALUE(detailed_description)%%>', html_attr($row['detailed_description']), $templateCode);
		}
		$templateCode = str_replace('<%%URLVALUE(detailed_description)%%>', urlencode($urow['detailed_description']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(bannertext1)%%>', safe_html($urow['bannertext1']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(bannertext1)%%>', html_attr($row['bannertext1']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(bannertext1)%%>', urlencode($urow['bannertext1']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(bannertext2)%%>', safe_html($urow['bannertext2']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(bannertext2)%%>', html_attr($row['bannertext2']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(bannertext2)%%>', urlencode($urow['bannertext2']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(bannertext3)%%>', safe_html($urow['bannertext3']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(bannertext3)%%>', html_attr($row['bannertext3']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(bannertext3)%%>', urlencode($urow['bannertext3']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(bannertext4)%%>', safe_html($urow['bannertext4']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(bannertext4)%%>', html_attr($row['bannertext4']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(bannertext4)%%>', urlencode($urow['bannertext4']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(address)%%>', safe_html($urow['address']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(address)%%>', html_attr($row['address']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(address)%%>', urlencode($urow['address']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(email)%%>', safe_html($urow['email']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(email)%%>', html_attr($row['email']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(email)%%>', urlencode($urow['email']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(phone)%%>', safe_html($urow['phone']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(phone)%%>', html_attr($row['phone']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(phone)%%>', urlencode($urow['phone']), $templateCode);
		$templateCode = str_replace('<%%VALUE(googlemap)%%>', html_attr($row['googlemap']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(googlemap)%%>', urlencode($urow['googlemap']), $templateCode);
		$templateCode = str_replace('<%%GOOGLEMAP(googlemap)%%>', get_embed('googlemap', $urow['googlemap'], '480', '360'), $templateCode);
		$templateCode = str_replace('<%%GOOGLEMAPTHUMB(googlemap)%%>', thisOr(get_embed('googlemap', $urow['googlemap'], '480', '360', 'thumbnail_url'), 'blank.gif'), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(website_name)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(website_name)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(tagline)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(tagline)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(icon)%%>', 'blank.gif', $templateCode);
		$templateCode = str_replace('<%%VALUE(keywords)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(keywords)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(short_description)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(short_description)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(detailed_description)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(detailed_description)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(bannertext1)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(bannertext1)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(bannertext2)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(bannertext2)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(bannertext3)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(bannertext3)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(bannertext4)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(bannertext4)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(address)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(address)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(email)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(email)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(phone)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(phone)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(googlemap)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(googlemap)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%GOOGLEMAP(googlemap)%%>', '', $templateCode);
		$templateCode = str_replace('<%%GOOGLEMAPTHUMB(googlemap)%%>', 'blank.gif', $templateCode);
	}

	// process translations
	foreach($Translation as $symbol=>$trans){
		$templateCode = str_replace("<%%TRANSLATION($symbol)%%>", $trans, $templateCode);
	}

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if($_REQUEST['dvprint_x'] == ''){
		$templateCode .= "\n\n<script>\$j(function(){\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption){
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id){
			$templateCode.="\n\tif(document.getElementById('emailEdit')){ document.getElementById('emailEdit').style.display='inline'; }";
			$templateCode.="\n\tif(document.getElementById('emailEditLink')){ document.getElementById('emailEditLink').style.display='none'; }";
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('titles');
	if($selected_id){
		$jdata = get_joined_record('titles', $selected_id);
		if($jdata === false) $jdata = get_defaults('titles');
		$rdata = $row;
	}
	$cache_data = array(
		'rdata' => array_map('nl2br', array_map('addslashes', $rdata)),
		'jdata' => array_map('nl2br', array_map('addslashes', $jdata))
	);
	$templateCode .= loadView('titles-ajax-cache', $cache_data);

	// hook: titles_dv
	if(function_exists('titles_dv')){
		$args=array();
		titles_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>