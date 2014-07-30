<?php  

add_action('sof_field_pages', 'sof_field_pages', 9999, 3);

function sof_field_pages($args, $option, $option_name) {
	
	extract($args);

	$current = $option;

	wp_dropdown_pages( array(
		'show_option_none' => __('Do not bind to a page', 'smart-people'),
		'selected' => $current,
		'name' => $option_name
	) );

}

?>