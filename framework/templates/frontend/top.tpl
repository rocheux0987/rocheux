{literal}
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click' , '.resmenu' ,function(){
            var atr = $(this).next().attr('data-attr');
            if(atr == 'show'){
                $(this).next().attr('data-attr' , 'hide');
                $('.ss').addClass('mshow');
                $('.ss').removeClass('mhide');
                $(this).attr('src', '{/literal}{$smarty.const._IMAGES_URL_}{literal}menubtn_clicked.png');

            }else{
                $(this).next().attr('data-attr' , 'show');
                $('.ss').addClass('mhide');
                $('.ss').removeClass('mshow');
                $(this).attr('src', '{/literal}{$smarty.const._IMAGES_URL_}{literal}menubtn.png');
            }
        });


        $(document).on('click' , '#modal-open' , function(){
            $("#login-response").html('');
            popup_open('#popup-login');
            $('#login_user').focus();
        });


        $(document).on('click' , '#logout-btn' , function(){
            $.post('{/literal}{"login.php"|seo_url}{literal}/?act=logout' , function(){
                location.reload();
            });
        });


        $('#login_user').keypress(function (e) {
         var key = e.which;
         if(key == 13)  // the enter key code
          {
            if($('#login_password').val() != ''){
                login();
            }else{
                $('#login_password').focus();
            }
          }
        });  

        $('#login_password').keypress(function (e) {
         var key = e.which;
         if(key == 13)  // the enter key code
          {
            if($('#login_user').val() != ''){
                $('#remember_me').focus();
            }else{
                $('#login_user').focus();
            }
          }
        });  

        $('#remember_me').keypress(function (e) {
         var key = e.which;
         if(key == 13)  // the enter key code
          {
            if($('#login_user').val() == ''){
                $('#login_user').focus();
            }else if($('#login_password').val() != ''){
                $('#login_user').focus();
            }else{
                login();
            }
          }
        });  
    });



    function login(){
        errors = 0; 
        if ($("#user").val() == ""){
            $("#user").addClass("field-error"); 
            errors++;
        }else{
            $("#user").removeClass("field-error");      
        }
        if ($("#password").val() == ""){
            $("#password").addClass("field-error"); 
            errors++;
        }else{
            $("#password").removeClass("field-error");      
        }
        if (errors>0){
            return false;   
        }
        
        var user = $('#login_user').val();
        var pass = $('#login_password').val();


        $.ajax({
            url: '{/literal}{"login.php"|seo_url}{literal}/?act=login',
            type:'POST',
            data: {user : user , pass : pass},
            beforeSend: function(){
                $('body, html, a').css({'cursor':'wait'});
            },
            success: function(response){    
                //Parse JSON data
                response_data = jQuery.parseJSON(response);
                $("#login-response").html(response_data["message"]);
                
                if (response_data["status"] == 1){
                    //Logged events, like redirect to protected area
                   location.reload();
                }else{
                    $("#login-response").css({'color':'red'});  
                }
                
                $("#user").val("");
                $("#password").val("");
                
                $('body, html, a').css({'cursor':'default'});
                $('a').css({'cursor':'pointer'});
            }   
        });    
    }
</script>
{/literal}
<div class="header">
    <div class="container">
        <div class="logo left">
            <a href="{"home.php"|seo_url}"><img src="{$smarty.const._IMAGES_URL_}logo.png" class="logo"></a>
        </div>
        {if isset($user_data)}
        <div class="login-top-menu right">
            <nav>
                <ul >
                {if isset($user_data.pet_data)}
                    <li><a href="{"profile.php"|seo_url}/{$pet_id}/edit"><img src="{'pet'|fn_generate_thumbnail:$user_data.pet_data.pet_image.image_path:50:50:true}"><span class="hide-sm" style="text-transform:capitalize;">{$user_data.pet_data.pet_name}</span></a></li>
                {/if}
                    <li class="line"></li>
                    <li class="no-icon"><a href="#"><span>Home</span></a></li>
                    <li><a href="#"><img src="{$smarty.const._IMAGES_URL_}neighbors.png"></a></li>
                    <li><a href="{"messages.php"|seo_url}"><img src="{$smarty.const._IMAGES_URL_}message.png"></a></li>
                    <li><a href="#"><img src="{$smarty.const._IMAGES_URL_}notification.png"></li>
                    <li><a href="#"><img src="{$smarty.const._IMAGES_URL_}find.png"></li>
                    <li><a href="#"><img src="{$smarty.const._IMAGES_URL_}track.png"></li>
                    <li><a href="#"><img src="{$smarty.const._IMAGES_URL_}settings.png">
                        <ul>
                            <li><a href="#"><span>Settings</span></a></li>
                            <li><a href="javascript:void(0);" id="logout-btn"><span>Logout</span></a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        {else}
        <div class="top-menu right bs">
            <nav>
                <ul>
                    <li><a href="#">{$lang.about_us}</a></li>
                    <li><a href="{"other.php"|seo_url}">PET NEEDS</a></li>
                    <li><a href="#">PET OF THE WEEK</a></li>
                    <li><a class="orange" href="{"reg.php"|seo_url}" data-toggle="modal" data-target="#signupModal">JOIN NOW</a></li>
                    <li><a class="dgrey" href="javascript:void(0);" id="modal-open">LOG IN</a></li>
                </ul>
            </nav>
        </div>
    
        <img class="resmenu right" src="{$smarty.const._IMAGES_URL_}menubtn.png">
        <div class="top-menu right ss mhide" data-attr="show">
            <nav>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Pet Needs</a></li>
                    <li class="noborder"><a href="#">Pet of the Week</a></li>
                    <li class="btnwbg dgrey"><a href="javascript:void(0);" onclick="popup_open('#popup-login');">LOG IN</a></li>
                    <li class="btnwbg orange"><a href="{"reg.php"|seo_url}">JOIN NOW</a></li>
                </ul>
            </nav>
        </div>
        {/if}
        <br clear="all"/>
    </div>
</div>

{* Modal login popup HTML *}
    <div class="modal-popup" id="popup-login" style="display:none">
        <div class="modal-popup-overlay" onclick="popup_close();"></div>
        <div class="modal-popup-inner">
            <div class="modal-header">
                <div class="modal-popup-close" onclick="popup_close();">x</div>
                <p class="modal-title left">Log in to your Rocky Superdog account</p>
                <br clear="all"/>
            </div>
            <h2>Please Log In</h2>
            <fieldset>
                <div class="popup-fields">
                    <div class="popup-field-label">
                    </div>
                    <div class="popup-field">
                    <input type="email" name="user" id="login_user" placeholder="Email" />
                    </div>
                </div>
                <div class="popup-fields">
                    <div class="popup-field-label">
                    </div>
                    <div class="popup-field">
                    <input type="password" name="password" id="login_password" placeholder="Password" />
                    </div>
                </div>
                <div class="popup-fields">
                    <input type="checkbox" name="remember" id="remember_me" value="remember">Remember me
                </div>
                <div class="popup-fields">
                    <div class="button-primary" onclick="login();">
                        Log in
                    </div>
                </div>
                <div id="login-response" style="font-family:'Century Gothic',CenturyGothic,AppleGothic,sans-serif; color:red; text-align:center;"></div>
            </fieldset>
        </div>
    </div>