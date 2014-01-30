<?php

include ('util/loggedIn.php');
global $LoggedInResponse;
$LoggedInResponse =loggedIn();

function renderHeader($page) {
?>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
				global $LoggedInResponse;
				if (empty($LoggedInResponse->user_id)) {
				?>
					<button class="btn btn-success" id="btLoginMdl" data-toggle="modal" data-target="#mdlLogin">Sign in</button>
				<?php
				} else {
				?>
					<div class="btn-group">
					  <button type="button" class="btn btn-default btn-success dropdown-toggle" data-toggle="dropdown">
						<?php echo $LoggedInResponse->email; ?> <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
						<li><a href="#">Reset Password</a></li>
						<li class="divider"></li>
						<li><a href="#" id="btLogout">Log Out</a></li>
					  </ul>
					</div>
				<?php
				}
				?>
				

				
				
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
	  <a href="#" class="btn btn-default pull-left">Register</a> <a href="#" class="btn btn-default pull-left">Forgot Password?</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btlogin">Login</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal -->
<div class="modal fade" id="mdlDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 id="detailsTitle" class="modal-title">Login</h4>
      </div>
      <div id="detailsBody" class="modal-body">
       
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btXfinity">Xfinity</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="popover" class="popover" style="display: block;top:-500;left:-500;">
	<div class="arrow"></div>
	<h3 id="popover-title" class="popover-title">A Title</h3>
	<div id="popover-content" class="popover-content">And here's some amazing content. It's very engaging. right?</div>
</div>
<script id="tmplDetails" type="text/x-jsrender">
<!--Released: {{:released}}<br>-->
	Expires: {{if expires!=null}}{{:expires}}{{else}}Never{{/if}}<br>
	{{if fan!=null}}Fan: {{:fan}}{{/if}}
	{{if critic!=null && fan!=null}}/{{/if}}
	{{if critic!=null}}Critic: {{:critic}}{{/if}}<br>
	Codes: {{:codes}}<br>
	{{:details}}
</script>
	<script>
	mouseDetected = false;
	mightBeMouse = false;
	$(window).on( "mousedown", function(event){
		mightBeMouse = false;
	});
	$(window).on( "mousemove", function(event){
		if(mightBeMouse) {
			mouseDetected= true;
			$(window).off( "mousedown");
			$(window).off( "mousemove");
		}
		mightBeMouse = true;
	});
	user = {
		
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
	commonmovies = {
		fetching:null,
		detailsList:[],
		details: function(param) {
			if (commonmovies.fetching ==param.movie.movieid ) {return false;}
			
			if (param.movie.movieid in commonmovies.detailsList) {commonmovies.renderdetails(param);return false;}
			commonmovies.fetching=param.movie.movieid;
			var request ={
				movieid: param.movie.movieid
			};
			$.when(
				$.ajax({
					url: "svc/movies.details.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				commonmovies.fetching=null;
				commonmovies.detailsList[param.movie.movieid] =data;
				commonmovies.detailsList[param.movie.movieid].codes=param.movie.codes.join(",");
				//param.func();
				commonmovies.renderdetails(param);
				
			});
		},
		showtip: function(that) {
			var 
				o =that.offset(),
				l=o.left+that.outerWidth(),
				pw=$("#popover").width(),
				p=$("#popover")
			;
			
			if (l+pw>$( window ).width()) {
				l=o.left-pw;
				p.removeClass("right").addClass("left");
			} else {
				p.removeClass("left").addClass("right");
			}
			p.offset({top:o.top, left:l});
		},
		renderdetails: function(param) {
				param.contTitle.html(param.movie.title);
				param.contMain.html($("#tmplDetails").render(commonmovies.detailsList[param.movie.movieid]));
				if (mouseDetected) {
					commonmovies.showtip(param.that);
				}
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
		$("#btXfinity").click(function() {
			window.open($("#mdlDetails").data("href"),"_new");
		});
		$("#movieList").on("click",".movielinks",function(){
			if (mouseDetected) {
				return true;
			} else {
				var
					movie_idx = $(this).closest(".movieblock").prevAll().length,
					group_idx =$(this).closest(".row").prevAll(".row").length,
					movie=movies.list[group_idx].movies[movie_idx]
				;				
				commonmovies.details({
					movie:movie,
					contMain:$("#detailsBody"),
					contTitle:$("#detailsTitle")
				});
			
				$("#mdlDetails").data("href",$(this).attr("href")).modal("show");
			
				return false;
			}
			return false;
		});
		$("#movieList").on('mouseenter', '.movieblock', function() {
			if (!mouseDetected) {
				return false;
			}
			var
				movie_idx = $(this).prevAll().length,
				group_idx =$(this).closest(".row").prevAll(".row").length,	
				movie=movies.list[group_idx].movies[movie_idx]
			;
			commonmovies.details({
				movie:movie,
				contMain:$("#popover-content"),
				contTitle:$("#popover-title"),
				that : $(this)
			});
		});
		$("#movieList").on('mouseleave', '.movieblock', function() {
			$("#popover").offset({top:-500, left:-500});
			$("#popover-content").html("");
		});
	});
	
	
	</script>
</body>
</html>
<?php } ?>