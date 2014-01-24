<?php 
include("common.php");
renderHeader("index");

 ?>
 


	  <div class="row " >
	  
		<div class="col-md-2 col-xs-6"><a href="#" id="toggleFree" class="btn btn-default navbar-btn" style="margin:0;">Show Pay</a></div>
		
   
	  </div>
	  
	  

<div  id="movieList"></div>

<a href-"#" id="loadmore" class="btn btn-default btn-lg btn-block" style="margin-bottom:20px;">Load More</a>

<!-- Modal -->
<div class="modal fade" id="mdlWatchlists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Save to Watchlist</h4>
      </div>
      <div class="modal-body">
		<div class="list-group" id="listWatchlist">
		  
		</div>

	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="popover" class="popover" style="display: block;top:-500;left:-500;">
	<div class="arrow"></div>
	<h3 id="popover-title" class="popover-title">A Title</h3>
	<div id="popover-content" class="popover-content">And here's some amazing content. It's very engaging. right?</div>
</div>
<script id="tmplWatchlist" type="text/x-jsrender">
  <a href="#" class="list-group-item" data-id={{:id}}>{{:name}}<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
</script>
<script id="tmpleMovies" type="text/x-jsrender">
<h3>{{:date}} ({{:freecount}} Free / {{:paycount}} Pay)</h3>
<div class="row">
 
	
	
	{{for movies}}
	<div class="movieblock col-md-2 col-xs-6 col-sm-4 {{if free==false}}pay{{/if}} {{if render==false}}hide{{/if}}">
		<div class="thumbnail ">
			<a href="<?php echo Xf_ROOT; ?>{{:href}}" target="_new">
				<img class="moviethumbnail" src="http://xfinitytv.comcast.net/api/entity/thumbnail/{{:id}}/180/240" />
			</a>
			<div class="caption">
				<b class="ellipsis" title="{{:title}}">{{:title}}</b>
				<p>Released: {{:released}}</p>
				<p ><span class="expiresLable">Expires: </span>{{if expires!=null}}{{:expires}}{{else}}Never{{/if}}</p>
				
				{{if inwatchlist}}
					<p><a href="#" class="btn btn-block btn-default" disabled="disabled">Added</a></p>
				{{else}}
					<p><a href="#" class="btn btn-block btn-default add">Add</a></p>
				{{/if}}
			</div>
		</div>
	</div>	
	{{/for}}
	</div>
	
</script>
<script>
	watchlist = {
		lists:[],
		movies:[],
		get : function () {
		$.when(
			$.ajax({
					url: "svc/watchlist.summary.php",
					type: "POST"
				})
		).done(function(data2) {
				watchlist.lists=data2.watchlists;
				watchlist.movies=data2.movies;
				$("#listWatchlist").html($("#tmplWatchlist").render(watchlist.lists));
				movies.get();
		});
		}
	};
	movies={
		paycodes:[<?php echo Xf_PAY_CODES;?>],
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
			/*Determines if a movie is already in our watchlist*/
			_(movies.render).forEach(function(o,idx) {
				if (_.contains(watchlist.movies, o.movieid)) {
					movies.render[idx].inwatchlist=true;
				}
				
			});;
			
		},
		filterByPay: function () {
			
			_(movies.render).each(function(value, index) {
				
				//If there arent any codes then its free
				if (value.codes.length==0) {
					movies.render[index].free=true;
					movies.render[index].render=true;
					return;
				}
				var shouldReturn = false;
				_(value.codes).forEach(function(code) {
					if (movies.paycodes.indexOf(code) ==-1) {
						shouldReturn= true;
						return false; //exit foeach early
					}					
				});
				if (movies.hidepay && !shouldReturn) {
					movies.render[index].render= false;
				} else {
					movies.render[index].render= true;
				}
				movies.render[index].free= shouldReturn; 
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
								var freecount=0,paycount=0;
								freecount =_(movies.render[value]).filter(function(num){return num.free}).value().length;
								paycount=movies.render[value].length-freecount;
								return {date:value, movies:movies.render[value], freecount:freecount, paycount:paycount};
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
				
			).done(function(data1) {
				
				
				
			
				
				
				//Filter the new movies
				movies.filter(data1.movies);
				movies.load=data1.load;
				movies.count=data1.count;
				
				//Merge movies with master list
				Array.prototype.push.apply(movies.list, movies.render);
				
				$("#movieList").append($("#tmpleMovies").render(movies.render));
				
			});
		},
		add: function (movieid,listid,button) {
			var request ={
				movieid: movieid,
				listid: listid
			};
			$.when(
				$.ajax({
					url: "svc/watchlist.movies.add.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				
				button.text("Added").attr("disabled","disabled");
				$("#mdlWatchlists").modal("hide");
			});
		}
	};
	$(function() {
		$("#movieList").on('mouseenter', '.movieblock', function() {
			var
				movie_idx = $(this).prevAll().length,
				group_idx =$(this).closest(".row").prevAll(".row").length
			;
			$("#popover-title").html(movies.list[group_idx].movies[movie_idx].title);
			$("#popover-content").html(movies.list[group_idx].movies[movie_idx].title);
		
			var 
				o =$(this).offset(),
				l=o.left+$(this).outerWidth(),
				pw=$("#popover").width(),
				p=$("#popover")
			;
			
			if (l+pw>$( window ).width()) {
				l=o.left-pw;
				p.removeClass("right").addClass("left");
			} else {
				p.removeClass("left").addClass("right");
			}
			p.offset({top:o.top, left:l});;
		});
		$("#movieList").on('mouseleave', '.movieblock', function() {
			$("#popover").offset({top:-500, left:-500});
		});
	
		watchlist.get();
		$("#toggleFree").click(function() {
			if ($(this).text()=="Show Pay") {
				$(this).text("Hide Pay");
			} else {
				$(this).text("Show Pay");
			}
			movies.hidepay=!movies.hidepay;
			//movies.filter(movies.list);
			//$("#movieList").html($("#tmpleMovies").render(movies.render));
			$(".pay").toggleClass("hide");
			return false;
		});
		$("#loadmore").click(function(){
			movies.get();
		});
		$("#listWatchlist").on("click",".list-group-item", function(){
			var 
				o=$("#mdlWatchlists").data("o"),
				button =$("#mdlWatchlists").data("button")
			;
			
			movies.add( o.movieid, $(this).attr("data-id"),button);
			return false;
		});
		$("#movieList").on("click",".add",function(){
			var
				movie_idx = $(this).closest(".movieblock").prevAll(".movieblock").length,
				group_idx =$(this).closest(".row").prevAll(".row").length,
				button = $(this),
				o =movies.list[group_idx].movies[movie_idx]
			;
			
			if (user.name=="") {
				alert("You need to sign in first");
			} else if (watchlist.lists.length==0) {
				alert("You need to add a watchlist first");
			} else if (watchlist.lists.length==1) {
				movies.add( o.movieid, watchlist.lists[0].id,button);
			} else {
				$("#mdlWatchlists").modal("show").data("o",o).data("button",button);
			}
			
			return false;
		});
	});
</script>


<?php renderFooter(); ?>