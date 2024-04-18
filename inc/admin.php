<?php
/**
 * Admin functions.
 *
 * @package block-pattern-plugin
 */

namespace wpdev\block_pattern_plugin\admin;

/**
 * Add admin menu link.
 *
 * @return void
 */
function add_admin_menu_link() {
	add_menu_page(
		__( 'Patterns', 'wpdev' ),
		__( 'Patterns', 'wpdev' ),
		'manage_options',
		admin_url( 'edit.php?post_type=wp_block' ),
		'',
		'dashicons-layout',
		30
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\add_admin_menu_link' );
