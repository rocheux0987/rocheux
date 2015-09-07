{literal}
<style type="text/css">
	.map-canvas{
		height: 150px;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		var map;
		var infowindow;
		$(document).on('click' , '#form_submit' , function(){
			 if($('#state_form').val() != ''){
			 	$( "#search_form" ).trigger("submit");

			 	$.ajax({
			 		url: $("#search_form").attr('action'),
			 		type: "GET",
			 		data: $("#search_form").serialize(),
			 		success: function(response){
			 			$('#load_here').html(response);
			 		}
			 	});
			 }
		});


		if(navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
				var currentloc = {lat: position.coords.latitude, lng: position.coords.longitude };	

				map = new google.maps.Map(document.getElementById('current_location_area'), 
				{
					center: currentloc,
					zoom: 15,
				});
				infowindow = new google.maps.InfoWindow();

				var marker = new google.maps.Marker({
					position: currentloc,
		    		animation: google.maps.Animation.DROP,
					title: 'Your Location',
					map: map
				});

				google.maps.event.addListener(map, "click", function (event) {

					var marker = new google.maps.Marker({
				      position: event.latLng,
				      map: map
				    });  
				    map.panTo(event.latLng);


				    var latitude = event.latLng.lat();
    				var longitude = event.latLng.lng();
				    $.ajax({
						url: '{/literal}{"petshop.php"|seo_url}{literal}/?act=nearest',
						type: "GET",
						data: {lat : latitude , lon : longitude},
						success: function(response){
							$('#load_here').html(response);
						}
					});



				});

				$.ajax({
					url: '{/literal}{"petshop.php"|seo_url}{literal}/?act=nearest',
					type: "GET",
					data: {lat : position.coords.latitude , lon : position.coords.longitude},
					success: function(response){
						$('#load_here').html(response);
					}
				});
			});
		}
		
	});

	function initMap(latitude, longitude , div) {
		var currentloc = {lat: latitude, lng: longitude};	

		map = new google.maps.Map(document.getElementById(div), 
		{
			center: currentloc,
			zoom: 15
		});

		infowindow = new google.maps.InfoWindow();

		createUserLocMarker(currentloc); 

	}
	
	function createUserLocMarker(currentloc)
	{
		var marker = new google.maps.Marker({
			position: currentloc,
    		animation: google.maps.Animation.DROP,
			title: 'Your Location',
			map: map
		});

	}
</script>
{/literal}

<div class="mid">
	<div class="nearest-header whitebox">
		<div class="text-center">
			<img src="{$smarty.const._IMAGES_URL_}cart-large.png">
			<h3>Nearest Petshop <br><small class="text-muted">Find your nearest Petshop</small></h3>
			<div class="search-input-nearest">
				<form method="get" action="{'petshop.php'|seo_url}/" id="search_form">
					<input type="hidden" name="act" value="search">
					<input type="text" class="form-control input-lg" name="value" id="state_form" placeholder="Search for...">
					<a href="javascript:void(0);" id="form_submit"><img src="{$smarty.const._IMAGES_URL_}search.png"></a>
				</form>
			</div>
			<br clear="all">
		</div>
	</div>
	<div class="newsfeed whitebox" >
		<!-- NEWSFEED CONTENT -->
		<div class="newsfeed-cont">
			<div id="current_location_area" style="height:50%;padding:2px;"></div>
		</div>
		
	</div>
	{if $is_search eq true}
		{foreach name="results" from=$data item=row}
		<!-- NEWSFEED AREA-->
		<div class="newsfeed whitebox">
			<!-- NEWSFEED CONTENT -->
			<div class="newsfeed-content">
				<div class="row nearest-content">
					<div class="map-image left">
						<a href="#"><img src="{$smarty.const._IMAGES_URL_}/new/vet1.jpg"></a>
					</div>

					<div class="nearest-infos right">
						<div class="map-canvas" id="map_{$row.vet_id}"></div>
						{literal}
						<script type="text/javascript">
						initMap({/literal}{$row.lat}{literal}, {/literal}{$row.lon}{literal} , 'map_{/literal}{$row.vet_id}{literal}');
						</script>
						{/literal}
						<div>
							<div class="naddress left" style="width:50%;padding-top:10px;">
								<p>{$row.address}</p>	
							</div>
							<div class="nother-infos right" style="width:40%;">
								<a href="tel:{$row.contact_number}"><i class="fa fa-phone"></i> <span>{$row.contact_number}</span></a>
								<br>
								<a href="mailto:{$row.email}"><i class="fa fa-envelope"></i> <span>{$row.email}</span></a>
							</div>
							<br clear="all"/>
						</div>
					</div>
					<br clear="all"/>
				</div>
				<!-- END NEWSFEED CONTENT -->

				<!-- COMMENT SECTION -->
				<div class="row newsfeed-bottom left">
					<div class="left">
						<a href="#"><img src="{$smarty.const._IMAGES_URL_}like.png"><span class="like">26</span></a>
					</div>
					<div class="left">
						<a href="javascript:void(0);" id="comment-down"><img src="{$smarty.const._IMAGES_URL_}comment.png"></a>
					</div>
					<br clear="all" />
				</div>
				<!-- END COMMENT SECTION -->
				<br clear="all" />
			</div>
		</div>
		{foreachelse}
		<p class="text-center">No Results...</p>
		{/foreach}
	{else}
		<div id="load_here"></div>
	{/if}
	
</div>

