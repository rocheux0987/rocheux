{if $type eq 1}
<div class="well"><h1>{$title}</h1></div>
{elseif $type eq 2}
<div class="well"><h2>{$title}</h2></div>
{elseif $type eq 3}
<div class="well" style="padding:5px; min-height:10px; margin-bottom:5px;"><h3>{$title}</h3></div>
{elseif $type eq 4}
<div class="well"><h4>{$title}</h4></div>
{elseif $type eq 5}
<div class="well"><h5>{$title}</h5></div>
{elseif $type eq 6}
<div class="well"><h6>{$title}</h6></div>
{/if}