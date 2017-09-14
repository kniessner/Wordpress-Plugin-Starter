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
