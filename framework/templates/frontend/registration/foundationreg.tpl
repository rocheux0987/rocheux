<div class="signup-content vet mer">
    <img src="{$smarty.const._IMAGES_URL_}reg-shelter-icon.png" class="genicon">
    <div class="general vet mer text-left">
        <div>
            <h2>PET FOUNDATION DETAILS</h2>
        </div>

        <div class="content">
            <form method="post" id="form_prev" action='{"reg.php"|seo_url}/?act=first step' >
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
            </form>
            <form method="POST" id="registration_form" action='{"reg.php"|seo_url}/?act=second step'>

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

                <!-- shelter name -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" id="shelter_name" name="reg_form[name]" data-status="no" class="validation_check" value="" placeholder="{'shelter_name'|get_lang}" required="required">
                    </div>
                </div>

                <!-- shelter contact number -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" id="contact_number" name="contact_number"  class="validation_check" placeholder="{'contact_number'|get_lang}" required="required">
                    </div>
                </div>

                <!-- website -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="reg_form[website]" class="" data-status="no" id="website" placeholder="{'website'|get_lang}" required >
                    </div>
                </div>
 
                <!-- work schedules -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="reg_form[work_schedules]" class="" value="" placeholder="{'work_schedules'|get_lang}" required="required">
                    </div>
                </div>

                <!-- about -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <textarea name="reg_form[about]" class="validation_check" value="" placeholder="{'shelter_about'|get_lang}" required="required"></textarea>
                    </div>
                </div>

                <!-- mission -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <textarea name="reg_form[mission]" class="" value="" placeholder="{'mission'|get_lang}" required="required"></textarea>
                    </div>
                </div>
                
                <!-- {$lang.image} -->
                <div class="form-ups">
                    <div class="control-group left">
                        <label class="imgup">Logo:</label>
                        <div class="controls">
                            <input type="file" name="mercimage" id="mercimage" class="hidden">
                            <a href="javascript:void(0)" class="browse">Browse</a>
                        </div>
                    </div>

                    <!-- {$lang.store_image} -->
                    <div class="control-group right">
                        <label class="imgup">Featured Pets (5):</label>
                        <div class="controls">
                            <input type="file" name="storeimage" id="storeimage" class="hidden" multiple>
                            <a href="javascript:void(0)" class="browse">Browse</a>
                        </div>
                    </div>
                    <br clear="all"/>
                </div>

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
                <br clear="all"/>
            </form>
        </div>
    </div>
</div>