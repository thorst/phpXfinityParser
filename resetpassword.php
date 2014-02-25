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
    <label for="exampleInputEmail1">New Password</label>
    <input type="password" class="form-control" id="txPasssword" placeholder="New Password">
  </div>
    <div class="form-group">
    <label for="exampleInputEmail1">New Password (confirm)</label>
    <input type="password" class="form-control" id="txPassword2" placeholder="New Password (confirm)">
  </div>
  <a href="#" id="resetPassword" class="btn btn-success">Reset Password</a>
</div>

<script>
$(function() {
$("#resetPassword").click(function(){
	if ($("#txPasssword").val()!=$("#txPassword2").val()) {alert("Passwords don't match");return false;}
		var request ={
				password: md5($("#txPasssword").val())
			};
			$.when(
				$.ajax({
					url: "svc/account.password.reset.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				if (data.successful) {
				alert("Passwords changed");
				} else {
				alert("Passwords NOT changed");
				}
				
			});
			return false;
});
});
</script>



<?php renderFooter(); ?>