<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
	<title>BLOG ADMIN STATISTICS</title>
	<link id="browser_favicon" rel="shortcut icon" href="">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row">

			<div class="panel panel-primary">
				<div class="panel-heading text-center">LATEST PAGE VIEWS&nbsp;&nbsp;(TOTAL PAGES VIEWED:<?php require_once("count_pages.php"); ?>)</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table">
							<tr class="info">
								<th>Page Name</th>
								<th>Total Views</th>
							</tr>
							<?php 
							include("connect.php");
							try {
//connect to mysql
								$con=new PDO($dsn,$username,$password);
								$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
								
							} catch (Exception $ex) {
								echo 'Not Connected '.$ex->getMessage();
							}

							?>
						</table>
					</div> 
				</div>
			</div>
		</div><!--row-->
		<div class="row">
			<div class="panel panel-success">
				<div class="panel-heading text-center">LATEST VISITOR DETAILS&nbsp;&nbsp;(TOTAL RECORDS:<?php require_once("count_user_stats.php"); ?>)</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table">
							<tr class="info">
								<th>IP ADDRESS</th>
								<th>USER AGENT</th>
								<th>TIME ACCESSED</th>
							</tr>
							<?php 
							include("connect.php");
							try {
//connect to mysql
								$con=new PDO($dsn,$username,$password);
								$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
								
							} catch (Exception $ex) {
								echo 'Not Connected '.$ex->getMessage();
							}


							?>
						</table>
					</div> 
				</div>
			</div>
		</div><!--row-->
	</div>
</body>
</html>