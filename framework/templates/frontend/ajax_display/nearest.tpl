{foreach name="results" from=$data item=row}
<!-- NEWSFEED AREA-->
<div class="newsfeed whitebox">
	<!-- NEWSFEED CONTENT -->
	<div class="newsfeed-content">
		<div class="row nearest-content">
			<div class="map-image left">
				<a href="#">
					<img src="{$row.image.image_path}">
				</a>
			</div>

			<div class="nearest-infos right">
				<div class="map-canvas" id="map_{$row.vet_id}"></div>
				{literal}
				<script type="text/javascript">
				initMap({/literal}{$row.lat}{literal}, {/literal}{$row.lon}{literal} , 'map_{/literal}{$row.vet_id}{literal}');
				</script>
				{/literal}
				<div>
					<div class="naddress left" style="padding-top:10px;">
						<p>
							<span>{$row.address}</span><br>
							<span>{$row.state}</span><br>
							<span>{$row.city}</span>
						</p>	
					</div>
					<div class="nother-infos right">
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
				<a href="#"><img src="{$smarty.const._IMAGES_URL_}like.png"><span class="like"></span></a>
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