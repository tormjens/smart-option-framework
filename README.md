Smart Option Framework
===

A simple framework for creating settings pages in WordPress. No fancy graphics added, as this I intended for use in plugins.

Field Types
---
* Text
* Textarea
* Checkbox
* Select
* Pages
* Image (Media Library)

You can add your own field types as well. See the file `fields/field.text.php` for an example of how it is done.

Example 1: Initialize
---

The following example will create a sub page under the "Settings"-page.

```
require_once 'smart-option-framework/option-framework.php';

new Smart_Option_Framework( array(
	'slug' => 'my-settings-page',
	'page_title' => 'My Settings',
	'menu_title' => 'My Settings',
	'capability' => 'manage_options',
	'type' => 'sub',
	'parent' => 'options-general.php',
	'option_name' => 'my_settings_options',
	'sections' => array(
		array(
			'id' => 'my_first_section',
			'title' => 'My First Section Name',
			'fields' => array(
				array(
					'id' => 'my_text_box',
					'title' => 'My Text Box',
					'type' => 'text'
				),
				array(
					'id' => 'my_text_area',
					'title' => 'My Text Area',
					'type' => 'textarea'
				),
				array(
					'id' => 'my_image',
					'title' => 'Image',
					'type' => 'image'
				),
				array(
					'id' => 'my_checkboxes',
					'title' => 'Checkboxes',
					'type' => 'checkbox',
					'options' => array(1 => 'First Option', 2 => 'Second Option', 3 => 'Third Option'),
					'default' => array(1, 3),
				)
			)
		)
	),
	'icon_url' => '',
	'position' => ''
) );
```

Example 2: Get an option
---

The framework comes with a function that retrieves the values from the database. If you want to retrieve all the options, simply use
`sof_option('my_settings_options')` where you would retrieve the options from our first example.

If you want to retrieve a specific option, simply seperate the value by dots. For example `sof_option('my_settings_options.my_image')` would retrieve the value for the image field and `sof_option('my_settings_options.my_checkboxes.0')` would retrieve the first option in the checkboxes field.