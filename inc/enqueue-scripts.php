<?php

define ('STARTER_DEV_MODE', false);


add_action('wp_enqueue_scripts', 'starter_enqueue_scripts');


function starter_enqueue_scripts(){
  $pathToDevJSFolder = substr(plugin_dir_path(__FILE__), 0, strlen(plugin_dir_path(__FILE__)) - 4 ) . 'dev/js/';
  $allJS = glob($pathToDevJSFolder . '*.js');
  asort($allJS);
  foreach ($allJS as $file){
    wp_enqueue_scripts($file);
  }


}
