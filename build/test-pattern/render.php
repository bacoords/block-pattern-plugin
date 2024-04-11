<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
$show_theme = isset( $attributes['showPatternsInTheme'] ) ? $attributes['showPatternsInTheme'] : true;
$show_db    = isset( $attributes['showPatternsInDB'] ) ? $attributes['showPatternsInDB'] : true;
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'alignfull is-layout-constrained has-global-padding' ) ); ?>>
	<?php
	ob_start();
	if ( $show_theme ) :
		foreach ( glob( get_stylesheet_directory() . '/patterns/*.php' ) as $pattern_path ) :
			$data = get_file_data(
				$pattern_path,
				array(
					'title'      => 'Title',
					'slug'       => 'Slug',
					'categories' => 'Categories',
				)
			);
			do_action( 'qm/debug', $data );
			if ( 'hidden' === $data['categories'] ) {
				continue;
			}
			include $pattern_path; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		endforeach;
	endif;

	if ( $show_db ) :

		$patterns = new WP_Query(
			array(
				'post_type'      => 'wp_block',
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'wp_pattern_category',
						'field'    => 'slug',
						'terms'    => 'page,hidden',
						'operator' => 'NOT IN',
					),
				),
			)
		);

		if ( $patterns->have_posts() ) :
			while ( $patterns->have_posts() ) :
				$patterns->the_post();
				the_content();
			endwhile;
		endif;
		wp_reset_postdata();
	endif;

	$content = ob_get_clean();
	echo do_blocks( $content );
	?>
</div>
