<div class="signup-content">
    <img src="{$smarty.const._IMAGES_URL_}reg-shelter-icon.png" class="genicon">
    <div class="general shelter">
    	<div>
    		<h2 class="padside">PET FOUNDATION DETAILS</h2>
    	</div>

    	<div class="content">
    		<form method="POST" id="registration_form" action='{"reg.php"|seo_url}/?act=second step'>
                <!-- email address -->
                <div class="padside left text-left">
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" id="shelter_name" name="reg_form[shelter_name]" data-status="no" class="" value="{$reg_form.email}" placeholder="{$lang.shelter_name}" required="required">
                        </div>
                    </div>

                  <!-- confirm email address -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" id="address" name="address" data-status="no" class="" placeholder="{$lang.shelter_address}" required="required">
                        </div>
                    </div>

                      <!-- password -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="password" name="reg_form[password]" class="validation_check email_check" data-status="no" id="pass1" placeholder="{$lang.password}" required >
                        </div>
                    </div>
 
                     <!-- first name -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" name="reg_form[first_name]" class="validation_check" value="{$reg_form.first_name}" placeholder="{$lang.first_name}" required="required">
                        </div>
                    </div>

                    <!-- last name -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" name="reg_form[last_name]" class="validation_check" value="{$reg_form.last_name}" placeholder="{$lang.last_name}" required="required">
                        </div>
                    </div>

                    <!-- address -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" name="reg_form[address]" class="validation_check" value="{$reg_form.address}" placeholder="{$lang.address}" id="address_map" readonly>
                        </div>
                    </div>

                    <!-- lat -->
                    <input type="hidden" name="reg_form[lat]" value="{$reg_form.lat}" id="lat" >

                    <!-- lon -->
                    <input type="hidden" name="reg_form[lon]" value="{$reg_form.lon}" id="lon" >
                </div>

                <div class="padside right text-left">
                    <div class="control-group">
                        <label style="font-weight: bold">REGISTER AS</label>
                        <ul class="user-type dgrey">
                            <li>
                                <input type='radio' value='B' name='reg_form[user_type]' class="user_type_radio" id='mem' {if $reg_form.user_type eq 'B'} checked="checked" {/if} checked/>
                                <label for='member' class="user_type">Member</label>
                            </li>
                            <li>
                                <input type='radio' value='M' name='reg_form[user_type]' class="user_type_radio" id='mer' {if $reg_form.user_type eq 'M'} checked="checked" {/if}/>
                                <label for='merchant' class="user_type">Merchant</label>
                            </li>
                            <li>
                                <input type='radio' value='P' name='reg_form[user_type]' class="user_type_radio" id='shel' {if $reg_form.user_type eq 'P'} checked="checked" {/if}/>
                                <label for='shelter' class="user_type">Pet Foundation</label>
                            </li>
                            <li>
                                <input type='radio' value='V' name='reg_form[user_type]' class="user_type_radio" id='vet' {if $reg_form.user_type eq 'V'} checked="checked" {/if} />
                                <label for='veterinarian' class="user_type">Veterinarian</label>
                            </li>
                        </ul>
                    </div>

                    <!-- phone # -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" name="reg_form[phone]" class="validation_check" value="{$reg_form.phone}" placeholder="{$lang.mobile_phone}">
                        </div>
                    </div>

                    <!-- state -->
                    <!-- <div class="control-group">
                       <label></label>
                       <input type="text" class="controls" id="state_div" placeholder="{$lang.state}">
                    </div> -->

                    <!-- country -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <select id="country" class="validation_check" name="reg_form[country]">
                                <option value="">-- Select {$lang.country} --</option>
                                {foreach name="results" from=$country item=row }
                                    <option value="{$row.code}"  {if $reg_form.country eq $row.code} selected="selected"{/if} >{$row.country}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <!-- state -->
                    <div class="control-group">
                        <div class="controls" id="state_div"></div>
                    </div>

                    <!-- city -->
                    <div class="control-group">
                        <label></label>
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" class="validation_check" name="reg_form[city]" value="{$reg_form.city}" placeholder="{$lang.city}">
                        </div>
                    </div>

                </div>
                <br clear="all"/>

                <!-- action submit -->
                <div class="signup-submit right">
                    <span class="left">
                        <small>By clicking the Next button, you agree to our <a href="#" class="txtred">Terms</a><br/>
                        and that you have read our <a href="#" class="txtred">Privacy policy</a>.</small>
                    </span>
                    &nbsp; &nbsp;
                    <div class="control-group right">
                        <div class="controls">
                             <a href="#" class="btn-next button-primary">{$lang.next}</a>
                        </div>
                    </div>
                </div>
    		</form>
        </div>
	</div>
</div>