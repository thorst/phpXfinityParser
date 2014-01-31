<?php include("common.php");
renderHeader(""); 

global $LoggedInResponse;
if(empty($LoggedInResponse->user_id)) {
	echo "<h1>Please sign in first</h1>";
	
	renderFooter(); 
	exit;
}

?>
<h2>Reset Password</h2>
<div role="form">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
  </div>
  <button type="submit" class="btn btn-success">Reset Password</button>
</div>



<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>



<?php renderFooter(); ?>