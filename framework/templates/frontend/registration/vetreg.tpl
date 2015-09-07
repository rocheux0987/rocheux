
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
            $('#vetreg_form').trigger('submit');
        }
    });
});
</script>
{/literal}

<div class="signup-content vet">
    <img src="{$smarty.const._IMAGES_URL_}reg-vet-icon.png" class="genicon">
    <div class="general vet text-left">
        <div>
            <h2>VETERINARIAN DETAILS</h2>
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
            <form method="post" id="vetreg_form" action='{"reg.php"|seo_url}/?act=third step' enctype="multipart/form-data">
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

               <!-- {$lang.specialization} -->
                <div class="control-group">
                    <div class="controls">
                        <select id="spec" name="vetreg_form[specialization_id]" class="validation_check">
                            <div class="valmsg arrow_left hide">
                                <small></small>
                            </div>
                            <option value="">- SELECT {$lang.specialization} --</option>
                            {foreach name="results" from=$spec item=row }
                                <option value="{$row.specialization_id}">{$row.value}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>

                <!-- {$lang.contact_number} -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="vetreg_form[contact_number]" class="validation_check" placeholder="{$lang.contact_number}">
                    </div>
                </div>

                <!-- {$lang.work_schedules} -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="vetreg_form[work_schedules]" class="validation_check" placeholder="{$lang.work_schedules}">
                    </div>
                </div>                

                <!--{$lang.vet_association} -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="vetreg_form[vet_association]" class="validation_check" placeholder="{$lang.vet_association}">
                    </div>
                </div>

                <!-- {$lang.licenses} -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="vetreg_form[licenses]" class="validation_check" placeholder="{$lang.licenses}">
                    </div>
                </div>

                <!-- {$lang.pet_type_id} -->
                <div class="control-group">
                    <label>{$lang.pet_type_id}: </label>
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        {foreach name="results" from=$pet item=row }
                        <input type="checkbox" name="vetreg_form[pet_type_id][]" value="{$row.pet_type_id}">{$row.value} &nbsp;
                        {/foreach}
                    </div>
                </div>

                <!-- {$lang.notes} -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <textarea name="vetreg_form[notes]" class="validation_check" placeholder="{$lang.notes}"></textarea>
                    </div>
                </div>

                <div class="form-ups">
                    <!-- {$lang.image} -->
                    <div class="control-group left">
                        <label class="imgup">{$lang.image}</label>
                        <div class="controls">
                            <input type="file" name="logo" class="hidden">
                            <a href="javascript:void(0)" class="browse">Browse</a>
                        </div>
                    </div>

                <!-- {$lang.image} -->
                    <div class="control-group right">
                        <label class="imgup">{$lang.image}</label>
                        <div class="controls">
                            <input type="file" name="vetimage[]" class="hidden"  accept="image/*" multiple>
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