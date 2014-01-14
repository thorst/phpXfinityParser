<?php
include('util/config.php');

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
		
	</style>
	<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
	<script src="//cdn.jsdelivr.net/jsrender/1.0pre35/jsrender.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.0/moment.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.compat.min.js"></script>
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
            <?php if ($page=="index") {?><li><a href="#" id="toggleFree">Show Pay</a></li><?php } ?>
          <li><a href="watchlist.php">Watchlist</a></li>
            <!--  <li><a href="#contact">Contact</a></li>-->
			
          </ul>
		 <?php if ($page=="index") {?> <form class="navbar-form navbar-left" role="form">
			<div class="form-group">
					<input class="form-control" type="text" placeholder="Search">
				</div>
		  </form><?php } ?>
		  <!--  <ul class="nav navbar-nav pull-right">
		  
            <li><a href="https://github.com/thorst/phpXfinityParser" target="_github">Github</a></li>
          <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>-->
		  
			<form class="navbar-form navbar-right" role="form">
				
				<button class="btn btn-success" data-toggle="modal" data-target="#mdlLogin">Sign in</button>
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
        <h4 class="modal-title" id="myModalLabel">Login</h4>
      </div>
      <div class="modal-body">
        <div role="form">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success">Login</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	
</body>
</html>
<?php } ?>