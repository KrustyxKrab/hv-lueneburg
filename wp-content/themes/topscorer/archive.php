<?php

get_header();

if ( topscorer_is_installed ( 'core' ) ) {
    if ( is_tax ( 'sp_venue' ) || is_tax ( 'sp_position' ) || is_tax ( 'sp_role' ) ) {
        // Include content template
        topscorer_template_part ( 'content', 'templates/content', 'sportspress' );
    } else {
        // Include content template
        topscorer_template_part ( 'content', 'templates/content', 'blog' );
    }
} else {
    // Include content template
    topscorer_template_part ( 'content', 'templates/content', 'blog' );
}

get_footer();