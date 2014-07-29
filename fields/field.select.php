<?php  

add_action('sof_field_select', 'sof_field_select', 9999, 3);

function sof_field_select($args, $option, $option_name) {
	
	extract($args);

	$current = $option ? $option : $default;

	$current = is_array($current) ? $current : array($current);

	if($options) {

		if($multiple)
			$option_name = $option_name.'[]';

		echo '<select id="check-'.$id.'-'.$key.'" name="'.$option_name.'" '.($multiple ? 'multiple' : '').'>';
		foreach($options as $key => $value) {

			echo '<option value="'. $key .'" '. ( is_array($current) ? ( in_array($key, $current) ? 'selected' : '' ) : ( $current == $key ? 'selected' : '' ) ) .'>'. $value .'</option>';

		}
		echo '</select>';
	}

}

?>