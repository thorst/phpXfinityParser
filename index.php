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
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#" id="toggleFree">Show Pay</a></li>
          <!--  <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>-->
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
<div class="container">
<div id="movieList">
</div>
</div>

<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="//cdn.jsdelivr.net/jsrender/1.0pre35/jsrender.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.compat.min.js"></script>
<script id="tmpleMovies" type="text/x-jsrender">
	<h3>{{:date}}</h3>
	{{for movies}}
		<div class="well well-sm" style="display:inline-block;">
			<a href="http://xfinitytv.comcast.net/{{:href}}" target="_new">
				<img src="http://xfinitytv.comcast.net/api/entity/thumbnail/{{:id}}/250/334" style="display:block;width:125px;" />
			</a>
			<div style="text-overflow: ellipsis;width:180px;overflow: hidden;white-space: nowrap;">{{:title}}</div>
			
		</div>
	{{/for}}
</script>
<script>
	movies={
		end: moment().add("days",1),
		list: null,
		render:null,
		filter: function(){
			movies.render=_.cloneDeep(movies.list);
			if ($("#toggleFree").text()=="Show Pay") {movies.filterByPay();}
			movies.sortByAdded();
		},
		filterByPay: function () {
			
			var paycodes =["d", "f",'e','h','cj'];
			movies.render = _(movies.render).filter(function(num) {
				//If there arent any codes then its free
				if (num.codes.length==0) {return true;}
				var shouldReturn = false;
				_(num.codes).forEach(function(code) {
					if (paycodes.indexOf(code) ==-1) {
						shouldReturn= true;
						return false; //exit foeach early
					}					
				});
				return shouldReturn; 
			});
			
		},
		sortByAdded: function(){
			//Would be nice to make this one chain...
				movies.render = _(movies.render)
							.groupBy( function(num) { return num.added; })
							.value();
							
				movies.render= _(movies.render)	
							.keys()
							.sort()
							.reverse()
							.map(function (value,index) {
								return {date:value, movies:movies.render[value]};
							})
							.value();
		},
		get: function() {
			var request ={
				start: movies.end.format("YYYY-MM-DD"),
				end:movies.end.add("days",-7).format("YYYY-MM-DD")
			};
			$.when(
				$.ajax({
					url: "svc/movies.get.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				//Put untouched version into storage
				movies.list=data;
				movies.filter();
				
				
				$("#movieList").html($("#tmpleMovies").render(movies.render));
			});
		}
	};
	$(function() {
		movies.get();
		$("#toggleFree").click(function() {
			if ($(this).text()=="Show Pay") {
				$(this).text("Hide Pay");
			} else {
				$(this).text("Show Pay");
			}
			movies.filter();
			$("#movieList").html($("#tmpleMovies").render(movies.render));
			return false;
		});
	});
</script>

</body>
</html>