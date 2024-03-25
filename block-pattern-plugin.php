<?php
/**
 * Plugin Name:       Block Pattern Plugin
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       block-pattern-plugin
 *
 * @package           wpdev
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function wpdev_block_pattern_plugin_block_init() {
	register_block_type( __DIR__ . '/build/test-pattern' );
	register_block_type( __DIR__ . '/build/test-pattern-wrapper' );
}
add_action( 'init', 'wpdev_block_pattern_plugin_block_init' );


/**
 * Limit blocks to our
 */
function wpdocs_filter_allowed_block_types_when_post_provided( $allowed_block_types, $editor_context ) {
	if ( ! empty( $editor_context->post ) ) {
		return array( 'wpdev/test-pattern', 'core/pattern', 'core/list-item', 'core/button' );
	}
	return $allowed_block_types;
}
add_filter( 'allowed_block_types_all', 'wpdocs_filter_allowed_block_types_when_post_provided', 10, 2 );


// function render_block_test( $block_content ) {

// do_action( 'qm/debug', $block_content );

// $p = new \WP_HTML_Tag_Processor( $block_content );

// if ( $p->next_tag( 'h2' ) ) {
// $p->add_class( 'has-h-3-font-size' );
// }
// if ( $p->next_tag( 'h6' ) ) {
// $p->add_class( 'has-h-3-font-size' );
// }
// $block_content = $p->get_updated_html();

// $p = new \WP_HTML_Tag_Processor( $block_content );
// if ( $p->next_tag( 'p' ) ) {
// $p->add_class( 'has-background-color has-primary-background-color' );
// }

// $block_content = $p->get_updated_html();

// return $block_content;
// }
// add_filter( 'render_block_tangent/test-pattern', 'render_block_test', 10, 1 );
