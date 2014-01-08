<html>
<head>

 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>Xfinity Movie List</title>

    <!-- Bootstrap core CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">

 
	
	<style>
		body {
			padding-top: 75px;
		}
	</style>
</head>
<body>


     <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Xfinity Movie List</a>
        </div>
        <!--<div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>-->
        </div><!--/.nav-collapse -->
      </div>
    </div>
<div class="container">
<table class="table table-hover table-stripped">
<thead>
<tr>
<th>Title</th>
<th>Added</th>
</tr>
</thead>
<tbody>
<?php
include('util/config.php');
$mysqli = new mysqli(DB_HOST, DB_USER,DB_PASSWORD,DB_NAME);
if ($result = $mysqli->query("SELECT * FROM movies Where removed is null and inserted > '".INITIAL_LOAD."'")) {
	while($obj = $result->fetch_object()){
		echo "
		<tr>
			<td>".$obj->title."</td>
			<td>".$obj->inserted."</td>
			
		</tr>
		";
    } 
	$result->close();
}
?>
</tbody>
</table>
</div>

<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="//cdn.jsdelivr.net/jsrender/1.0pre35/jsrender.min.js"></script>
<script>
	movies={
		get: function() {
			var request ={
				date: new Date()
			};
			$.when(
				$.ajax({
					url: "svc/movies.get.php",
					context: document.body,
					type: "POST",
					data: request
				})
			).done(function() {
			
			});
		}
	};
	$(function() {
		movies.get();
	});
</script>

</body>
</html>