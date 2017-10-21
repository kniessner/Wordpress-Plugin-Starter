<?php
function creatCategoryPages(){


$terms = get_terms( array(
    'taxonomy' => 'category_media',
    'hide_empty' => false,
) );
foreach($terms as $term ){


$post_id = -1;
$author_id = 1;
$slug = $term->slug;
$title = $term->name;

if( null == get_page_by_title( $title ) ) {

	$post_id = wp_insert_post(
		array(
			'comment_status'	=>	'closed',
			'ping_status'		=>	'closed',
			'post_author'		=>	$author_id,
			'post_name'				=>	$slug,
			'post_title'		=>	$title,
			'post_status'		=>	'publish',
			'post_type'		=>	'galleries'
		)
	);

} else {
    $post_id = -2;

}


}
	}

function get_images_from_media_library() {
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
        'posts_per_page' => 5,
        'orderby' => 'rand'
    );
    $query_images = new WP_Query( $args );
    $images = array();
    foreach ( $query_images->posts as $image) {
        $images[]= $image->guid;
    }
    return $images;
}


function display_images_from_media_library() {

	$imgs = get_images_from_media_library();
	$html = '<div id="media-gallery">';

	foreach($imgs as $img) {

		//$html .= '<img src="' . $img . '" alt="" />';
		        $html .=  wp_get_attachment_image($img->ID );


	}

	$html .= '</div>';

	return $html;

}




function process_images($results) {
	$id = $results;


   // if( $results['type'] === 'image/jpeg' ) { // or /png or /tif / /whatever
		  $my_post = array(
	      'ID'           => $id,
	      'post_name'   =>  $id,
	      'post_title'   =>  basename( get_attached_file( $id )),
		  );
		  wp_update_post( $my_post );
   // }
}

add_filter( 'add_attachment', 'process_images');






/**
 * Add Fields
 */







add_filter( 'restrict_manage_posts', 'categorie_add_image_category_filter' );
function categorie_add_image_category_filter() {
    $screen = get_current_screen();
    if ( 'upload' == $screen->id ) {
        $args = array(
			'show_option_all'    => 'Categorie',
			'orderby'            => 'ID',
			'order'              => 'ASC',
			'show_count'         => 1,
			'hide_empty'         => 1,
			'hide_if_empty'      => false);

        wp_dropdown_categories( $args );
    }
}

function custom_add_album_filters() {
	global $typenow;

	// Use the taxonomy name or slug
	$taxonomies = array('album' );

	// must set this to the post type you want the filter(s) displayed on

		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Album</option>";
				foreach ($terms as $term) {
					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";

		}
	}
}
add_action( 'restrict_manage_posts', 'custom_add_album_filters' );


function custom_add_ranking_filters() {
	global $typenow;

	// Use the taxonomy name or slug
	$taxonomies = array('ranking' );

	// must set this to the post type you want the filter(s) displayed on

		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Ranking</option>";
				foreach ($terms as $term) {
					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";

		}
	}
}
add_action( 'restrict_manage_posts', 'custom_add_ranking_filters' );


function custom_add_publicity_filters() {
	global $typenow;

	// Use the taxonomy name or slug
	$taxonomies = array('publicity' );

	// must set this to the post type you want the filter(s) displayed on

		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Publicity</option>";
				foreach ($terms as $term) {
					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";

		}
	}
}
add_action( 'restrict_manage_posts', 'custom_add_publicity_filters' );


function custom_add_location_filters() {
	global $typenow;

	// Use the taxonomy name or slug
	$taxonomies = array('location' );

	// must set this to the post type you want the filter(s) displayed on

		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Locations</option>";
				foreach ($terms as $term) {
					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";

		}
	}
}
add_action( 'restrict_manage_posts', 'custom_add_location_filters' );

function getTaxTerms($t){



	$terms = get_terms( $t );
	$count = count( $terms );
	if ( $count > 0 ) {
	    echo '<h3>Total : '. $count . '</h3>';
	    echo '<ul>';
	    foreach ( $terms as $term ) {
	        echo '<li>' . $term->name . '</li>';
	    }
	    echo '</ul>';
	}
}


/**
 * Add Photographer Name and URL fields to media uploader
 *
 * @param $form_fields array, fields to include in attachment form
 * @param $post object, attachment record in database
 * @return $form_fields, modified form fields
 */

function be_attachment_field_credit( $form_fields, $post ) {
	$form_fields['be-photographer-name'] = array(
		'label' => 'Photographer Name',
		'input' => 'text',
		'value' => get_post_meta( $post->ID, 'be_photographer_name', true ),
		'helps' => 'If provided, photo credit will be displayed',
	);

	$form_fields['be-photographer-url'] = array(
		'label' => 'Photographer sURL',
		'input' => 'text',
		'value' => get_post_meta( $post->ID, 'be_photographer_url', true ),
		'helps' => 'If provided, photographer name will link here',
	);

	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'be_attachment_field_credit', 10, 2 );
/**
 * Save values of Photographer Name and URL in media uploader
 *
 * @param $post array, the post data for database
 * @param $attachment array, attachment fields from $_POST form
 * @return $post array, modified post data
 */
function be_attachment_field_credit_save( $post, $attachment ) {
	if( isset( $attachment['be-photographer-name'] ) )
		update_post_meta( $post['ID'], 'be_photographer_name', $attachment['be-photographer-name'] );

	if( isset( $attachment['be-photographer-url'] ) )
		update_post_meta( $post['ID'], 'be_photographer_url', $attachment['be-photographer-url'] );

	return $post;
}
add_filter( 'attachment_fields_to_save', 'be_attachment_field_credit_save', 10, 2 );

/**
 * Add "Include in Rotator" option to media uploader
 * Limited to Home page
 *
 * @param $form_fields array, fields to include in attachment form
 * @param $post object, attachment record in database
 * @return $form_fields, modified form fields
 */

function be_attachment_field_rotator( $form_fields, $post ) {
//  // Only show on front page
	if( ! ( $post->post_parent == get_option( 'page_on_front' ) ) )
		return $form_fields;
	// Set up options
	$options = array( '1' => 'Yes', '0' => 'No' );

	// Get currently selected value
	$selected = get_post_meta( $post->ID, 'be_rotator_include', true );

	// If no selected value, default to 'No'
	if( !isset( $selected ) )
		$selected = '0';

	// Display each option
	foreach ( $options as $value => $label ) {
		$checked = '';
		$css_id = 'rotator-include-option-' . $value;
		if ( $selected == $value ) {
			$checked = " checked='checked'";
		}
		$html = "<div class='rotator-include-option'><input type='radio' name='attachments[$post->ID][be-rotator-include]' id='{$css_id}' value='{$value}'$checked />";
		$html .= "<label for='{$css_id}'>$label</label>";
		$html .= '</div>';
		$out[] = $html;
	}
	// Construct the form field
	$form_fields['be-include-rotator'] = array(
		'label' => 'Include in Rotator',
		'input' => 'html',
		'html'  => join("\n", $out),
	);

	// Return all form fields
	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'be_attachment_field_rotator', 10, 2 );
/**
 * Save value of "Include in Rotator" selection in media uploader
 *
 * @param $post array, the post data for database
 * @param $attachment array, attachment fields from $_POST form
 * @return $post array, modified post data
 */

function be_attachment_field_rotator_save( $post, $attachment ) {
	if( isset( $attachment['be-rotator-include'] ) )
		update_post_meta( $post['ID'], 'be_rotator_include', $attachment['be-rotator-include'] );

	return $post;
}
add_filter( 'attachment_fields_to_save', 'be_attachment_field_rotator_save', 10, 2 );
?>