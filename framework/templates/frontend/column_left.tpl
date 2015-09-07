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
            <li class="side-icon"><a class="groomers" href="{"groomers.php"|seo_url}"></a><a href="{"groomers.php"|seo_url}"><span>Pet Groomers</span></a></li>
            <li class="side-icon"><a class="walkers" href="{"walkers.php"|seo_url}"></a><a href="{"walkers.php"|seo_url}"><span>Pet Walkers</span></a></li>
            <li class="side-icon"><a class="boarding" href="{"boarding.php"|seo_url}"></a><a href="{"boarding.php"|seo_url}"><span>Pet Boarding</span></a></li>
            <li class="side-icon"><a class="dating" href="{"dating.php"|seo_url}"></a><a href="{"dating.php"|seo_url}"><span>Pet Dating</span></a></li>
        </ul>
    </div>
    
    <div class="leftbox whitebox">
        <label class="text-muted">PET-FRIENDLY</label>
        <ul>
            <li class="side-icon"><a class="restaurants" href="{"restaurants.php"|seo_url}"></a><a href="{"restaurants.php"|seo_url}"><span>Restaurants</span></a></li>
            <li class="side-icon"><a class="hotels" href="{"hotels.php"|seo_url}"></a><a href="{"restaurants.php"|seo_url}"><span>Hotels</span></a></li>
            <li class="side-icon"><a class="park" href="{"park.php"|seo_url}"></a><a href="{"restaurants.php"|seo_url}"><span>Park/Beach</span></a></li>
            <li class="side-icon"><a class="travel" href="{"travel.php"|seo_url}"></a><a href="{"restaurants.php"|seo_url}"><span>Travel</span></a></li>
        </ul>
    </div>
</div>