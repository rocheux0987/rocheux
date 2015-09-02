<?php
if ( $_GET['act'] == 'test' ){
	$image = $images->fn_get_image(120, 'pet', $_GET['id']);
	
	$image2 = $images->fn_generate_thumbnail('pet', $image['image_path'], 400);
	$image3 = $images->fn_generate_thumbnail('pet', $image['image_path'], 200, 100, true);
	//$image4 = $images->fn_generate_thumbnail('pet', $image['image_path'], 100, 50, true);
	
	$common->fn_print_r($image,$image2);
	
	$smarty->assign('image',$image);
	$smarty->assign('image2',$image2);
	$smarty->assign('image3',$image3);
	//$smarty->assign('image4',$image4);
	
	//$images->fn_delete_image(120, 'pet');

	#Smarty display.
	$smarty->display(_TPL_FRONTEND_DIR_."test_images.tpl");
}
?>