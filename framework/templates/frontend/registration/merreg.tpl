{* JS Validations *}
{literal}
<script type="text/javascript">
$(document).ready(function(){
    //button for upload photos
    $(document).on('click' , '.browse' , function(){
        $(this).prev().trigger('click');
    });

    $(document).on('click' , '.btn-register' , function(){
        $('#mer_form').trigger('submit');
    });
});
</script>
{/literal}

<div class="signup-content vet mer">
    <img src="{$smarty.const._IMAGES_URL_}reg-vet-icon.png" class="genicon">
    <div class="general vet mer text-left">
        <div>
            <h2>MERCHANT DETAILS</h2>
        </div>

        <div class="content">
            <form method="post" id="mer_form" action='{"reg.php"|seo_url}/?act=third step' enctype="multipart/form-data">
                <input type="hidden" name="reg_form[email]" value="{$reg_form.email}">
                <input type="hidden" name="reg_form[password]" value="{$reg_form.password}">
                <input type="hidden" name="reg_form[first_name]" value="{$reg_form.first_name}">
                <input type="hidden" name="reg_form[last_name]" value="{$reg_form.last_name}">
                <input type="hidden" name="reg_form[address]" value="{$reg_form.address}">
                <input type="hidden" name="reg_form[lat]" value="{$reg_form.lat}">
                <input type="hidden" name="reg_form[lon]" value="{$reg_form.lon}">
                <input type="hidden" name="reg_form[phone]" value="{$reg_form.phone}">
                <input type="hidden" name="reg_form[country]" value="{$reg_form.country}">
                <input type="hidden" name="reg_form[city]" value="{$reg_form.city}">
                <input type="hidden" name="reg_form[state]" value="{$reg_form.state}">
                <input type="hidden" name="reg_form[user_type]" value="{$reg_form.user_type}">
               <!-- {$lang.store_name} -->
                <div class="control-group">
                    <label>Store Name:</label>
                    <div class="controls">
                        <input type="text" name="merc_form[store_name]">
                    </div>
                </div>

                <!-- {$lang.contact_number} -->
                <div class="control-group">
                    <label>Contact Number:</label>
                    <div class="controls">
                        <input type="text" name="merc_form[contact_number]">
                    </div>
                </div>

                <!-- {$lang.work_schedules} -->
                <div class="control-group">
                    <label>Work Schedules:</label>
                    <div class="controls">
                        <input type="text" name="merc_form[work_schedules]">
                    </div>
                </div>

                <!-- {$lang.website} -->
                <div class="control-group">
                    <label>Website:</label>
                    <div class="controls">
                        <input type="text" name="merc_form[website]">
                    </div>
                </div>
                
                <!-- {$lang.image} -->
                <div class="control-group left">
                    <label class="imgup">Image:</label>
                    <div class="controls">
                        <input type="file" name="mercimage" id="mercimage" class="hidden">
                        <a href="javascript:void(0)" class="browse">Browse</a>
                    </div>
                </div>

                <!-- {$lang.store_image} -->
                <div class="control-group right">
                    <label class="imgup">Store Image:</label>
                    <div class="controls">
                        <input type="file" name="storeimage" id="storeimage" class="hidden" multiple>
                        <a href="javascript:void(0)" class="browse">Browse</a>
                    </div>
                </div>
                <br clear="all"/>

                <!-- action submit -->
                <div class="signup-submit right">
                    <div class="control-group left">
                        <div class="controls">
                            <a href="#" class="btn-next button-primary">{$lang.previous}</a> 
                        </div>
                    </div>
                    <div class="control-group right">
                        <div class="controls">
                            <a href="#" class="btn-next button-primary btn-register">{$lang.register}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>