<?php include("common.php");
renderHeader("watchlist"); ?>
<div class="row">
<div class="col-md-12">
<h3>Lists: <small><a href="#" id="addWatchlist">Add</a> <a href="#" id="renameWatchlist">Rename</a> <a href="#" id="deleteWatchlist">Delete</a></small></h3>	
<select id="list" class="form-control"></select>
</div>
</div>

<div class="row "  style="margin-top:15px;">
	  
		<div class="col-md-1"><a href="#" id="toggleSort" class="btn btn-default" style="margin:0;">Sort By Expire Date</a></div>
		<div class="col-md-2">
   
	  </div>
	    </div>
	<div class="row">
		<div id="movieList"></div>
	</div>

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
<script id="tmplWatchlist" type="text/x-jsrender">
  <a href="#" class="list-group-item" data-id={{:id}}>{{:name}}<span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
</script>
<script id="tmplLists" type="text/x-jsrender">
	<option value={{:id}}>{{:name}}</option>
</script>
<script id="tmpleMovies" type="text/x-jsrender">
	<div class="movieblock col-md-2 col-xs-6 col-sm-4 {{if render==false}}hide pay{{/if}}">
		<div class="thumbnail ">
			<a href="<?php echo Xf_ROOT; ?>{{:href}}" target="_new">
				<img class="moviethumbnail" src="http://xfinitytv.comcast.net/api/entity/thumbnail/{{:id}}/180/240" />
			</a>
			<div class="caption">
				<b class="ellipsis" title="{{:title}}">{{:title}}</b>
				<p>Released: {{:released}}</p>
				<p ><span class="expiresLable">Expires: </span>{{if expires!=null}}{{:expires}}{{else}}Never{{/if}}</p>
				
			
					<p><a href="#" class="btn btn-block btn-default move">Move</a></p>
			
					<p><a href="#" class="btn btn-block btn-default delete">Delete</a></p>
			
			</div>
		</div>
	</div>	
</script>
<script>
watchlist = {
	list: [],
	get: function () {
		$("#movieList").html('');
		$.when(
			$.ajax({
				url: "svc/watchlist.lists.get.php",
				type: "POST"
			})
		).done(function(data) {
			watchlist.list=data.watchlists;
			
			if (watchlist.list.length==1) {
				$("#list").html( $("#tmplLists").render(watchlist.list ));
				movies.get(watchlist.list[0].id);
			} else {
				$("#list").html("<option>Choose One:</option>" + $("#tmplLists").render(watchlist.list ));
			}
		});
	},
	add: function (name) {
		var request ={
				name: name
			};
			$.when(
				$.ajax({
					url: "svc/watchlist.list.add.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				watchlist.get();
				alert("Done");
			});
	},
	rename: function (name,id) {
		var request ={
				name: name,
				userwatchlist_id:id
			};
			$.when(
				$.ajax({
					url: "svc/watchlist.list.rename.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				watchlist.get();
				alert("Done");
			});
	},
	delete: function (id) {
		var request ={
				
				userwatchlist_id:id
			};
			$.when(
				$.ajax({
					url: "svc/watchlist.list.delete.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				
				watchlist.get();
				alert("Done");
				
			});
	}

};
movies = {
	list:[],
	get: function (val) {
			var request ={
				userwatchlist_id:val
			};
			$.when(
				$.ajax({
					url: "svc/watchlist.movies.get.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				movies.list=data.movies;
				$("#movieList").html($("#tmpleMovies").render(movies.list));
			});
	},
	delete : function(idx,block) {
		var request ={
				watchlistmovies_id : movies.list[idx].watchlistmovies_id
			};
			$.when(
				$.ajax({
					url: "svc/watchlist.movies.delete.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				block.remove();//remove dom
				
				movies.list.splice(idx, 1);//remove global
			});
	},
	move: function(idx,block,list_id) {
	var request ={
				watchlistmovies_id : movies.list[idx].watchlistmovies_id,
				userwatchlist_id:list_id
				
			};
			$.when(
				$.ajax({
					url: "svc/watchlist.movies.move.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				block.remove();//remove dom
				
				movies.list.splice(idx, 1);//remove global
				$("#mdlWatchlists").modal("hide");
			});
	}
};
$(function() {
	watchlist.get();
	
			
	$("#addWatchlist").click(function(){
		var person=prompt("What would you like your watchlist to be called?");

		if (person!=null) {
		  watchlist.add(person);
		}
	});
	$("#movieList").on("click", ".delete", function(){
		var
			block= $(this).closest(".movieblock"),
			idx=block.prevAll().length
		;
		movies.delete(idx,block);
		return false;
	});
	$("#movieList").on("click", ".move", function(){
		if (watchlist.list.length ==1) {
			alert("You only have one list");
			return false;
		}
		var
			block= $(this).closest(".movieblock"),
			idx=block.prevAll().length
		;
		$("#mdlWatchlists").modal("show").data("block",block).data("idx",idx);
		return false;
	});
	$("#listWatchlist").on("click",".list-group-item", function(){
			var 
				idx=$("#mdlWatchlists").data("idx"),
				block =$("#mdlWatchlists").data("block"),
				list_id=$(this).attr("data-id")
			;
			movies.move(idx,block,list_id);
			
			return false;
		});
	$("#list").change(function() {
		movies.get($(this).val());
		var 
			t = _.cloneDeep(watchlist.list),
			idx = $(this)[0].selectedIndex-1 //compensate for the first value being "choose one"
		;
		t.splice(idx,1);
		$("#listWatchlist").html($("#tmplWatchlist").render(t));		
	});
	$("#renameWatchlist").click(function(){
		var 
			idx = $("#list")[0].selectedIndex,
			id = $("#list").val()
		;
		
		if (!$("#list option:selected").is("[value]")) {alert("Select the list you want to rename.");return false;}
		var person=prompt("What would you like your watchlist to be called?",$("#list option:selected").text());

		if (person!=null) {
		  watchlist.rename(person,id);
		}
	});
	
	$("#deleteWatchlist").click(function(){
		var 
			idx = $("#list")[0].selectedIndex,
			id = $("#list").val()
		;
		
		if (!$("#list option:selected").is("[value]")) {alert("Select the list you want to delete.");return false;}
		var r=confirm("Are you sure? This cant be undone");
		if (r==true)
		{
		watchlist.delete(id);
		}
		
		  
		
	});
	$("#toggleSort").click(function(){
		if ($(this).text()=="Sort By Expire Date") {
			$(this).text("Sort Alphabetically");
			
			movies.list =_(movies.list).sortBy( function(num) { return moment(num.expires, "MM-DD-YYYY").unix(); }).value();
		} else {
			$(this).text("Sort By Expire Date");
			movies.list =_(movies.list).sortBy( function(num) { return num.title; }).value();
		}
		
		
		$("#movieList").html($("#tmpleMovies").render(movies.list));
		
		return false;
	});
	
});
</script>


<?php renderFooter(); ?>