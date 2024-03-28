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
		if ( $data['title'] ) :
			?>
				<div class="wp-block-group has-global-padding" style="padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--sm)">
					<h2><?php echo esc_html( $data['title'] ); ?></h2>
				</div>
			<?php endif; ?>
			<?php
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
			?>
				<div class="wp-block-group has-global-padding" style="padding-top:var(--wp--preset--spacing--xl);padding-right:var(--wp--preset--spacing--sm);padding-bottom:var(--wp--preset--spacing--xl);padding-left:var(--wp--preset--spacing--sm)">
					<h2><?php the_title(); ?></h2>
				</div>
			<?php
			the_content();
		endwhile;
	endif;
	wp_reset_postdata();

	$content = ob_get_clean();
	echo do_blocks( $content );
	?>
</div>
