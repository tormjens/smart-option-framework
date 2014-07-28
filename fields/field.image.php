<?php  

add_action('sof_field_image', 'sof_field_image', 9999, 3);

function sof_field_image($args, $option, $option_name) {

	extract($args);

	printf(
        '<input class="image_upload" type="text" id="'.$id.'" name="'.$option_name.'" value="%s" /> <button id="upload_image" class="button-primary upload_image_button">Last opp logo</button>',
        $option ? esc_attr( $option ) : $default
    );

}

add_action('sof_script_for_field_image', 'sof_script_for_field_image');

function sof_script_for_field_image() {
	wp_enqueue_media();
	wp_register_script( 'sof-image', SOF_URL . 'js/field.image.js', array('jquery') );
	wp_enqueue_script( 'sof-image' );

}



?>