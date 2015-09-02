<div class="right-nav right">
    <div class="rightbox whitebox">
        <p><a href="#" data-toggle="modal" data-target="#missingPet" class="findbtn">FIND A PET</a></p>
        <p><a href="#" data-toggle="modal" data-target="#foundpet" class="foundbtn">I FOUND A PET</a></p>
    </div>
    
    <div class="rightbox whitebox">
        <label class="text-muted">Funny Videos</label>
        <div class="video-content">
            <div>
                <img src="{$smarty.const._IMAGES_URL_}vid1.jpg" class="img-responsive thumb">
            </div>
            <a href="javascript:void(0);" route="#" class="play"><img src="{$smarty.const._IMAGES_URL_}play.png" class="img-responsive"></a>
        </div>
        <div class="video-content">
            <div>
                <img src="{$smarty.const._IMAGES_URL_}vid2.jpg" class="img-responsive thumb">
            </div>
            <a href="javascript:void(0);" route="#" class="play"><img src="{$smarty.const._IMAGES_URL_}play.png" class="img-responsive"></a>
        </div>
        <div class="video-content">
            <div>
                <img src="{$smarty.const._IMAGES_URL_}vid3.jpg" class="img-responsive thumb">
            </div>
            <a href="javascript:void(0);" route="#" class="play"><img src="{$smarty.const._IMAGES_URL_}play.png" class="img-responsive"></a>
        </div>
    </div>
    
    <div class="rightbox whitebox">
    	<label class="text-muted">Pet of the Week</label>
		
        {foreach from=$pets_of_the_week item="pet" key="pkey"}
        <div class="video-content">
            <div>
                <a href="#" data-toggle="modal" data-target="#pwoneModal">
                <img src="{'pet'|fn_generate_thumbnail:$pet.image.image_path:270:190:true}" class="img-responsive thumb" />
                </a>
            </div>
        </div>
        {/foreach}
        
        <div class="video-content">
            <div>
                <a href="#" data-toggle="modal" data-target="#pwoneModal">
                <img src="{$smarty.const._IMAGES_URL_}nom1.jpg" class="img-responsive thumb">
                </a>
            </div>
        </div>
        <div class="video-content">
            <div>
                <a href="#" data-toggle="modal" data-target="#pwtwoModal">
                    <img src="{$smarty.const._IMAGES_URL_}nom2.jpg" class="img-responsive thumb">
                </a>
            </div>
        </div>
        <div class="video-content">
            <div>
                <a href="#" data-toggle="modal" data-target="#pwthrModal">
                    <img src="{$smarty.const._IMAGES_URL_}nom3.jpg" class="img-responsive thumb">
                </a>
            </div>
        </div>
    </div>
    
    <div class="missingpets-area whitebox">
        <img src="{$smarty.const._IMAGES_URL_}post1.jpg">
        <div>
            <legend><h2>Sonic</h2></legend>
            <h2>Missing <br><small>02 DAYS</small></h2>
            <a href="#"><span>VIEW</span></a>
        </div>
    </div>
    <div class="missingpets-area whitebox">
    	<img src="{$smarty.const._IMAGES_URL_}post2.jpg">
        <div>
            <legend><h2>Jorge</h2></legend>
            <h2>Missing <br><small>04 DAYS</small></h2>
            <a href="#"><span>VIEW</span></a>
        </div>
    </div>
</div>