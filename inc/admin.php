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



/**
 * Adds a column to the wp_block post type.
 *
 * @since 1.0.0
 *
 * @param array $columns Array of columns.
 *
 * @return array
 */
function add_pattern_custom_column( array $columns ): array {
	unset( $columns['date'] );

	$columns['sync_status'] = __( 'Sync Status', 'block-pattern-plugin' );
	$columns['date']        = __( 'Date', 'block-pattern-plugin' );

	return $columns;
}
add_filter( 'manage_wp_block_posts_columns', __NAMESPACE__ . '\add_pattern_custom_column' );



/**
 * Adds a column to the wp_block post type.
 *
 * @since 1.0.0
 *
 * @param string $column_name The column name.
 * @param int    $post_id      The post ID.
 *
 * @return void
 */
function show_sync_status_column( string $column_name, int $post_id ) {
	if ( 'sync_status' !== $column_name ) {
		return;
	}

	$sync_status = get_pattern_sync_status( $post_id );

	if ( $sync_status ) {
		echo '<span style="color: green;">Synced</span>';
	} else {
		echo '<span style="color: red;">Not Synced</span>';
	}
}
add_action( 'manage_wp_block_posts_custom_column', __NAMESPACE__ . '\show_sync_status_column', 10, 2 );



function get_pattern_sync_status( int $post_id ): bool {

	// Compare the pattern in the filesystem with the post content.

	$pattern = get_post( $post_id );

	if ( ! $pattern ) {
		return false;
	}

	$pattern_content = \wpdev\block_pattern_plugin\export\generate_pattern_content_export( $pattern->post_content );

	$pattern_file = get_stylesheet_directory() . '/patterns/' . $pattern->post_name . '.php';

	if ( ! file_exists( $pattern_file ) ) {
		return false;
	}

	$pattern_file_content = file_get_contents( $pattern_file );

	// Strip out the first php tag.
	$pattern_file_content = substr( $pattern_file_content, strpos( $pattern_file_content, '?>' ) + 3 );

	// Remove trailing whitespace
	$pattern_content      = trim( $pattern_content );
	$pattern_file_content = trim( $pattern_file_content );

	if ( $pattern_content !== $pattern_file_content ) {
		do_action( 'qm/debug', $pattern_file_content );
		do_action( 'qm/debug', $pattern_content );
		return false;
	}

	return true;
}
