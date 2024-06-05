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

	add_submenu_page(
		admin_url( 'edit.php?post_type=wp_block' ),
		__( 'All Patterns', 'wpdev' ),
		__( 'All Patterns', 'wpdev' ),
		'manage_options',
		admin_url( 'edit.php?post_type=wp_block' )
	);

	add_submenu_page(
		admin_url( 'edit.php?post_type=wp_block' ),
		__( 'Site Editor Patterns', 'wpdev' ),
		__( 'Site Editor Patterns', 'wpdev' ),
		'manage_options',
		admin_url( 'site-editor.php?path=%2Fpatterns&categoryId=my-patterns' )
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

	$columns['sync_status'] = __( 'Theme Sync Status', 'block-pattern-plugin' );
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


/**
 * Get the sync status of a pattern.
 *
 * @param integer $post_id The post ID.
 * @return boolean
 */
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

	$pattern_file_content = file_get_contents( $pattern_file ); // phpcs:ignore

	// Strip out the first php tag.
	$pattern_file_content = substr( $pattern_file_content, strpos( $pattern_file_content, '?>' ) + 3 );

	// Remove trailing whitespace.
	$pattern_content      = trim( $pattern_content );
	$pattern_file_content = trim( $pattern_file_content );

	if ( $pattern_content !== $pattern_file_content ) {
		return false;
	}

	return true;
}




/**
 * Adds duplicate links and re-adds quick edit link.
 *
 * @since 1.0.0
 *
 * @param array    $actions Array of actions.
 * @param \WP_Post $post    Post object.
 *
 * @return array
 */
function add_post_row_actions( array $actions, \WP_Post $post ) {
	if ( 'wp_block' === $post->post_type ) {
		$actions['site_editor'] = sprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			admin_url(
				'site-editor.php?postType=wp_block&postId=' . $post->ID
			),
			/* translators: %s: Post title. */
				esc_attr( sprintf( __( 'Open &#8220;%s&#8221; in Site Editor', 'pattern-editor' ), $post->post_title ) ),
			__( 'Open in Site Editor', 'pattern-editor' )
		);
	}

	return $actions;
}
add_filter( 'post_row_actions', __NAMESPACE__ . '\add_post_row_actions', 10, 2 );
