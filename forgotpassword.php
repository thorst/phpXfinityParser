<?php include("common.php");
renderHeader(""); 

global $LoggedInResponse;
if(!empty($LoggedInResponse->user_id)) {
	echo "<h1>You are already signed in</h1>";
	
	renderFooter(); 
	exit;
}

?>
<h2>Forgot Password?</h2>
<div role="form">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="txEmailReset" placeholder="Enter email">
  </div>
  <a id="reset" href="#"  class="btn btn-success">Reset Password</a>
</div>


<script>
$(function() {
	$("#reset").click(function(){
			var request ={
				email: $("#txEmailReset").val()
			};
			$.when(
				$.ajax({
					url: "svc/password.forgot.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
			
				if (data.successful) {
				alert("Reset Succeedded");
				} else {
				alert("Reset Failed");
				}
				
			});
			
		return false;
	});
});


</script>



<?php renderFooter(); ?>