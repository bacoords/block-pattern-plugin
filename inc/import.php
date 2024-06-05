<?php
/**
 * Import functions. Much of this code was forked from the Pattern Editor plugin.
 *
 * @see https://github.com/blockifywp/pattern-editor
 *
 * @package block-pattern-plugin
 */

namespace wpdev\block_pattern_plugin\import;

use WP;
use WP_CLI;

/**
 * Import block patterns.
 */
function import_block_patterns() {

	$registered = \WP_Block_Patterns_Registry::get_instance()->get_all_registered();

	// Import block patterns.
	$pattern_paths = glob( get_stylesheet_directory() . '/patterns/*.php' );

	foreach ( $pattern_paths as $pattern ) {

		$data = get_file_data(
			$pattern,
			array(
				'title'      => 'Title',
				'slug'       => 'Slug',
				'categories' => 'Categories',
			)
		);

		$slug = $data['slug'] ?? null;

		if ( ! $slug ) {
			continue;
		}

		$pattern_registered = array_filter(
			$registered,
			function ( $pattern ) use ( $slug ) {
				return $pattern['slug'] === $slug;
			}
		);

		$pattern_registered = array_values( $pattern_registered );

		$content = isset( $pattern_registered[0] ) ? $pattern_registered[0]['content'] :
		'<!-- wp:paragraph -->';

		$slug = explode( '/', $slug )[1];
		$args = array(
			'post_name'    => $slug,
			'post_title'   => $data['title'],
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'wp_block',
			'tax_input'    => array(
				'wp_pattern_category' => $data['categories'] ?? array(),
			),
		);

		if ( get_page_by_path( $data['slug'], OBJECT, 'wp_block' ) ) {
			continue;
		}

		wp_insert_post( wp_slash( $args ) );
	}

	flush_rewrite_rules();
}
