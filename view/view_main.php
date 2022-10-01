<!--banner-->
<div class="banner">
	<div class="container">
		<section class="rw-wrapper">
			<h1 class="rw-sentence">
				<span>Fragrant &amp; Love</span>
				<div class="rw-words rw-words-1">
					<span>Just like men </span>
					<span>Perfume is never perfect right away</span>
					<span>You have to let it seduce you</span>
				</div>
				<div class="rw-words rw-words-2">
					<span>Perfume is a way for time to stop</span>
					<span>You smell a great scent </span>
					<span>And you remember something</span>
				</div>
			</h1>
		</section>
	</div>
</div>
<!--content-->
<div class="content">
	<div class="container">
		<div class="content-top">

			<div class="col-md-6 col-md">
				<div class="col-1">
					<a href="single.html" class="b-link-stroke b-animate-go  thickbox">
						<img src="public/images/nh15.jpg" class="img-responsive" alt="" />
						<!--<div class="b-wrapper1 long-img"><p class="b-animate b-from-right    b-delay03 ">Không Gian </p><label class="b-animate b-from-right    b-delay03 "></label><h3 class="b-animate b-from-left    b-delay03 ">Cửa Hàng</h3></div>-->
					</a>

					<!--<a href="single.html"><img src="images/nh14.jpg" class="img-responsive" alt=""></a>-->
				</div>

			</div>
			<div class="col-md-6 col-md">
				<div class="col-3">
					<a href="index.php?controller=detail_men_category_product"><img src="public/images/nhnam.jpg" class="img-responsive" alt="">
						<div class="col-pic">
							<p>Nước Hoa</p>
							<label></label>
							<h5>Nam</h5>
						</div>
					</a>
				</div>

				<div class="col-3">
					<a href="index.php?controller=detail_women_category_product"><img src="public/images/nhnu.jpg" class="img-responsive" alt="">
						<div class="col-pic">
							<p>Nước Hoa</p>
							<label></label>
							<h5>Nữ</h5>
						</div>
					</a>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<!--products-->
		<?php
		include_once("controller/controller_sanphamnoibat.php");
		?>
		<!--//products-->
		<?php
		include_once("controller/controller_sanphambanchay.php");
		?>
		<!--//products-->