<?php

// Hook to include additional content before 404 page content
do_action( 'topscorer_action_before_404_page_content' );

// Include module content template
echo apply_filters( 'topscorer_filter_404_page_content_template', topscorer_get_template_part( '404', 'templates/404-content', '', topscorer_get_404_page_parameters() ) );

// Hook to include additional content after 404 page content
do_action( 'topscorer_action_after_404_page_content' );