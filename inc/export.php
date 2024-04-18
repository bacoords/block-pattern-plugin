<?php
/**
 * Export functions. Much of this code was forked from the Pattern Editor plugin.
 *
 * @see https://github.com/blockifywp/pattern-editor
 *
 * @package block-pattern-plugin
 */

namespace wpdev\block_pattern_plugin\export;

/**
 * Returns memoized array of all nav menus.
 *
 * @since 1.0.0
 *
 * @return array
 */
function get_nav_menus(): array {
	static $nav_menus = array();

	if ( ! empty( $nav_menus ) ) {
		return $nav_menus;
	}

	$nav_menus = get_posts(
		array(
			'post_type'      => 'wp_navigation',
			'posts_per_page' => 100,
		)
	);

	return $nav_menus;
}

/**
 * Removes nav menu references from pattern content.
 *
 * @since 0.0.1
 *
 * @param string $html The HTML content.
 *
 * @return string
 */
function replace_nav_menu_refs( string $html = '' ): string {
	$nav_menus = get_nav_menus();

	foreach ( $nav_menus as $nav_menu ) {
		$html = str_replace(
			'"ref":' . $nav_menu->ID,
			'"ref":""',
			$html
		);
	}

	return $html;
}

/**
 * Replace reusable blocks with patterns.
 *
 * @since 0.0.1
 *
 * @param string $html The HTML content.
 *
 * @return string
 */
function replace_reusable_blocks( string $html = '' ): string {
	if ( ! $html ) {
		return $html;
	}

	$reusable_blocks = get_reusable_blocks();

	foreach ( $reusable_blocks as $reusable_block ) {
		$id   = $reusable_block->ID;
		$slug = sanitize_title_with_dashes( $reusable_block->post_title ?? '' );

		$html = str_replace(
			'<!-- wp:block {"ref":' . $id . '} /-->',
			'<!-- wp:pattern {"slug":"' . $slug . '"} /-->',
			$html
		);
	}

	return $html;
}

/**
 * Returns memoized array of all reusable blocks.
 *
 * @since 1.0.0
 *
 * @return array
 */
function get_reusable_blocks(): array {
	static $reusable_blocks = array();

	if ( ! empty( $reusable_blocks ) ) {
		return $reusable_blocks;
	}

	$reusable_blocks = get_posts(
		array(
			'post_type'      => 'wp_block',
			'posts_per_page' => -1,
		)
	);

	return $reusable_blocks;
}


/**
 * Replaces image paths with theme URI.
 *
 * @since 1.0.0
 *
 * @param string $html        The HTML content.
 * @param string $content_dir The content directory.
 *
 * @return string
 */
function replace_image_paths( string $html, string $content_dir ): string {
	$regex       = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
	$types       = array( 'jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'mov', 'svg', 'webm' );
	$upload_dir  = wp_upload_dir();
	$content_dir = trailingslashit( $content_dir );
	$stylesheet  = get_stylesheet();
	$setting     = apply_filters( 'blockify_image_export_dir', "themes/$stylesheet/assets" );

	// Remove trailing slashes.
	$setting   = implode( DIRECTORY_SEPARATOR, explode( DIRECTORY_SEPARATOR, $setting ) );
	$asset_dir = $content_dir . $setting . DIRECTORY_SEPARATOR;

	preg_match_all( $regex, $html, $matches );

	if ( ! isset( $matches[0] ) || ! is_array( $matches[0] ) ) {
		return $html;
	}

	foreach ( $matches[0] as $url ) {
		$basename = basename( $url );

		if ( ! str_contains( $basename, '.' ) ) {
			continue;
		}

		[ $file, $type ] = explode( '.', basename( $url ) );

		if ( ! in_array( $type, $types, true ) ) {
			continue;
		}

		// Limit to current site.
		$host = wp_parse_url( get_home_url() )['host'] ?? '';

		if ( ! str_contains( $url, $host ) ) {
			continue;
		}

		$original = str_replace(
			$upload_dir['baseurl'],
			$upload_dir['basedir'],
			$url
		);

		if ( ! file_exists( $original ) ) {
			continue;
		}

		if ( 'svg' === $type ) {
			$sub_dir = 'svg';
		} elseif ( 'mp4' === $type || 'mov' === $type ) {
				$sub_dir = 'video';
		} elseif ( 'gif' === $type ) {
				$sub_dir = 'gif';
		} else {
			$sub_dir = 'img';
		}

		$new_dir = $asset_dir . $sub_dir . DIRECTORY_SEPARATOR;

		if ( ! file_exists( $new_dir ) ) {
			wp_mkdir_p( $new_dir );
		}

		$new = $new_dir . $basename;

		if ( ! file_exists( $new ) ) {
			copy( $original, $new );
		}

		$html = str_replace( $url, $new, trim( $html ) );
	}

	$html = str_replace(
		$asset_dir,
		'<?php echo esc_url( content_url( "/' . $setting . '/" ) ) ?>',
		$html
	);

	$html = str_replace(
		get_stylesheet_directory_uri(),
		'<?php echo get_stylesheet_directory_uri() ?>',
		$html
	);

	$html = str_replace(
		home_url(),
		'<?php echo esc_url( home_url() ) ?>',
		$html
	);

	return $html;
}


function generate_pattern_content_export( string $content ) {

	$content_dir = trailingslashit( dirname( get_template_directory(), 2 ) );

	$content = replace_image_paths( $content, $content_dir );
	$content = replace_nav_menu_refs( $content );
	$content = replace_reusable_blocks( $content );
	$content = preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content );
	return $content;
}



/**
 * Handles export pattern request.
 *
 * @since 0.0.1
 *
 * @param int      $post_ID The post ID.
 * @param \WP_Post $post    The post object.
 * @param bool     $update  Whether this is an existing post being updated or
 *                          not.
 *
 * @return int
 */
function export_pattern( int $post_ID, \WP_Post $post, bool $update ): int {
	if ( ! $update ) {
		return $post_ID;
	}

	if ( ! $post ) {
		return $post_ID;
	}

	if ( 'publish' !== $post->post_status ) {
		return $post_ID;
	}

	$slug = $post->post_name;

	if ( ! $slug ) {
		return $post_ID;
	}
	$namespaced_slug = get_stylesheet() . '/' . $slug;

	$title = $post->post_title;

	$categories = get_the_terms( $post_ID, 'wp_pattern_category' );
	$categories = ( ! is_wp_error( $categories ) && is_array( $categories ) ) ? wp_list_pluck( $categories, 'slug' ) : array( 'uncategorized' );

	$content = $post->post_content ?? '';
	$content = generate_pattern_content_export( $content );
	$content = apply_filters( 'blockify_pattern_export_content', $content, $post, $categories[0] );

	$block_types = '';

	if ( in_array( 'page', $categories, true ) ) {
		$block_types .= 'core/post-content,';
	}

	if ( in_array( 'header', $categories, true ) ) {
		$block_types .= 'core/template-part/header,';
	}

	if ( in_array( 'footer', $categories, true ) ) {
		$block_types .= 'core/template-part/footer,';
	}

	if ( $block_types ) {
		$block_types = 'Block Types: ' . rtrim( $block_types, ',' );
	}

	$pattern_dir = get_pattern_dir( $post, $content );

	if ( ! file_exists( $pattern_dir ) ) {
		wp_mkdir_p( $pattern_dir );
	}

	$use_category_dirs = apply_filters( 'blockify_pattern_export_use_category_dirs', true );

	if ( $use_category_dirs && ! file_exists( $pattern_dir . $categories[0] ) ) {
		wp_mkdir_p( $pattern_dir . $categories[0] );
	}

	global $wp_filesystem;

	if ( ! $wp_filesystem ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	$categories_str = implode( ', ', $categories );

	$header_comment = <<<EOF
<?php
/**
 * Title: $title
 * Slug: $namespaced_slug
 * Categories: $categories_str
EOF;

	if ( $block_types ) {
		$header_comment .= "\n * $block_types";
	}

	if ( in_array( 'template', $categories ) || in_array( 'hidden', $categories ) ) {
		$header_comment .= "\n * Template Types: $slug";
		$header_comment .= "\n * Inserter: false";
	}

	$header_comment .= "\n */\n";
	$header_comment .= "?>\n";

	$wp_filesystem->put_contents(
		$pattern_dir . ( $use_category_dirs ? $categories[0] . DIRECTORY_SEPARATOR : '' ) . $slug . '.php',
		$header_comment . $content
	);

	flush_rewrite_rules();

	return $post_ID;
}
add_action( 'save_post_wp_block', __NAMESPACE__ . '\export_pattern', 10, 3 );



/**
 * Returns path to pattern directory.
 *
 * @since 1.0.0
 *
 * @param \WP_Post|null $post    Post object (optional).
 * @param string        $content Replaced content (optional).
 *
 * @return string
 */
function get_pattern_dir( \WP_Post $post = null, string $content = '' ): string {
	$post        = $post ?? get_post() ?? null;
	$stylesheet  = get_stylesheet();
	$default_dir = trailingslashit( dirname( get_template_directory(), 2 ) ) . "themes/$stylesheet/patterns";

	/**
	 * Filters the pattern directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $default_dir Filtered pattern directory.
	 * @param ?WP_Post $post        Post object (optional).
	 * @param ?string  $content     Replaced content (optional).
	 */
	$filtered_dir = apply_filters( 'blockify_pattern_export_dir', $default_dir, $post, $content );

	return esc_html( trailingslashit( $filtered_dir ) );
}
