<div class="left-nav left">
    <div class="leftbox whitebox">
        <ul>
        <li><a href="{"newsfeed.php"|seo_url}"><img src="{$smarty.const._IMAGES_URL_}notification.png" class="icon" /></a><a href="{"newsfeed.php"|seo_url}"><span>Newsfeed</span></a></li>
        </ul>
    </div>

	{if isset($user_data)}
    <div class="leftbox whitebox">
        <label class="text-muted">MEMBER OPTIONS</label>
        <ul>
            <li class="side-icon"><a class="create" href="{"upload.php"|seo_url}"></a><a href="{"upload.php"|seo_url}"><span>Create Post</span></a></li>
            <li class="side-icon"><a class="switch" href="{"profile.php"|seo_url}/{$pet_id}/switch"></a><a href="{"profile.php"|seo_url}/{$pet_id}/switch"><span>Switch Profile</span></a></li>
            <li class="side-icon"><a class="edit" href="{"profile.php"|seo_url}/{$pet_id}/edit"></a><a href="{"profile.php"|seo_url}/{$pet_id}/edit"><span>Edit Profile</span></a></li>
        </ul>
    </div>
    {/if}
    
    <div class="leftbox whitebox">
        <label class="text-muted">PET NEEDS</label>
        <ul>
            <li class="side-icon"><a class="petshops" href="{"petshop.php"|seo_url}"></a><a href="{"petshop.php"|seo_url}"><span>Nearest Petshops</span></a></li>
            <li class="side-icon"><a class="vets" href="{"vets.php"|seo_url}"></a><a href="{"vets.php"|seo_url}"><span>Nearest Vets</span></a></li>
            <li class="side-icon"><a class="shelters" href="#"></a><a href="{"shelters.php"|seo_url}"><span>Animal Shelters</span></a></li>
            <li class="side-icon"><a class="adopt" href="#"></a><a href="{"adopt.php"|seo_url}"><span>Adopt a Pet</span></a></li>
        </ul>
    </div>
    
    <div class="leftbox whitebox">
        <label class="text-muted">PET WANTS</label>
        <ul>
            <li class="side-icon"><a class="groomers" href="{"pet_wants.php"|seo_url}/?address=groomers"></a><a href="{"pet_wants.php"|seo_url}/?address=groomers"><span>Pet Groomers</span></a></li>
            <li class="side-icon"><a class="walkers" href="{"pet_wants.php"|seo_url}/?address=walkers"></a><a href="{"pet_wants.php"|seo_url}/?address=walkers"><span>Pet Walkers</span></a></li>
            <li class="side-icon"><a class="boarding" href="{"pet_wants.php"|seo_url}/?address=boarding"></a><a href="{"pet_wants.php"|seo_url}/?address=boarding"><span>Pet Boarding</span></a></li>
            <li class="side-icon"><a class="dating" href="{"pet_wants.php"|seo_url}/?address=dating"></a><a href="{"pet_wants.php"|seo_url}/?address=dating"><span>Pet Dating</span></a></li>
        </ul>
    </div>
    
    <div class="leftbox whitebox">
        <label class="text-muted">PET-FRIENDLY</label>
        <ul>
            <li class="side-icon"><a class="restaurants" href="{"pet_friendly.php"|seo_url}/?address=restaurants"></a><a href="{"pet_friendly.php"|seo_url}/?address=restaurants"><span>Restaurants</span></a></li>
            <li class="side-icon"><a class="hotels" href="{"pet_friendly.php"|seo_url}/?address=hotels"></a><a href="{"pet_friendly.php"|seo_url}/?address=hotels"><span>Hotels</span></a></li>
            <li class="side-icon"><a class="park" href="{"pet_friendly.php"|seo_url}/?address=park and beach"></a><a href="{"pet_friendly.php"|seo_url}/?address=park and beach"><span>Park/Beach</span></a></li>
            <li class="side-icon"><a class="travel" href="{"pet_friendly.php"|seo_url}/?address=travel"></a><a href="{"pet_friendly.php"|seo_url}/?address=travel"><span>Travel</span></a></li>
        </ul>
    </div>
</div>