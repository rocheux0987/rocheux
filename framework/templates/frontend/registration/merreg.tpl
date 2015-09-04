{* JS Validations *}
{literal}
<script type="text/javascript">
$(document).ready(function(){
    //button for upload photos
    $(document).on('click' , '.browse' , function(){
        $(this).prev().trigger('click');
    });
    $(document).on('click' , '.btn-prev' , function(){
        var a = confirm("Are you sure?");

        if(a == true){
            $('#form_prev').trigger('submit');
        }
    });
    $(document).on('click' , '.btn-register' , function(){
        var error = false;

        $('.validation_check').each(function(){
            $(this).removeClass('error_form').prev().hide();;
        });

        $('.validation_check').each(function(){
            if($(this).val() == ''){
                $(this).addClass('error_form').prev().show().find('small').html('Required').css({ color : "#ff0000"});
                error = true;
            }
        });

        if(error == false){
            $('#mer_form').trigger('submit');
        }
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
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="merc_form[store_name]" class="validation_check">
                    </div>
                </div>

                <!-- {$lang.contact_number} -->
                <div class="control-group">
                    <label>Contact Number:</label>
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="merc_form[contact_number]" class="validation_check">
                    </div>
                </div>

                <!-- {$lang.work_schedules} -->
                <div class="control-group">
                    <label>Work Schedules:</label>
                    <div class="controls">
                        <div class="valmsg arrow_left hide" >
                            <small></small>
                        </div>
                        <input type="text" name="merc_form[work_schedules]" class="validation_check">
                    </div>
                </div>

                <!-- {$lang.website} -->
                <div class="control-group">
                    <label>Website:</label>
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div> 
                        <input type="text" name="merc_form[website]" class="validation_check">
                    </div>
                </div>
                
                <div class="form-ups">
                    <!-- {$lang.store_image} -->
                    <div class="control-group left">
                        <label class="imgup">Store Image:</label>
                        <div class="controls">
                            <input type="file" name="storeimage" id="storeimage" class="hidden" multiple>
                            <a href="javascript:void(0)" class="browse">Browse</a>
                        </div>
                    </div>

                    <!-- {$lang.store_image} -->
                    <div class="control-group right">
                        <label class="imgup">Store Image:</label>
                        <div class="controls">
                            <input type="file" name="storeimage" id="storeimage" class="hidden" accept="image/*" multiple >
                            <a href="javascript:void(0)" class="browse">Browse</a>
                        </div>
                    </div>
                    <br clear="all"/>
                </div>

                <!-- action submit -->
                <div class="signup-submit right">
                    <div class="control-group left">
                        <div class="controls">
                            <a href="#" class="btn-next button-primary btn-prev">{$lang.previous}</a> 
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