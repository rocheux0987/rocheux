<?php
function smarty_modifier_fn_generate_thumbnail($type, $image_path, $width, $height = 0, $make_box = false)
{
	global $images;
	return $images->fn_generate_thumbnail($type, $image_path, $width, $height, $make_box);
}
?>
