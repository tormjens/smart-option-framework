<?php  

add_action('sof_field_checkbox', 'sof_field_checkbox', 9999, 3);

function sof_field_checkbox($args, $option, $option_name) {
	
	extract($args);

	$current = $option ? $option : $default;

	if($options) {
		foreach($options as $key => $value) {

			printf(
	            '<label for="check-'.$id.'-'.$key.'"><input type="checkbox" id="check-'.$id.'-'.$key.'" name="'.$option_name.'[]" value="%s" '.(in_array($key, $current) ? 'checked="checked"' : '').'/> %s</label><br />',
	            $key, $value
	        );

		}
	}

}

?>