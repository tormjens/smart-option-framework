<?php  

add_action('sof_field_text', 'sof_field_text', 9999, 3);

function sof_field_text($args, $option, $option_name) {
	
	extract($args);

	printf(
        '<input type="text" id="'.$id.'" name="'.$option_name.'" value="%s" />',
        isset( $option ) ? esc_attr( $option) : $default
    );

}

?>