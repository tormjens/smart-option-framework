<?php  

add_action('sof_field_textarea', 'sof_field_textarea', 9999, 3);

function sof_field_textarea($args, $option, $option_name) {
	
	extract($args);

	printf(
        '<textarea id="'.$id.'" name="'.$option_name.'" rows="'.$rows.'">%s</textarea>',
        isset( $option ) ? esc_attr( $option) : $default
    );

}

?>