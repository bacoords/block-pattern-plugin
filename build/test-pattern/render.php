<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
$show_single = isset( $attributes['showSinglePattern'] ) ? $attributes['showSinglePattern'] : true;
$show_theme  = isset( $attributes['showPatternsInTheme'] ) ? $attributes['showPatternsInTheme'] : true;
$show_db     = isset( $attributes['showPatternsInDB'] ) ? $attributes['showPatternsInDB'] : true;
do_action( 'qm/debug', $attributes );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'alignfull is-layout-constrained has-global-padding' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php
	ob_start();
	if ( $show_single ) :
		$pattern_path = get_stylesheet_directory() . '/patterns/' . $attributes['singlePatternName'] . '.php';
		if ( file_exists( $pattern_path ) ) :
			include $pattern_path; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		endif;
	elseif ( ! $show_single && $show_theme ) :
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
	elseif ( ! $show_single && $show_db ) :
		$patterns = new WP_Query(
			array(
				'post_type'      => 'wp_block',
				'posts_per_page' => -1,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
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
	echo wp_kses_post( do_blocks( $content ) );
	?>
</div>
