<!--footer-->
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-lg-4 footer-grid-agileits-w3ls text-right">
					<h3>درباره ما</h3>
					<p><?php getshortdescription("titles"); ?></p>
					<div class="read">
						<a href="about.php" class="btn btn-sm rounded btn-primary read-m">مشاهده بیشتر</a>
					</div>
				</div>
				<div class="col-lg-4 footer-grid-agileits-w3ls text-right">

					<div class="tech-btm">
						<h3>مقالات پربازدید</h3>
					</div>
				</div>
				<!-- subscribe -->
				<div class="col-lg-4 subscribe-main footer-grid-agileits-w3ls text-right">
					<h3 class="fs-3">برای عضویت در خبرنامه ایمیل خود را وارد نمایید</h3>
					<div class="subscribe-main text-left">
							<div class="subscribe-form">
									<form action="#" method="post" class="subscribe_form">
										<input class="form-control" type="email" placeholder="Enter your email..." required="">
										<button type="submit" class="btn btn-sm btn-primary submit">ارسال</button>
									</form>
									<div class="clearfix"> </div>
						   </div>
					</div>
					<!-- //subscribe -->
				</div>
			</div>
			<!-- footer -->
			<div class="footer-cpy text-center">
				<div class="w3layouts-agile-copyrightbottom">
					<p>© <?php $current=date("Y"); print_r($current); echo"&nbsp;"; getwebname("titles");?>. web Design By
					</p>

				</div>
			</div>
			<!-- //footer -->
		</div>
	</footer>