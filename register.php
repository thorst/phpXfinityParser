<?php 
include("common.php");
include_once('util/config.php');
//print_r ($Questions);
renderHeader(""); 

global $LoggedInResponse;
if(!empty($LoggedInResponse->user_id)) {
	echo "<h1>You are already registered.</h1>";
	
	renderFooter(); 
	exit;
}

?>

<h2>Register</h2>
<div role="form">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="txEmailReset" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Question</label>
	<?php
	# pick a random key in your array
	$rand_key = array_rand($Questions);

	# extract the corresponding value
	$rand_value = $Questions[$rand_key];
	echo '<div>'.$rand_key.'</div>';
	?>
	<input id="hQuestion" type="hidden" value="<?php echo array_search($rand_key,array_keys($Questions)); ?>" />
    <input type="email" class="form-control" id="txAnswer" placeholder="Enter Answer">
  </div>
  <a id="reset" href="#"  class="btn btn-success">Register</a>
</div>


<script>
question = {
answer:"<?php //echo md5($rand_value) ; ?>"
};
$(function() {
	$("#reset").click(function(){
			var request ={
				email: $("#txEmailReset").val(),
				answer: $("#txAnswer").val(),
				question: $("#hQuestion").val()
			};
			$.when(
				$.ajax({
					url: "svc/account.create.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
			
				if (data.successful) {
				alert("Create Succeedded");
				} else {
				alert(data.error);
				}
				
			});
			
		return false;
	});
});


</script>


<?php renderFooter(); ?>