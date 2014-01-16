<?php include("common.php");
renderHeader("watchlist"); ?>
<div class="row">
<div class="col-md-12">
<h3>Lists: <small><a href="#">Rename</a> <a href="#">Delete</a></small></h3>	
<select id="list" class="form-control"></select>
</div>
</div>
	<div class="row">
 
<div id="movieList">
</div></div>
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
				<p >Expires: {{if expires!=null}}{{:expires}}{{else}}Never{{/if}}</p>
				
				{{if inwatchlist}}
					<p><a href="#" class="btn btn-block btn-default" disabled="disabled">Added</a></p>
				{{else}}
					<p><a href="#" class="btn btn-block btn-default add">Add</a></p>
				{{/if}}
			</div>
		</div>
	</div>	
</script>
<script>
watchlist = {
	lists: [],
	get: function () {
		$.when(
			$.ajax({
				url: "svc/watchlist.lists.get.php",
				type: "POST"
			})
		).done(function(data) {
			$("#list").html( $("#tmplLists").render( data.watchlists));
		});
	}

};
$(function() {
watchlist.get();
			var request ={
				
			};
			$.when(
				$.ajax({
					url: "svc/watchlist.movies.get.php",
					type: "POST",
					data: request
				})
			).done(function(data) {
				$("#movieList").append($("#tmpleMovies").render(data.watchlists[0].movies));
			});
});
</script>


<?php renderFooter(); ?>