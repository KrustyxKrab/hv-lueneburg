<div class="qodef-grid-item <?php echo esc_attr( topscorer_get_page_content_sidebar_classes() ); ?>">
	<div class="qodef-blog qodef-m <?php echo esc_attr( topscorer_get_blog_holder_classes() ); ?>">
		<?php
		// Include posts loop
		topscorer_template_part( 'blog', 'templates/parts/loop' );
		
		if ( ! is_single() ) {
			// Include pagination
			topscorer_template_part( 'pagination', 'templates/pagination-wp' );
		}
		?>
	</div>
</div>