<?php
include ('util/loggedIn.php');
global $user_id;
$user_id =loggedIn();



function renderHeader($page) {
?>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Xfinity Movie List</title>

    <!-- Bootstrap core CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			padding-top: 75px;
		}
		.ellipsis {
			text-overflow: ellipsis;
			overflow: hidden;
			white-space: nowrap;
			width:100%;
			display:block;
			
  
		}
		.thumbnail p {
			margin-bottom:0;
		}
		@media (max-width: 768px) { 
			.expiresLable {
				display:block;
			}
		}
	</style>
	<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
	<script src="//cdn.jsdelivr.net/jsrender/1.0pre35/jsrender.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.0/moment.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.compat.min.js"></script>
	<script src = "md5.js"></script>
</head>
<body>
<div id="wrap">
     <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Xfinity Movie List</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          <li <?php if ($page=="index") {echo 'class="active"';}?>><a href="index.php">Home</a></li>
          <li <?php if ($page=="watchlist") {echo 'class="active"';}?>><a href="watchlist.php">Watchlist</a></li>
           <li <?php if ($page=="about") {echo 'class="active"';}?>><a href="about.php">About</a></li>
			
          </ul>
		
		  <!--  <ul class="nav navbar-nav pull-right">
		  
            <li><a href="https://github.com/thorst/phpXfinityParser" target="_github">Github</a></li>
          <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>-->
		  
			<form class="navbar-form navbar-right" role="form">
				<?php
				
				?>
				<button class="btn btn-success <?php if(empty($user_id)) {echo "hide";}?>" id="btLoginMdl" data-toggle="modal" data-target="#mdlLogin">Sign in</button>
				<button class="btn btn-success <?php if(!empty($user_id)) {echo "hide";}?>" id="btLogout">Log Out</button>
			
				
				
			</form>


        </div><!--/.nav-collapse -->
      </div>
    </div>
	<div class="container">
	
	<?php
	}
	function renderFooter() {
	?>
		</div>
</div>
	<!-- Modal -->
<div class="modal fade" id="mdlLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Login</h4>
      </div>
      <div class="modal-body">
        <div role="form">
		  <div class="form-group">
			<label for="txEmail">Email address</label>
			<input type="email" class="form-control" id="txEmail" placeholder="Enter email">
		  </div>
		  <div class="form-group">
			<label for="txPassword">Password</label>
			<input type="password" class="form-control" id="txPassword" placeholder="Password">
		  </div>
		</div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btlogin">Login</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	<script>
	user = {
		name:"<?php global $user_id;echo $user_id; ?>",
		login: function () {
			var request ={
				email: $("#txEmail").val(),
				password: md5($("#txPassword").val())
			};
			$.when(
				$.ajax({
					url: "svc/login.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				if (data.successful) {
					location.reload();
				} else {
					alert("Error logging in.");
				}
				
			});
		}
	};
	$(function() {
		$('#mdlLogin').on('shown.bs.modal', function (e) {
			$("#txEmail").focus();
		});
		$("#txPassword").keypress(function(e){
		 if (e.which == 13) {user.login();}
		});
		$("#btlogin").click(function(){
			user.login();
		});
		$("#btLogout").click(function(){
			var request ={
			};
			$.when(
				$.ajax({
					url: "svc/logout.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				if (data.successful) {
					location.reload();
				}
				
			});
			return false;
		});
	});
	
	
	</script>
</body>
</html>
<?php } ?>