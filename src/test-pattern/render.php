<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'alignfull is-layout-constrained has-global-padding' ) ); ?>>
	<?php
	ob_start();
	foreach ( glob( get_stylesheet_directory() . '/patterns/*.php' ) as $pattern_path ) :
		$data = get_file_data(
			$pattern_path,
			array(
				'title'      => 'Title',
				'slug'       => 'Slug',
				'categories' => 'Categories',
			)
		);
		include $pattern_path; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	endforeach;

	$patterns = new WP_Query(
		array(
			'post_type'      => 'wp_block',
			'posts_per_page' => -1,
		)
	);

	if ( $patterns->have_posts() ) :
		while ( $patterns->have_posts() ) :
			$patterns->the_post();
			the_content();
		endwhile;
	endif;
	wp_reset_postdata();

	$content = ob_get_clean();
	echo do_blocks( $content );
	?>
</div>
