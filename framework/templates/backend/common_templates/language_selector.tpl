<ul class="nav pull-right">
    <li class="dropdown" style="width:162px;">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <!--i class="halflings-icon white user"></i!--><img src="img/flags/{$current_language.icon}"> {$current_language.name}
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li class="dropdown-menu-title">
                <span>System language</span>
            </li>
            {foreach name="supported_languages" from=$supported_languages item=row}
            <li><a href="javascript:void(0)" onclick="language_change('{$row.lang_code}')"><img src="img/flags/{$row.icon}"> {$row.name}</a></li>
            {/foreach}
        </ul>
    </li>
</ul>

<div style="clear:both"></div>
{literal}
<script type="text/javascript">
function language_change(lang_code){
	document.location.href=removeURLParameter("{/literal}{$current_uri}{literal}", "sl")+"&sl="+lang_code;
}
function removeURLParameter(url, parameter) {
    //prefer to use l.search if you have a location/link object
    var urlparts= url.split('?');   
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        url= urlparts[0]+'?'+pars.join('&');
        return url;
    } else {
        return url;
    }
}
</script>
{/literal}