<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Update Info</title>

	<!-- jQuery UI css -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/jquery/jquery-ui/jquery-ui.min.css">
	<!-- font awesome -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css">
	<!-- bootstrap css -->	
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/bootstrap.min.css">
	<!-- custom css -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/client/main.css">

</script>
</head>
<body>
	<?php 
		if($this->session->userdata('loggedInGooge')){
			$userGGData = $this->session->userdata('userGGData');	
						 
		}else if($this->session->userdata('loggedInFB')){
			$userFBData = $this->session->userdata('userFBData');
			
		}	
	?>
	<script>	
		var base_url = "<?php echo base_url(); ?>";
		window.fbAsyncInit = function() {			
			FB.init({
				appId      			: '463997824000380',
				status				: true,
                xfbml      			: true,  			// parse social plugins on this page
                version    			: 'v2.8' 			// use graph api version 2.8
        	});
		};

		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

		function logoutFB(){
			FB.getLoginStatus(function(response) {

				if(response.status === 'connected'){
					var uid = response.authResponse.userID;
		            var accessToken = response.authResponse.accessToken;

		            FB.api('/'+ uid +'/permissions', 'delete', function(response){
		            	//console.log(response);
	            	});
				}

	            $.ajax({
	            	url: '/login/fb_Logout',
	            	type: 'POST',
	            	dataType: 'JSON',		            	
	            })
	            .done(function() {
	            	console.log("success");
	            })
	            .fail(function() {
	            	console.log("error");
	            })
	            .always(function() {
	            	console.log("complete");
	            	window.location = base_url;
	            });
    		});
		}

	</script>
	<!-- navbar -->
	<nav id="my-navbar" class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="#">Logo</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
			<ul class="nav navbar-nav navbar-right">
				<li class="nav-item active top-bar-items">
					<a class="nav-link" href="#"><i class="fa fa-trophy" aria-hidden="true"></i></a>
				</li>
				<li class="nav-item top-bar-items">
					<a class="nav-link" href="#"><i class="fa fa-bell" aria-hidden="true"></i></a>
					<div class="notifi-num">
						<p>8</p>
					</div>
				</li>
				<li class="nav-item">
					<!-- dropdown button -->
					<div class="dropdown">
						<button class="user-name btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
							<span class="caret"></span>
							<?php 
								if($this->session->userdata('loggedInGooge')){
									echo $userGGData['USER_NAME'];
								}else if($this->session->userdata('loggedInFB')){
									echo $userFBData['USER_NAME'];
								}
							?>
						</button>
						<ul id="user-func-dropdown" class="dropdown-menu dropdown-menu-right">
							
							<?php if($this->session->userdata('loggedInGooge')){ ?>
								<!-- <li class="func-items text-center"><a href="" target="_blank">About me</a></li> -->
								<li class="func-items text-center"><a href="<?php echo base_url().'login/logoutGoogle'; ?>">Đăng xuất</a></li>
							<?php } else if($this->session->userdata('loggedInFB')){ ?>
								<!-- <li class="func-items text-center"><a href="" target="_blank">About me</a></li> -->
								<li class="func-items text-center"><a href="#" onclick="logoutFB()">Đăng xuất</a></li>
							<?php } ?>
						</ul>
					</div>
					<!-- dropdown button -->
				</div>
			</li>
		</ul>
	</div>
</nav>  

<!-- content -->
<div class="container">
	<div class="row">
		<div class="col-md-6 col-lg-6 col-xl-6 col-centered my-panel">
			<div class="content">
				<h2 class="text-center">Cập nhật thông tin</h2>
				<span class="sub-cap panel-des" style="color:red; font-weight:bold">Lưu ý: Hãy điền thông tin chính xác để chúng tôi liên hệ với bạn khi trúng thưởng</span>
				<form name="updateInfo" action="<?php echo base_url().'userController/addUser'; ?>" method="POST">
					<div class="form-group">
						<input type="text" class="form-control" name="USER_NAME" id="fullName" placeholder="Họ và tên*" required>
						<?php 
							if($this->session->userdata('loggedInGooge')){
						?>
							<input type="hidden" class="form-control" name="USER_CIF" value="<?php echo $userGGData['USER_CIF']; ?>">
							<input type="hidden" class="form-control" name="USER_EMAIL" value="<?php echo $userGGData['USER_EMAIL']; ?>">
						
						<?php }else if($this->session->userdata('loggedInFB')){ ?>
							<input type="hidden" class="form-control" name="USER_CIF" value="<?php echo $userFBData['USER_CIF']; ?>">
							<input type="hidden" class="form-control" name="USER_EMAIL" value="<?php echo $userFBData['USER_EMAIL']; ?>">
						<?php } ?>
					</div>
					<div class="form-group">
						<input type="number" class="form-control" name="USER_PHONE" id="phoneNumber" placeholder="Số điện thoại*" required>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="USER_ADDRESS" id="address" placeholder="Địa chỉ*" required>
					</div>					
					<button id="btn-update" name="update" type="submit" class="btn btn-default my-btn col-12">Cập nhật thông tin</button>
				</form>  
			</div>
		</div>      
	</div>
</div><!-- .content -->

<!-- footer -->
<footer>
	<div class="container">
		<div class="row">
			<div id="contact-us" class="center">
				<ul id="contact-item">
					<li class="d-inline-block"><a href="#"><i class="fa fa-facebook fa-lg" aria-hidden="true"></i></a></li>
					<li class="d-inline-block"><a href="#"><i class="fa fa-twitter fa-lg" aria-hidden="true"></i></a></li>
					<li class="d-inline-block"><a href="#"><i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i></a></li>
					<li class="d-inline-block"><a href="#"><i class="fa fa-google-plus fa-lg" aria-hidden="true"></i></a></li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div id="about-us" class="center">
				<p class="d-inline-block"><a href="#">Về chúng tôi</a></p>
				<p class="d-inline-block"><a href="#">Liên hệ</a></p>
				<p class="d-inline-block"><a href="#">Hướng dẫn</a></p>
			</div>
		</div>
		<div class="break-line center"></div> 
		<p class="copy-right">&copy; 9/2017</p>     
	</div>
</footer> <!-- end footer -->

<!-- jquery -->
<script src="<?php echo base_url(); ?>assets/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/popper/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/jquery/jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>assets/bootstrap/jquery.slim.min.js"></script>

<!-- My script -->
<script type="text/javascript" src="<?php echo base_url(); ?>js/client/app.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/client/ui.js"></script>
</body>
</html>