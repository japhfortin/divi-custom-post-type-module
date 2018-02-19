<?php
/**
* Plugin Name: Divi Custom Post Type Module
* Plugin URI: https://www.elegantthemes.com/
* Description: Display content of any custom post type just like the blog module
* Version: 1.0
* Author: Japh Fortin
* Author URI: http://japhfortin.com/
**/

include 'includes/custom-post-type-module.php';


function wp_get_all_post_types(){
		// $post_types = get_post_types( 
		// array(
		//    'public'   => true,
		//    '_builtin' => false
		// ), 'names', 'and' );

		// $detailed_taxonomy = [];

		// foreach ($post_types as $post_type) {
		// 	$taxonomies = get_object_taxonomies( $post_type );
		// 	if (!empty($taxonomies)) {
		// 		// $custom_tax = [];
		// 		foreach ($taxonomies as $taxonomy) {
		// 			var_dump($taxonomy);
		// 			$terms = get_terms( array(
		// 			    'taxonomy' => $taxonomy,
		// 			    'hide_empty' => false,
		// 			) );
		// 			foreach ($terms as $term) {
		// 				$cats_array[] = $term;
		// 			}
		// 		}
		// 	}
		// 	// $detailed_taxonomy[] = $custom_tax;

		// }
		// var_dump($cats_array);

}
add_action ('admin_init','wp_get_all_post_types', 199);


if ( ! function_exists( 'et_builder_include_taxonomies_option' ) ) :
function et_builder_include_taxonomies_option( $args = array() ) {
	$defaults = apply_filters( 'et_builder_include_taxonomies_defaults', array (
		'use_terms' => true,
		'term_name' => '',
	) );

	$args = wp_parse_args( $args, $defaults );

	$output = "\t" . "<% var et_pb_include_taxonomies_temp = typeof et_pb_include_taxonomies !== 'undefined' ? et_pb_include_taxonomies.split( ',' ) : []; %>" . "\n";

	if ( $args['use_terms'] ) {
		$cats_array = get_terms( $args['term_name'] );
	} else {
		$post_types = get_post_types( 
		array(
		   'public'   => true,
		   '_builtin' => false
		), 'names', 'and' );

		$detailed_taxonomy = [];

		foreach ($post_types as $post_type) {
			$taxonomies = get_object_taxonomies( $post_type );
			if (!empty($taxonomies)) {
				// $custom_tax = [];
				foreach ($taxonomies as $taxonomy) {
					$terms = get_terms( array(
					    'taxonomy' => $taxonomy,
					    'hide_empty' => false,
					) );
					$cats_array = $terms;
					if (!empty($cats_array)) {
						$output.= '<h4>' . $post_type . '</h4>';
						foreach ( $cats_array as $category ) {
							$contains = sprintf(
								'<%%= _.contains( et_pb_include_taxonomies_temp, "%1$s" ) ? checked="checked" : "" %%>',
								esc_html( $category->term_id )
							);

							$output .= sprintf(
								'%4$s<label><input type="checkbox" name="et_pb_include_taxonomies" value="%1$s"%3$s> %2$s</label><br/>',
								esc_attr( $category->term_id ),
								esc_html( $category->name ),
								$contains,
								"\n\t\t\t\t\t"
							);
						}
					}
				}
			}

		}
	}

	// if ( empty( $cats_array ) ) {
	// 	$output = '<p>' . esc_html__( "You currently don't have any projects assigned to a category.", 'et_builder' ) . '</p>';
	// }

	// foreach ( $cats_array as $category ) {
	// 	$contains = sprintf(
	// 		'<%%= _.contains( et_pb_include_taxonomies_temp, "%1$s" ) ? checked="checked" : "" %%>',
	// 		esc_html( $category->term_id )
	// 	);

	// 	$output .= sprintf(
	// 		'%4$s<label><input type="checkbox" name="et_pb_include_taxonomies" value="%1$s"%3$s> %2$s</label><br/>',
	// 		esc_attr( $category->term_id ),
	// 		esc_html( $category->name ),
	// 		$contains,
	// 		"\n\t\t\t\t\t"
	// 	);
	// }

	$output = '<div id="et_pb_include_taxonomies">' . $output . '</div>';

	return apply_filters( 'et_builder_include_taxonomies_option_html', $output );
}
endif;
// add_action ('admin_init','et_builder_include_taxonomies_option', 200);