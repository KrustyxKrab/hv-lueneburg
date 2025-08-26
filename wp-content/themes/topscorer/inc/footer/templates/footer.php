<footer id="qodef-page-footer">
	<?php
	// Hook to include additional content before page footer content
	do_action( 'topscorer_action_before_page_footer_content' );
	
	// Include module content template
	echo apply_filters( 'topscorer_filter_footer_content_template', topscorer_get_template_part( 'footer', 'templates/footer-content' ) );
	
	// Hook to include additional content after page footer content
	do_action( 'topscorer_action_after_page_footer_content' );
	?>
</footer>