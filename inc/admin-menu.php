<?php
include_once('project-settings.php');
add_action('admin_menu', 'add_main_menu');

function add_main_menu()
{
      add_menu_page(__(WP_STARTER_PLUGIN_NAME, WP_STARTER_TEXT_DOMAIN), __(WP_STARTER_PLUGIN_NAME, WP_STARTER_TEXT_DOMAIN), 'manage_options', 'mt-top-level-handle', 'mt_toplevel_page_menu_call_back' );

}

function mt_toplevel_page_menu_call_back()
{
  $pathToDevJSFolder = substr(plugin_dir_path(__FILE__), 0, strlen(plugin_dir_path(__FILE__)) - 4 ) . 'dev/js/';
  $allJS = glob($pathToDevJSFolder . '*.js');
  asort($allJS);
  foreach ($allJS as $file)
  {
    echo $file;
  }
}


add_action( 'admin_menu', 'media_add_admin_menu' );
add_action( 'admin_init', 'media_settings_init' );


function media_add_admin_menu(  ) { 

	add_options_page( 'complex', 'complex', 'manage_options', 'complex', 'media_options_page' );

}


function media_settings_init(  ) { 

	register_setting( 'pluginPage', 'media_settings' );

	add_settings_section(
		'media_pluginPage_section', 
		__( 'Your section description', 'complex' ), 
		'media_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'media_text_field_0', 
		__( 'Settings field description', 'complex' ), 
		'media_text_field_0_render', 
		'pluginPage', 
		'media_pluginPage_section' 
	);

	add_settings_field( 
		'media_radio_field_1', 
		__( 'Settings field description', 'complex' ), 
		'media_radio_field_1_render', 
		'pluginPage', 
		'media_pluginPage_section' 
	);

	add_settings_field( 
		'media_select_field_2', 
		__( 'Settings field description', 'complex' ), 
		'media_select_field_2_render', 
		'pluginPage', 
		'media_pluginPage_section' 
	);


}


function media_text_field_0_render(  ) { 

	$options = get_option( 'media_settings' );
	?>
	<input type='text' name='media_settings[media_text_field_0]' value='<?php echo $options['media_text_field_0']; ?>'>
	<?php

}


function media_radio_field_1_render(  ) { 

	$options = get_option( 'media_settings' );
	?>
	<input type='radio' name='media_settings[media_radio_field_1]' <?php checked( $options['media_radio_field_1'], 1 ); ?> value='1'>
	<?php

}


function media_select_field_2_render(  ) { 

	$options = get_option( 'media_settings' );
	?>
	<select name='media_settings[media_select_field_2]'>
		<option value='1' <?php selected( $options['media_select_field_2'], 1 ); ?>>Option 1</option>
		<option value='2' <?php selected( $options['media_select_field_2'], 2 ); ?>>Option 2</option>
	</select>

<?php

}


function media_settings_section_callback(  ) { 

	echo __( 'This section description', 'complex' );

}


function media_options_page(  ) { 

	?>
	<form action='options.php' method='post'>

		<h2>complex</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}