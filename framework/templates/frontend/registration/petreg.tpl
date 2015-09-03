
{* JS Validations *}
{literal}
<script type="text/javascript">

$(document).ready(function(){
	$(document).on('change' , '.pet' ,function(){
		var pet  = $('.pet').val();
		var type = $(this).attr('data-type');	
		var url  = window.location.href;

		if(type == 'pet'){
			$.post(url , { act : pet , type : type }  , function(data){
				$('#breed_div').html(data);
                // $('#food_div').html('<select><option>- SELECT -</option></select>');
                // $('#foodbrand_div').html('<select><option>- SELECT -</option></select>');
                // $('#foodstyle_div').html('<select><option>- SELECT -</option></select>');
			});	
		}else if(type == 'breed'){
			$.post(url , { act : pet , type : type }  , function(data){
				$('#food_div').html(data);
                // $('#foodbrand_div').html('<select><option>- SELECT -</option></select>');
                // $('#foodstyle_div').html('<select><option>- SELECT -</option></select>');
			});
		}else if(type == 'food'){
			$.post(url , { act : pet , type : type }  , function(data){
				$('#foodbrand_div').html(data);
                // $('#foodstyle_div').html('<select><option>- SELECT -</option></select>');
			});
		}else if(type == 'foodbrand'){
			$.post(url , { act : pet , type : type }  , function(data){
				$('#foodstyle_div').html(data);
			});
		}
	});

    $(document).on('click' , '.btn-register' , function(){

        var error = false;

        $('.validation_check').each(function(){
            $(this).removeClass('error_form').prev().hide();;
        });

        $('.validation_check').each(function(){
            if($(this).val() == ''){
                $(this).prev().show().find('small').html('Required').css({ color : "#ff0000"});
                error = true;
            }
        });
        
        if(error == false){
            $('#petreg_form').trigger('submit');
        }
    });

    $(document).on('click' , '.btn-prev' , function(){
        var a = confirm("Are you sure?");

        if(a == true){
            $('#form_prev').trigger('submit');
        }
    });

    $(document).on('click' , '.browse' , function(){
        $(this).prev().trigger('click');
    });
});

</script>
{/literal}

<div class="signup-content vet addpet">
    <img src="{$smarty.const._IMAGES_URL_}reg-mem-icon.png" class="genicon">
    <div class="general vet addpet text-left">
        <div>
            <h2>ADD PET</h2>
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
            <form method="post" id="petreg_form" action='{"reg.php"|seo_url}/?act=third step' enctype="multipart/form-data">
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


               <!-- {$lang.pet_name} -->
                <div class="control-group"> 
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="petreg_form[name]" class="validation_check" placeholder="{$lang.pet_name}">
                    </div>
                </div>

                <!-- {$lang.howtocall} -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <input type="text" name="petreg_form[howtocall]" class="validation_check" placeholder="{$lang.howtocall}">
                    </div>
                </div>

                <!-- {$lang.gender} -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                        </div>
                        <select id="gender" class="validation_check" name="petreg_form[gender]">
                            <option value="">- Select {$lang.gender} -</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                </div>

                <!-- {$lang.pet_type} -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                         </div>
                        <select class="pet validation_check" data-type="pet" name="petreg_form[pet_type_id]">
                            <option value="">- Select {$lang.pet_type} -</option>
                            {foreach name="results" from=$pet item=row }
                                <option value="{$row.pet_type_id}">{$row.value}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>

                <!-- {$lang.breed} -->
                <div class="control-group">
                    <div class="controls" id="breed_div">
                    </div>
                </div>

                <!-- {$lang.food} -->
                <div class="control-group">
                    <div class="controls" id="food_div">
                    </div>
                </div>

                <!-- {$lang.food_brand} -->
                <div class="control-group">
                    <div class="controls" id="foodbrand_div">
                    </div>
                </div>

                <!-- {$lang.food_style} -->
                <div class="control-group">
                    <div class="controls" id="foodstyle_div">
                    </div>
                </div>

                <!-- {$lang.weight} default value = kilo -->
                <div class="control-group left">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                         </div>
                        <input type="number" name="petreg_form[weight]" class="validation_check" placeholder="{$lang.weight} in kilo">
                    </div>
                </div>

                <!-- {$lang.height} default value = centimeters -->
                <div class="control-group right">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                         </div>
                        <input type="number" name="petreg_form[height]" class="validation_check" placeholder="{$lang.height} in cm">
                    </div>
                </div>
                <br clear="all"/>

                <!-- {$lang.feeding_time} -->
                <div class="control-group">
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                         </div>
                        <input type="text" name="petreg_form[feeding_time]" class="validation_check" placeholder="{$lang.feeding_time}">
                    </div>
                </div>

                <!-- {$lang.birthdate}-->
                <div class="control-group">
                    <label>Birthdate:</label>
                    <div class="controls">
                        <div class="valmsg arrow_left hide">
                            <small></small>
                         </div>
                        <input type="date" class="datepicker validation_check" name="petreg_form[birthdate]" value="timestamp">
                    </div>
                </div>

                <!-- {$lang.image} -->
                <div class="control-group">
                    <label class="imgup">Image:</label>
                    <div class="controls">
                        <input type="file" name="petimage" id="petimage" class="hidden">
                        <a href="javascript:void(0)" class="browse">Browse</a>
                    </div>
                </div>
                <br clear="all"/>
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
            </form>
        </div>
    </div>
</div>