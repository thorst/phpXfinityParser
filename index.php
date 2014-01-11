<?php
include('util/config.php');
?>
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
		.moviethumbnail {
			display:block;
			width:125px;
		}
		.movietitle {
			width:125px;
		}
		.ellipsis {
			text-overflow: ellipsis;
			overflow: hidden;
			white-space: nowrap;
		}
		.movieblock {
			display:inline-block;
			height:265px;
			vertical-align:top;
			padding: 2px;
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
          <li><a href="watchlist.php">Watchlist</a></li>
            <!--  <li><a href="#contact">Contact</a></li>-->
          </ul>
		  
		  <ul class="nav navbar-nav pull-right">
            <li><a href="https://github.com/thorst/phpXfinityParser" target="_github">Github</a></li>
          <!--  <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>-->
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
<div class="container">
	<div id="movieList"></div>
	<a href-"#" id="loadmore" class="btn btn-default btn-lg btn-block">Load More</a>
</div>

<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="//cdn.jsdelivr.net/jsrender/1.0pre35/jsrender.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.compat.min.js"></script>
<script id="tmpleMovies" type="text/x-jsrender">
	<h3>{{:date}} ({{:movies.length}} {{if movies.length==1}}movie{{else}}movies{{/if}})</h3>
	{{for movies}}
		<div class="well well-sm movieblock" >
			<a href="<?php echo Xf_ROOT; ?>{{:href}}" target="_new">
				<img class="moviethumbnail" src="http://xfinitytv.comcast.net/api/entity/thumbnail/{{:id}}/180/240" />
			</a>
			<div class="ellipsis movietitle" title="{{:title}}">{{:title}}</div>
			<div class="ellipsis movietitle">Released: {{:released}}</div>
			{{if expires!=""}}<div class="ellipsis movietitle">Expires:{{:expires}}</div>{{/if}}
			<a href="#" class="btn btn-block btn-default">Add</a>
		</div>
	{{/for}}
</script>
<script>
	movies={
		end: moment().add("days",1),
		list: [],
		render:null,
		count:0,
		load: null,
		showpay: false,
		filter: function(d){
			movies.render=_.cloneDeep(d);
			if (movies.showpay) {movies.filterByPay();}
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
				load: movies.load,
				count: movies.count
			};
			$.when(
				$.ajax({
					url: "svc/movies.get.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				//Merge movies with master list
				Array.prototype.push.apply(movies.list, data.movies);
				
				//Filter the new movies
				movies.filter(data.movies);
				movies.load=data.load;
				movies.count=data.count;
				
				$("#movieList").append($("#tmpleMovies").render(movies.render));
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
			movies.showpay=!movies.showpay;
			movies.filter(movies.list);
			$("#movieList").html($("#tmpleMovies").render(movies.render));
			return false;
		});
		$("#loadmore").click(function(){
			movies.get();
		});
	});
</script>

</body>
</html>