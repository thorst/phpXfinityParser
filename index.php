<?php 
include("common.php");
renderHeader("index");
 ?>
<div  id="movieList"></div>

<a href-"#" id="loadmore" class="btn btn-default btn-lg btn-block" style="margin-bottom:20px;">Load More</a>



<script id="tmpleMovies" type="text/x-jsrender">
<div class="row">
 

 <div class="col-md-12">
	<h3>{{:date}} ({{:count}})</h3>
	{{for movies}}
	<div class="movieblock col-md-2 col-xs-6 col-sm-4 {{if render==false}}hide{{/if}}">
		<div class="thumbnail ">
			<a href="<?php echo Xf_ROOT; ?>{{:href}}" target="_new">
				<img class="moviethumbnail" src="http://xfinitytv.comcast.net/api/entity/thumbnail/{{:id}}/180/240" />
			</a>
			<div class="caption">
				<b class="ellipsis" title="{{:title}}">{{:title}}</b>
				<p>Released: {{:released}}</p>
				{{if expires!=null}}<p >Expires:{{:expires}}</p>{{/if}}
				
				{{if inwatchlist}}
				<p><a href="#" class="btn btn-block btn-default add" disabled="disabled">Added</a></p>
				{{else}}
				<p><a href="#" class="btn btn-block btn-default add">Add</a></p>
				{{/if}}
			</div>
		</div>
	</div>	
	{{/for}}
	</div>
	</div>
</script>
<script>
	watchlist = {
		lists:[],
		movies:[]
	};
	movies={
		paycodes:["d", "f",'e','h','cj'],
		end: moment().add("days",1),
		list: [],
		render:null,
		count:0,
		load: null,
		hidepay: true,
		filter: function(d){
			movies.render=_.cloneDeep(d);
			movies.filterAdded();
			if (movies.hidepay) {movies.filterByPay();}
			movies.sortByAdded();
		},
		filterAdded: function(d){
			_(movies.render).forEach(function(o,idx) {
			console.log(o);
				if (_.contains(watchlist.movies, o.movieid)) {
					movies.render[idx].inwatchlist=true;
				}
				
			});;
			
		},
		filterByPay: function () {
			
			_(movies.render).each(function(value, index) {
				
				//If there arent any codes then its free
				if (value.codes.length==0) {movies.render[index].render=true;return;}
				var shouldReturn = false;
				_(value.codes).forEach(function(code) {
					if (movies.paycodes.indexOf(code) ==-1) {
						shouldReturn= true;
						return false; //exit foeach early
					}					
				});
				movies.render[index].render= shouldReturn; 
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
								var count=0;
								movies.hidepay ?  count =_(movies.render[value]).filter(function(num){return num.render}).value().length : count=movies.render[value].length;
								return {date:value, movies:movies.render[value], count:count};
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
				}),
				$.ajax({
					url: "svc/watchlist.summary.php",
					type: "POST"
				})
			).done(function(data1, data2) {
				data1=data1[0];
				data2=data2[0];
				watchlist.lists=data2.watchlists;
				watchlist.movies=data2.movies;
			
				//Merge movies with master list
				Array.prototype.push.apply(movies.list, data1.movies);
				
				//Filter the new movies
				movies.filter(data1.movies);
				movies.load=data1.load;
				movies.count=data1.count;
				
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
			movies.hidepay=!movies.hidepay;
			movies.filter(movies.list);
			$("#movieList").html($("#tmpleMovies").render(movies.render));
			return false;
		});
		$("#loadmore").click(function(){
			movies.get();
		});
		$("#movieList").on("click",".add",function(){
			var
				idx = $(this).closest(".movieblock").prevAll(".movieblock").length,
				that = $(this)
			;
			var request ={
				movieid: movies.list[idx].movieid
			};
			$.when(
				$.ajax({
					url: "svc/watchlist.movies.add.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				console.log(movies.list[idx].title + " added");
				that.text("Added").attr("disabled","disabled");
			});
			
			return false;
		});
	});
</script>


<?php renderFooter(); ?>