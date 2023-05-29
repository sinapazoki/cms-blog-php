<?php require("libs/fetch_data.php");?>
<?php  require_once("libs/jdf.php"); ?>
<?php //code to get the item using its id
include("database/conn.php");//database config file
$id=$_REQUEST['id']; $query="SELECT * from blogs where id='".$id."'"; $result=mysqli_query($GLOBALS["___mysqli_ston"],$query) or die ( ((is_object($GLOBALS["___mysqli_ston"]))? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ?$___mysqli_res : true))); 
$row = mysqli_fetch_assoc($result);

//query to get user details
$query2="SELECT * from membership_users where memberID='".$row['author']."'"; $result2=mysqli_query($GLOBALS["___mysqli_ston"],$query2) or die ( ((is_object($GLOBALS["___mysqli_ston"]))? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ?$___mysqli_res : true)));
$user = mysqli_fetch_assoc($result2);

?>
<!DOCTYPE html>
<html lang="zxx">
<head>
	<title><?php echo $row['title']; ?>|<?php getwebname("titles");?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<link id="browser_favicon" rel="shortcut icon" href="blogadmin/images/<?php geticon("titles"); ?>">
	<meta charset="utf-8" name="description" content="<?php getshortdescription("titles");?>">
	<meta name="keywords" content="<?php getkeywords("titles");?>" />
	<script>
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="css/single.css">
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<link href="css/fontawesome-all.css" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800"
	rel="stylesheet">
	<!--additional javascripts will be placed here-->
	<?php getjavascripts("links"); ?>
</head>

<style>
	.blockquote-custom {
  position: relative;
  font-size: 1.1rem;
}

.blockquote-custom-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: -25px;
  left: 50px;
}

</style>

<body>
	<!--Header-->
	<?php include("header.php");?> 
	<!--//header-->
	<!--update database on page views-->

	<div class="banner-inner">
	</div>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="index.php">خانه</a>
		</li>
		<li class="breadcrumb-item active">مقاله</li>
	</ol>

	<!--//banner-->
	<section class="banner-bottom">
		<!--/blog-->
		<div class="container">
			<div class="row">
				<!--left-->
				<div class="col-lg-8 left-blog-info-w3layouts-agileits text-left">
					<div  dir="rtl" class="blog-grid-top text-right">
						<div  class="b-grid-top ">
							<div class="blog_info_left_grid">
								<a href="#">
									<img src="blogadmin/images/<?php echo $row['photo']; ?>" class="img-fluid" alt="image not available" style="width:900px;height:300px">
								</a>
							</div>
							<div  class="blog-info-middle ">
								<ul>
									<li>
										<a href="#">
											<i class="far fa-calendar-alt"></i>
											<?php
											//  echo $row['date']; 
											$y =  substr($row['date'], 0, 4);
											 $m = substr($row['date'],5, 2);
											 $d  =substr($row['date'],8, 2);

											echo gregorian_to_jalali($y,$m, $d,'/');
											 ?></a>
										</li>
										<li class="mx-2">
											<a href="#">
												<i class="far fa-user"></i><?php echo $row['author']; ?></a>
											</li>
											<li>
												<a href="#" class="d-flex align-items-center">
												<i class="far fa-user"></i>
												<span>دقیقه</span>
												<?php echo $row['time']; ?></a>
												</li>


												</ul>
											</div>
										</div>

										<h3 class="text-right">
											<a href="single.html"><?php echo $row['title']; ?></a>
										</h3>
										<div   class="text-justify">
											<?php echo $row['content']; ?>
										</div>

									</div>
									<div class="">
										<blockquote class="blockquote blockquote-custom bg-white py-5 shadow rounded">
											<div class="blockquote-custom-icon bg-info shadow-sm"><i class="fa fa-quote-left text-white"></i></div>
											<p class="mb-0 mt-2 font-italic text-right">
											<?php echo $user['comments']; ?>
											</p>
											<div class="blockquote-footer pt-4 mt-4 border-top"><?php echo $user['memberID']; ?>
												<cite title="Source Title"><?php echo $user['email']; ?></cite>
										</div>
										</blockquote>
									</div>
								</div>

								<!--//left-->
								<!--right-->
								<aside class="col-lg-4 agileits-w3ls-right-blog-con text-right">
									<div class="right-blog-info text-right">
										<h4 class="mb-4"><strong>دسته بندی ها</strong></h4>
										<ul class="list-group single">
											<?php countcategories();?>
										</ul>


										</div>
								</aside>
												<!--//right-->
									</div>
										</div>
									</section>
									<!--//main-->
									<!--footer-->
									<?php include("footer.php");?>
									<!---->
									<!-- js -->
									<script src="js/jquery-2.2.3.min.js"></script>
									<!-- //js -->
									<!--/ start-smoth-scrolling -->
									<script src="js/move-top.js"></script>
									<script src="js/easing.js"></script>
									<script>
										jQuery(document).ready(function ($) {
											$(".scroll").click(function (event) {
												event.preventDefault();
												$('html,body').animate({
													scrollTop: $(this.hash).offset().top
												}, 900);
											});
										});
									</script>
									<!--// end-smoth-scrolling -->

									<script>
										$(document).ready(function () {
			/*
									var defaults = {
							  			containerID: 'toTop', // fading element id
										containerHoverID: 'toTopHover', // fading element hover id
										scrollSpeed: 1200,
										easingType: 'linear' 
							 		};
							 		*/

							 		$().UItoTop({
							 			easingType: 'easeOutQuart'
							 		});

							 	});
							 </script>
							 <a href="#home" class="scroll" id="toTop" style="display: block;">
							 	<span id="toTopHover" style="opacity: 1;"> </span>
							 </a>

							 <!-- //Custom-JavaScript-File-Links -->
							 <script src="js/bootstrap.js"></script>


							</body>

							</html>