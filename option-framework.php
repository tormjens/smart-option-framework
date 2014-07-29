<?php  

/**
 * Option Page Framework
 *
 * @package WordPress
 * @author tormjens
 **/

if(!class_exists('Smart_Option_Framework')) {

	defined('SOF_DIR')	or define( 'SOF_DIR', plugin_dir_path( __FILE__ ) );
	defined('SOF_URL')	or define( 'SOF_URL', plugin_dir_url( __FILE__ ) );

	class Smart_Option_Framework
	{

		/**
		 * Slug for option page
		 *
		 * @var string
		 **/
		var $slug;

		/**
		 * Option Name
		 *
		 * @var string
		 **/
		var $option_name;

		/**
		 * Settings Section
		 *
		 * @var string
		 **/
		var $section;

		/**
		 * Arguments
		 *
		 * @var string
		 **/
		var $args;

		/**
		 * Fields for options page
		 *
		 * @var array
		 **/
		var $fields;

		/**
		 * The holder of our options
		 *
		 * @var string
		 **/
		var $options;

		/**
		 * Construct The Admin Page
		 *
		 * @return void
		 * @author 
		 **/

		function __construct($args = '') {

			// only run scripts while in admin
			if(!is_admin())
				return;

			// the defautls
			$defaults = array(
				'slug' => '', // the url slug for the settings page
				'page_title' => '', // title for page
				'menu_title' => '', // menu title for page
				'capability' => 'manage_options', // who can view the page
				'type' => 'sub', // whether its a sub menu page or not
				'parent' => 'options-general.php', // the parent page if its a sub menu
				'option_name' => '', // the name of the option. defaults to 'yourslug_option'
				'sections' => '', // the sections and fields of the option page
				'icon_url' => '', // url to an icon (only for parent pages)
				'position' => '' // menu position (only for parent pages)
			);

			// parse the defaults with the new arguments
			$args = wp_parse_args( $args, $defaults );

			// extract all arguments as variables
			extract($args);

			// set option name if it was empty
			if(!$option_name)
				$option_name = $slug . '_option';

			$this->args = $args; // pass arguments to the class property
			$this->slug = $slug; // pass slug to the class property
			$this->section = $slug . '_settings_section'; // pass section name to the class property
			$this->option_name = $option_name; // pass option name to the class property
			$this->fields = array(); // pass fields to the class property

			// find all fields
			if($sections) {
				foreach($sections as $section) {
					foreach( $section['fields'] as $field ) {

						// add it to the property
						$this->fields[] = $field['type'];
						

					}
				}
			}

			// load field types from the folders
			$this->load_field_types();

			// add the menu page
			add_action( 'admin_menu', array( $this, 'add_page' ) );

			// setup the page
	        add_action( 'admin_init', array( $this, 'init_page' ) );

	        // load scripts
	        add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

		}

		/**
		 * Load field types
		 *
		 * @return void
		 * @author tormjens
		 **/

		function load_field_types() {

			// search for files matching field.*.php in sub directory fields
			$files = SOF_DIR . 'fields/field.*.php';

			foreach (glob($files) as $filename) {
			    require_once $filename;
			}

		}

		/**
		 * Add admin page
		 *
		 * @return void
		 * @author tormjens
		 **/
		function add_page() {

			// if we are adding a sub page
			if($this->args['type'] == 'sub') {
				add_submenu_page( $this->args['parent'], $this->args['page_title'], $this->args['menu_title'], $this->args['capability'], $this->args['slug'], array( $this, 'create_page' ) );
			}
			// for the grown ups
			else {
				add_menu_page( $this->args['page_title'], $this->args['menu_title'], $this->args['capability'], $this->args['slug'], array( $this, 'create_page' ), $this->args['icon_url'], $this->args['position'] );
			}

			
			
		}

		/**
		 * Create page
		 *
		 * @return void
		 * @author tormjens
		 **/
		function create_page() {

			// Set class property
	        $this->options = get_option( $this->option_name );
	        ?>
	        <div class="wrap">
	            <h2><?php echo $this->args['page_title']; ?></h2>           
	            <form method="post" action="options.php">
	            <?php
	                // This prints out all hidden setting fields
	                settings_fields( $this->slug );   
	                do_settings_sections( $this->section );
	                submit_button(); 
	            ?>
	            </form>
	        </div>
	        <?php

		}

		/**
		 * Init page
		 *
		 * @return void
		 * @author tormjens
		 **/
		function init_page() {

			register_setting(
	            $this->slug, // Option group
	            $this->option_name, // Option name
	            array( $this, 'sanitize' ) // Sanitize
	        );

	        $sections = $this->args['sections'];

	        if($sections) {

	        	// find all sections and add it to the settings page
	        	foreach($sections as $section) {

	        		add_settings_section(
			            $section['id'], // ID
			            $section['title'], // Title
			            '__return_empty_string', // Callback
			            $this->section // Page
			        ); 

			        if($section['fields']) {

			        	// find and add this sections fields
			        	foreach($section['fields'] as $field) {

			        		add_settings_field(
					            $field['id'], // ID
					            $field['title'], // Title 
					            array( $this, 'field' ), // Callback
					            $this->section, // Page
					            $section['id'], // Section
					            $field
					        );

			        	}

			        }

	        	}

	        }

		}

		function field($args) {
			
			// default arguments
			$defaults = array(
				'id' => '',
				'type' => 'text',
				'title' => '',
				'default' => '',
				'description' => '',
				'rows' => '10',
				'options' => '',
				'multiple' => false
			);

			// parse them
			$args = wp_parse_args( $args, $defaults );

			// extract them
			extract($args);

			// get the current option
			$option = $this->options[$id];

			// fire the action which brings it to life
			do_action('sof_field_'. $type, $args, $option, $this->option_name.'['.$id.']');

			// this would be universal for all fields, so no need to have many places
			if($description)
				echo '<p class="description">'. $description .'</p>';

		}

		/**
		 * Add required scripts
		 *
		 * @return void
		 * @author tormjens
		 **/
		function scripts() {

			$base = get_current_screen()->base; // get the current base
			$slug = $this->slug; // the slug for the page

			// check if our slug is part of the base
			if(stristr($base, $slug) !== false) {

				// add scripts related to field types
				if($this->fields) {
					foreach($this->fields as $field) {

						do_action('sof_script_for_field_'. $field);
						
					}
				}

			}

		}

		/**
		 * TODO: Sanitize Fields
		 *
		 * @return void
		 * @author tormjens
		 **/
		function sanitize($input) {

			return $input;

		}

	} // END class Smart_Option_Framework

	/**
	 * Get a option value
	 *
	 * @return string
	 * @author 
	 **/
	
	if(!function_exists('sof_option')) {

		function sof_option($option) {

			$options = explode('.', $option);

			if(count($options) == 1) {
				return get_option( $option, array() );
			}
			else {
				$opt = get_option( $options[0] );
				unset($options[0]);
				foreach($options as $option) {
					$opt = $opt[$option];
				}

				return $opt;
				
			}

		}

	}

}

?>