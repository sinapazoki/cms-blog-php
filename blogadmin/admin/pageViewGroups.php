<?php
	$currDir = dirname(__FILE__);
	require("{$currDir}/incCommon.php");
	$GLOBALS['page_title'] = $Translation['groups'];
	include("{$currDir}/incHeader.php");


	$numGroups = sqlValue("select count(1) from membership_groups $where");
	if(!$numGroups && $searchSQL != ''){
		echo "<div class=\"alert alert-danger\">{$Translation['no matching results found']}</div>";
		$noResults = true;
		$page = 1;
	}else{
		$noResults = false;
	}

	$page = intval($_GET['page']);
	if($page < 1){
		$page = 1;
	}elseif($page > ceil($numGroups / $adminConfig['groupsPerPage']) && !$noResults){
		redirect("admin/pageViewGroups.php?page=" . ceil($numGroups / $adminConfig['groupsPerPage']));
	}

	$start = ($page - 1) * $adminConfig['groupsPerPage'];

?>
<div class="page-header">
	<h1>
		<?php echo $Translation['groups']; ?>
		<span class="pull-right"><a href="pageEditGroup.php" class="btn btn-success btn-lg"><i class="glyphicon glyphicon-plus"></i> <?php echo $Translation['add group']; ?></a></span>
		<div class="clearfix"></div>
	</h1>
</div>

<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th><?php echo $Translation["group"]  ; ?></th>
			<th><?php echo $Translation["description"] ; ?></th>
			<th><?php echo $Translation['members count'] ; ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php

			$res = sql("select groupID, name, description from membership_groups $where limit $start, ".$adminConfig['groupsPerPage'], $eo);
			while( $row = db_fetch_row($res)){
				$groupMembersCount = sqlValue("select count(1) from membership_users where groupID='$row[0]'");
				?>
				<tr>
					<td><a href="pageEditGroup.php?groupID=<?php echo $row[0]; ?>"><?php echo $row[1]; ?></a></td>
					<td><?php echo thisOr($row[2]); ?></td>
					<td class="text-right"><?php echo $groupMembersCount; ?></td>
					<td class="text-center">
						<a href="pageEditGroup.php?groupID=<?php echo $row[0]; ?>" title="<?php echo $Translation['Edit group']; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
						<?php if(!$groupMembersCount){ ?>
								<a href="pageDeleteGroup.php?groupID=<?php echo $row[0]; ?>" 
								   title="<?php echo $Translation['delete group'] ; ?>" 
								   onClick="return confirm('<?php echo $Translation['confirm delete group'] ; ?>');">
									<i class="glyphicon glyphicon-trash text-danger"></i>
								</a>
						<?php }else{ ?>
								<i class="glyphicon glyphicon-trash text-muted"></i>
						<?php } ?>
						<a href="pageEditMember.php?groupID=<?php echo $row[0]; ?>" title="<?php echo $Translation["add new member"]; ?>"><i class="glyphicon glyphicon-plus text-success"></i></a>
						<a href="pageViewRecords.php?groupID=<?php echo $row[0]; ?>" title="<?php echo $Translation['view group records'] ; ?>"><i class="glyphicon glyphicon-th"></i></a>
						<?php if($groupMembersCount){ ?>
								<a href="pageViewMembers.php?groupID=<?php echo $row[0]; ?>" title="<?php echo $Translation['view group members'] ; ?>"><i class="glyphicon glyphicon-user"></i></a>
								<a href="pageMail.php?groupID=<?php echo $row[0]; ?>" title="<?php echo $Translation['send message to group']; ?>"><i class="glyphicon glyphicon-envelope"></i></a>
						<?php }else{ ?>
								<i class="glyphicon glyphicon-user text-muted"></i>
								<i class="glyphicon glyphicon-envelope text-muted"></i>
						<?php } ?>
					</td>
				</tr>
				<?php
			}
		?>
	</tbody>
</table>

<style>
	.form-inline .form-group{ margin: 0.5em 1em; }
</style>

<?php
	include("{$currDir}/incFooter.php");
?>
