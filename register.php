<?php 
include("common.php");
renderHeader(""); 

global $LoggedInResponse;
if(!empty($LoggedInResponse->user_id)) {
	echo "<h1>You are already registered.</h1>";
	
	renderFooter(); 
	exit;
}

?>

<?php renderFooter(); ?>