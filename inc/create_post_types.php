<?php
if ( ! function_exists('post_type_projects') ) {
	
		add_action( 'init', 'post_type_projects', 0 );
		// Register Custom Post Type
		function post_type_projects() {

			$labels = array(
				'name'                  => _x( 'Projects', 'Post Type General Name', 'project_complex' ),
				'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'project_complex' ),
				'menu_name'             => __( 'Projects', 'project_complex' ),
				'name_admin_bar'        => __( 'Projects', 'project_complex' ),
				'archives'              => __( 'Projects Archives', 'project_complex' ),
				'attributes'            => __( 'Projects Attributes', 'project_complex' ),
				'parent_item_colon'     => __( 'Parent Project:', 'project_complex' ),
				'all_items'             => __( 'All Projects', 'project_complex' ),
				'add_new_item'          => __( 'Add New Project', 'project_complex' ),
				'add_new'               => __( 'Add New Project', 'project_complex' ),
				'new_item'              => __( 'New Project', 'project_complex' ),
				'edit_item'             => __( 'Edit Project', 'project_complex' ),
				'update_item'           => __( 'Update Project', 'project_complex' ),
				'view_item'             => __( 'View Project', 'project_complex' ),
				'view_items'            => __( 'View Projects', 'project_complex' ),
				'search_items'          => __( 'Search Project', 'project_complex' ),
				'not_found'             => __( 'Not found', 'project_complex' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'project_complex' ),
				'featured_image'        => __( 'Featured Image', 'project_complex' ),
				'set_featured_image'    => __( 'Set featured image', 'project_complex' ),
				'remove_featured_image' => __( 'Remove featured image', 'project_complex' ),
				'use_featured_image'    => __( 'Use as featured image', 'project_complex' ),
				'insert_into_item'      => __( 'Insert into Project', 'project_complex' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'project_complex' ),
				'items_list'            => __( 'Projects list', 'project_complex' ),
				'items_list_navigation' => __( 'Projects list navigation', 'project_complex' ),
				'filter_items_list'     => __( 'Filter Projects list', 'project_complex' ),
			);
			$args = array(
				'label'                 => __( 'Project', 'project_complex' ),
				'description'           => __( 'Post Type Description', 'project_complex' ),
				'labels'                => $labels,
				'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', ),
				'taxonomies'            => array( 'category', 'post_tag' ),
				'hierarchical'          => true,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-clipboard',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,		
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'capability_type'       => 'page',
				'show_in_rest'          => true,
				'rest_base'             => 'projects',
				'rest_controller_class' => 'WP_REST_Posts_Projects',
			);
			register_post_type( 'projects', $args );

		}
}