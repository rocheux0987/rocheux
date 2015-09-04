{literal}
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click' , '.resmenu' ,function(){
            var atr = $(this).next().attr('data-attr');
            if(atr == 'show'){
                $(this).next().attr('data-attr' , 'hide');
                $('.ss').addClass('mshow');
                $('.ss').removeClass('mhide');
                $(this).attr('src', 'images/menubtn_clicked.png');

            }else{
                $(this).next().attr('data-attr' , 'show');
                $('.ss').addClass('mhide');
                $('.ss').removeClass('mshow');
                $(this).attr('src', '/rocky/images/menubtn.png');
            }
        });


        $(document).on('click' , '#modal-open' , function(){
            $("#login-response").html('');
            popup_open('#popup-login');
        });


        $(document).on('click' , '#logout-btn' , function(){
            $.post('{/literal}{"login.php"|seo_url}{literal}/?act=logout' , function(){
                location.reload();
            });
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
        
        var user = $('#user').val();
        var pass = $('#password').val();


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
                    <li><a href="#"><img src="{$smarty.const._IMAGES_URL_}nom1.jpg"><span class="hide-sm">Rocky</span></a></li>
                    <li class="line"></li>
                    <li class="no-icon"><a href="#"><span>Home</span></a></li>
                    <li><a href="#"><img src="{$smarty.const._IMAGES_URL_}neighbors.png"></a></li>
                    <li><a href="#"><img src="{$smarty.const._IMAGES_URL_}message.png"></a></li>
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
                    <input type="email" name="user" id="user" placeholder="Email" />
                    </div>
                </div>
                <div class="popup-fields">
                    <div class="popup-field-label">
                    </div>
                    <div class="popup-field">
                    <input type="password" name="password" id="password" placeholder="Password" />
                    </div>
                </div>
                <div class="popup-fields">
                    <input type="checkbox" name="remember" value="remember">Remember me
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