<?php
if ( ! function_exists('custom_taxonomies') ) {
			
		add_action('init', 'custom_taxonomies');
		function custom_taxonomies() {

			 $labels = array(
		        'name'              => 'Add Ranking',
		        'singular_name'     => 'Ranking',
		        'search_items'      => 'Search Ranking',
		        'edit_item'         => 'Edit Ranking',
		        'update_item'       => 'Update Ranking',
		        'add_new_item'      => 'Add New Ranking',
		        'new_item_name'     => 'New Ranking',
		        'menu_name'         => 'Ranking',
		    );

		    $args = array(
		        'labels' => $labels,
		        'hierarchical' => true,
		        'query_var' => true,
		        'rewrite' => true,
		        'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'update_count_callback' => '_update_generic_term_count',
		    	'publicly_queryable'=>  true,
		    	'has_archive'       =>  true,
		    	'rewrite' => array('slug' => 'ranking', 'with_front' => false)
		    );

		    register_taxonomy( 'ranking', 'attachment', $args );
			
			$labels = array(
			        'name'              => 'Add Category',
			        'singular_name'     => 'Category',
			        'search_items'      => 'Search Category',
			        'edit_item'         => 'Edit Category',
			        'update_item'       => 'Update Category',
			        'add_new_item'      => 'Add New Category',
			        'new_item_name'     => 'New Category',
			        'menu_name'         => 'Category',
			    );

		    $args = array(
		        'labels' => $labels,
		        'hierarchical' => true,
		        'query_var' => true,
		        'rewrite' => true,
		       	'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'update_count_callback' => '_update_generic_term_count',
		    	'publicly_queryable'=>  true,
		    	'has_archive'       =>  true,
		    	'rewrite' => array('slug' => 'category', 'with_front' => false)
		    );
		    register_taxonomy( 'category', 'attachment', $args );

		    $labels = array(
		        'name'              => 'Add Album',
		        'singular_name'     => 'Album',
		        'search_items'      => 'Search Album',
		        'edit_item'         => 'Edit Album',
		        'update_item'       => 'Update Album',
		        'add_new_item'      => 'Add New Album',
		        'new_item_name'     => 'New Album',
		        'menu_name'         => 'Album',
		    );

		    $args = array(
		        'labels' => $labels,
		        'hierarchical' => true,
		        'query_var' => true,
		        'rewrite' => true,
		       	'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'update_count_callback' => '_update_generic_term_count',
		    	'publicly_queryable'=>  true,
		    	'has_archive'       =>  true,
		    	'rewrite' => array('slug' => 'album', 'with_front' => false)
		    );

		    register_taxonomy( 'album', 'attachment', $args );
		};


}