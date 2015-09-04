{* JS Validations *}
{literal}
<script type="text/javascript">

$(document).ready(function(){
    $('.hide').hide();
	$(document).on('change' , '#country' ,function(){
		var country = $(this).val();	
		var url     = '{/literal}{"reg.php"|seo_url}{literal}/?act='+country;
		
		$.post(url , function(data){
			if(data != ''){
                $('#state_div').html(data);
            }
		});
	});

    $(document).on('focus' , '#address_map' , function(){
        popup_open('#popup-addressmap');
    });

    $(document).on('click' , '#save' , function(){
        $("#address_map").val($('#us3-address').val());
        $("#lat").val($('#us3-lat').val());
        $("#lon").val($('#us3-lon').val());
        popup_close();
    });


    $(document).on('click' , '.user_type' , function(){
        $('.user_type_radio').prop("checked", false)
        $(this).prev().prop("checked", true);
    });


    $(document).on('click' , '.btn-next' , function(){
        var error = false;
        
        $('.validation_check').each(function(){
            $(this).removeClass('error_form').prev().hide();;
        });


        $('.email_check').each(function(){
            if($(this).attr('data-status') != 'ok'){
                error = true;
            }
        });

        $('.validation_check').each(function(){
            if($(this).val() == ''){
                $(this).prev().show().find('small').html('Required').css({ color : "#ff0000"});
                $(this).addClass('error_form');
                error = true;
            }
        });
        if(error == false){
            $('#registration_form').trigger('submit');
        }
    });

    $(document).on('focusout' , '#email1' , function(){
        var ito = $(this);
        $.ajax({
            url:'{/literal}{"login.php"|seo_url}{literal}/?act=check_email',
            type: "post",
            data: { email : ito.val() } ,
            success: function(response){
                if(response != '1'){
                    ito.prev().show().find('small').html(" Email is Available").css({ color : "#00ff00"});
                    ito.removeClass('error_form');
                    ito.addClass('success_form');
                    ito.attr('data-status' , 'ok');
                }else{  
                    ito.prev().show().find('small').html(" Email is not Available").css({ color : "#ff0000"});
                    ito.removeClass('success_form');
                    ito.addClass('error_form');
                    ito.attr('data-status' , 'no');
                }
            }
        });

    });

    $(document).on('focusout' , '#email2' , function(){

        if($(this).val() == $('#email1').val()){
            $(this).prev().hide();
            $(this).removeClass('error_form');
            $(this).attr('data-status' , 'ok');
        }else{
            $(this).prev().show().find('small').html(" Email is not match").css({ color : "#ff0000"});
            $(this).removeClass('success_form');
            $(this).addClass('error_form');
            $(this).attr('data-status' , 'no');
        }

    });


    $(document).on('focusout' , '#pass1' , function(){
        if($(this).val().length < 8){
            $(this).prev().show().find('small').html("Password must Contain atleast 8 Characters").css({ color : "#ff0000" });
            $(this).removeClass('success_form');
            $(this).addClass('error_form');
            $(this).attr('data-status' , 'no');
        }else{
            $(this).prev().hide();
            $(this).removeClass('error_form');
            $(this).attr('data-status' , 'ok');
        }
    });

});


</script>
{/literal}
{if $prev eq true}
    {literal}
        <script type="text/javascript">
            $(document).ready(function(){
                $('#email1').attr('data-status' , 'ok');
                var country = $("#country").val();    
                var url     = '{/literal}{"reg.php"|seo_url}{literal}/?act='+country;
                
                $.post(url , function(data){
                    $('#state_div').html(data);
                });
            });
        </script>      
    {/literal}      
{/if}
<div class="signup-content">
    <img src="{$smarty.const._IMAGES_URL_}reg-gen-icon.png" class="genicon">
    <div class="general">
    	<div>
    		<h2 class="padside">GENERAL INFO</h2>
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
                            <input type="email" id="email1" name="reg_form[email]" data-status="no" class="validation_check email_check" value="{$reg_form.email}" placeholder="{'email_address'|get_lang}" required="required">
                        </div>
                    </div>

                  <!-- confirm email address -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="email" id="email2" name="email2" data-status="no" class="validation_check email_check" placeholder="{'password'|get_lang}" required="required">
                        </div>
                    </div>

                      <!-- password -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="password" name="reg_form[password]" class="validation_check email_check" data-status="no" id="pass1" placeholder="{'confirm_password'|get_lang}" required >
                        </div>
                    </div>
 
                     <!-- first name -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" name="reg_form[first_name]" class="validation_check" value="{$reg_form.first_name}" placeholder="{'first_name'|get_lang}" required="required">
                        </div>
                    </div>

                    <!-- last name -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" name="reg_form[last_name]" class="validation_check" value="{$reg_form.last_name}" placeholder="{'last_name'|get_lang}" required="required">
                        </div>
                    </div>

                    <!-- address -->
                    <div class="control-group">
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" name="reg_form[address]" class="validation_check" value="{$reg_form.address}" placeholder="{'address'|get_lang}" id="address_map" readonly>
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
                            <input type="text" name="reg_form[phone]" class="validation_check" value="{$reg_form.phone}" placeholder="{'mobile_phone'|get_lang}">
                        </div>
                    </div>

                     <!-- city -->
                    <div class="control-group">
                        <label></label>
                        <div class="controls">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" class="validation_check" name="reg_form[city]" value="{$reg_form.city}" placeholder="{'city'|get_lang}">
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
                                <option value="">-- Select {'country'|get_lang} --</option>
                                {foreach name="results" from=$country item=row }
                                    <option value="{$row.code}"  {if $reg_form.country eq $row.code} selected="selected"{/if} >{$row.country}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <!-- state -->
                    <div class="control-group">
                        <div class="controls" id="state_div">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <input type="text" class="validation_check" name="reg_form[state]" placeholder="{'state'|get_lang}">
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
                             <a href="#" class="btn-next button-primary">{'next'|get_lang}</a>
                        </div>
                    </div>
                </div>
    		</form>
        </div>
	</div>
</div>

<!-- MODAL -->
<div class="modal-popup" id="popup-addressmap" style="display:none">
    <div class="modal-popup-overlay" onclick="popup_close();"></div>
    <div class="modal-popup-inner">
        <div class="modal-header">
            <div class="modal-popup-close" onclick="popup_close();">x</div>
            <p class="modal-title left">Select Your Location</p>
            <br clear="all"/>
        </div>
        <fieldset>
            <div class="popup-fields">
                <div class="popup-field-label">
                    Location:
                </div>
                <div class="popup-field">
                    <input type="text" class="form-control" id="us3-address" disabled />
                </div>
                <div class="map_container">
                    <div id="us3" class="map_canvas"></div>
                 </div>
                <div class="clearfix">&nbsp;</div>
            </div>
            <div class="popup-fields">
                <div class="popup-field-label">
                    Lat.:
                </div>
                <div class="popup-field">
                    <input type="text" class="form-control" style="width: 110px" id="us3-lat" disabled />
                </div>
            </div>
             <div class="popup-fields">
                <div class="popup-field-label">
                    Long.:
                </div>
                <div class="popup-field">
                    <input type="text" class="form-control" style="width: 110px" id="us3-lon" disabled />
                </div>
            </div>
            <div>
            <input type="hidden" class="form-control" style="width: 110px" id="us3-radius"  />
            <input type="hidden" class="form-control" style="width: 110px" id="us3-country"  />
            <input type="hidden" class="form-control" style="width: 110px" id="us3-stateOrProvince"  />
            <input type="hidden" class="form-control" style="width: 110px" id="us3-district"  />
            <input type="hidden" class="form-control" style="width: 110px" id="us3-city"  />
            <input type="hidden" class="form-control" style="width: 110px" id="us3-address2"  />
            </div>
            <div class="clearfix"></div>
            <div class="popup-fields">
                <div class="button-primary" id="save">
                    Save Location
                </div>
            </div>
        </fieldset>
    </div>
</div>

{literal}
<script type="text/javascript" language="javascript">
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            showPicker(position.coords.latitude, position.coords.longitude);
        });
    
        function updateControls(addressComponents) {
            $('#us3-country').val(addressComponents.country);
            $('#us3-stateOrProvince').val(addressComponents.stateOrProvince);
            $('#us3-district').val(addressComponents.district);
            $('#us3-city').val(addressComponents.city);
            $('#us3-address2').val( [addressComponents.streetName, addressComponents.streetNumber].join(' ').trim());
        }
        
        function showPicker(latitude, longitude){
            $('#us3').locationpicker({
                location: { latitude: latitude, longitude: longitude  },
                radius: 200,
                inputBinding: {
                    latitudeInput: $('#us3-lat'),
                    longitudeInput: $('#us3-lon'),
                    radiusInput: $('#us3-radius'),
                    locationNameInput: $('#us3-address')
                },
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    var addressComponents = $(this).locationpicker('map').location.addressComponents;
                    updateControls(addressComponents);
                },
                oninitialized: function(component) {
                    var addressComponents = $(component).locationpicker('map').location.addressComponents;
                    updateControls(addressComponents);
                },
                enableAutocomplete: true,
                            // onchanged: function (currentLocation, radius, isMarkerDropped) {
                            //     // Uncomment line below to show alert on each Location Changed event
                            //     alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                            // }
                        });
        }
        
        $('#popup-addressmap').on('shown.bs.modal', function() {
            $('#us3').locationpicker('autosize');
        });
    }
</script>
{/literal}